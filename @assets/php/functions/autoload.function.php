<?php
spl_autoload_register(function ($class) {
    require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/classes/{$class}.class.php";
});
