<?php
include '../../../util/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];

    
    if (empty($id) || empty($password)) {
        echo 'error';
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    $sql = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hashed_password, $id);

    if ($stmt->execute()) {
        echo 'success'; 
    } else {
        echo 'error'; 
    }

    $stmt->close();
    $conn->close();
}
?>
