<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

// Accetto solo richiesta con method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mi creo una collection con i dati inviati dal form così da essere sicuri di non intercettare altri campi non
    // attesi.
    $data = [
        'category' => trim($_POST['category'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'id' => trim($_POST['id'] ?? ''),
        'image' => $_FILES['image'],
        'qty' => trim($_POST['qty'] ?? ''),
        'title' => trim($_POST['title'] ?? ''),
    ];

    // Mi creo una collection vuota per gli errori
    $errors = [];

    if (empty($data['category'])) {
        $errors['category'] = 'Il campo categoria è obbligatorio';
    } elseif (strlen($data['category']) <= 3) {
        $errors['category'] = 'Il campo categoria deve essere lungo almeno 3 caratteri';
    }

    if (!empty($data['id']) &&
        !UuidV4Validate($data['id'])
    ) {
        $errors['id'] = 'Non è possibile modificare il prodotto indicato';
    }

    if (($data['image']['error'] === UPLOAD_ERR_OK) &&
        !in_array($data['image']['type'], ['image/jpeg', 'image/png'])
    ) {
        $errors['image'] = 'Il file deve essere di tipo JPEG o PNG';
    }

    if (!is_numeric($data['qty'])) {
        $errors['qty'] = 'Il campo quantità deve contenere solo numeri';
    } elseif (($data['qty'] < 0) ||
        ($data['qty'] > 65_535)
    ) {
        $errors['qty'] = 'Il campo quantità deve essere compreso tra 0 e 65.535';
    }

    if (empty($data['title'])) {
        $errors['title'] = 'Il campo titolo è obbligatorio';
    } elseif (strlen($data['title']) <= 3) {
        $errors['title'] = 'Il campo titolo deve essere lungo almeno 3 caratteri';
    }

    // Se ci sono errori, reindirizzo alla pagina di login con gli errori
    if (!empty($errors)) {
        header('Location: /admin/products/upsert.php?' . http_build_query($errors));
        exit;
    }

    if (empty($data['id'])) {
        if (!file_exists($config['filesystem']['storage'])) {
            mkdir("{$config['filesystem']['storage']}/products", 0755, true);
        }

        $insert = $pdo->prepare('
        INSERT INTO `products` (`id`, `category`, `title`, `description`, `image`, `qty`)
        VALUES (:id, :category, :title, :description, :image, :qty)');

        $insert->bindValue(':id', $data['id']);
        $insert->bindValue(':category', $data['category']);
        $insert->bindValue(':title', $data['title']);
        $insert->bindValue(':description', $data['description']);
        $insert->bindValue(':image', $data['image']);
        $insert->bindValue(':qty', $data['qty'], PDO::PARAM_INT);
//        $insert->execute();

        header('Location: /admin/products/upsert.php?success=true');
    } else {
        // Aggiorno il prodotto
    }
} else {
    // Reindirizzo al back-end
    header('Location: /admin');
}
