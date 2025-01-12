<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'cliente') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}

include '../util/conexion.php';

// Obtén el ID del usuario conectado desde la sesión
$usuario_id = $_SESSION['usuario_id'];

// Prepara la consulta SQL con un parámetro
$sql = "SELECT alquileres.id, alquileres.fecha_inicio, alquileres.fecha_fin, alquileres.estado, usuarios.nombre AS nombre_usuario,
               vehiculos.id AS vehiculo_id, vehiculos.matricula, vehiculos.marca, vehiculos.tarifa, alquileres.monto_esperado,
               vehiculos.modelo, vehiculos.imagen
        FROM alquileres
        JOIN usuarios ON alquileres.usuario_id = usuarios.id
        JOIN vehiculos ON alquileres.vehiculo_id = vehiculos.id
        WHERE alquileres.usuario_id = ? AND alquileres.estado = 'Activo'";


// Prepara la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id); // El tipo 'i' indica que el parámetro es un entero
$stmt->execute();
$result = $stmt->get_result();

// Procesa los resultados
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'id' => $row['id'],
            'fecha_inicio' => $row['fecha_inicio'],
            'fecha_fin' => $row['fecha_fin'],
            'matricula' => $row['matricula'],
            'marca' => $row['marca'],
            'modelo' => $row['modelo'],
            'imagen' => $row['imagen'],
            'vehiculo_id' => $row['vehiculo_id'],
            'nombre_usuario' => $row['nombre_usuario'],
            'estado' => $row['estado'],
            'monto_esperado' => $row['monto_esperado'],
            'tarifa' => $row['tarifa']
        );
    }
}

// Libera los recursos y cierra la conexión
$stmt->close();
$conn->close();

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
    <title>Mis alquileres</title>

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

    .table th, .table td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
    }
    .table{
      --bs-table-bg: none !important;
    }
    .table thead {        
        background: linear-gradient(90deg, #6a11cb, #2575fc);
        color: white ;        
        font-size: 1rem;
    }
    .table thead th{
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

    .table th:first-child, .table td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .table th:last-child, .table td:last-child {
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
        .table th, .table td {
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
    include '../employee/home_section/modals/contrato.html';

    ?>

    <div class="container-fluid vh-100 d-flex flex-column overflow-hidden">
        <div class="row flex-grow-1 ">
            <?php
             include './util/menu.php';
            ?>
            <main class="col-12 mx-auto px-4 main-content d-flex flex-column h-100">
                <h1 class="tittle-p">Gestion de alquileres</h1>
                <div id="alerta2" class="alert d-none" role="alert"></div>
                <table class="table table-striped mt-4">
                    <thead>
                        <th>Cliente</th>
                        <th>Imagen Vehiculo</th>
                        <th>Matricula</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>  
                        <th>Tarifa <br>(por hora)</th>
                        <th>Monto esperado</th>                                                                      
                        <th>Contrato</th>
                        
                        
                    </thead>
                    <tbody id="tablaRents">
                        <?php foreach ($data as $rent): ?>
                            <tr id="rent-<?php echo $rent['id'] ?>">
                              <th class="nombre"><?php echo $rent['nombre_usuario'] ?></th>
                                <th>
                                    <img src="../images/autos/<?php echo htmlspecialchars($rent['imagen'], ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="Imagen de <?php echo $rent['modelo']; ?>"
                                        style="width: 100px; height: auto; border-radius: 5px;">
                                </th>
                                <th class="matricula"><?php echo $rent['matricula'] ?></th>
                                <th class="marca"><?php echo $rent['marca'] ?></th>
                                <th class="modelo"><?php echo $rent['modelo'] ?></th>
                                <th class="fecha_inicio"><?php echo $rent['fecha_inicio'] ?></th>
                                <th class="fecha_fin"><?php echo $rent['fecha_fin'] ?></th>                                 
                                <th class="tarifa"><?php echo $rent['tarifa'] ?></th>
                                <th class="monto_esperado"><?php echo $rent['monto_esperado'] ?></th>
                                

                                <th>
                                    <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contratoModal"
                                        data-id="<?php echo $rent['id']; ?>"
                                        data-fechaInicio="<?php echo $rent['fecha_inicio']; ?>"
                                        data-fechaInicio="<?php echo $rent['fecha_fin']; ?>"
                                        data-nombreUsuario="<?php echo $rent['nombre_usuario']; ?>"
                                        data-matricula="<?php echo htmlspecialchars($rent['matricula'], ENT_QUOTES, 'UTF-8'); ?>"
                                        data-vehiculoid="<?php echo htmlspecialchars($rent['vehiculo_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        Ver Contrato
                                    </button>
                                </th>
                                
                                                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
               
            </main>
        </div>
    </div>





    <div class="modal fade" id="modalError" tabindex="-1" aria-labelledby="modalErrorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalErrorLabel">Error de Fecha</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        La fecha de inicio y la fecha de fin deben estar separadas por al menos 1 minuto.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


    <?php include '../util/footer.html'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>    
    <script src="../employee/home_section/js/contrato.js"></script>
    

</body>

</html>