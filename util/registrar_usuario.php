<?php
include 'conexion.php';
// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
$tipo_usuario = 'cliente';

// Preparar la consulta SQL para insertar el nuevo usuario
$sql = "INSERT INTO usuarios (nombre, email, password, tipo_usuario) VALUES (?, ?, ?, ?)";

// Preparar la declaración
$stmt = $conn->prepare($sql);

// Vincular los parámetros
$stmt->bind_param("ssss", $nombre, $email, $password_hash, $tipo_usuario);

// Ejecutar la declaración
if ($stmt->execute()) {    
    echo json_encode(['success' => true, 'message' => '¡Usuario registrado¡']);
} else {
    echo json_encode(['success' => false, 'message' => 'No se puede registrar al usuario']);
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>