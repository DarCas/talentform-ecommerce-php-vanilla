<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

if (isset($_GET['id']) &&
    UuidV4Validate($_GET['id']) &&
    isset($_GET['status']) &&
    in_array((int)$_GET['status'], [-1, 0, 1])
) {
    $update = $pdo->prepare('
    UPDATE `products`
    SET `status` = :status
    WHERE `id` = :id');
    $update->bindValue(':id', $_GET['id']);
    $update->bindValue(':status', $_GET['status'], PDO::PARAM_INT);
    $update->execute();

    // Reindirizzo alla lista dei prodotti
    header('Location: /admin/products?status=true');
} else {
    // Reindirizzo al back-end
    header('Location: /admin');
}
