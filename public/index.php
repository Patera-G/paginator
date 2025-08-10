<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Paginator;
use App\RequestHelper;

$errorMessage = '';
$pageData = [];
$currentPage = 1;
$totalPages = 0;
$itemsPerPage = 10;

try {
    [$currentPage, $itemsPerPage] = RequestHelper::getPaginationParams($_GET);

    $emptyRaw = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_INT);
    $useEmpty = $emptyRaw === 1;

    $data = $useEmpty ? [] : range(1, 95);

    $paginator = new Paginator($data, $currentPage, $itemsPerPage);

    $pageData = $paginator->getPage();
    $totalPages = $paginator->getTotalPages();

    // AJAX request detekce
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjax) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'currentPage' => $paginator->getCurrentPage(),
            'totalPages' => $paginator->getTotalPages(),
            'itemsPerPage' => $paginator->getItemsPerPage(),
            'items' => $pageData,
        ]);
        exit;
    }
} catch (\InvalidArgumentException $e) {
    $errorMessage = "Chyba ve vstupních datech: " . $e->getMessage();
} catch (\Throwable $e) {
    $errorMessage = "Neočekávaná chyba: " . $e->getMessage();
}

// Načteme šablonu, předáme do ní proměnné (pomocí extract)
extract([
    'errorMessage' => $errorMessage,
    'pageData' => $pageData,
    'currentPage' => $currentPage,
    'totalPages' => $totalPages,
    'itemsPerPage' => $itemsPerPage,
]);

require __DIR__ . '/template.php';
