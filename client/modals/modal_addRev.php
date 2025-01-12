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

<div class="modal fade" id="addRevModal" tabindex="-1" aria-labelledby="addRentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRentModalLabel">Agregar Nuevo Alquiler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario principal -->
        <form id="formAddRent">
          <!-- Selección de fechas -->
          <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha y Hora Inicio</label>
            <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
          </div>
          <div class="mb-3">
            <label for="fecha_fin" class="form-label">Fecha y Hora Fin</label>
            <input type="text" class="form-control" id="fecha_fin" name="fecha_fin" required>
          </div>

          <!-- Vehículo seleccionado dinámicamente -->
          <div id="vehicleInfo" class="mb-3">
            <!-- Aquí se llenará la información del vehículo -->
            <p class="text-muted">Selecciona un vehículo para ver su información.</p>
          </div>

          <!-- Campos ocultos -->
          <input type="hidden" id="id_usuario" name="id_usuario" value="">
          <input type="hidden" id="id_vehiculo" name="id_vehiculo" value="">

          <!-- Botón para agregar alquiler -->
          <button type="submit" class="btn btn-primary" id="submitRentButton">Agregar Alquiler</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Lista de vehículos (se renderizan fuera del modal) -->
<ul class="d-none">
  <?php foreach ($datavehicles as $vehicle): ?>
    <li 
      class="vehicle-item" 
      data-id="<?php echo $vehicle['id']; ?>" 
      data-marca="<?php echo $vehicle['marca']; ?>" 
      data-modelo="<?php echo $vehicle['modelo']; ?>" 
      data-matricula="<?php echo $vehicle['matricula']; ?>">
      <img 
        src="../images/autos/<?php echo $vehicle['imagen']; ?>" 
        alt="Imagen de <?php echo $vehicle['marca']; ?>" 
        style="display: none;">
    </li>
  <?php endforeach; ?>
</ul>









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

