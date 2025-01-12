<?php
include '../../../util/conexion.php';


$id_vehicle = $_POST['id_vehicle'];


$sql = "DELETE FROM vehiculos WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id_vehicle);


if ($stmt->execute()) {    
    if ($stmt->affected_rows > 0) {
        echo "exitoso"; 
    } else {
        echo "No se encontró un vehiculo con ese ID."; 
    }
} else {
    echo "Error: " . $stmt->error; 
}


$stmt->close();
$conn->close();
?>
