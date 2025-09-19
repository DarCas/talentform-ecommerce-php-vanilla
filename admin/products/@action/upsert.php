<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/admin/bootstrap.inc.php";

// Accetto solo richiesta con method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mi creo una collection con i dati inviati dal form così da essere sicuri di non intercettare altri campi non attesi.
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
        if (!empty($data['id'])) {
            $errors['id'] = $data['id'];
        }

        header('Location: /admin/products/upsert.php?' . http_build_query($errors));
        exit;
    }

    if (empty($data['id'])) {
        // Creo un nuovo prodotto

        // Genero lo UUIDv4 per il prodotto
        $id = UuidV4();

        /** @var string|null $image */
        $image = null;

        if ($data['image']['size'] !== 0) {
            // Se effettivamente ho fatto un upload, salvo l'immagine nel percorso specificato di seguito

            if (!file_exists($config['filesystem']['products'])) {
                // Se la cartella non esiste, la creo

                mkdir($config['filesystem']['products'], 0755, true);
            }

            // Elaboro l'estensione dell'immagine
            $extension = ($data['image']['type'] === 'image/jpeg') ? 'jpg' : 'png';

            // Salvo l'immagine nel percorso specificato
            move_uploaded_file($data['image']['tmp_name'], "{$config['filesystem']['products']}/{$id}.{$extension}");

            // Verifico che l'immagine sia stata salvata correttamente
            if (file_exists("{$config['filesystem']['products']}/{$id}.{$extension}")) {

                // Salvo il percorso dell'immagine nel database
                $image = "{$id}.{$extension}";
            }
        }

        $insert = $pdo->prepare('
        INSERT INTO `products` (`id`, `category`, `title`, `description`, `image`, `qty`)
        VALUES (:id, :category, :title, :description, :image, :qty)');

        $insert->bindValue(':id', $id);
        $insert->bindValue(':category', $data['category']);
        $insert->bindValue(':title', $data['title']);
        $insert->bindValue(':description', $data['description']);
        $insert->bindValue(':image', $image);
        $insert->bindValue(':qty', $data['qty'], PDO::PARAM_INT);
        $insert->execute();

        header('Location: /admin/products?insert=true');
    } else {
        // Modifico un prodotto esistente

        /**
         * Variabile valorizzata con l'attualmente valore del campo "image" presente nella tabella del prodotto.
         * @var string|null $image
         */
        $image = $_POST['prevImage'] ?? null;

        if ($data['image']['size'] !== 0) {
            // Se effettivamente ho fatto un upload, salvo l'immagine nel percorso specificato di seguito

            if (!file_exists($config['filesystem']['products'])) {
                // Se la cartella non esiste, la creo

                mkdir($config['filesystem']['products'], 0755, true);
            }

            // Trovo eventuali immagini pregresse del prodotto e le cancello una per una
            $files = glob("{$config['filesystem']['products']}/{$data['id']}.{jpg,png}", GLOB_BRACE);
            foreach ($files as $file) {
                unlink($file);
            }

            // Elaboro l'estensione dell'immagine
            $extension = ($data['image']['type'] === 'image/jpeg') ? 'jpg' : 'png';

            // Salvo l'immagine nel percorso specificato
            move_uploaded_file($data['image']['tmp_name'], "{$config['filesystem']['products']}/{$data['id']}.{$extension}");

            // Verifico che l'immagine sia stata salvata correttamente
            if (file_exists("{$config['filesystem']['products']}/{$data['id']}.{$extension}")) {

                // Salvo il percorso dell'immagine nel database
                $image = "{$data['id']}.{$extension}";
            }
        }

        /** @var PDOStatement $update */
        $update = $pdo->prepare('
        UPDATE `products`
        SET
            `category` = :category,
            `title` = :title,
            `description` = :description,
            `image` = :image,
            `qty` = :qty
        WHERE `id` = :id');
        $update->bindValue(':category', $data['category']);
        $update->bindValue(':title', $data['title']);
        $update->bindValue(':description', $data['description']);
        $update->bindValue(':image', $image);
        $update->bindValue(':qty', $data['qty'], PDO::PARAM_INT);
        $update->bindValue(':id', $data['id']);
        $update->execute();

        header('Location: /admin/products?update=true');
    }
} else {
    // Reindirizzo al back-end
    header('Location: /admin');
}
