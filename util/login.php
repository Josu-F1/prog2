<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include 'conexion.php';

$error = '';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT id, password, tipo_usuario FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($password, $usuario['password'])) {
        
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

        echo json_encode(['success' => true, 'tipo_usuario' => $usuario['tipo_usuario']]);
        exit();
    } else {        
        $error = "La contraseña es incprrecta";
    }
} else {    
    $error = "No se encontró el usuario, por favor registrarse.";
}

$stmt->close();
$conn->close();

echo json_encode(['success' => false, 'error' => $error]);
exit();
?>
