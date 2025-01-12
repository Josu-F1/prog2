<?php
include '../../../util/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $fecha_fin = $_POST['fecha_fin'];
    $fecha_inicio = $_POST['fecha_inicio'];

    if (empty($id) || empty($fecha_fin) || empty($fecha_inicio)) {
        echo 'error';
        exit;
    }

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Obtener el ID del vehículo asociado al alquiler y su tarifa
        $sql_vehicle = "SELECT vehiculo_id, tarifa FROM alquileres INNER JOIN vehiculos ON alquileres.vehiculo_id = vehiculos.id WHERE alquileres.id = ?";
        $stmt_vehicle = $conn->prepare($sql_vehicle);
        $stmt_vehicle->bind_param("i", $id);
        $stmt_vehicle->execute();
        $result_vehicle = $stmt_vehicle->get_result();

        if ($result_vehicle->num_rows > 0) {
            $row_vehicle = $result_vehicle->fetch_assoc();
            $vehiculo_id = $row_vehicle['vehiculo_id'];
            $tarifa = $row_vehicle['tarifa'];

            // Calcular la diferencia en horas entre las fechas
            $datetime1 = new DateTime($fecha_inicio);
            $datetime2 = new DateTime($fecha_fin);
            $interval = $datetime1->diff($datetime2);
            $hours = $interval->days * 24 + $interval->h + ($interval->i > 0 ? 1 : 0); // Redondear hacia arriba si hay minutos

            // Si las horas son menos de 1, cobrar como mínimo 1 hora
            $hours = max(1, $hours);

            // Calcular el monto esperado
            $monto_esperado = $tarifa * $hours;

            // Actualizar las fechas y el monto_esperado
            $sql_update = "UPDATE alquileres SET fecha_fin = ?, fecha_inicio = ?, monto_esperado = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssdi", $fecha_fin, $fecha_inicio, $monto_esperado, $id);

            if ($stmt_update->execute()) {
                $conn->commit(); // Confirmar la transacción
                echo 'success';
            } else {
                $conn->rollback(); // Revertir la transacción
                echo 'error';
            }
        } else {
            $conn->rollback();
            echo 'error';
        }

        $stmt_vehicle->close();
        $stmt_update->close();
    } catch (Exception $e) {
        $conn->rollback();
        echo 'error: ' . $e->getMessage();
    }

    $conn->close();
}
?>
