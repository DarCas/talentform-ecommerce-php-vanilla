<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";
?>
<!doctype html>
<html lang="it">
<head>
    <title>Prodotti ~ Admin</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/header.inc.php"; ?>
</head>
<body>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/navbar.inc.php"; ?>
<div class="container-fluid px-5">
    <div class="text-end mb-4">
        <a href="/admin/products/upsert.php" class="btn btn-primary shadow-sm">Aggiungi</a>
    </div>
    <table class="table table-striped shadow">
        <thead>
        <tr>
            <th scope="col">
                <!-- Visualizzare se il prodotto è in evidenza -->
            </th>
            <th scope="col">Categoria</th>
            <th scope="col">Titolo</th>
            <th scope="col">Immagine</th>
            <th scope="col">Q.tà</th>
            <th scope="col">
                <!-- Menù a tendina -->
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        /** @var PDOStatement $select */
        $select = $pdo->query('SELECT * FROM products');
        $select->execute();

        $products = $select->fetchAll(PDO::FETCH_ASSOC);

        if (count($products) > 0) {
            /** @var array $product */
            foreach ($products as $product) {
                ?>
                <tr>
                    <td></td>
                    <td><?= $product['category']; ?></td>
                    <td><?= $product['title']; ?></td>
                    <td><?= $product['image']; ?></td>
                    <td><?= $product['qty']; ?></td>
                    <td></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6" class="text-center py-5">
                    Non ci sono prodotti da visualizzare.
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/footer.inc.php"; ?>
</body>
</html>
