<?php
include '../../../util/conexion.php';


$id_usuario = $_POST['id_usuario'];


$sql = "DELETE FROM usuarios WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id_usuario);


if ($stmt->execute()) {    
    if ($stmt->affected_rows > 0) {
        echo "exitoso"; 
    } else {
        echo "No se encontró un usuario con ese ID."; 
    }
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>
