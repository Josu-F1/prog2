<?php
// Habilitar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$hostname = "localhost";
$username = "root";
$password = "";
$database = "prog";

$conn = new mysqli($hostname, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la conexión a la base de datos: " . $conn->connect_error
    ]);
    exit;
}

// Validar método de solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar datos enviados
    if (isset($_POST['editRentID'], $_POST['descripcion_devolucion'], $_POST['fecha_devolucion'], $_POST['devuelto'], $_POST['cargos_extra'], $_POST['metodo_pago'])) {
        $editRentID = intval($_POST['editRentID']);
        $descripcion_devolucion = $conn->real_escape_string($_POST['descripcion_devolucion']);
        $fecha_devolucion = $conn->real_escape_string($_POST['fecha_devolucion']);
        $devuelto = $conn->real_escape_string($_POST['devuelto']);
        $cargos_extra = floatval($_POST['cargos_extra']);
        $metodo_pago = $conn->real_escape_string($_POST['metodo_pago']);

        // Validar que el método de pago sea válido
        if (!in_array($metodo_pago, ['tarjeta', 'efectivo'])) {
            echo json_encode([
                "success" => false,
                "message" => "Método de pago inválido."
            ]);
            exit;
        }

        // Obtener la información del alquiler para calcular las tarifas
        $query = "SELECT a.fecha_inicio, v.tarifa ,a.fecha_fin
                  FROM alquileres a 
                  INNER JOIN vehiculos v ON a.vehiculo_id = v.id 
                  WHERE a.id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $editRentID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $fecha_inicio = $row['fecha_inicio'];
            $fecha_fin = $row['fecha_fin'];
            $tarifa_diaria = floatval($row['tarifa']);
        
            // Convertir las fechas a objetos DateTime
            $fecha_inicio_dt = new DateTime($fecha_inicio);
            $fecha_fin_dt = new DateTime($fecha_fin);
            $fecha_devolucion_dt = new DateTime($fecha_devolucion);
        
            // Validar que la fecha de devolución no sea anterior a la fecha de finalización
            if ($fecha_devolucion_dt < $fecha_fin_dt) {
                echo json_encode([
                    "success" => false,
                    "message" => "La fecha de devolución no puede ser anterior a la fecha de finalización del alquiler."
                ]);
                exit;
            }
        
            // Calcular el número de días entre las fechas
            $dias_alquiler = $fecha_inicio_dt->diff($fecha_fin_dt)->days;
        
            if ($dias_alquiler < 0) {
                echo json_encode([
                    "success" => false,
                    "message" => "La fecha de devolución debe ser posterior a la fecha de inicio."
                ]);
                exit;
            }
        
            // Calcular el monto de la tarifa y el monto total
            if ($dias_alquiler == 0) {
                $dias_alquiler = 1;
            }
            $monto_tarifa = $dias_alquiler * $tarifa_diaria;
            $monto_total = $monto_tarifa + $cargos_extra;
        

            // Actualizar los datos del alquiler
            $update_query = "UPDATE alquileres SET 
                descripcion_devolucion = ?, 
                fecha_devolucion = ?, 
                devuelto = ?, 
                cargos_extra = ?, 
                monto_tarifa = ?, 
                monto_total = ?, 
                metodo_pago = ? 
                WHERE id = ?";

            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param(
                "sssdddss", 
                $descripcion_devolucion, 
                $fecha_devolucion, 
                $devuelto, 
                $cargos_extra, 
                $monto_tarifa, 
                $monto_total, 
                $metodo_pago, 
                $editRentID
            );

            if ($update_stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Alquiler actualizado correctamente.",
                    "monto_tarifa" => $monto_tarifa,
                    "monto_total" => $monto_total
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Error al actualizar el alquiler: " . $update_stmt->error
                ]);
            }

            $update_stmt->close();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No se encontró el alquiler con el ID proporcionado."
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Faltan datos en la solicitud."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Método no permitido. Se requiere POST."
    ]);
}

$conn->close();
