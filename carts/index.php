<?php
/**
 * @var PDO $pdo
 * @var array[] $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

$products = CartsHelper::get();

switch (true) {
    case isset($_GET['order']):
    case isset($_GET['error']):
        header("Refresh: 2; url={$_SERVER['SCRIPT_NAME']}");
        break;
}
?>
<!DOCTYPE html>
<html lang="it" data-bs-theme="auto">
<head>
    <title>Carrello ~ Vanilla E-Commerce</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/header.inc.php"; ?>
</head>
<body>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/navbar.inc.php"; ?>

<main class="bg-body-tertiary">
    <div class="container py-5">
        <div class="row">
            <div class="col-<?= !count($products) ? '12' : 9 ?>">
                <div class="card">
                    <div class="card-body">
                        <h2>Carrello</h2>
                        <?php


                        if (count($products) > 0) {
                            foreach ($products as $product) {
                                ?>
                                <div class="opacity-25">
                                    <hr>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-2">
                                        <img src="<?php

                                        echo $product['image'] ?
                                            "/@storage/products/{$product['image']}" :
                                            'https://placehold.co/640x640.png?text=Image\nNot+Found';

                                        ?>" class="img-thumbnail" alt=""
                                             style="width:180px;aspect-ratio: 1; object-fit: cover">
                                    </div>
                                    <div class="col-8">
                                        <h5><?= $product['title']; ?></h5>
                                        <div><?= nl2br($product['description']) ?></div>

                                        <div class="mt-2">
                                            <div class="dropdown dropdown-menu-start">
                                                <button
                                                    class="btn btn-sm btn-danger"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                >
                                                    Togli dal carrello
                                                </button>
                                                <ul class="dropdown-menu bg-danger">
                                                    <li>
                                                        <a class="dropdown-item text-bg-danger"
                                                           href="./@action/remove.php?id=<?= $product['id'] ?>">
                                                            Conferma la cancellazione dal carrello
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col text-end">
                                        <h5 class="fw-bold">
                                            <?= NumberFormat::formatCurrencyAmazon($product['price'], 'EUR'); ?>
                                        </h5>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            if (isset($_GET['order'])) {
                                ?>
                                <div class="alert alert-info">
                                    L'ordine è stato inviato correttamente
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="alert alert-light">
                                    Il carrello è vuoto
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            if (count($products) > 0) {
                ?>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <h6>Totale provvisorio (<?= CartsHelper::count() ?> articoli):</h6>
                            <h4 class="fw-bold"><?php

                                $formatter = NumberFormatter::create('it_IT', NumberFormatter::CURRENCY);
                                echo $formatter->formatCurrency(CartsHelper::amountCart(), 'EUR');

                                ?></h4>

                            <div><a href="/carts/order.php" class="d-block btn btn-warning">Procedi all'ordine</a></div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</main>

<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/footer.inc.php"; ?>
</body>
</html>
