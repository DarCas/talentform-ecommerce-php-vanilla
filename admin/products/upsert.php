<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";
?>
<!doctype html>
<html lang="it">
<head>
    <title>Aggiunti un prodotto ~ Prodotti ~ Admin</title>
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
        <input type="hidden" name="id" value="<?= $_GET['id'] ?? '' ?>">

        <div class="card-header shadow-sm">
            Aggiungi un prodotto
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <label for="formCategory" class="form-label">Categoria *</label>
                    <input type="text" class="form-control" id="formCategory" name="category" maxlength="100" required>
                    <div class="form-text text-danger"><?= $_GET['category'] ?? ''; ?></div>
                </div>
                <div class="col-6">
                    <label for="formTitle" class="form-label">Titolo *</label>
                    <input type="text" class="form-control" id="formTitle" name="title" maxlength="100" required>
                    <div class="form-text text-danger"><?= $_GET['title'] ?? ''; ?></div>
                </div>

                <div class="col-12">
                    <label for="formDescription" class="form-label">Descrizione</label>
                    <textarea class="form-control" name="description" id="formDescription" rows="10" maxlength="65535"></textarea>
                    <div class="form-text text-danger"><?= $_GET['description'] ?? ''; ?></div>
                </div>

                <div class="col-6">
                    <label for="formImage" class="form-label">Immagine</label>
                    <input type="file" class="form-control" id="formImage" name="image" accept="image/jpeg,image/png">
                    <div class="form-text text-danger"><?= $_GET['image'] ?? ''; ?></div>
                </div>
                <div class="col-6">
                    <label for="formQty" class="form-label">Quantità *</label>
                    <input type="number" min="0" max="65535" step="1" value="0" class="form-control" id="formTitle" name="qty" required>
                    <div class="form-text text-danger"><?= $_GET['qty'] ?? ''; ?></div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary">Salva il prodotto</button>
        </div>
    </form>
</div>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/footer.inc.php"; ?>
</body>
</html>
