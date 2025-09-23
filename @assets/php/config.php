<?php

use PHPMailer\PHPMailer\PHPMailer;

return [
    'dev' => ($_SERVER['HTTP_HOST'] === 'php-code.loc'),

    'filesystem' => [
        'storage' => "{$_SERVER['DOCUMENT_ROOT']}/@storage/",
        'products' => "{$_SERVER['DOCUMENT_ROOT']}/@storage/products/",
    ],

    // Dati per accedere al database MariaDB
    'mariadb' => [
        'dbhost' => 'localhost',                // Host dove è presente il database
        'dbname' => 'talentform_ecommerce',     // Il nome del database
        'dbpass' => 'FMicXiMtMnxupGkC',         // La password del database
        'dbport' => '3306',                     // La porta TCP/IP di ascolto del database
        'dbuser' => 'talentform_ecommerce',     // Il nome utente del database
    ],

    'mail' => [
//        'smtp' => [
//            'host' => 'smtp.google.com',
//            'auth' => true,
//            'username' => 'username',
//            'password' => 'password',
//            'secure' => PHPMailer::ENCRYPTION_SMTPS,    // Enable implicit TLS encryption
//            'port' => 465,                              // TCP port to connect to; use 587 if you have set `PHPMailer::ENCRYPTION_STARTTLS`
//        ],
        'smtp' => null,
        'from' => [
            'address' => 'no-replay@php-code.loc',
            'name' => 'Vanilla E-Commerce',
        ],
        'to' => [
            'address' => 'email@domain.com',
            'name' => 'Il mio nome',
        ],
    ],
];
