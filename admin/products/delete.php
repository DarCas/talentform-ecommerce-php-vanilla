<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

if (empty($_GET['id']) || !UuidV4Validate($_GET['id'])) {
    header('Location: /admin/products?' . http_build_query(['error' => "L'ID non è valido"]));
    exit;
}

/** @var PDOStatement $select */
$select = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$select->execute(['id' => $_GET['id']]);

$product = $select->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="it" style="height: 100dvh;">
<head>
    <title>Cancella il prodotto ~ Prodotti ~ Admin</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/header.inc.php"; ?>
</head>
<body style="height: 100dvh;">
<main class="container" style="height: 100dvh;align-content: center;max-width: 600px;">
    <form
        action="./@action/delete.php"
        class="card shadow"
        method="post"
    >
        <input type="hidden" name="id" value="<?= $product['id']; ?>">

        <div class="card-header shadow-sm">
            Cancella il prodotto
        </div>
        <div class="card-body">
            <p>Sei sicuro di voler cancellare il prodotto "<i><?= $product['title']; ?></i>"?</p>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-6">
                    <a href="./index.php" class="btn btn-success">No, annulla</a>
                </div>
                <div class="col-6 text-end">
                    <button type="submit" class="btn btn-danger">Sì, sono sicuro</button>
                </div>
            </div>
        </div>
    </form>
</main>

<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/footer.inc.php"; ?>
</body>
</html>
