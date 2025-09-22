<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

function isStatusActive(int $status, int $check): string
{
    return $status === $check ? 'bi-check-square' : 'bi-square';
}

function getStatus(int $status): string
{
    return match ($status) {
        -1 => 'Cestinato',
        0 => 'Bozza',
        1 => 'Pubblicato',
    };
}

switch (true) {
    case isset($_GET['insert']):
    case isset($_GET['update']):
    case isset($_GET['delete']):
    case isset($_GET['status']):
        header("Refresh: 2; url={$_SERVER['SCRIPT_NAME']}");
        break;
}

?>
<!doctype html>
<html lang="it">
<head>
    <title>Prodotti ~ Admin</title>
    <?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/header.inc.php"; ?>
</head>
<body>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/navbar.inc.php"; ?>
<div class="container-fluid px-5">
    <div class="row align-items-center">
        <div class="col-6">
            <?php
            if (isset($_GET['insert'])) {
                ?>
                <div class="alert alert-success" role="alert">
                    Elemento aggiunto con successo!
                </div>
                <?php
            } else if (isset($_GET['update'])) {
                ?>
                <div class="alert alert-success" role="alert">
                    Elemento modificato con successo!
                </div>
                <?php
            } else if (isset($_GET['delete'])) {
                ?>
                <div class="alert alert-success" role="alert">
                    Elemento cancellato con successo!
                </div>
                <?php
            } else if (isset($_GET['status'])) {
                ?>
                <div class="alert alert-success" role="alert">
                    Lo stato dell'elemento è stato modificato con successo!
                </div>
                <?php
            } else if (isset($_GET['error'])) {
                ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_GET['error']; ?>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="col-6 text-end pb-4">
            <a href="/admin/products/upsert.php" class="btn btn-secondary">Aggiungi</a>
            <a href="./@action/faker.php" class="btn btn-outline-secondary">
                <i class="bi bi-shuffle"></i>
            </a>
        </div>
    </div>

    <?php
    /**
     * Quanti prodotti devo visualizzare
     */
    $itemsCount = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();

    [
        $template,
        $itemsPerPage,
        $offset,
    ] = paginationRender(10, $itemsCount, $_GET['p'] ?? null)
    ?>

    <table class="table table-striped shadow">
        <thead>
        <tr>
            <th scope="col" style="width: 74px">&nbsp;</th>
            <th scope="col" style="width: 74px">&nbsp;</th>
            <th scope="col">Categoria</th>
            <th scope="col">Titolo</th>
            <th scope="col">Q.tà</th>
            <th scope="col">Prezzo</th>
            <th scope="col" style="width: 150px">
                <!-- Menù a tendina -->
            </th>
        </tr>
        <tr>
            <td colspan="7"><?= $template; ?></td>
        </tr>
        </thead>

        <tfoot>
        <tr>
            <td colspan="7"><?= $template; ?></td>
        </tr>
        </tfoot>

        <tbody>
        <?php
        /** @var PDOStatement $select */
        $select = $pdo->query("SELECT * FROM products LIMIT {$itemsPerPage} OFFSET {$offset}");
        $select->execute();

        $products = $select->fetchAll(PDO::FETCH_ASSOC);

        if (count($products) > 0) {
            /** @var array $product */
            foreach ($products as $product) {
                ?>
                <tr>
                    <td style="vertical-align: middle" class="text-center">
                        <a href="./@action/toggle-highlight.php?id=<?= $product['id']; ?>">
                            <i class="bi bi-star<?= $product['highlight'] ? '-fill' : '' ?>"></i>
                        </a>
                    </td>
                    <td>
                        <img
                            class="rounded"
                            style="height: 64px; width: 64px; object-fit: cover;"
                            src="<?php
                            if (!empty($product['image'])) {
                                echo "/@storage/products/{$product['image']}";
                            } else {
                                echo 'https://placehold.co/64x64?text=No+Image';
                            }
                            ?>"
                            alt="">
                    </td>
                    <td style="vertical-align: middle"><?= $product['category']; ?></td>
                    <td style="vertical-align: middle"><?= $product['title']; ?></td>
                    <td style="vertical-align: middle" class="text-end"><?php

                        $formatter = NumberFormatter::create('it_IT', NumberFormatter::GROUPING_SEPARATOR_SYMBOL);
                        echo $formatter->format($product['qty']);

                        ?></td>
                    <td style="vertical-align: middle" class="text-end"><?php

                        $formatter = NumberFormatter::create('it_IT', NumberFormatter::CURRENCY);
                        echo $formatter->formatCurrency($product['price'], 'EUR');

                        ?></td>
                    <td style="vertical-align: middle" class="text-end">
                        <div class="dropdown dropdown-menu-end">
                            <button
                                class="btn btn-outline-secondary dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <?= getStatus($product['status']); ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><h6 class="dropdown-header text-uppercase">Stato pubblicazione</h6></li>
                                <li>
                                    <a class="dropdown-item"
                                       href="./@action/change-status.php?id=<?= $product['id']; ?>&status=-1"
                                    >
                                        <i class="bi <?= isStatusActive($product['status'], -1) ?> me-1"></i>
                                        Cestinato
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                       href="./@action/change-status.php?id=<?= $product['id']; ?>&status=0"
                                    >
                                        <i class="bi <?= isStatusActive($product['status'], 0) ?> me-1"></i>
                                        Bozza
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item"
                                       href="./@action/change-status.php?id=<?= $product['id']; ?>&status=1"
                                    >
                                        <i class="bi <?= isStatusActive($product['status'], 1) ?> me-1"></i>
                                        Pubblicato
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item text-primary" href="./upsert.php?id=<?= $product['id']; ?>">
                                        <i class="bi bi-pencil me-1"></i>
                                        Modifica
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item text-danger" href="./delete.php?id=<?= $product['id']; ?>">
                                        <i class="bi bi-trash me-1"></i>
                                        Cancella
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="7" class="text-center py-5">
                    Non ci sono prodotti da visualizzare.
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/partials/footer.inc.php"; ?>
</body>
</html>
