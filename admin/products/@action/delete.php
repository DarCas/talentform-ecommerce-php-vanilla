<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

// Accetto solo richiesta con method POST
if (($_SERVER['REQUEST_METHOD'] === 'POST') &&
    !empty($_POST['id']) &&
    UuidV4Validate($_POST['id'])
) {
    // Trovo eventuali immagini del prodotto e le cancello una per una
    $files = glob("{$config['filesystem']['products']}/{$_POST['id']}.{jpg,png}", GLOB_BRACE);
    foreach ($files as $file) {
        unlink($file);
    }

    // Elimino il prodotto dalla tabella del database
    $delete = $pdo->prepare('DELETE FROM `products` WHERE `id` = :id');
    $delete->execute(['id' => $_POST['id']]);

    // Reindirizzo alla lista dei prodotti
    header('Location: /admin/products?delete=true');
} else {
    // Reindirizzo al back-end
    header('Location: /admin');
}
