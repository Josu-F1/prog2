<?php
include '../../../util/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];
    $nombre = $_POST['nombre'];
    $tipo_usuario = $_POST['tipo_usuario'];

    if (empty($id) || empty($password)) {
        echo 'error';
        exit;
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update query including 'nombre' and 'tipo_usuario'
    $sql = "UPDATE usuarios SET password = ?, nombre = ?, tipo_usuario = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $hashed_password, $nombre, $tipo_usuario, $id);

    if ($stmt->execute()) {
        echo 'success'; 
    } else {
        echo 'error'; 
    }

    $stmt->close();
    $conn->close();
}
?>