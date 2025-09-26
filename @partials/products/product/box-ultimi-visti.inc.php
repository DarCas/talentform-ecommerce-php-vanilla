<?php
$products = LatestProductsView::get();

if (count($products) > 0) {
    ?>
    <div class="card shadow-sm">
        <div class="card-header">
            Prodotti visti di recente
        </div>
        <div class="card-body pb-0">
            <?php
            foreach ($products as $product) {
                $image = $product['image'] ?
                    "/@storage/products/{$product['image']}" :
                    'https://placehold.co/640x640.png?text=Image\nNot+Found';
                ?>
                <a href="/products/product.php?id=<?= $product['id'] ?>"
                   class="card mb-3"
                   style="text-decoration:none"
                >
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="<?= $image ?>"
                                 class="img-fluid rounded-start"
                                 alt=""
                                 style="aspect-ratio: 1; width: 100%; object-fit: cover;"
                            >
                        </div>
                        <div class="col-md-8">
                            <div class="card-body d-flex align-items-center" style="height: 100%;">
                                <div>
                                    <div class="twoline-ellipsis">
                                        <strong><?= $product['category'] ?></strong>
                                        <?= $product['title'] ?>
                                    </div>
                                    <div class="fw-bold mt-1"><?php

                                        $formatter = NumberFormatter::create('it_IT', NumberFormatter::CURRENCY);
                                        echo $formatter->formatCurrency($product['price'], 'EUR');

                                        ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}
