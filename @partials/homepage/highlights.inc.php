<?php
/**
 * @var PDO $pdo
 * @var array[] $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

try {
    $select = $pdo->query("
    SELECT *
    FROM products
    WHERE status = 1
    ORDER BY
        highlight DESC,
        update_date DESC
    LIMIT {$config['homepage']['productsHighlights']}");
    $products = $select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    print_r($e);
    exit;
}

if (count($products) > 0) {
    ?>
    <div class="album py-5 bg-body-tertiary">
        <div class="container">
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
                                <p class="card-text" style="height: 74px"><?= $product['title'] ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="/products/product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">Visualizza</a>
                                    </div>
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
        </div>
    </div>
    <?php
}
