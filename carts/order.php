<?php
/**
 * @var PDO $pdo
 * @var array[] $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

switch (true) {
    case isset($_GET['success']):
    case isset($_GET['error']):
        header("Refresh: 2; url={$_SERVER['SCRIPT_NAME']}");
        break;
}
?>
<!DOCTYPE html>
<html lang="it" data-bs-theme="auto">
<head>
    <title>Dati d'acquisto ~ Carrello ~ Vanilla E-Commerce</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/header.inc.php"; ?>
</head>
<body>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/navbar.inc.php"; ?>

<main class="bg-body-tertiary">
    <div class="container py-5">
        <div class="row">
            <div class="col-9">
                <div class="card">
                    <div class="card-body">
                        <h2>Dati d'acquisto</h2>
                        <div class="opacity-25">
                            <hr>
                        </div>
                        <form action="./@action/confirmation.php" method="post"
                              class="row align-items-center">
                            <div class="col-12"><?php

                                if (isset($_GET['success'])) {
                                    ?>
                                    <div class="alert alert-success" role="alert">
                                        Il tuo ordine è stato inviato con successo!
                                    </div>
                                    <?php
                                } else if (isset($_GET['error'])) {
                                    ?>
                                    <div class="alert alert-danger" role="alert">
                                        Si è verificato un errore durante l'invio dell'ordine.
                                    </div>
                                    <?php
                                }

                                ?></div>

                            <div class="col-6 pb-2">
                                <label for="inputName" class="form-label">Nome *</label>
                                <input type="text" class="form-control form-control-lg" id="inputName"
                                       required name="name">
                                <div class="form-text text-danger">
                                    <?= $_GET['name'] ?? ''; ?>
                                </div>
                            </div>
                            <div class="col-6 pb-2">
                                <label for="inputSurname" class="form-label">Cognome *</label>
                                <input type="text" class="form-control form-control-lg" id="inputSurname"
                                       required name="surname">
                                <div class="form-text text-danger">
                                    <?= $_GET['surname'] ?? ''; ?>
                                </div>
                            </div>

                            <div class="col-6 pb-2">
                                <label for="inputTaxCode" class="form-label">Codice fiscale *</label>
                                <input type="text" class="form-control form-control-lg" id="inputTaxCode"
                                       maxlength="16" required name="taxCode">
                                <div class="form-text text-danger">
                                    <?= $_GET['taxCode'] ?? ''; ?>
                                </div>
                            </div>

                            <div class="col-12 pb-2">
                                <label for="inputAddress" class="form-label">Indirizzo di spedizione *</label>
                                <input type="text" class="form-control form-control-lg" id="inputAddress"
                                       required name="address">
                                <div class="form-text text-danger">
                                    <?= $_GET['address'] ?? ''; ?>
                                </div>
                            </div>

                            <div class="col-6">
                                <label for="inputEmail" class="form-label">E-mail *</label>
                                <input type="email" class="form-control form-control-lg" id="inputEmail"
                                       required name="email">
                                <div class="form-text text-danger">
                                    <?= $_GET['email'] ?? ''; ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="inputTelefono" class="form-label">Telefono *</label>
                                <input type="tel" class="form-control form-control-lg" id="inputTelefono"
                                       required name="telefono">
                                <div class="form-text text-danger">
                                    <?= $_GET['telefono'] ?? ''; ?>
                                </div>
                            </div>
                            <div class="col-12 py-2">
                                <label for="inputNote" class="form-label">Eventuali note</label>
                                <textarea class="form-control form-control-lg" id="inputNote"
                                          name="note" rows="4"></textarea>
                                <div class="form-text text-danger">
                                    <?= $_GET['note'] ?? ''; ?>
                                </div>
                            </div>

                            <div class="col-8">
                                <a href="./" class="page-link">
                                    <i class="bi bi-arrow-left"></i>
                                    Torna al carrello
                                </a>
                            </div>

                            <div class="col-4 text-end">
                                <button type="submit" class="btn btn-lg w-100 block btn-warning">Conferma l'ordine</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Totale ordine (<?= CartsHelper::count() ?> articoli):</h6>
                        <h4 class="fw-bold"><?php

                            $formatter = NumberFormatter::create('it_IT', NumberFormatter::CURRENCY);
                            echo $formatter->formatCurrency(CartsHelper::amountCart(), 'EUR');

                            ?></h4>

                        <?php
                        $products = CartsHelper::get();
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
                                <div class="col-10">
                                    <h6><?= $product['title']; ?></h6>
                                    <div><?= NumberFormat::formatCurrencyAmazon($product['price'], 'EUR'); ?></div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/footer.inc.php"; ?>
</body>
</html>
