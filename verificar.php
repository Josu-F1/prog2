<?php
function verificarEmail($email) {
    list($usuario, $dominio) = explode('@', $email);

    // Verificar si el dominio tiene registros MX
    if (!checkdnsrr($dominio, 'MX')) {
        return "El dominio no tiene servidores de correo válidos.";
    }

    // Conectar al servidor SMTP
    $servidor = gethostbyname($dominio);
    $puerto = 25;
    $conexion = fsockopen($servidor, $puerto);

    if (!$conexion) {
        return "No se pudo conectar al servidor SMTP.";
    }

    // Comandos SMTP
    fputs($conexion, "HELO servidor.local\r\n");
    fputs($conexion, "MAIL FROM:<prueba@servidor.local>\r\n");
    fputs($conexion, "RCPT TO:<$email>\r\n");
    $respuesta = fgets($conexion, 1024);

    fclose($conexion);

    if (strpos($respuesta, "250") !== false) {
        return "El correo parece existir.";
    } else {
        return "El correo no existe.";
    }
}

// Prueba de ejemplo
echo verificarEmail("usuario@ejemplo.com");


?>