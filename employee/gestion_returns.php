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
    <title>Gestión de Alquileres y Devoluciones</title>    
    <link rel="stylesheet" href="../styles/home_admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .container-fluid {
            font-family: 'Roboto', sans-serif;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card img:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 15px;
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

        /* Fondo con imagen */
        .container {
            background-image: url('../images/fondo.jpg');
            background-size: cover;
            background-position: center;
            padding: 50px;
            border-radius: 15px;
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
        <h1 class="text-center text-white">Gestión de Alquileres y Devoluciones</h1>
        <div id="alerta2" class="alert d-none" role="alert"></div>
        <div class="row">
            <?php foreach ($data as $rent): ?>
                <div class="col-md-4 col-lg-3 col-sm-6">
                    <div class="card">
                        <img src="../images/autos/<?php echo htmlspecialchars($rent['imagen'], ENT_QUOTES, 'UTF-8'); ?>" alt="Imagen de <?php echo $rent['modelo']; ?>">
                        <div class="card-body">
                        <h5 class="card-title"><?php echo $rent['matricula'] . ' / ' . $rent['marca'] . ' / ' . $rent['modelo']; ?></h5>
                            <p><strong>Cliente:</strong> <?php echo $rent['nombre_usuario']; ?></p>
                            <p><strong>Fecha de Inicio:</strong> <?php echo $rent['fecha_inicio']; ?></p>
                            <p><strong>Fecha de Fin:</strong> <?php echo $rent['fecha_fin']; ?></p>
                            <p><strong>Retorno:</strong> <?php echo $rent['fecha_devolucion'] ?? 'No disponible'; ?></p>
                            <p><strong>Higiene:</strong> <?php echo $rent['limpieza'] ?? 'No disponible'; ?></p>
                            <p><strong>Daños:</strong> <?php echo $rent['daños_visibles'] ?? 'No disponible'; ?></p>
                            <p><strong>Tanque:</strong> <?php echo $rent['nivel_combustible'] ?? 'No disponible'; ?></p>
                            <p><strong>Aspecto:</strong> <?php echo $rent['estado_vehiculo'] ?? 'No disponible'; ?></p>
                            
                            
                            <div class="d-flex justify-content-between">
                                <div>
                                    <?php if (is_null($rent['devolucion_id'])): ?>
                                        <button data-id="<?php echo $rent['alquiler_id']; ?>" data-fechaInicio="<?php echo $rent['fecha_inicio']; ?>" type="button"
                                            class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#devolucionModal">
                                            Registrar Devolución
                                        </button>
                                    <?php else: ?>
                                        
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if (!is_null($rent['devolucion_id'])): ?>
                                        <a href="./generarFactura.php?id=<?php echo $rent['devolucion_id']; ?>"
                                            class="btn btn-dark btn-sm eliminar"
                                            target="_blank">
                                            Factura
                                        </a>
                                    <?php else: ?>
                                        
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal -->

    <!-- Bootstrap CSS y JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./home_section/js/editDev.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="./home_section/js/devAdd.js"></script>
</body>

</html>