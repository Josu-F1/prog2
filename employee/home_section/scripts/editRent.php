<?php
include '../../../util/conexion.php';

$id_vehicle = $_POST['id_vehiculo'];
$id_usuario = $_POST['id_usuario'];
$id_alquiler = $_POST['id_alquiler'];

$conn->begin_transaction();

try {
    function fetchSingleResult($conn, $query, $types, ...$params) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("No se encontraron datos para la consulta: $query");
        }
        return $result->fetch_assoc();
    }

    // Obtener los datos actuales
    $vehiculo_actual = fetchSingleResult($conn, "SELECT id, marca, modelo, matricula, tarifa FROM vehiculos WHERE id = ?", "i", $id_vehicle);
    $usuario_actual = fetchSingleResult($conn, "SELECT id, nombre FROM usuarios WHERE id = ?", "i", $id_usuario);
    $alquiler_actual = fetchSingleResult($conn, "SELECT vehiculo_id, usuario_id, fecha_inicio, fecha_fin FROM alquileres WHERE id = ?", "i", $id_alquiler);

    $vehiculo_antiguo = fetchSingleResult($conn, "SELECT id, marca, modelo, matricula FROM vehiculos WHERE id = ?", "i", $alquiler_actual['vehiculo_id']);
    $usuario_antiguo = fetchSingleResult($conn, "SELECT id, nombre FROM usuarios WHERE id = ?", "i", $alquiler_actual['usuario_id']);

    // Calcular la duración del alquiler en horas
    $datetime1 = new DateTime($alquiler_actual['fecha_inicio']);
    $datetime2 = new DateTime($alquiler_actual['fecha_fin']);
    $interval = $datetime1->diff($datetime2);
    $hours = $interval->days * 24 + $interval->h + ($interval->i > 0 ? 1 : 0); // Redondear hacia arriba si hay minutos
    $hours = max(1, $hours); // Mínimo 1 hora

    // Calcular el nuevo monto esperado
    $tarifa_actual = $vehiculo_actual['tarifa'];
    $monto_esperado = $tarifa_actual * $hours;

    // Actualizar el alquiler con el nuevo vehículo, usuario, y monto esperado
    $stmt = $conn->prepare("UPDATE alquileres SET usuario_id = ?, vehiculo_id = ?, monto_esperado = ? WHERE id = ?");
    $stmt->bind_param("iidi", $id_usuario, $id_vehicle, $monto_esperado, $id_alquiler);
    if (!$stmt->execute()) {
        throw new Exception("Error al actualizar el alquiler");
    }

    // Actualizar disponibilidad del vehículo actual
    $stmt2 = $conn->prepare("UPDATE vehiculos SET disponibilidad = 'No disponible' WHERE id = ?");
    $stmt2->bind_param("i", $id_vehicle);
    if (!$stmt2->execute()) {
        throw new Exception("Error al actualizar el vehículo actual");
    }

    // Actualizar disponibilidad del vehículo antiguo
    $stmt3 = $conn->prepare("UPDATE vehiculos SET disponibilidad = 'Disponible' WHERE id = ?");
    $stmt3->bind_param("i", $vehiculo_antiguo['id']);
    if (!$stmt3->execute()) {
        throw new Exception("Error al actualizar el vehículo antiguo");
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'vehiculo_antiguo' => $vehiculo_antiguo,
        'usuario_antiguo' => $usuario_antiguo,
        'vehiculo_actual' => $vehiculo_actual,
        'usuario_actual' => $usuario_actual,
        'tarifa_actual' => $tarifa_actual,
        'monto_esperado' => $monto_esperado,
        'message' => 'Alquiler actualizado exitosamente'
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>
