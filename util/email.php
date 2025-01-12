<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

// Generar cuatro dígitos aleatorios
$digito1 = rand(0, 9);
$digito2 = rand(0, 9);
$digito3 = rand(0, 9);
$digito4 = rand(0, 9);

// Construir el código de seguridad
$codigo = strval($digito1 . $digito2 . $digito3 . $digito4);

// Almacenar el código en una cookie con una duración de 5 minutos
setcookie('codigoCorreo', $codigo, time() + 300, "/");

try {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    $email = "adrianmora8180@gmail.com"; // Cambia esto por tu correo
    $pass = "bpws ojjw fzqp tonb";      // Cambia esto por tu contraseña de aplicación
    $para = $_POST["email"];
    $htmlContent = file_get_contents('../util/modalemail.html');

    // Insertar los dígitos en la plantilla HTML
    $htmlContent = str_replace('<span id="digit-1"></span>', $digito1, $htmlContent);
    $htmlContent = str_replace('<span id="digit-2"></span>', $digito2, $htmlContent);
    $htmlContent = str_replace('<span id="digit-3"></span>', $digito3, $htmlContent);
    $htmlContent = str_replace('<span id="digit-4"></span>', $digito4, $htmlContent);

    // Configuración de PHPMailer
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $email;
    $mail->Password = $pass;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    //$mail->setFrom($email, 'AUTORENT');
    $mail->addAddress($para);

    $mail->Subject = 'Codigo de seguridad';
    $mail->msgHTML($htmlContent);

    // Enviar el correo
    $mail->send();
    echo json_encode(array(
        'error' => false,
        'message' => 'Correo enviado',
        'digito1' => $digito1,
        'digito2' => $digito2,
        'digito3' => $digito3,
        'digito4' => $digito4
    ));
} catch (Exception $e) {
    echo json_encode(array(
        'error' => true,
        'message' => 'Error al enviar: ' . $mail->ErrorInfo
    ));
}
?>
