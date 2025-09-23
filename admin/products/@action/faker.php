<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

use FakerEcommerce\Ecommerce;

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

/**
 * @method televisions()
 * @method mobilePhones()
 * @method laptops()
 * @method cameras()
 * @method mensClothing()
 * @method womensClothing()
 * @method watches()
 * @method jewelry()
 */
$faker = Faker\Factory::create('it_IT');
$faker->addProvider(new Ecommerce($faker));

/** @var PDOStatement $insert */
$insert = $pdo->prepare('
    INSERT IGNORE INTO  products (`id`, `category`, `title`, `description`, `price`, `qty`, status)
    VALUES (:id, :category, :title, :description, :price, :qty, :status)');

$map = [
    'Televisions' => 'televisions',
    'MobilePhones' => 'mobilePhones',
    'Laptops' => 'laptops',
    'Cameras' => 'cameras',
    'Mens clothing' => 'mensClothing',
    'Womens clothing' => 'womensClothing',
    'Watches' => 'watches',
    'Jewelry' => 'jewelry',
];

$i = 0;

do {
    $category = $faker->randomElement([
        'Televisions',
        'MobilePhones',
        'Laptops',
        'Cameras',
        'Mens clothing',
        'Womens clothing',
        'Watches',
        'Jewelry',
    ]);

    $return = $insert->execute([
        'id' => UuidV4(),
        'category' => $category,
        'title' => substr($faker->{$map[$category]}() . " #{$faker->randomNumber(3)}", 0, 100),
        'description' => $faker->paragraph(),
        'price' => $faker->randomFloat(2, 1, 1000),
        'qty' => $faker->numberBetween(0, 100),
        'status' => $faker->numberBetween(-1, 1),
    ]);

    if ($return === true) {
        ++$i;
    }
} while ($i < 100);

header('Location: /admin/products');
