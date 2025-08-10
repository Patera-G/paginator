<?php

namespace App;

class Paginator
{
    private array $items;
    private int $itemsPerPage;
    private int $currentPage;
    private int $totalPages;

    public function __construct(array $items, int $currentPage = 1, int $itemsPerPage = 10)
    {
        $this->items = $items;
        $this->itemsPerPage = max(1, $itemsPerPage);

        if (empty($items)) {
            // Prázdné pole - žádné stránky
            $this->totalPages = 0;
            $this->currentPage = 0;
        } else {
            $this->totalPages = (int) ceil(count($items) / $this->itemsPerPage);
            // Zajistí, že currentPage je minimálně 1 a maximálně totalPages
            $this->currentPage = max(1, min($currentPage, $this->totalPages));
        }
    }

    public function getPage(): array
    {
        if (empty($this->items) || $this->currentPage === 0) {
            return [];
        }

        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        return array_slice($this->items, $offset, $this->itemsPerPage);
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}
