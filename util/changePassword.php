<?php
include 'conexion.php';
// Obtener los datos del formulario
$email = $_POST['email'];
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
$conn->begin_transaction();
try {
    // Preparar la consulta SQL para insertar el nuevo usuario
    $sql = $conn->prepare("UPDATE usuarios SET password=? WHERE email=?");
    $sql->bind_param("ss", $password_hash, $email);
    if (!$sql->execute()) {
        throw new Exception("Error al actualizar la contraseña");
    }
    $conn->commit();


    echo json_encode([
        'success' => true,        
        'message' => 'Contraseña actualizada correctamente'
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
