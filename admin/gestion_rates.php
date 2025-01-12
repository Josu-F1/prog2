<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}

include '../util/conexion.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_vehiculo = $_POST['tipo_vehiculo'];
    $duracion_alquiler = $_POST['duracion_alquiler'];
    $temporada = $_POST['temporada'];
    $precio = $_POST['precio'];


    $sql = "INSERT INTO tarifas (tipo_vehiculo, duracion_alquiler, temporada, precio) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $tipo_vehiculo, $duracion_alquiler, $temporada, $precio);
    $stmt->execute();
    $stmt->close();

    echo "Tarifa agregada correctamente";
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tarifas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/home_admin.css">
    <style>
        .eliminarR {
            color: black;
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

        /* General */
        .sidebar {
            transition: transform 0.3s ease-in-out;
            height: 100vh;
            position: fixed;
            z-index: 1030;
            top: 0;
            left: 0;
            width: 250px;
            overflow-y: auto;
            background-color: #f8f9fa;
        }

        /* Ocultar el menú por defecto en pantallas pequeñas */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            /* Mostrar el menú cuando el checkbox esté marcado */
            #menu-toggle:checked~.sidebar {
                transform: translateX(0);
            }

            /* Ajustes al encabezado del menú */
            .sidebar h4 {
                font-size: 1.2rem;
            }

            .nav-item .nav-link {
                font-size: 1rem;
            }
        }

        /* Botón para alternar el menú */
        .sidebar-toggler-label {
            display: none;
            position: fixed;

            background-color: #ffffff;
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            font-size: 1.5rem;
            z-index: 1040;
            cursor: pointer;
        }

        .sidebar-toggler-label i {
            color: #333;
        }

        /* Mostrar el botón solo en pantallas pequeñas */
        @media (max-width: 768px) {
            .sidebar-toggler-label {
                display: inline-block;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <?php
            include './home_section/scripts/menu.php';
            ?>

            <!-- Contenido Principal -->
            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <h1 class="mt-5">Gestión de Tarifas</h1>
                <div class="xd">
                    <!-- Formulario para agregar tarifas -->
                    <form method="post" class="mt-4">
                        <div class="mb-3">
                            <label for="tipo_vehiculo" class="form-label">Tipo de Vehículo</label>
                            <select type="text" class="form-control" id="tipo_vehiculo" name="tipo_vehiculo" required>
                                <option value="camion">camion</option>
                                <option value="camioneta">camioneta</option>
                                <option value="auto">auto</option>
                                <option value="maquinaria">maquinaria</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="duracion_alquiler" class="form-label">Duración del Alquiler (en días)</label>
                            <input type="number" class="form-control" id="duracion_alquiler" name="duracion_alquiler" required>
                        </div>
                        <div class="mb-3">
                            <label for="temporada" class="form-label">Temporada</label>
                            <select class="form-control" id="temporada" name="temporada" required>
                                <option value="alta">Alta</option>
                                <option value="baja">Baja</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Tarifa</button>
                    </form>
                </div>
                <!-- Mostrar lista de tarifas -->
                <h2 class="mt-5">Lista de Tarifas</h2>
                <div class="xd">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tipo de Vehículo</th>
                                <th>Duración del Alquiler</th>
                                <th>Temporada</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Mostrar las tarifas almacenadas
                            $result = $conn->query("SELECT * FROM tarifas");
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>{$row['tipo_vehiculo']}</td>
                                <td>{$row['duracion_alquiler']} días</td>
                                <td>{$row['temporada']}</td>
                                <td>\${$row['precio']}</td>
                            </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Al final del archivo antes de cerrar el </body> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>