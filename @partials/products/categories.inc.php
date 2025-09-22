<?php
/**
 * @var PDO $pdo
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

$filter = [
    'status = 1',
];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $filter[] = "(title LIKE '%{$_GET['q']}%' OR description LIKE '%{$_GET['q']}%')";
}

function countItemsByCategory(string $category, array $filter): int
{
    global $pdo;

    $filter[] = 'category = :category';

    $count = $pdo->prepare('
    SELECT COUNT(*)
    FROM products
    WHERE ' . implode(' AND ', $filter));
    $count->execute([':category' => $category]);

    return $count->fetchColumn();
}

$select = $pdo->query('
SELECT DISTINCT(category) AS category
FROM products
WHERE status = 1
ORDER BY category');

$categories = $select->fetchAll(PDO::FETCH_COLUMN);

if (count($categories) > 0) {
    ?>
    <ul class="list-group list-group-flush">
        <?php
        if (isset($_GET['cat'])) {
            ?>
            <li class="list-group-item text-end">
                <a class="page-link" href="./"><small>Disattiva filtro</small></a>
            </li>
            <?php
        }

        foreach ($categories as $category) {
            $count = countItemsByCategory($category, $filter);

            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a class="page-link <?php

                if (isset($_GET['cat']) && $_GET['cat'] === $category) {
                    echo 'disabled fw-bolder';
                } else if (!$count) {
                    echo 'disabled';
                }

                ?>" href="./?cat=<?= htmlentities($category); ?>"><?= $category; ?></a>
                <span class="badge text-bg-primary rounded-pill"><?= $count; ?></span>
            </li>
            <?php
        }
        ?>

    </ul>
    <?php
}
