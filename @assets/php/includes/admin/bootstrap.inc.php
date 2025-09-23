<?php

// Carico il file di configurazione
$config = require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/config.php";

require_once "{$_SERVER['DOCUMENT_ROOT']}/vendor/autoload.php";

// Carico PDO per la connessione al database
require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/database.inc.php";

if ($_SERVER['SCRIPT_NAME'] !== '/admin/@action/login.php') {
    // Siccome la pagina "/admin/@action/login.php" mi serve ad autenticare l'utente, non è necessario eseguire le
    // operazioni di verifica di autenticazione, perché ovviamente l'utente non è ancora autenticato.

    require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/functions/admin/authentication.function.php";
}

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/functions/admin/pagination.render.function.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/functions/uuidv4.function.php";
