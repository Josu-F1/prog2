<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'empleado') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/home_admin.css">
    <link rel="stylesheet" href="../styles/footer.css">
    <style>
        canvas {
            border-radius: 10px;
            /* Bordes redondeados */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Sombra suave para los gráficos */
            margin-bottom: 30px;
            /* Espaciado inferior */
            width: 400px !important;
            height: 400px !important;
        }

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
                <h1>Bienvenido, Empleado</h1>
                <div class="xd">
                <!-- Sección de Dashboard -->
                <div id="dashboard" class="content-section active">
                    <h2>Dashboard</h2>
                    <p>Información general sobre las tareas y alquileres actuales.</p>

                    <div class="row">
                        <div class="col-md-6">
                            <h4>Estadísticas de Alquileres</h4>
                            <canvas id="rentalStats" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h4>Vehículos Disponibles</h4>
                            <canvas id="vehiculosStats" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                </div>
                <!-- Sección de Alquileres -->
                <div id="alquileres" class="content-section">
                    <h2>Alquileres Actuales</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID de Alquiler</th>
                                <th>Cliente</th>
                                <th>Vehículo</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>001</td>
                                <td>Juan Pérez</td>
                                <td>Toyota Corolla</td>
                                <td>2024-09-20</td>
                                <td>2024-09-27</td>
                                <td>Activo</td>
                            </tr>
                            <tr>
                                <td>002</td>
                                <td>Ana Gómez</td>
                                <td>Honda Civic</td>
                                <td>2024-09-22</td>
                                <td>2024-09-29</td>
                                <td>Activo</td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                <div id="vehiculos" class="content-section">
                    <h2>Vehículos Disponibles</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID de Vehículo</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Año</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>001</td>
                                <td>Toyota</td>
                                <td>Corolla</td>
                                <td>2022</td>
                                <td>Disponible</td>
                            </tr>
                            <tr>
                                <td>002</td>
                                <td>Honda</td>
                                <td>Civic</td>
                                <td>2021</td>
                                <td>Mantenimiento</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Sección de Tareas Diarias -->
                <div id="tareas" class="content-section">
                    <h2>Tareas Diarias</h2>
                    <ul class="list-group">
                        <li class="list-group-item">Revisar estado de los vehículos</li>
                        <li class="list-group-item">Atender a los clientes</li>
                        <li class="list-group-item">Registrar nuevos alquileres</li>
                    </ul>
                </div>
            </main>
        </div>
    </div>
    <?php include '../util/footer.html'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function showSection(section) {
            // Ocultar todas las secciones de contenido
            document.querySelectorAll('.content-section').forEach((sec) => {
                sec.classList.remove('active');
            });
            // Mostrar la sección seleccionada
            document.getElementById(section).classList.add('active');
        }

        const ctxRental = document.getElementById('rentalStats').getContext('2d');
        const rentalStatsChart = new Chart(ctxRental, {
            type: 'bar',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Total de Alquileres',
                    data: [150, 120, 170, 200, 180, 220],
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxVehiculos = document.getElementById('vehiculosStats').getContext('2d');
        const vehiculosStatsChart = new Chart(ctxVehiculos, {
            type: 'pie',
            data: {
                labels: ['Disponible', 'Mantenimiento'],
                datasets: [{
                    label: 'Estado de Vehículos',
                    data: [70, 30],
                    backgroundColor: ['rgba(75, 192, 192, 0.5)', 'rgba(255, 99, 132, 0.5)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>

</html>