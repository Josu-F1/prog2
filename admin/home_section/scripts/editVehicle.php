<?php
include '../../../util/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener los datos del formulario
    $id = $_POST['id'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $matricula = $_POST['matricula'];
    $tarifa = $_POST['tarifa'];
    $estado = $_POST['estado'];
    $tipo_vehiculo = $_POST['tipo_vehiculo'];
    $disponibilidad = $_POST['disponibilidad'];    
    $tipo_transmision = $_POST['tipo_transmision'];
    $tipo_combustible = $_POST['tipo_combustible'];
    $cilindraje = $_POST['cilindraje'];
    $descripcion = $_POST['descripcion'];

    // Verificar si hay campos vacíos
    if (empty($id) || empty($marca) || empty($modelo) || empty($matricula) || empty($tarifa) || empty($estado) || empty($disponibilidad) || empty($tipo_vehiculo) || empty($tipo_transmision) || empty($tipo_combustible) || empty($cilindraje) || empty($descripcion)) {
        echo json_encode(['status' => 'error', 'message' => 'Error: Invalid input']);
        exit;
    }

    // Verificar si la tarifa es válida
    if (!is_numeric($tarifa) || $tarifa < 0) {
        echo json_encode(['status' => 'error', 'message' => 'Error: Tarifa must be a valid number greater than or equal to 0']);
        exit;
    }

    // Verificar si la matrícula ya está registrada en otro vehículo
    $sql_check_matricula = "SELECT COUNT(*) FROM vehiculos WHERE matricula = ? AND id != ?";
    $stmt_check_matricula = $conn->prepare($sql_check_matricula);
    $stmt_check_matricula->bind_param("si", $matricula, $id);
    $stmt_check_matricula->execute();
    $stmt_check_matricula->bind_result($matricula_count);
    $stmt_check_matricula->fetch();
    $stmt_check_matricula->close();

    // Si la matrícula ya está registrada en otro vehículo, devolver un error
    if ($matricula_count > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Error: La matrícula ya está registrada en otro vehículo']);
        exit;
    }

    // Preparar la consulta para actualizar los datos del vehículo
    $sql = "UPDATE vehiculos SET marca = ?, modelo = ?, matricula = ?, tarifa = ?, estado = ?, disponibilidad = ?, tipo_vehiculo = ?, tipo_transmision = ?, tipo_combustible = ?, cilindraje = ?, descripcion = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssssssssi", $marca, $modelo, $matricula, $tarifa, $estado, $disponibilidad, $tipo_vehiculo, $tipo_transmision, $tipo_combustible, $cilindraje, $descripcion, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Vehículo actualizado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar vehículo: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error en la consulta: ' . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
