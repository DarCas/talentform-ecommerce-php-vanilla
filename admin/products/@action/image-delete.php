<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

if (isset($_GET['id']) &&
    UuidV4Validate($_GET['id'])
) {
    // Trovo eventuali immagini del prodotto e le cancello una per una
    $files = glob("{$config['filesystem']['products']}/{$_POST['id']}.{jpg,png}", GLOB_BRACE);
    foreach ($files as $file) {
        unlink($file);
    }

    $update = $pdo->prepare('UPDATE `products` SET `image` = NULL WHERE `id` = :id');
    $update->bindValue(':id', $_GET['id']);
    $update->execute();

    header("Location: /admin/products/upsert.php?id={$_GET['id']}");
} else {
    // Reindirizzo al back-end
    header('Location: /admin');
}
