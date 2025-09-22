<?php

/**
 * Funzione per generare il template di paginazione, nonché elaborare i parametri da passare alla query SQL
 *
 * @param int $itemsPerPage Numero di prodotti per pagina
 * @param int $itemsCount Numero totale di prodotti
 * @param int|null $currentPage Pagina corrente
 * @param int $flexThreshold Numero di pagine a partire dal quale la paginazione diventa flessibile
 * @param int $range Numero di pagine da mostrare ai lati della paginazione flessibile
 * @param string $pageName Nome della variabile GET che contiene il numero della pagina
 * @param string $align Allineamento menù di paginazione "start", "center" o "end"
 *
 * @return array [0 => template, 1 => itemsPerPage, 2 => offset]
 */
function paginationRender(
    int      $itemsPerPage,
    int      $itemsCount,
    int|null $currentPage = 1,
    int      $flexThreshold = 10,
    int      $range = 4,
    string   $pageName = 'p',
    string   $align = 'end',
): array
{
    /**
     * Il numero di pagine necessarie a visualizzare tutti i prodotti
     */
    $pages = ceil($itemsCount / $itemsPerPage);

    /**
     * Attivo o disattivo la paginazione flessibile
     */
    $flex = $pages > $flexThreshold;

    if ($currentPage < 1) {
        $currentPage = 1;
    } else if ($currentPage > $pages) {
        $currentPage = $pages;
    }

    ob_start();
    ?>
    <div class="row align-items-center">
        <div class="col-4 p-2 px-4">
            Pagina <?= $currentPage ?> di <?= $pages ?>
        </div>
        <div class="col-8">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-<?= $align; ?> mb-0">
                    <!-- Pagina precedente -->
                    <li class="page-item <?= $currentPage === 1 ? 'disabled' : '' ?>">
                        <a href="?<?php
                        $queryString = $_GET;
                        $queryString[$pageName] = $currentPage - 1;

                        echo http_build_query($queryString);
                        ?>" class="page-link">
                            <i class="bi bi-caret-left"></i>
                        </a>
                    </li>

                    <?php
                    if ($flex) {
                        if ($currentPage > ($range + 2)) {
                            ?>
                            <li class="page-item">
                                <a class="page-link <?= $currentPage === 1 ? 'disabled' : '' ?>"
                                   href="?<?php
                                   $queryString = $_GET;
                                   $queryString[$pageName] = 1;

                                   echo http_build_query($queryString);
                                   ?>">1</a>
                            </li>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php
                        }

                        for ($i = max(1, $currentPage - $range); $i <= min($pages, $currentPage + $range); ++$i) {
                            ?>
                            <li class="page-item">
                                <a class="page-link <?= $currentPage === $i ? 'disabled' : '' ?>"
                                   href="?<?php
                                   $queryString = $_GET;
                                   $queryString[$pageName] = $i;

                                   echo http_build_query($queryString);
                                   ?>"><?= $i; ?></a>
                            </li>
                            <?php
                        }

                        if ($currentPage < ($pages - $range - 1)) {
                            ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                            <li class="page-item">
                                <a class="page-link <?= $currentPage === 1 ? 'disabled' : '' ?>"
                                   href="?<?php
                                   $queryString = $_GET;
                                   $queryString[$pageName] = $pages;

                                   echo http_build_query($queryString);
                                   ?>"><?= $pages ?></a>
                            </li>
                            <?php
                        }
                    } else {
                        for ($i = 1; $i <= $pages; ++$i) {
                            ?>
                            <li class="page-item">
                                <a class="page-link <?= $currentPage === $i ? 'disabled' : '' ?>"
                                   href="?<?php
                                   $queryString = $_GET;
                                   $queryString[$pageName] = $i;

                                   echo http_build_query($queryString);
                                   ?>"><?= $i; ?></a>
                            </li>
                            <?php
                        }
                    }
                    ?>

                    <!-- Pagina successiva -->
                    <li class="page-item <?= $currentPage == $pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php
                        $queryString = $_GET;
                        $queryString[$pageName] = $currentPage + 1;

                        echo http_build_query($queryString);
                        ?>">
                            <i class="bi bi-caret-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>


    <?php
    $template = ob_get_contents();
    ob_end_clean();

    return [
        $template,
        $itemsPerPage,
        ($currentPage - 1) * $itemsPerPage,
    ];
}
