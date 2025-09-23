<?php
/**
 * @var PDO $pdo
 * @var array $config
 */

require_once "{$_SERVER['DOCUMENT_ROOT']}/@assets/php/includes/front-end/bootstrap.inc.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

try {
    $data = [
        'fullname' => trim($_POST['fullname'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'messaggio' => trim(strip_tags($_POST['messaggio']) ?? ''),
        'product' => trim($_POST['product'] ?? ''),
    ];

    // Mi creo una collection vuota per gli errori
    $errors = [];

    if (empty($data['fullname'])) {
        $errors['fullname'] = 'Il campo nome è obbligatorio';
    } elseif (strlen($data['category']) <= 3) {
        $errors['fullname'] = 'Il campo nome deve essere lungo almeno 3 caratteri';
    }

    if (empty($data['email'])) {
        $errors['email'] = 'Il campo e-mail è obbligatorio';
    } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Il campo e-mail deve essere un indirizzo e-mail valido';
    }

    if (empty($data['messaggio'])) {
        $errors['messaggio'] = 'Il campo messaggio è obbligatorio';
    } elseif (strlen($data['messaggio']) <= 20) {
        $errors['messaggio'] = 'Il campo messaggio deve essere lungo almeno 20 caratteri';
    }

    // Se ci sono errori, reindirizzo alla pagina di login con gli errori
    if (!empty($errors)) {
        header('Location: /contacts?' . http_build_query($errors));
        exit;
    }

    /**
     * Configuriamo il server di invio della posta elettronica
     */
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    if (!is_null($config['mail']['smtp'])) {
        $mail->isSMTP();
        $mail->Host = $config['mail']['smtp']['host'];
        $mail->SMTPAuth = $config['mail']['smtp']['auth'];

        if ($config['mail']['smtp']['auth']) {
            $mail->Username = $config['mail']['smtp']['username'];
            $mail->Password = $config['mail']['smtp']['password'];
            $mail->SMTPSecure = $config['mail']['smtp']['secure'];
        }

        $mail->Port = $config['mail']['smtp']['port'];
    } else {
        $mail->isMail();
    }

    /**
     * Configuriamo il mittente e il destinatario della posta elettronica
     */

    $mail->setFrom($config['mail']['from']['address'], $config['mail']['from']['name']);
    $mail->addAddress($config['mail']['to']['address'], $config['mail']['to']['name']);
    $mail->addReplyTo($data['email'], $data['fullname']);

    /**
     * Configuriamo il contenuto della posta elettronica
     */
    $mail->isHTML();

    if (!empty($data['product'])) {
        $mail->Subject = 'Richiesta informazioni su prodotto da Vanilla E-Commerce';

        $template = file_get_contents(__DIR__ . '/contact-product.html');
        $template = str_replace('{{product}}', $data['product'], $template);
    } else {
        $mail->Subject = 'Contatto dal sito Vanilla E-Commerce';

        $template = file_get_contents(__DIR__ . '/contact-simple.html');
    }

    $template = str_replace('{{fullname}}', $data['fullname'], $template);
    $template = str_replace('{{email}}', $data['email'], $template);
    $template = str_replace('{{telefono}}', $data['telefono'], $template);
    $template = str_replace('{{messaggio}}', nl2br($data['messaggio']), $template);

    $mail->Body = $template;
    $mail->AltBody = strip_tags($template);

    if ($mail->send()) {
        header('Location: /contacts?success=true');
        exit();
    }
} catch (Exception $e) {
}

header('Location: /contacts?error=true');
