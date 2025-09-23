<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carico il file di configurazione
$config = require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/config.php";

// Carico PDO per la connessione al database
require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/database.inc.php";

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/functions/admin/pagination.render.function.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/functions/front-end/carts-helper.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/functions/uuidv4.function.php";
