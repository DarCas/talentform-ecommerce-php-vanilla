<?php

// Distruggo il cookie di autenticazione
setcookie('admin', '', -1, '/admin');

// Reindirizzo alla pagina di login
header('Location: /admin/login.php');
