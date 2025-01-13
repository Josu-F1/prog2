<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'empleado') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}

include '../util/conexion.php';

$sql = "SELECT id, marca, modelo, matricula, estado, tipo_vehiculo FROM vehiculos WHERE disponibilidad='Disponible'";
$result = mysqli_query($conn, $sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $data[] = array(
            'id' => $row['id'],
            'marca' => $row['marca'],
            'modelo' => $row['modelo'],
            'matricula' => $row['matricula'],
            'estado' => $row['estado'],
            'tipo_vehiculo' => $row['tipo_vehiculo']
        );
    }
} else {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tabla = document.getElementById('tablaBody');
        var fila = tabla.insertRow();
        var celda = fila.insertCell(0);
        celda.innerHTML='No existen vehículos disponibles';});
    </script>";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehículos Disponibles - Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/home_admin.css">
    <link rel="stylesheet" href="../styles/footer.css">
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            width: 18rem;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-body {
            padding: 0;
        }

        .card-text {
            margin-bottom: 10px;
        }

        .btn {
            border-radius: 5px;
        }

        .xd {
            display: flex;
            width: 90%;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 10px;
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include './home_section/scripts/menu.php'; ?>

        <!-- Contenido Principal -->
        <main class="col-12 mx-auto px-4 main-content d-flex flex-column h-100">
            <h1>Vehículos Disponibles</h1>
            <div class="xd">
                <?php foreach ($data as $vehiculo): ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $vehiculo['marca'] ?> - <?php echo $vehiculo['modelo'] ?></h5>
                            <p class="card-text"><strong>Matricula:</strong> <?php echo $vehiculo['matricula'] ?></p>
                            <p class="card-text"><strong>Estado:</strong> <?php echo $vehiculo['estado'] ?></p>
                            <p class="card-text"><strong>Tipo Vehículo:</strong> <?php echo $vehiculo['tipo_vehiculo'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>