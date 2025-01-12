<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'empleado') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}

include '../util/conexion.php';

// Consulta para obtener datos de alquileres y devoluciones
$sql = "SELECT 
            alquileres.id AS alquiler_id,
            alquileres.fecha_inicio,
            alquileres.fecha_fin,
            alquileres.estado,
            alquileres.devuelto,
            usuarios.nombre AS nombre_usuario,
            vehiculos.matricula,
            vehiculos.marca,
            vehiculos.modelo,
            vehiculos.imagen,
            devoluciones.id AS devolucion_id,
            devoluciones.fecha_devolucion,
            devoluciones.estado_vehiculo,
            devoluciones.limpieza,
            devoluciones.nivel_combustible,
            devoluciones.daños_visibles,
            devoluciones.costo_total,
            devoluciones.observaciones
        FROM alquileres
        JOIN usuarios ON alquileres.usuario_id = usuarios.id
        JOIN vehiculos ON alquileres.vehiculo_id = vehiculos.id
        LEFT JOIN devoluciones ON devoluciones.alquiler_id = alquileres.id";

$result = mysqli_query($conn, $sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $data[] = array(
            'alquiler_id' => $row['alquiler_id'],
            'fecha_inicio' => $row['fecha_inicio'],
            'fecha_fin' => $row['fecha_fin'],
            'matricula' => $row['matricula'],
            'marca' => $row['marca'],
            'modelo' => $row['modelo'],
            'imagen' => $row['imagen'],
            'nombre_usuario' => $row['nombre_usuario'],
            'estado' => $row['estado'],
            'devuelto' => $row['devuelto'],
            'devolucion_id' => $row['devolucion_id'],
            'fecha_devolucion' => $row['fecha_devolucion'],
            'estado_vehiculo' => $row['estado_vehiculo'],
            'limpieza' => $row['limpieza'],
            'nivel_combustible' => $row['nivel_combustible'],
            'daños_visibles' => $row['daños_visibles'],
            'costo_total' => $row['costo_total'],
            'observaciones' => $row['observaciones']
        );
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/footer.css">

    
    
    <title>Gestión de Devoluciones</title>    


    <link rel="stylesheet" href="../styles/home_admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .xd {
            display: flex;
            width: 90%;
            height: 90%;
            background-color: aliceblue;
            border-radius: 20px;
            padding: 10px;
            overflow-x: scroll;
        }
    </style>
    <style>
        /* General layout styles */
        .container-fluid {
            font-family: 'Roboto', sans-serif;
        }

        .tittle-p {
            font-size: 1.5rem;
            font-weight: bold;
            color: #343a40;
            margin-top: 20px;
        }

        .main-content {

            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .table {
            font-size: 0.9rem;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin: 20px 0;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }

        .table {
            --bs-table-bg: none !important;
        }

        .table thead {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
            color: white;
            font-size: 1rem;
        }

        .table thead th {
            color: white;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }

        .table tbody tr:nth-child(even) {
            background-color: #e9ecef;
        }

        .table tbody tr:hover {
            background-color: #dee2e6;
            transform: scale(1.01);
            transition: all 0.2s ease-in-out;
        }

        .table th:first-child,
        .table td:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .table th:last-child,
        .table td:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        img {
            width: 80px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        img:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn {
            font-size: 0.85rem;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .btn-warning {
            background-color: #f1c40f;
            color: white;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e67e22;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .table th,
            .table td {
                font-size: 0.8rem;
            }

            img {
                width: 60px;
            }
        }

        @media (max-width: 576px) {
            .table {
                font-size: 0.8rem;
            }

            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 16px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                overflow: hidden;
            }

            .table tbody tr td {
                display: flex;
                justify-content: space-between;
                padding: 10px;
            }

            .table tbody tr td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #6c757d;
                margin-right: 10px;
            }

            img {
                width: 50px;
            }
        }
    </style>
</head>

<body>
    <?php
    include './home_section/modals/contrato.html';
    include './home_section/modals/modal_editDev.html';
    include './home_section/modals/devAdd.html';
    include './home_section/scripts/menu.php'
    ?>

    <div class="container mt-4">
        <h1 class="text-center">Gestión de Alquileres y Devoluciones</h1>
        <div id="alerta2" class="alert d-none" role="alert"></div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Vehículo</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Cliente</th>
                    <th>Acciones</th>
                    <th>Fecha Devolución</th>
                    <th>Estado Vehículo</th>
                    <th>Limpieza</th>
                    <th>Nivel Combustible</th>
                    <th>Daños Visibles</th>
                    <th>Costo Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $rent): ?>
                    <tr>
                        <td>
                            <img src="../images/autos/<?php echo htmlspecialchars($rent['imagen'], ENT_QUOTES, 'UTF-8'); ?>"
                                alt="Imagen de <?php echo $rent['modelo']; ?>">
                        </td>
                        <td><?php echo $rent['matricula'] . ' / ' . $rent['marca'] . ' / ' . $rent['modelo']; ?></td>
                        <td><?php echo $rent['fecha_inicio']; ?></td>
                        <td><?php echo $rent['fecha_fin']; ?></td>
                        <td><?php echo $rent['nombre_usuario']; ?></td>
                        <td>
                            <?php if (is_null($rent['devolucion_id'])): ?>
                                <button data-id="<?php echo $rent['alquiler_id']; ?>" data-fechaInicio="<?php echo $rent['fecha_inicio']; ?>" type="button"
                                    class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#devolucionModal">
                                    Registrar Devolución
                                </button>
                            <?php else: ?>
                                <span class="text-success">Devolución registrada</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $rent['fecha_devolucion'] ?? 'N/A'; ?></td>
                        <td><?php echo $rent['estado_vehiculo'] ?? 'N/A'; ?></td>
                        <td><?php echo $rent['limpieza'] ?? 'N/A'; ?></td>
                        <td><?php echo $rent['nivel_combustible'] ?? 'N/A'; ?></td>
                        <td><?php echo $rent['daños_visibles'] ?? 'N/A'; ?></td>
                        <td><?php echo is_null($rent['costo_total']) ? 'N/A' : '$' . number_format($rent['costo_total'], 2); ?></td>
                        <td>
                            <?php if (!is_null($rent['devolucion_id'])): ?>
                                <a href="./generarFactura.php?id=<?php echo $rent['devolucion_id']; ?>"
                                    class="btn btn-danger btn-sm eliminar"
                                    target="_blank">
                                    Generar PDF Factura
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No disponible</span>
                            <?php endif; ?>
                        </td>


                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>







    <!-- Botón para abrir el modal -->


    <!-- Modal -->


    <!-- Bootstrap CSS y JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php include '../util/footer.html'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./home_section/js/editDev.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="./home_section/js/devAdd.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const camposFecha = document.querySelectorAll(".fecha_fin");

            // Obtener la fecha actual
            const hoy = new Date();
            hoy.setDate(hoy.getDate()); // Fecha mínima es hoy
            const fechaMinima = hoy.toISOString().split("T")[0];

            // Establecer el atributo min para todos los campos
            camposFecha.forEach((campo) => {
                campo.setAttribute("min", fechaMinima);

                // Validar en el evento de cambio
                campo.addEventListener("change", function() {
                    const fechaSeleccionada = new Date(campo.value);
                    if (fechaSeleccionada <= hoy) {
                        alert("Por favor, selecciona una fecha mayor a hoy.");
                        campo.value = ""; // Limpiar el campo
                    }
                });
            });
        });
    </script>
    <script>

    </script>

</body>

</html>