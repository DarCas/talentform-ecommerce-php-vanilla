<?php
/**
 * @var PDO $pdo
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

try {
    $select = $pdo->query('
    SELECT *
    FROM products
    WHERE status = 1
      AND highlight = TRUE
    ORDER BY RAND()
    LIMIT 1');
    $product = $select->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    print_r($e);
    exit;
}

if ($product) {
    $image = $product['image'] ?
        "/@storage/products/{$product['image']}" :
        'https://placehold.co/1920x1080.png?text=Image+Not+Found';
?>
<div
    class="bg-hero"
    style="background-image: url('<?= $image; ?>')"
>
    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light"><?= $product['title']; ?></h1>
                <p class="lead text-body-secondary"><?= $product['description']; ?></p>
                <p>
                    <a href="/products/product.php?id=<?= $product['id'] ?>"
                       class="btn btn-primary my-2">Maggiori informazioni</a>
                </p>
            </div>
        </div>
    </section>
</div>
<?php
}
