<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

// Variabile di comodo utile per sapere se sto creando o modificando un prodotto
$edit = false;

if (!empty($_GET['id']) && !UuidV4Validate($_GET['id'])) {
    header('Location: /admin/products?' . http_build_query(['error' => "L'ID non è valido"]));
    exit;
} else if (!empty($_GET['id'])) {
    /** @var PDOStatement $select */
    $select = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $select->execute(['id' => $_GET['id']]);

    $product = $select->fetch(PDO::FETCH_ASSOC);

    if (empty($product)) {
        header('Location: /admin/products?' . http_build_query(['error' => "Il prodotto non esiste"]));
        exit;
    }

    $edit = true;
}
?>
<!doctype html>
<html lang="it">
<head>
    <title><?= $edit ? 'Modifica il prodotto' : 'Aggiungi il prodotto'; ?> ~ Prodotti ~ Admin</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/header.inc.php"; ?>
</head>
<body>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/navbar.inc.php"; ?>
<div class="container px-5">
    <form
        action="./@action/upsert.php"
        class="card shadow"
        enctype="multipart/form-data"
        method="post"
    >
        <input type="hidden" name="id" value="<?= $product['id'] ?? '' ?>">

        <div class="card-header shadow-sm">
            <?= $edit ? 'Modifica il prodotto' : 'Aggiungi il prodotto'; ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <label for="formCategory" class="form-label">Categoria *</label>
                    <input type="text" class="form-control" id="formCategory" list="categories"
                           name="category" maxlength="100" required
                           value="<?= htmlentities($product['category'] ?? ''); ?>">
                    <datalist id="categories"><?php
                        // Stampo la lista delle categorie già presenti nella tabella "products" del database e la uso
                        // come lista di scelta per il campo "category" del form

                        /** @var PDOStatement $select */
                        $select = $pdo->query('SELECT DISTINCT(`category`) AS `category` FROM products ORDER BY `category`');
                        $select->execute();

                        foreach ($select->fetchAll(PDO::FETCH_ASSOC) as $category) {
                            ?>
                            <option value="<?= htmlentities($category['category']); ?>">
                                <?= $category['category']; ?>
                            </option>
                            <?php
                        }

                        ?></datalist>
                    <div class="form-text text-danger"><?= $_GET['category'] ?? ''; ?></div>
                </div>
                <div class="col-6">
                    <label for="formTitle" class="form-label">Titolo *</label>
                    <input type="text" class="form-control" id="formTitle"
                           name="title" maxlength="100" required
                           value="<?= htmlentities($product['title'] ?? ''); ?>">
                    <div class="form-text text-danger"><?= $_GET['title'] ?? ''; ?></div>
                </div>

                <div class="col-12">
                    <label for="formDescription" class="form-label">Descrizione</label>
                    <textarea class="form-control" name="description" id="formDescription"
                              rows="10" maxlength="65535"><?= $product['description'] ?? ''; ?></textarea>
                    <div class="form-text text-danger"><?= $_GET['description'] ?? ''; ?></div>
                </div>

                <?php
                if (!empty($product['image'])) {
                    ?>
                    <input type="hidden" name="prevImage" value="<?= htmlentities($product['image']); ?>">
                    <div class="col-1">
                        <div class="dropdown">
                            <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img
                                    class="img-thumbnail"
                                    src="/@storage/products/<?= $product['image']; ?>"
                                    style="width: 100%; aspect-ratio: 1; object-fit: cover;"
                                    alt="">
                            </button>
                            <ul class="dropdown-menu text-bg-danger">
                                <li>
                                    <a class="dropdown-item text-bg-danger"
                                       href="./@action/image-delete.php?id=<?= $_GET['id'] ?? ''; ?>">
                                        <i class="bi bi-trash me-1"></i>
                                        Cancella immagine
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="col-<?= empty($product['image']) ? '6' : '5' ?>">
                    <label for="formImage" class="form-label">Immagine</label>
                    <input type="file" class="form-control" id="formImage" name="image" accept="image/jpeg,image/png">
                    <div class="form-text text-danger"><?= $_GET['image'] ?? ''; ?></div>
                </div>

                <div class="col-6">
                    <label for="formQty" class="form-label">Quantità *</label>
                    <input type="number" min="0" max="65535" step="1"
                           class="form-control" id="formTitle" name="qty" required
                           value="<?= htmlentities($product['qty'] ?? '0'); ?>">
                    <div class="form-text text-danger"><?= $_GET['qty'] ?? ''; ?></div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-6">
                    <a href="./" class="btn btn-success">Annulla</a>
                </div>
                <div class="col-6 text-end">
                    <button type="submit" class="btn btn-primary">Salva il prodotto</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/footer.inc.php"; ?>
</body>
</html>
