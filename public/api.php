<?php

require __DIR__ . '/../vendor/autoload.php';

use App\paginator;
use App\requestHelper;

header('Content-Type: application/json; charset=utf-8');

function sendError(int $code, string $message): void {
    http_response_code($code);
    echo json_encode(['error' => $message], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Povolené metody - budeme akceptovat GET i POST
$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['GET', 'POST'], true)) {
    sendError(405, 'Metoda není povolena. Použijte GET nebo POST.');
}

try {
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            sendError(400, 'Neplatný JSON.');
        }

        if (!isset($input['data'])) {
            sendError(400, 'Pole "data" musí být přítomno.');
        }

        $data = RequestHelper::validateData($input['data']);
        $page = $input['page'] ?? null;
        $perPage = $input['per_page'] ?? null;
        [$page, $itemsPerPage] = RequestHelper::getPaginationParams(['page' => $page, 'per_page' => $perPage]);
    } else {
        [$page, $itemsPerPage] = RequestHelper::getPaginationParams($_GET);
        $data = range(1, 95);
    }

    $paginator = new Paginator($data, $page, $itemsPerPage);
} catch (\InvalidArgumentException $e) {
    sendError(400, $e->getMessage());
} catch (\Throwable $e) {
    sendError(500, 'Interní chyba serveru: ' . $e->getMessage());
}

$response = [
    'current_page' => $paginator->getCurrentPage(),
    'total_pages' => $paginator->getTotalPages(),
    'items_per_page' => $paginator->getItemsPerPage(),
    'data' => $paginator->getPage(),
];

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
