<?php
/**
 * @var PDO $pdo
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

// Accetto solo richiesta con method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mi creo una collection con i dati inviati dal form così da essere sicuri di non intercettare altri campi non
    // attesi.
    $data = [
        'passwd' => trim($_POST['passwd'] ?? ''),
        'usernm' => trim($_POST['usernm'] ?? ''),
    ];

    // Mi creo una collection vuota per gli errori
    $errors = [];

    // Devo verificare che "usernm" non sia vuoto
    if (empty($data['usernm'])) {
        $errors['usernm'] = 'Il campo username è obbligatorio';
    }
    // Devo verificare che "usernm" non sia un indirizzo e-mail formalmente valido
    elseif (filter_var($data['usernm'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['usernm'] = 'Il campo username deve essere un indirizzo e-mail valido';
    }

    // Devo verificare che "passwd" non sia vuoto
    if (empty($data['passwd'])) {
        $errors['passwd'] = 'Il campo password è obbligatorio';
    }

    // Se ci sono errori, reindirizzo alla pagina di login con gli errori
    if (!empty($errors)) {
        header('Location: /admin/login.php?' . http_build_query($errors));
        exit;
    }

    // Inizio la fase di verifica dei dati di accesso, facendo una query SELECT sulla tabella "users"

    /** @var PDOStatement $select */
    $select = $pdo->prepare('SELECT id, usernm FROM users WHERE usernm = :usernm AND passwd = :passwd');
    $select->bindValue(':usernm', $data['usernm'], PDO::PARAM_STR);
    $select->bindValue(':passwd', sha1($data['passwd']), PDO::PARAM_STR);
    $select->execute();

    // Ricevo il risultato della query
    $user = $select->fetch(PDO::FETCH_ASSOC);

    // Se non ricevo alcun risultato significa che i dati non sono corretti
    if (empty($user)) {
        header('Location: /admin/login.php?error=unauthorized');
        exit;
    }

    // Aggiungo la data dell'ultimo accesso dell'utente appena autorizzato, facendo una query INSERT sulla tabella
    // "users_logs", passando come parametro l'ID dell'utente. Nel caso in cui l'ID dell'utente è già presente nella
    // tabella, viene aggiornato il campo "latest_login", utilizzando la clausola "ON DUPLICATE KEY UPDATE".

    /** @var PDOStatement $upsert */
    $upsert = $pdo->prepare('
    INSERT INTO users_logs (user_id)
    VALUES (:user_id)
    ON DUPLICATE KEY
        UPDATE latest_login = NOW()');
    $upsert->bindValue(':user_id', $user['id'], PDO::PARAM_STR);
    $upsert->execute();

    // Imposto un cookie di sessione per l'autenticazione dell'utente, accessibile solo dalla cartella "/admin".
    setcookie('admin', "{$user['id']}:" . sha1(http_build_query($user)), 0, '/admin');

    // Reindirizzo alla home del pannello di amministrazione
    header('Location: /admin/');
} else {
    // Reindirizzo alla pagina di login senza dare ulteriori informazioni
    header('Location: /admin/login.php');
}
