<?php
/**
 * @var PDO $pdo
 * @var array[] $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'surname' => trim($_POST['surname'] ?? ''),
            'taxCode' => trim($_POST['taxCode'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'note' => trim(strip_tags($_POST['note']) ?? ''),
        ];

        // Mi creo una collection vuota per gli errori
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Il campo nome è obbligatorio';
        } elseif (strlen($data['name']) <= 3) {
            $errors['name'] = 'Il campo nome deve essere lungo almeno 3 caratteri';
        }

        if (empty($data['surname'])) {
            $errors['surname'] = 'Il campo cognome è obbligatorio';
        } elseif (strlen($data['surname']) <= 3) {
            $errors['surname'] = 'Il campo cognome deve essere lungo almeno 3 caratteri';
        }

        if (empty($data['taxCode'])) {
            $errors['taxCode'] = 'Il campo codice fiscale è obbligatorio';
        } elseif (strlen($data['taxCode']) !== 16) {
            $errors['taxCode'] = 'Il campo codice fiscale deve essere lungo 16 caratteri';
        }

        if (empty($data['address'])) {
            $errors['address'] = 'Il campo indirizzo di spedizione è obbligatorio';
        } elseif (strlen($data['address']) > 255) {
            $errors['address'] = 'Il campo indirizzo di spedizione è troppo lungo';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Il campo e-mail è obbligatorio';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Il campo e-mail deve essere un indirizzo e-mail valido';
        }

        if (empty($data['telefono'])) {
            $errors['telefono'] = 'Il campo telefono è obbligatorio';
        }

        if (!empty($errors)) {
            header('Location: /carts/order.php?' . http_build_query($errors));
            exit;
        }

        // Avvio una transazione unica per tutte le operazioni di inserimento dei dati del carrello
        $pdo->beginTransaction();

        /** @var PDOStatement $upsert */
        $upsert = $pdo->prepare('
        INSERT INTO customers
            (id, name, surname, tax_code, address, email, telefono, note)
        VALUES
            (:id, :name, :surname, :taxCode, :address, :email, :telefono, :note)
        ON DUPLICATE KEY
            UPDATE
                address = :address,
                telefono = :telefono,
                note = :note');
        $upsert->bindValue(':id', UuidV4());
        $upsert->bindValue(':name', $data['name']);
        $upsert->bindValue(':surname', $data['surname']);
        $upsert->bindValue(':taxCode', $data['taxCode']);
        $upsert->bindValue(':address', $data['address']);
        $upsert->bindValue(':email', $data['email']);
        $upsert->bindValue(':telefono', $data['telefono']);
        $upsert->bindValue(':note', $data['note']);
        $upsert->execute();

        /** @var PDOStatement $select */
        $select = $pdo->prepare('SELECT id FROM customers WHERE tax_code = :tax_code');
        $select->execute([':tax_code' => $data['taxCode']]);

        /** @var string $customerId */
        $customerId = $select->fetchColumn();

        $insert = $pdo->prepare('
        INSERT INTO carts
            (id, customer_id, product_id, qty, create_date, status)
        VALUES
            (:id, :customer_id, :product_id, 1, NOW(), 0)');

        array_map(function ($product) use ($insert, $customerId) {
            $insert->bindValue(':id', UuidV4());
            $insert->bindValue(':customer_id', $customerId);
            $insert->bindValue(':product_id', $product['id']);
            $insert->execute();
        }, CartsHelper::get());

        // Se tutto fila liscio, a questo punto consolido le operazioni nel database
        $pdo->commit();

        CartsHelper::removeAll();

        header('Location: /carts?order=true');
    } else {
        header(
            header: 'HTTP/1.1 405 Method Not Allowed',
            response_code: 405
        );
        exit;
    }
} catch (Exception $e) {
    // Se si è verificato un errore durante le operazioni sul database, eseguo il rollback della transazione
    if ($pdo->inTransaction()) {
        $pdo->rollback();
    }
}

header('Location: /carts/order.php?error=true');
