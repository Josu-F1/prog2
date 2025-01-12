<?php
include '../../../util/conexion.php';

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password_hash = password_hash($_POST['password'],PASSWORD_DEFAULT);
$tipo_usuario = $_POST['tipo_usuario'];


$sql = $conn->prepare("INSERT INTO usuarios (nombre, email, password, tipo_usuario) VALUES (?, ?, ?, ?)");
$sql->bind_param("ssss", $nombre, $email, $password_hash, $tipo_usuario);

if ($sql->execute()) {
    $id = $sql->insert_id;
    //$fecha_registro = date('Y-m-d H:i:s');
    
    echo json_encode([
        'success' => true,
        'id' => $id,
        'nombre' => $nombre,
        'email'=> $email,        
        'tipo_usuario'=>$tipo_usuario,
        //'fecha_registro' => $fecha_registro
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al insertar usuario']);
}

$sql->close();
mysqli_close($conn);
?>