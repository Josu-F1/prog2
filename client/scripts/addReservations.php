<?php
include '../../util/conexion.php';

$id_vehiculo = intval($_POST['id_vehiculo']);
$id_usuario = intval($_POST['id_usuario']);
$fecha_fin = $_POST['fecha_fin'];
$fecha_inicio = $_POST['fecha_inicio'];

$conn->begin_transaction();

try {    
    // Obtener la tarifa del vehículo
    $tarifa_sql = $conn->prepare("SELECT tarifa FROM vehiculos WHERE id = ?");
    $tarifa_sql->bind_param("i", $id_vehiculo);
    $tarifa_sql->execute();
    $result_tarifa = $tarifa_sql->get_result();

    if ($result_tarifa->num_rows > 0) {
        $row_tarifa = $result_tarifa->fetch_assoc();
        $tarifa = $row_tarifa['tarifa'];

        // Calcular la diferencia en horas entre las fechas
        $datetime1 = new DateTime($fecha_inicio);
        $datetime2 = new DateTime($fecha_fin);
        $interval = $datetime1->diff($datetime2);
        $hours = $interval->days * 24 + $interval->h + ($interval->i > 0 ? 1 : 0); // Redondear hacia arriba si hay minutos

        // Si las horas son menos de 1, cobrar como mínimo 1 hora
        $hours = max(1, $hours);

        // Calcular el monto esperado
        $monto_esperado = $tarifa * $hours;

        // Insertar el alquiler con el monto esperado y el estado inicial "Reservado"
        $sql = $conn->prepare("INSERT INTO alquileres (vehiculo_id, usuario_id, fecha_inicio, fecha_fin, monto_esperado, estado) VALUES (?, ?, ?, ?, ?, 'Reservado')");
        $sql->bind_param("iissd", $id_vehiculo, $id_usuario, $fecha_inicio, $fecha_fin, $monto_esperado);

        if ($sql->execute()) {
            $id_alquiler = $sql->insert_id;

            // Actualizar la disponibilidad del vehículo
            $update_sql = $conn->prepare("UPDATE vehiculos SET disponibilidad = 'No disponible' WHERE id = ?");
            $update_sql->bind_param("i", $id_vehiculo);

            if ($update_sql->execute()) {
                // Obtener detalles del vehículo
                $vehicle_sql = $conn->prepare("SELECT marca, modelo, matricula, imagen FROM vehiculos WHERE id = ?");
                $vehicle_sql->bind_param("i", $id_vehiculo);
                $vehicle_sql->execute();
                $result_vehicle = $vehicle_sql->get_result();

                if ($result_vehicle->num_rows > 0) {
                    $vehicle = $result_vehicle->fetch_assoc();

                    // Obtener el nombre del usuario
                    $usuario_sql = $conn->prepare("SELECT nombre FROM usuarios WHERE id = ?");
                    $usuario_sql->bind_param("i", $id_usuario);
                    $usuario_sql->execute();
                    $result_usuario = $usuario_sql->get_result();

                    if ($result_usuario->num_rows > 0) {
                        $usuario = $result_usuario->fetch_assoc();

                        // Confirmar la transacción
                        $conn->commit();

                        // Enviar respuesta
                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => true,
                            'id' => $id_alquiler,
                            'fecha_inicio' => $fecha_inicio,
                            'fecha_fin' => $fecha_fin,
                            'id_usuario' => $id_usuario,
                            'marca' => $vehicle['marca'],
                            'modelo' => $vehicle['modelo'],
                            'matricula' => $vehicle['matricula'],
                            'imagen' => $vehicle['imagen'],
                            'nombre' => $usuario['nombre'],
                            'id_vehiculo' => $id_vehiculo,
                            'monto_esperado' => $monto_esperado
                        ]);
                        exit;
                    } else {
                        throw new Exception('Error: No existe el usuario.');
                    }
                } else {
                    throw new Exception('Error: No existe el vehículo.');
                }
            } else {
                throw new Exception('Error al actualizar la disponibilidad del vehículo.');
            }
        } else {
            throw new Exception('Error al insertar el alquiler.');
        }
    } else {
        throw new Exception('Error: No se encontró la tarifa del vehículo.');
    }
} catch (Exception $e) {
    $conn->rollback();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
} finally {
    $tarifa_sql->close();
    if (isset($sql)) $sql->close();
    if (isset($update_sql)) $update_sql->close();
    if (isset($vehicle_sql)) $vehicle_sql->close();
    if (isset($usuario_sql)) $usuario_sql->close();
    mysqli_close($conn);
}
