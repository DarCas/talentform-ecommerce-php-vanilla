<pre><?php
/**
 * @var PDO $pdo
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

CartsHelper::add($_GET['id']);

header("Location: {$_GET['returnUrl']}");
