<?php
/**
 * @var PDO $pdo
 */

// Verifico se il cookie di autenticazione sia settato e abbia del contenuto
if (!isset($_COOKIE['admin']) &&
    !empty($_COOKIE['admin'])
) {
    // Reindirizzo alla pagina di login
    header('Location: /admin/login.php');
    exit;
} else {
    [$id, $hash] = explode(':', $_COOKIE['admin']);

    /** @var PDOStatement $select */
    $select = $pdo->prepare('SELECT id, usernm FROM users WHERE id = :id');
    $select->bindValue(':id', $id, PDO::PARAM_STR);
    $select->execute();

    $user = $select->fetch(PDO::FETCH_ASSOC);

    if (empty($user) ||
        (sha1(http_build_query($user)) !== $hash)
    ) {
        // Reindirizzo alla pagina di login
        header('Location: /admin/login.php');
        exit;
    }
}
