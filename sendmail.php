<?php
header('Content-Type: application/json; charset=utf-8');

function respond($success, $message, $status = 200)
{
    http_response_code($status);
    echo json_encode(array('success' => $success, 'message' => $message), JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(false, 'Método no permitido.', 405);
}

// Honeypot: los usuarios reales dejan este campo vacío.
if (!empty($_POST['website'])) {
    respond(true, 'Gracias por escribirnos. Nos pondremos en contacto con usted a la brevedad.');
}

$name    = isset($_POST['name']) ? trim((string) $_POST['name']) : '';
$email   = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim((string) $_POST['subject']) : '';
$message = isset($_POST['message']) ? trim((string) $_POST['message']) : '';

$name    = str_replace(array("\r", "\n", "%0a", "%0d"), ' ', $name);
$email   = str_replace(array("\r", "\n", "%0a", "%0d"), '', $email);
$subject = str_replace(array("\r", "\n", "%0a", "%0d"), ' ', $subject);

if ($name === '' || $email === '' || $subject === '' || $message === '') {
    respond(false, 'Por favor, complete todos los campos requeridos.', 422);
}

$email = filter_var($email, FILTER_VALIDATE_EMAIL);
if ($email === false) {
    respond(false, 'Por favor, ingrese un correo electrónico válido.', 422);
}

if (mb_strlen($name, 'UTF-8') > 100 || mb_strlen($subject, 'UTF-8') > 200 || mb_strlen($message, 'UTF-8') > 5000) {
    respond(false, 'Uno o más campos exceden la longitud permitida.', 422);
}

$to = 'ventas@econtrisac.com';
$encodedSubject = '=?UTF-8?B?' . base64_encode('[Web] ' . $subject) . '?=';

$body  = "Nuevo mensaje desde el formulario de contacto de econtrisac.com\n\n";
$body .= "Nombre: " . $name . "\n";
$body .= "Correo: " . $email . "\n";
$body .= "Asunto: " . $subject . "\n\n";
$body .= "Mensaje:\n" . $message . "\n";

$headers  = "From: Econtrisac Web <no-reply@econtrisac.com>\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

if (mail($to, $encodedSubject, $body, $headers)) {
    respond(true, 'Gracias por escribirnos. Nos pondremos en contacto con usted a la brevedad.');
}

respond(false, 'No se pudo enviar el mensaje. Por favor, inténtelo nuevamente más tarde.', 500);
