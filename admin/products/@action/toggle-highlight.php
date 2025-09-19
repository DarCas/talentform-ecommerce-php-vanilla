<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

if (isset($_GET['id']) &&
    UuidV4Validate($_GET['id'])
) {
    // Aggiorno lo stato di highlight del prodotto, invertendo il valore corrente
    $update = $pdo->prepare('
    UPDATE products
    SET highlight = IF(highlight, FALSE, TRUE)
    WHERE id = :id');
    $update->bindValue(':id', $_GET['id']);
    $update->execute();

    // Reindirizzo alla lista dei prodotti
    header('Location: /admin/products?status=true');
} else {
    // Reindirizzo al back-end
    header('Location: /admin');
}
