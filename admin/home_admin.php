<?php
session_start();

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}

include('../util/conexion.php');

// Consultas para obtener los datos
$usuariosQuery = "SELECT COUNT(*) AS cantidad FROM usuarios";
$vehiculosQuery = "SELECT COUNT(*) AS cantidad FROM vehiculos";
$alquileresQuery = "SELECT COUNT(*) AS cantidad FROM alquileres";

// Ejecutar las consultas y obtener resultados
$numUsuarios = $conn->query($usuariosQuery)->fetch_assoc()['cantidad'];
$numVehiculos = $conn->query($vehiculosQuery)->fetch_assoc()['cantidad'];
$numAlquileres = $conn->query($alquileresQuery)->fetch_assoc()['cantidad'];

// Obtener alquileres por mes
$query = "SELECT MONTH(fecha_inicio) AS mes, COUNT(*) AS cantidad FROM alquileres GROUP BY MONTH(fecha_inicio)";
$result = $conn->query($query);

// Crear un array con los meses y sus cantidades
$alquileres_por_mes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $alquileres_por_mes[(int)$row['mes']] = $row['cantidad'];
    }
}

// Obtener vehículos por estado
$query2 = "SELECT estado, COUNT(*) AS cantidad FROM vehiculos GROUP BY estado";
$result2 = $conn->query($query2);

$estado_vehiculos = [];
if ($result2) {
    while ($row = $result2->fetch_assoc()) {
        $estado_vehiculos[$row['estado']] = $row['cantidad'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/home_admin.css">
    <style>
        .table {
            width: 100%;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
            background-color: #ffffff;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        .table th {
            background-color:rgb(11, 11, 11);
            color: #ffffff;
            text-transform: uppercase;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tr:hover {
            background-color: #f1c40f;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <?php include './home_section/scripts/menu.php'; ?>

    <div class="container my-4">
        <h2 class="text-center mb-4">Estadísticas</h2>

        <!-- Tabla de cantidad de registros -->
        <div class="mb-4">
            <h4 class="text-center">Cantidad de Registros</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Usuarios Registrados</td>
                        <td><?php echo $numUsuarios; ?></td>
                    </tr>
                    <tr>
                        <td>Vehículos Registrados</td>
                        <td><?php echo $numVehiculos; ?></td>
                    </tr>
                    <tr>
                        <td>Alquileres Registrados</td>
                        <td><?php echo $numAlquileres; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tabla de alquileres por mes -->
        <div class="mb-4">
            <h4 class="text-center">Alquileres por Mes</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Mes</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    foreach ($meses as $indice => $mes) { 
                        $cantidad = $alquileres_por_mes[$indice + 1] ?? 0; // Usar índice + 1 porque los meses empiezan en 1
                        echo "<tr><td>$mes</td><td>$cantidad</td></tr>";
                    } 
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Tabla de vehículos por estado -->
        <div class="mb-4">
            <h4 class="text-center">Estado de los Vehículos</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $estados = ['Premium', 'Confiable', 'Aceptable'];
                    foreach ($estados as $estado) { 
                        $cantidad = $estado_vehiculos[$estado] ?? 0; // Verificar si existe el estado
                        echo "<tr><td>$estado</td><td>$cantidad</td></tr>";
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
