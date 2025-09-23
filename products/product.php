<?php
/**
 * @var PDO $pdo
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

try {
    $select = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $select->bindValue(':id', $_GET['id'], PDO::PARAM_STR);
    $select->execute();

    $product = $select->fetch(PDO::FETCH_ASSOC);

    if (empty($product)) {
        header('HTTP/1.0 404 Not Found', true, 404);
        exit;
    }

    $image = $product['image'] ?
        "/@storage/products/{$product['image']}" :
        'https://placehold.co/1920x1080.png?text=Image+Not+Found';
} catch (PDOException $e) {
    print_r($e);
    exit;
}
?>
<!DOCTYPE html>
<html lang="it" data-bs-theme="auto">
<head>
    <title><?= htmlentities($product['title']) ?> ~ Prodotti ~ Vanilla E-Commerce</title>
    <meta name="description" content="<?= htmlentities($product['description']) ?>">
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/header.inc.php"; ?>
</head>
<body>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/navbar.inc.php"; ?>

<main class="bg-body-tertiary">
    <div
        class="bg-hero shadow-sm"
        style="background-image: url('<?= $image; ?>');height: 460px;"
    ></div>

    <div class="container py-5" style="width: 55%">
        <h1 class="mb-4"><?= $product['title'] ?></h1>
        <p class="mb-4"><?= nl2br($product['description']) ?></p>

        <div class="row">
            <div class="col-6">
                <h2>
                    <?php

                    if ($product['qty'] > 0) {
                        echo 'Disponibile: ';

                        $formatter = NumberFormatter::create('it_IT', NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
                        echo $formatter->format($product['qty']);
                    } else {
                        echo '<span class="text-danger">Non disponibile</span>';
                    }


                    ?>
                </h2>
            </div>
            <div class="col-6 text-end">
                <h2>
                    Prezzo: <?php
                    $formatter = NumberFormatter::create('it_IT', NumberFormatter::CURRENCY);
                    echo $formatter->formatCurrency($product['price'], 'EUR');
                    ?>
                </h2>
            </div>

            <div class="col-6 py-4 text-end">
                <a href="/contacts/?id=<?= $product['id'] ?>"
                   class="btn btn-outline-primary">
                    <i class="bi bi-envelope"></i>
                    Richiedi informazioni
                </a>
            </div>
            <div class="col-6 py-4">
                <a href="/carts/@action/add.php?id=<?= $product['id'] ?>&returnUrl=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                   class="btn <?php

                   if (($product['qty'] <= 0) ||
                       CartsHelper::inCart($product['id'])
                   ) {
                       echo 'disabled';
                   } else {
                       echo 'btn-primary';
                   }

                   ?>">
                    <i class="bi bi-cart-plus-fill"></i>
                    Aggiungi al carrello
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/footer.inc.php"; ?>
</body>
</html>
