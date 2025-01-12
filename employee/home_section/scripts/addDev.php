<?php
include '../../../util/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos enviados desde el formulario del modal
    $alquiler_id = intval($_POST['alquilerId']);
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_devolucion = $_POST['fechaDevolucion'];
    $estado_vehiculo = $_POST['estadoVehiculo'];
    $limpieza = $_POST['limpieza'];
    $nivel_combustible = $_POST['nivelCombustible'];
    $costo_limpieza = floatval($_POST['costoLimpieza']);
    $costo_danos_leves = floatval($_POST['costoDanosLeves']);
    $costo_danos_graves = floatval($_POST['costoDanosGraves']);
    $costo_combustible = floatval($_POST['costoCombustible']);
    $observaciones = $_POST['observaciones'];

    $conn->begin_transaction();

    try {
        // Registrar la devolución en la base de datos (sin incluir costo_total)
        $devolucion_sql = $conn->prepare("
            INSERT INTO devoluciones (alquiler_id, fecha_devolucion, estado_vehiculo, limpieza, nivel_combustible, observaciones) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $devolucion_sql->bind_param(
            "isssss",
            $alquiler_id,
            $fecha_devolucion,
            $estado_vehiculo,
            $limpieza,
            $nivel_combustible,
            $observaciones
        );

        if ($devolucion_sql->execute()) {
            // Actualizar la disponibilidad del vehículo a 'Disponible'
            $update_vehicle_sql = $conn->prepare("
                UPDATE vehiculos 
                SET disponibilidad = 'Disponible' 
                WHERE id = (SELECT vehiculo_id FROM alquileres WHERE id = ?)
            ");
            $update_vehicle_sql->bind_param("i", $alquiler_id);

            if ($update_vehicle_sql->execute()) {
                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Devolución registrada exitosamente.']);
                exit;
            } else {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la disponibilidad del vehículo.']);
                exit;
            }
        } else {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Error al registrar la devolución.']);
            exit;
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error en la transacción: ' . $e->getMessage()]);
        exit;
    }

    mysqli_close($conn);
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']);
    exit;
}
?>
