<?php
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ApiTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'base_uri' => 'http://host.docker.internal:8080/',
            'http_errors' => false,
        ]);
    }

    public function testGetReturnsValidPagination()
    {
        $response = $this->client->get('api.php', [
            'query' => [
                'page' => 2,
                'per_page' => 5,
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode((string)$response->getBody(), true);

        $this->assertEquals(2, $json['current_page']);
        $this->assertEquals(5, $json['items_per_page']);
        $this->assertCount(5, $json['data']);
    }

    public function testPostReturnsValidPaginationWithCustomData()
    {
        $payload = [
            'data' => range(100, 110), // 11 items
            'page' => 2,
            'per_page' => 5,
        ];

        $response = $this->client->post('api.php', [
            'json' => $payload,
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode((string)$response->getBody(), true);

        $this->assertEquals(2, $json['current_page']);
        $this->assertEquals(3, $json['total_pages']);
        $this->assertCount(5, $json['data']);
    }

    public function testPostInvalidJson()
    {
        $response = $this->client->post('api.php', [
            'body' => '{invalid_json}', // deliberately broken
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $json = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('error', $json);
    }

    public function testPostMissingDataField()
    {
        $response = $this->client->post('api.php', [
            'json' => ['page' => 1],
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $json = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('error', $json);
    }

    public function testGetInvalidParameters()
    {
        $response = $this->client->get('api.php', [
            'query' => [
                'page' => 'abc',
                'per_page' => -5,
            ],
        ]);

        $this->assertEquals(400, $response->getStatusCode());

        $json = json_decode((string)$response->getBody(), true);
        $this->assertArrayHasKey('error', $json);
    }
}
