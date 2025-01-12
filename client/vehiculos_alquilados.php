<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'cliente') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}

include '../util/conexion.php';

// Obtener el ID del usuario logueado
$usuario_id = $_SESSION['usuario_id'];

// Consulta para obtener los vehículos alquilados por el usuario
$sqlAlquileres = "
    SELECT 
        v.id AS vehiculo_id, 
        v.marca, 
        v.modelo, 
        v.matricula, 
        v.color, 
        v.tipo_combustible, 
        v.tipo_transmision, 
        v.tipo_vehiculo, 
        v.imagen, 
        a.fecha_inicio, 
        a.fecha_fin, 
        a.estado
    FROM alquileres a
    INNER JOIN vehiculos v ON a.vehiculo_id = v.id
    WHERE a.usuario_id = ? AND a.estado = 'Activo'
    ORDER BY a.fecha_inicio DESC
";

// Preparar la consulta
$stmt = $conn->prepare($sqlAlquileres);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

// Guardar datos de los alquileres en un arreglo
$alquileres = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $alquileres[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis vehiculos alquilados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/menu.css">
    <link rel="stylesheet" href="../styles/footer.css">
    <link rel="stylesheet" href="../styles/background.css">
    <link rel="stylesheet" href="./css/catalogo.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include './util/menu.php'; ?>
            <div class="container my-5">
        <?php if (!empty($alquileres)): ?>
            <div class="row">
                <h1>Vehiculos Alquilados</h1>
                <?php foreach ($alquileres as $alquiler): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <img src="../images/autos/<?php echo $alquiler['imagen']; ?>" class="card-img-top" alt="Imagen del vehículo">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $alquiler['marca'] . " " . $alquiler['modelo']; ?></h5>
                                <p class="card-text">
                                    <strong>Matrícula:</strong> <?php echo $alquiler['matricula']; ?><br>
                                    <strong>Color:</strong> <?php echo $alquiler['color']; ?><br>
                                    <strong>Combustible:</strong> <?php echo $alquiler['tipo_combustible']; ?><br>
                                    <strong>Transmisión:</strong> <?php echo $alquiler['tipo_transmision']; ?><br>
                                    <strong>Fecha inicio:</strong> <?php echo $alquiler['fecha_inicio']; ?><br>
                                    <strong>Fecha fin:</strong> <?php echo $alquiler['fecha_fin'] ?: 'En curso'; ?><br>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center" role="alert">
                No tienes vehículos alquilados actualmente.
            </div>
        <?php endif; ?>
    </div>
        </div>
    </div>
    <?php
  include '../util/footer.html'
  ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>