<?php
/**
 * @var string $orderBy
 * @var string $orderDesc
 */
?>
<div class="card mb-4 shadow-sm">
    <div class="card-header">
        Ordinamento prodotti
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="../../../products/partials?<?php

                $queryString = $_GET;
                $queryString['orderBy'] = 'title';

                if ($orderBy === 'title') {
                    $queryString['orderDesc'] = $orderDesc === 'DESC' ? '0' : '1';
                } else {
                    $queryString['orderDesc'] = '0';
                }

                unset($queryString['page']);

                echo http_build_query($queryString);

                ?>" class="page-link">Titolo</a>

                <span><?php
                    if ($orderBy === 'title') {
                        echo $orderDesc === 'DESC' ? ' <i class="bi bi-sort-alpha-up"></i>' :
                            ' <i class="bi bi-sort-alpha-down"></i>';
                    }
                    ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="../../../products/partials?<?php

                $queryString = $_GET;
                $queryString['orderBy'] = 'price';

                if ($orderBy === 'price') {
                    $queryString['orderDesc'] = $orderDesc === 'DESC' ? '0' : '1';
                } else {
                    $queryString['orderDesc'] = '0';
                }

                unset($queryString['page']);

                echo http_build_query($queryString);

                ?>" class="page-link">Prezzo</a>

                <span><?php
                    if ($orderBy === 'price') {
                        echo $orderDesc === 'DESC' ? ' <i class="bi bi-sort-numeric-up"></i>' :
                            ' <i class="bi bi-sort-numeric-down"></i>';
                    }
                    ?></span>
            </li>
        </ul>
    </div>
</div>
