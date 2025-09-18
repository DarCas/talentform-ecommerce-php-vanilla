<?php
try {
    /**
     * Database Source Name
     */
    $dsn = "mysql:host={$config['mariadb']['dbhost']};dbname={$config['mariadb']['dbname']}";
    $pdo = new PDO($dsn, $config['mariadb']['dbuser'], $config['mariadb']['dbpass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo '<pre>';
    print_r($e);
}
