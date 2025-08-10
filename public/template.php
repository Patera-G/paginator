<!-- public/template.php -->
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Paginator - Stránkování</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; background: #f9f9f9; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 1.5rem; box-shadow: 0 0 8px rgba(0,0,0,0.1);}
        h1 { margin-bottom: 1rem; }
        ul { padding-left: 1.2rem; }
        .pagination a {
            margin: 0 0.25rem;
            text-decoration: none;
            color: #007bff;
        }
        .pagination a:hover {
            text-decoration: underline;
        }
        .error { color: #c00; margin-bottom: 1rem; }
        .info { margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Stránkování položek</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <?php if ($totalPages === 0): ?>
            <p>Žádná data k zobrazení.</p>
        <?php else: ?>
            <div class="info">
                Stránka <span id="currentPage"><?= $currentPage ?></span> z <span id="totalPages"><?= $totalPages ?></span><br>
                Počet položek na stránku: <span id="itemsPerPage"><?= $itemsPerPage ?></span>
            </div>

            <ul id="itemsList">
                <?php foreach ($pageData as $item): ?>
                    <li>Položka <?= htmlspecialchars((string)$item) ?></li>
                <?php endforeach; ?>
            </ul>

            <div class="pagination" id="paginationLinks">
                <?php if ($currentPage > 1): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>" class="page-link">Předchozí</a>
                <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>" class="page-link">Další</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const paginationDiv = document.getElementById('paginationLinks');
            const itemsList = document.getElementById('itemsList');
            const currentPageSpan = document.getElementById('currentPage');
            const totalPagesSpan = document.getElementById('totalPages');

            paginationDiv.addEventListener('click', async (event) => {
                if (event.target.classList.contains('page-link')) {
                    event.preventDefault();
                    const url = event.target.href;

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'  // Označíme AJAX request
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Chyba při načítání dat');
                        }

                        const data = await response.json();

                        // Aktualizujeme seznam položek
                        itemsList.innerHTML = '';
                        for (const item of data.items) {
                            const li = document.createElement('li');
                            li.textContent = `Položka ${item}`;
                            itemsList.appendChild(li);
                        }

                        // Aktualizujeme informace o stránce
                        currentPageSpan.textContent = data.currentPage;
                        totalPagesSpan.textContent = data.totalPages;

                        // Aktualizujeme odkazy pro stránkování
                        paginationDiv.innerHTML = '';
                        if (data.currentPage > 1) {
                            const prevLink = document.createElement('a');
                            prevLink.href = `?page=${data.currentPage - 1}&per_page=${data.itemsPerPage}`;
                            prevLink.classList.add('page-link');
                            prevLink.textContent = 'Předchozí';
                            paginationDiv.appendChild(prevLink);
                        }
                        if (data.currentPage < data.totalPages) {
                            const nextLink = document.createElement('a');
                            nextLink.href = `?page=${data.currentPage + 1}&per_page=${data.itemsPerPage}`;
                            nextLink.classList.add('page-link');
                            nextLink.textContent = 'Další';
                            paginationDiv.appendChild(nextLink);
                        }
                    } catch (error) {
                        alert(error.message);
                    }
                }
            });
        });
    </script>
</body>
</html>
