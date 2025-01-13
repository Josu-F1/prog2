<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'empleado') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}
include '../util/conexion.php';
$sql = "SELECT alquileres.id, alquileres.fecha_inicio, alquileres.fecha_fin, alquileres.estado, usuarios.nombre AS nombre_usuario, vehiculos.id AS vehiculo_id, vehiculos.matricula, vehiculos.marca, vehiculos.tarifa, alquileres.monto_esperado, vehiculos.modelo, vehiculos.imagen
        FROM alquileres
        JOIN usuarios ON alquileres.usuario_id = usuarios.id
        JOIN vehiculos ON alquileres.vehiculo_id = vehiculos.id
        WHERE alquileres.estado = 'Reservado'";
$result = mysqli_query($conn, $sql);


$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
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
            'tarifa'=> $row['tarifa']
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
        font-size: 2rem;
        font-weight: bold;
        color:rgb(255, 255, 255);
        margin-top: 20px;
    }

    .main-content {
        
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }
    .card {
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.card-title {
    font-size: 1.25rem;
    font-weight: bold;
}

.card-text {
    font-size: 0.9rem;
    color: #555;
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
        background: linear-gradient(90deg, #6a11cb,rgb(11, 12, 15));
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
        background-color:rgb(0, 95, 204);
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
        background-color:rgb(25, 29, 31);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color:rgb(20, 28, 33);
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

    ?>

    <div class="container-fluid vh-100 d-flex flex-column overflow-hidden">
        <div class="row flex-grow-1 ">
            <?php
            include './home_section/scripts/menu.php';            
            include './home_section/modals/aprobarRes.html';
            include './home_section/modals/eliminarRes.html';
            ?>
            <main class="col-12 mx-auto px-4 main-content d-flex flex-column h-100">
                <h1 class="tittle-p">Gestion de reservas</h1>
                <div id="alerta2" class="alert d-none" role="alert"></div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-4">
    <?php foreach ($data as $rent): ?>
        <div class="col">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-dark">Cliente: <?php echo $rent['nombre_usuario']; ?></h5>
                    <p class="card-text">
                        <strong>Matricula:</strong> <?php echo $rent['matricula']; ?><br>
                        <strong>Marca:</strong> <?php echo $rent['marca']; ?><br>
                        <strong>Modelo:</strong> <?php echo $rent['modelo']; ?><br>
                        <strong>Fecha Inicio:</strong> <?php echo $rent['fecha_inicio']; ?><br>
                        <strong>Fecha Fin:</strong> <?php echo $rent['fecha_fin']; ?><br>
                        <strong>Tarifa:</strong> $<?php echo $rent['tarifa']; ?> / hora<br>
                        <strong>Monto esperado:</strong> $<?php echo $rent['monto_esperado']; ?><br>
                    </p>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-warning btn-sm editar"
                            data-bs-toggle="modal"
                            data-bs-target="#editModalRent"
                            data-id="<?php echo $rent['id']; ?>">
                            Aprobar
                        </button>
                        <button class="btn btn-danger btn-sm eliminar" data-id="<?php echo $rent['id']; ?>">
                            Desaprobar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
               
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



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./home_section/js/desaprobar.js"></script>
    <script src="./home_section/js/aprobar.js"></script>
       
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
  // Inicializar Flatpickr para el campo de fecha y hora de inicio
  flatpickr("#fecha_inicio_1", {
    enableTime: true,   // Habilitar la selección de hora
    time_24hr: true,    // Formato de 24 horas
    dateFormat: "Y-m-d H:i:S",  // Formato con fecha, hora, minutos y segundos
    minuteIncrement: 1, // Incremento de 1 minuto
    secondIncrement: 1, // Incremento de 1 segundo
    onChange: function(selectedDates, dateStr, instance) {
      // Cuando se cambia la fecha de inicio, actualizar la fecha de fin
      if (selectedDates[0]) {
        const inicioDate = selectedDates[0];
        // Establecer el mínimo permitido en fecha de fin (con precisión de horas, minutos y segundos)
        const minDate = inicioDate.toLocaleString('sv-SE');  // Utiliza el formato local
        document.getElementById('fecha_fin_1')._flatpickr.set('minDate', minDate);

        // Validar la diferencia de tiempo (debe ser al menos 1 minuto)
        const fechaFin = document.getElementById('fecha_fin_1')._flatpickr.selectedDates[0];
        if (fechaFin && (fechaFin - inicioDate) < 60000) {  // 60000 ms = 1 minuto
          // Mostrar el modal de error
          var myModal = new bootstrap.Modal(document.getElementById('modalError'));
          myModal.show();
          document.getElementById('fecha_fin_1')._flatpickr.clear();
        }
      }
    }
  });

  // Inicializar Flatpickr para el campo de fecha y hora de fin
  flatpickr("#fecha_fin_1", {
    enableTime: true,   // Habilitar la selección de hora
    time_24hr: true,    // Formato de 24 horas
    dateFormat: "Y-m-d H:i:S",  // Formato con fecha, hora, minutos y segundos
    minuteIncrement: 1, // Incremento de 1 minuto
    secondIncrement: 1, // Incremento de 1 segundo
    onChange: function(selectedDates, dateStr, instance) {
      // Cuando se cambia la fecha de fin, actualizar la fecha de inicio
      if (selectedDates[0]) {
        const finDate = selectedDates[0];
        // Establecer el máximo permitido en fecha de inicio (con precisión de horas, minutos y segundos)
        const maxDate = finDate.toISOString().slice(0, 19);
        document.getElementById('fecha_inicio_1')._flatpickr.set('maxDate', maxDate);

        // Validar la diferencia de tiempo (debe ser al menos 1 minuto)
        const fechaInicio = document.getElementById('fecha_inicio_1')._flatpickr.selectedDates[0];
        if (fechaInicio && (finDate - fechaInicio) < 60000) {  // 60000 ms = 1 minuto
          // Mostrar el modal de error
          var myModal = new bootstrap.Modal(document.getElementById('modalError'));
          myModal.show();
          document.getElementById('fecha_inicio_1')._flatpickr.clear();
        }
      }
    }
  });
</script>



<script>
  // Inicializar Flatpickr para el campo de fecha y hora de inicio
  flatpickr("#fecha_inicio", {
    enableTime: true,   // Habilitar la selección de hora
    time_24hr: true,    // Formato de 24 horas
    dateFormat: "Y-m-d H:i:S",  // Formato con fecha, hora, minutos y segundos
    minuteIncrement: 1, // Incremento de 1 minuto
    secondIncrement: 1, // Incremento de 1 segundo
    onChange: function(selectedDates, dateStr, instance) {
      // Cuando se cambia la fecha de inicio, actualizar la fecha de fin
      if (selectedDates[0]) {
        const inicioDate = selectedDates[0];
        // Establecer el mínimo permitido en fecha de fin (con precisión de horas, minutos y segundos)
        const minDate = inicioDate.toLocaleString('sv-SE');  // Utiliza el formato local
        document.getElementById('fecha_fin')._flatpickr.set('minDate', minDate);

        // Validar la diferencia de tiempo (debe ser al menos 1 minuto)
        const fechaFin = document.getElementById('fecha_fin')._flatpickr.selectedDates[0];
        if (fechaFin && (fechaFin - inicioDate) < 60000) {  // 60000 ms = 1 minuto
          // Mostrar el modal de error
          var myModal = new bootstrap.Modal(document.getElementById('modalError'));
          myModal.show();
          document.getElementById('fecha_fin')._flatpickr.clear();
        }
      }
    }
  });

  // Inicializar Flatpickr para el campo de fecha y hora de fin
  flatpickr("#fecha_fin", {
    enableTime: true,   // Habilitar la selección de hora
    time_24hr: true,    // Formato de 24 horas
    dateFormat: "Y-m-d H:i:S",  // Formato con fecha, hora, minutos y segundos
    minuteIncrement: 1, // Incremento de 1 minuto
    secondIncrement: 1, // Incremento de 1 segundo
    onChange: function(selectedDates, dateStr, instance) {
      // Cuando se cambia la fecha de fin, actualizar la fecha de inicio
      if (selectedDates[0]) {
        const finDate = selectedDates[0];
        // Establecer el máximo permitido en fecha de inicio (con precisión de horas, minutos y segundos)
        const maxDate = finDate.toISOString().slice(0, 19);
        document.getElementById('fecha_inicio')._flatpickr.set('maxDate', maxDate);

        // Validar la diferencia de tiempo (debe ser al menos 1 minuto)
        const fechaInicio = document.getElementById('fecha_inicio')._flatpickr.selectedDates[0];
        if (fechaInicio && (finDate - fechaInicio) < 60000) {  // 60000 ms = 1 minuto
          // Mostrar el modal de error
          var myModal = new bootstrap.Modal(document.getElementById('modalError'));
          myModal.show();
          document.getElementById('fecha_inicio')._flatpickr.clear();
        }
      }
    }
  });
</script>




</body>

</html>