<?php
include '../util/conexion.php';

$sql = "SELECT nombre, id FROM usuarios";
$result = mysqli_query($conn, $sql);

$dataUsers = array();
if ($result->num_rows > 0) {
  while ($row = mysqli_fetch_array($result)) {
    $dataUsers[] = array(
      'nombre' => $row['nombre'],
      'id' => $row['id']
    );
  }
} else {
  echo "no existen elementos";
}

$sqlv = "SELECT marca, modelo, matricula, imagen, id FROM vehiculos WHERE disponibilidad = 'Disponible'";
$resultv = mysqli_query($conn, $sqlv);

$datavehicles = array();
if ($resultv->num_rows > 0) {
  while ($row = mysqli_fetch_array($resultv)) {
    $datavehicles[] = array(
      'marca' => $row['marca'] ?? 'Sin Marca',
      'modelo' => $row['modelo'] ?? 'Sin Modelo',
      'matricula' => $row['matricula'] ?? 'Sin Matrícula',
      'id' => $row['id'],
      'imagen' => $row['imagen'] ?? 'placeholder.jpg'
    );
  }
} else {
  echo "";
}
?>

<div class="modal fade" id="addRentModal" tabindex="-1" aria-labelledby="addRentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRentModalLabel">Agregar Nuevo Alquiler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex">
        <!-- Formulario principal -->
        <form id="formAddRent" class="w-50">
        <div class="mb-3">
  <label for="fecha_inicio_1" class="form-label">Fecha y Hora Inicio</label>
  <input type="text" class="form-control fecha_inicio" id="fecha_inicio_1" name="fecha_inicio_1" required>
</div>
<div class="mb-3">
  <label for="fecha_fin_1" class="form-label">Fecha y Hora Fin</label>
  <input type="text" class="form-control fecha_fin" id="fecha_fin_1" name="fecha_fin_1" required>
</div>


          <div class="mb-3">
            <label for="nombre_usuario" class="form-label">Nombre Usuario</label>
            <select id="nombre_usuario" name="nombre_usuario" class="form-select" required>
              <?php foreach ($dataUsers as $user): ?>
                <option value="<?php echo $user['id']; ?>"><?php echo $user['nombre']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <input type="hidden" id="matricula_vehiculo" name="matricula_vehiculo" required>
          <p id="selectedVehicle" class="text-success"></p>
          <button type="submit" class="btn btn-primary" id="submitRentButton" disabled>Agregar Alquiler</button>
        </form>
        <!-- Lista de vehículos con búsqueda y selección -->
        <div class="w-50 ms-3">
          <label for="searchVehicle" class="form-label">Buscar Vehículo</label>
          <div class="input-group mb-3">
            <input type="text" id="searchVehicle" class="form-control" placeholder="Buscar por matrícula, marca o modelo">
            <!--<button class="btn btn-outline-secondary" id="searchButton" type="button">Buscar</button>-->
          </div>
          <ul id="vehicleList" class="list-group overflow-auto" style="max-height: 300px;">
            <?php foreach ($datavehicles as $vehicle): ?>
              <li class="list-group-item d-flex align-items-center vehicle-item"
                data-id="<?php echo $vehicle['id']; ?>"
                data-marca="<?php echo strtolower($vehicle['marca']); ?>"
                data-modelo="<?php echo strtolower($vehicle['modelo']); ?>"
                data-matricula="<?php echo strtolower($vehicle['matricula']); ?>"
                onclick="selectVehicle('<?php echo $vehicle['id']; ?>', '<?php echo $vehicle['marca']; ?>', '<?php echo $vehicle['modelo']; ?>', '<?php echo $vehicle['matricula']; ?>')">
                <img src="../images/autos/<?php echo $vehicle['imagen']; ?>" alt="Imagen de <?php echo $vehicle['marca']; ?>" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                <div>
                  <strong><?php echo $vehicle['marca']; ?> - <?php echo $vehicle['modelo']; ?></strong><br>
                  <small>Matrícula: <?php echo $vehicle['matricula']; ?></small>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>









            <!--MODAL ELIMINAR RENTA-->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar este alquiler?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>

