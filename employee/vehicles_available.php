<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'empleado') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}


include '../util/conexion.php';

$sql = "SELECT id,marca,modelo,matricula,estado,tipo_vehiculo FROM vehiculos WHERE disponibilidad='Disponible'";
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
        celda.innerHTML='No existen vechiculos disponibles';});
    </script>";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vehiculos Disponibles - Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/home_admin.css">
    <link rel="stylesheet" href="../styles/footer.css">
    <style>
        .xd{
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
    .table {
        font-family: 'Verdana', sans-serif;
        font-size: 0.9rem;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: center;
        padding: 12px;
    }

    .table thead {
        background: linear-gradient(90deg, #6a11cb, #2575fc);
        color: white;
        border-radius: 8px;
    }

    .table thead th {
        border: none;
        padding: 15px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f0f4ff;
    }

    .table-striped tbody tr:nth-of-type(even) {
        background-color: #e6edfc;
    }

    .table tbody tr:hover {
        background-color: #d1e3ff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transform: scale(1.02);
        transition: all 0.3s ease;
    }

    img {
        border: 3px solid #dee2e6;
        border-radius: 8px;
        transition: transform 0.3s, border-color 0.3s;
    }

    img:hover {
        transform: scale(1.1);
        border-color: #6a11cb;
    }

    .btn-warning {
        background-color: #fcb045;
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        background-color: #f58025;
        transform: scale(1.05);
    }

    .btn-danger {
        background-color: #ff4e50;
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #d93a40;
        transform: scale(1.05);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
        border-radius: 4px;
    }

    th, td {
        border-bottom: 1px solid #dcdcdc;
    }

    th:first-child, td:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    th:last-child, td:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
</style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php
            include './home_section/scripts/menu.php';
            ?>

            <!-- Contenido Principal -->
            <main class="col-12 mx-auto px-4 main-content d-flex flex-column h-100">
                <h1>Vehiculos Disponibles</h1>
                <div class="xd">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Matricula</th>
                            <th>Estado</th>
                            <th>Tipo vehiculo</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBody">
                        <?php foreach ($data as $vehiculo): ?>
                            <tr>
                                <th><?php echo $vehiculo['marca'] ?></th>
                                <th><?php echo $vehiculo['modelo'] ?></th>
                                <th><?php echo $vehiculo['matricula'] ?></th>
                                <th><?php echo $vehiculo['estado'] ?></th>
                                <th><?php echo $vehiculo['tipo_vehiculo'] ?></th>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            </main>
        </div>
    </div>
    <?php include '../util/footer.html'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</body>

</html>