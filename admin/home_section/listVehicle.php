<?php include '../util/conexion.php';
$sql = "SELECT id, marca, modelo, matricula, disponibilidad, tarifa, estado, color, fecha_registro, tipo_vehiculo, imagen, tipo_transmision, tipo_combustible, cilindraje, descripcion FROM vehiculos";
$result = mysqli_query($conn, $sql);

$datavehicle = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $datavehicle[] = array(
            'id' => $row['id'],
            'marca' => $row['marca'],
            'modelo' => $row['modelo'],
            'matricula' => $row['matricula'],
            'disponibilidad' => $row['disponibilidad'],
            'tarifa' => $row['tarifa'],
            'estado' => $row['estado'],
            'tipo_vehiculo' => $row['tipo_vehiculo'],
            'color' => $row['color'],
            'imagen' => $row['imagen'],
            'tipo_combustible' => $row['tipo_combustible'],
            'tipo_transmision' => $row['tipo_transmision'],
            'cilindraje' => $row['cilindraje'],
            'descripcion' => $row['descripcion'],
            'fecha_registro' => $row['fecha_registro']
        );
    }
} else {
    echo "No existen vehículos.";
}

$conn->close();
?>

<div id="listVehicles" class="content-section active">
    <!-- Fila para el buscador y el botón de agregar vehículo -->
    <div class="d-flex justify-content-between mb-3">
        <input type="text" id="searchVehicle" class="form-control w-50" placeholder="Buscar vehículo..." />
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">Agregar Vehículo</button>
    </div>

    <!-- Contenedor de tarjetas de vehículos -->
    <div id="alertaVehicle" class="alert d-none" role="alert"></div>
    <div class="row" id="cardsContainer">
        <?php foreach ($datavehicle as $vehicle) : ?>
            <div class="col-12 col-sm-6 col-md-4 mb-4" id="vehicle-<?php echo $vehicle['id']; ?>">
                <div class="card h-100">
                    <img src="../images/autos/<?php echo $vehicle['imagen']; ?>" class="card-img-top" alt="Imagen de <?php echo $vehicle['marca']; ?>" style="width: 100%; height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">Marca: <?php echo $vehicle['marca']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Modelo: <?php echo $vehicle['modelo']; ?></h6>
                        <p class="card-text">
                            <strong>Matrícula:</strong> <?php echo $vehicle['matricula']; ?><br>
                            <strong>Tarifa:</strong> <?php echo $vehicle['tarifa']; ?><br>
                            <strong>Estado:</strong> <?php echo $vehicle['estado']; ?><br>                            
                            <strong>Tipo:</strong> <?php echo $vehicle['tipo_vehiculo']; ?><br>
                            <strong>Disponibilidad:</strong> <?php echo $vehicle['disponibilidad']; ?><br>
                            <strong>Tipo de transmisión:</strong> <?php echo $vehicle['tipo_transmision']; ?><br>
                            <strong>Tipo de combustible:</strong> <?php echo $vehicle['tipo_combustible']; ?><br>
                            <strong>Cilindraje:</strong> <?php echo $vehicle['cilindraje']; ?><br>
                            <strong>Descripción:</strong> <?php echo $vehicle['descripcion']; ?><br>
                        </p>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editModalVehicle"
                            data-id="<?php echo $vehicle['id']; ?>"
                            data-editMarca="<?php echo $vehicle['marca']; ?>"
                            data-editModelo="<?php echo $vehicle['modelo']; ?>"
                            data-editMatricula="<?php echo $vehicle['matricula']; ?>"
                            data-editTarifa="<?php echo $vehicle['tarifa']; ?>"
                            data-editEstado="<?php echo $vehicle['estado']; ?>"
                            data-editTipo="<?php echo $vehicle['tipo_vehiculo']; ?>"
                            data-editDisponibilidad="<?php echo $vehicle['disponibilidad']; ?>"
                            data-editTransmision="<?php echo $vehicle['tipo_transmision']; ?>"
                            data-editCombustible="<?php echo $vehicle['tipo_combustible']; ?>"
                            data-editCilindraje="<?php echo $vehicle['cilindraje']; ?>"
                            data-editDescripcion="<?php echo $vehicle['descripcion']; ?>">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm eliminar" data-id="<?php echo $vehicle['id']; ?>">Eliminar</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<script>
    document.getElementById('searchVehicle').addEventListener('input', function () {
        var searchTerm = this.value.toLowerCase();
        var rows = document.querySelectorAll('#cardsContainer .col-md-4');

        rows.forEach(function (row) {
            var textContent = row.textContent.toLowerCase();
            if (textContent.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

