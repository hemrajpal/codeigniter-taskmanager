<?php
$currentPage = $pager->getCurrentPage();
$totalPages  = $pager->getPageCount();
?>

<?php if ($totalPages > 1): ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 px-3 pb-3">

        <div class="text-secondary small">
            Page <?= $currentPage ?> of <?= $totalPages ?>
        </div>

        <nav aria-label="Pagination">
            <ul class="pagination pagination-sm mb-0">

                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link"
                       href="<?= esc($pager->getPreviousPageURI() ?? '#') ?>">
                        &laquo; Previous
                    </a>
                </li>

                <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                    <li class="page-item <?= $page === $currentPage ? 'active' : '' ?>">
                        <a class="page-link"
                           href="<?= esc($pager->getPageURI($page)) ?>">
                            <?= $page ?>
                        </a>
                    </li>
                <?php endfor ?>

                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link"
                       href="<?= esc($pager->getNextPageURI() ?? '#') ?>">
                        Next &raquo;
                    </a>
                </li>

            </ul>
        </nav>

    </div>
<?php endif ?>