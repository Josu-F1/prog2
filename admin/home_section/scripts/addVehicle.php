<?php
include '../../../util/conexion.php';

$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$matricula = $_POST['matricula'];
$tarifa = $_POST['tarifa'];
$estado = $_POST['estado'];
$disponibilidad = $_POST['disponibilidad'];
$tipo_transmision = $_POST['tipo_transmision'];
$tipo_combustible = $_POST['tipo_combustible'];
$cilindraje = $_POST['cilindraje'];
$descripcion = $_POST['descripcion'];
$tipo_vehiculo = $_POST['tipo_vehiculo'];

$imagenNombre = $_FILES['imagen']['name'];
$imagenTmp = $_FILES['imagen']['tmp_name'];
$directorioDestino = "../../../images/autos/";


if (!file_exists($directorioDestino)) {
    mkdir($directorioDestino, 0777, true);
}

$nombreUnicoImagen = uniqid() . "_" . basename($imagenNombre);
$rutaFinalImagen = $directorioDestino . $nombreUnicoImagen;


$checkMatricula = $conn->prepare("SELECT COUNT(*) FROM vehiculos WHERE matricula = ?");
$checkMatricula->bind_param("s", $matricula);
$checkMatricula->execute();
$checkMatricula->bind_result($count);
$checkMatricula->fetch();
$checkMatricula->close();


if ($count > 0) {
    echo json_encode(['success' => false, 'message' => 'La matrícula ya existe en el sistema.']);
    exit;
}


if (move_uploaded_file($imagenTmp, $rutaFinalImagen)) {    
    $sql = $conn->prepare("INSERT INTO vehiculos (marca, modelo, matricula, tarifa, estado, disponibilidad, imagen, tipo_combustible, tipo_transmision, cilindraje, descripcion, tipo_vehiculo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("ssssssssssss", $marca, $modelo, $matricula, $tarifa, $estado, $disponibilidad, $nombreUnicoImagen, $tipo_combustible, $tipo_transmision, $cilindraje, $descripcion, $tipo_vehiculo);

    if ($sql->execute()) {
        $id = $sql->insert_id;
        $fecha_registro = date('Y-m-d H:i:s');
        echo json_encode([
            'success' => true,
            'id' => $id,
            'marca' => $marca,
            'modelo' => $modelo,
            'matricula' => $matricula,
            'tarifa' => $tarifa,
            'estado' => $estado,
            'disponibilidad' => $disponibilidad,
            'imagen' => $nombreUnicoImagen,
            'fecha_registro' => $fecha_registro,
            'tipo_combustible' => $tipo_combustible,
            'tipo_transmision' => $tipo_transmision,
            'cilindraje' => $cilindraje,
            'tipo_vehiculo' => $tipo_vehiculo,
            'descripcion' => $descripcion
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al insertar en la base de datos.']);
    }

    $sql->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error al subir la imagen.']);
}

mysqli_close($conn);
?>
