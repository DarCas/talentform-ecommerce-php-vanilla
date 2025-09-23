<pre><?php
/**
 * @var PDO $pdo
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!in_array($_GET['id'], $_SESSION['cart'])) {
    $_SESSION['cart'][] = $_GET['id'];
}

header("Location: {$_GET['returnUrl']}");
