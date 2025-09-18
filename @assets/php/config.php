<?php

return [
    'filesystem' => [
        'storage' => "{$_SERVER['DOCUMENT_ROOT']}/@storage/",
    ],

    // Dati per accedere al database MariaDB
    'mariadb' => [
        'dbhost' => 'localhost',                // Host dove è presente il database
        'dbname' => 'talentform_ecommerce',     // Il nome del database
        'dbpass' => 'FMicXiMtMnxupGkC',         // La password del database
        'dbport' => '3306',                     // La porta TCP/IP di ascolto del database
        'dbuser' => 'talentform_ecommerce',     // Il nome utente del database
    ],
];
