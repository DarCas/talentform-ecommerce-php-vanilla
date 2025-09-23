<?php
/**
 * @var PDO $pdo
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

try {
    /**
     * Ordinamento dei risultati
     */
    $orderBy = $_GET['orderBy'] ?? 'title';

    $orderDesc = (isset($_GET['orderDesc']) && $_GET['orderDesc']) ? 'DESC' : 'ASC';

    if (!in_array($orderBy, ['title', 'price'])) {
        $orderBy = 'title';
    }

    $filter = [
        'status = 1',
    ];

    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $filter[] = "(title LIKE '%{$_GET['q']}%' OR description LIKE '%{$_GET['q']}%')";
    }

    if (isset($_GET['cat']) && !empty($_GET['cat'])) {
        $filter[] = "category = '{$_GET['cat']}'";
    }

    /**
     * Quanti prodotti devo visualizzare
     */
    $itemsCount = $pdo->query('
    SELECT COUNT(*)
    FROM products
    WHERE ' . implode(' AND ', $filter))->fetchColumn();

    [
        $template,
        $itemsPerPage,
        $offset,
    ] = paginationRender(
        itemsPerPage: 9,
        itemsCount: $itemsCount,
        currentPage: $_GET['page'] ?? null,
        pageName: 'page'
    );

    $select = $pdo->query('
    SELECT *
    FROM products
    WHERE ' . implode(' AND ', $filter) . "
    ORDER BY {$orderBy} {$orderDesc}
    LIMIT {$itemsPerPage} OFFSET {$offset}");
    $products = $select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    print_r($e);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <title>Prodotti ~ Vanilla E-Commerce</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/header.inc.php"; ?>
</head>
<body>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/navbar.inc.php"; ?>

<main class="bg-body-tertiary">
    <div class="container py-5">
        <div class="row">
            <div class="col-8">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                    foreach ($products as $product) {
                        $image = $product['image'] ?
                            "/@storage/products/{$product['image']}" :
                            'https://placehold.co/1920x1080.png?text=Image+Not+Found';
                        ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div
                                    class="bd-placeholder-img card-img-top bg-hero"
                                    style="background-image: url('<?= $image; ?>');height: 225px"
                                ></div>
                                <div class="card-body">
                                    <p class="card-text threeline-ellipsis" style="height: 74px">
                                        <strong><?= $product['category'] ?></strong><br>
                                        <?= $product['title'] ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="/carts/@action/add.php?id=<?= $product['id'] ?>&returnUrl=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                                           class="btn btn-sm <?php

                                           if (($product['qty'] <= 0) ||
                                               CartsHelper::inCart($product['id'])
                                           ) {
                                               echo 'disabled';
                                           } else {
                                               echo 'btn-primary';
                                           }

                                           ?>">
                                            <i class="bi bi-cart-plus-fill"></i>
                                        </a>

                                        <a href="/products/product.php?id=<?= $product['id'] ?>"
                                           class="btn btn-sm btn-outline-primary">Visualizza</a>

                                        <strong class="text-success">
                                            <?php
                                            $formatter = NumberFormatter::create('it_IT', NumberFormatter::CURRENCY);
                                            echo $formatter->formatCurrency($product['price'], 'EUR');
                                            ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <?= $template ?>
                    </div>
                </div>

            </div>
            <div class="col-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        Ordinamento prodotti
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="./?<?php

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
                                <a href="./?<?php

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

                <div class="card shadow-sm">
                    <div class="card-header">
                        Categorie prodotti
                    </div>
                    <div class="card-body">
                        <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@partials/products/categories.inc.php"; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/footer.inc.php"; ?>
</body>
</html>
