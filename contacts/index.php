<?php
/**
 * @var PDO $pdo
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

try {
    if (!empty($_GET['id']) && UuidV4Validate($_GET['id'])) {
        $select = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $select->bindValue(':id', $_GET['id'], PDO::PARAM_STR);
        $select->execute();

        $product = $select->fetch(PDO::FETCH_ASSOC);

        if (empty($product)) {
            header('HTTP/1.0 404 Not Found', true, 404);
            exit;
        }
    }
} catch (PDOException $e) {
    print_r($e);
    exit;
}

switch (true) {
    case isset($_GET['success']):
    case isset($_GET['error']):
        header("Refresh: 2; url={$_SERVER['SCRIPT_NAME']}");
        break;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <title>Contatti ~ Vanilla E-Commerce</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/header.inc.php"; ?>
</head>
<body>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/navbar.inc.php"; ?>

<main class="bg-body-tertiary">
    <div class="container py-5" style="width: 55%">
        <div class="card shadow-sm">
            <div class="card-body text-body-secondary">
                <p>Compila il seguente modulo per richiedere informazioni su acquisti e prodotti. I campi contrassegnati
                    da asterisco (*) sono obbligatori.</p>

                <form action="./@action/sendmail.php" method="post" class="row">
                    <?php
                    if (isset($product)) {
                        ?>
                        <input type="hidden" name="product" value="<?= htmlentities($product['title']); ?>">
                        <card class="col-7 mb-3">
                            <div class="card bg-body-tertiary shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-3 text-center">
                                            <img
                                                class="rounded"
                                                style="height: 64px; width: 64px; object-fit: cover;"
                                                src="<?php
                                                if (!empty($product['image'])) {
                                                    echo "/@storage/products/{$product['image']}";
                                                } else {
                                                    echo 'https://placehold.co/64x64?text=No+Image';
                                                }
                                                ?>"
                                                alt="">
                                        </div>
                                        <div class="col">
                                            <h5 class="mb-0">Richiesta informazioni</h5>
                                            <h6 class="fw-bolder"><?= htmlentities($product['title']); ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </card>
                        <?php
                    }
                    ?>

                    <div class="col-12"><?php

                        if (isset($_GET['success'])) {
                            ?>
                            <div class="alert alert-success" role="alert">
                                Il messaggio è stato inviato con successo!
                            </div>
                            <?php
                        } else if (isset($_GET['error'])) {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                Si è verificato un errore durante l'invio del messaggio.
                            </div>
                            <?php
                        }

                        ?></div>

                    <div class="col-12 pb-2">
                        <label for="inputNome" class="form-label">Nome *</label>
                        <input type="text" class="form-control form-control-lg" id="inputNome"
                               required name="fullname">
                        <div class="form-text text-danger">
                            <?= $_GET['fullname'] ?? ''; ?>
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
                        <label for="inputTelefono" class="form-label">Telefono</label>
                        <input type="tel" class="form-control form-control-lg" id="inputTelefono"
                               name="telefono">
                        <div class="form-text text-danger">
                            <?= $_GET['telefono'] ?? ''; ?>
                        </div>
                    </div>
                    <div class="col-12 py-2">
                        <label for="inputMessaggio" class="form-label">Messaggio *</label>
                        <textarea class="form-control form-control-lg" id="inputMessaggio"
                                  name="messaggio" rows="4"></textarea>
                        <div class="form-text text-danger">
                            <?= $_GET['messaggio'] ?? ''; ?>
                        </div>
                    </div>

                    <div class="col-4 offset-4">
                        <button type="submit" class="btn btn-lg w-100 block btn-success">Invia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/footer.inc.php"; ?>
</body>
</html>
