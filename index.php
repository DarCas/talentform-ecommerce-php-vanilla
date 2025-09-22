<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <title>Vanilla E-Commerce</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/header.inc.php"; ?>
</head>
<body>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/navbar.inc.php"; ?>

<main>
    <?php

    require_once "{$_SERVER['DOCUMENT_ROOT']}/@partials/homepage/hero.inc.php";
    require_once "{$_SERVER['DOCUMENT_ROOT']}/@partials/homepage/highlights.inc.php";

    ?>
</main>

<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/partials/footer.inc.php"; ?>
</body>
</html>
