<?php

use PHPUnit\Framework\TestCase;
use App\Paginator;

final class PaginatorTest extends TestCase
{
    public function testEmptyArray(): void
    {
        $paginator = new Paginator([], 1, 10);
        $this->assertSame(0, $paginator->getTotalPages());
        $this->assertSame(0, $paginator->getCurrentPage());
        $this->assertSame([], $paginator->getPage());
    }

    public function testSinglePage(): void
    {
        $items = [1, 2, 3];
        $paginator = new Paginator($items, 1, 10);

        $this->assertSame(1, $paginator->getTotalPages());
        $this->assertSame(1, $paginator->getCurrentPage());
        $this->assertSame($items, $paginator->getPage());
    }

    public function testMultiplePages(): void
    {
        $items = range(1, 25);
        $itemsPerPage = 10;

        $paginator = new Paginator($items, 1, $itemsPerPage);
        $this->assertSame(3, $paginator->getTotalPages());
        $this->assertSame(1, $paginator->getCurrentPage());
        $this->assertSame(range(1, 10), $paginator->getPage());

        // Test stránka 2
        $paginator = new Paginator($items, 2, $itemsPerPage);
        $this->assertSame(range(11, 20), $paginator->getPage());

        // Test stránka 3
        $paginator = new Paginator($items, 3, $itemsPerPage);
        $this->assertSame(range(21, 25), $paginator->getPage());
    }

    public function testCurrentPageBounds(): void
    {
        $items = range(1, 10);
        $paginator = new Paginator($items, 0, 5);  // currentPage < 1
        $this->assertSame(1, $paginator->getCurrentPage());

        $paginator = new Paginator($items, 10, 5); // currentPage > totalPages
        $this->assertSame(2, $paginator->getCurrentPage()); // 10 items / 5 = 2 pages
    }

    public function testItemsPerPageMinimum(): void
    {
        $items = range(1, 5);
        $paginator = new Paginator($items, 1, 0);  // itemsPerPage < 1, nastaví na 1
        $this->assertSame(1, $paginator->getItemsPerPage());
        $this->assertSame(5, $paginator->getTotalPages());
    }
}
