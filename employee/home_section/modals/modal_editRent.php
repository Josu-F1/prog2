<div class="modal fade" id="editModalRent" tabindex="-1" aria-labelledby="editModalRentLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: white; color: black;">
      <div class="modal-header" style="background-color: white; color: black;">
        <h5 class="modal-title" id="editModalRentLabel">Cambio de alquiler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario principal -->
        <form id="formEditRent">
          <input type="hidden" name="id" id="editRentID">
          <div class="mb-3">
            <label for="nombre_usuarioE" class="form-label">Nombre Usuario</label>
            <select id="nombre_usuarioE" name="nombre_usuarioE" class="form-select" required>
              <?php foreach ($dataUsers as $user): ?>
              <option value="<?php echo $user['id']; ?>"><?php echo $user['nombre']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <input type="hidden" id="matricula_vehiculoE" name="matricula_vehiculoE">

          <!-- Vehículo seleccionado -->
          <div id="selectedVehicleContainerE" class="alert alert-success d-none">
            <h6>Vehículo Seleccionado:</h6>
            <p id="selectedVehicleE" class="mb-0"></p>
          </div>

          <button type="submit" class="btn btn-dark w-100" id="submitEditRentButton" disabled>Editar Alquiler</button>
        </form>

        <!-- Lista de vehículos con búsqueda y selección -->
        <div class="mt-4">
          <label for="searchVehicleE" class="form-label">Buscar Vehículo</label>
          <div class="input-group mb-3">
            <input type="text" id="searchVehicleE" class="form-control" placeholder="Buscar por matrícula, marca o modelo">
          </div>
          <ul id="vehicleListE" class="list-group overflow-auto" style="max-height: 300px;">
            <?php foreach ($datavehicles as $vehicle): ?>
            <li class="list-group-item d-flex align-items-center vehicle-itemE" 
                data-id="<?php echo $vehicle['id']; ?>" 
                data-marca="<?php echo strtolower($vehicle['marca']); ?>" 
                data-modelo="<?php echo strtolower($vehicle['modelo']); ?>" 
                data-matricula="<?php echo strtolower($vehicle['matricula']); ?>" 
                onclick="selectVehicleE('<?php echo $vehicle['id']; ?>', '<?php echo $vehicle['marca']; ?>', '<?php echo $vehicle['modelo']; ?>', '<?php echo $vehicle['matricula']; ?>')">
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

<script>
// Filtrado de vehículos en el modal
document.getElementById('searchVehicleE').addEventListener('input', function () {
  const query = this.value.toLowerCase().trim();
  const vehicles = document.querySelectorAll('.vehicle-itemE');
  let visibleVehicles = 0;

  vehicles.forEach(vehicle => {
    const marca = vehicle.dataset.marca;
    const modelo = vehicle.dataset.modelo;
    const matricula = vehicle.dataset.matricula;

    if (marca.includes(query) || modelo.includes(query) || matricula.includes(query)) {
      vehicle.style.display = '';
      visibleVehicles++;
    } else {
      vehicle.style.display = 'none';
    }
  });

  const noResults = document.getElementById('noResultsMessageE');
  if (visibleVehicles === 0 && !noResults) {
    const message = document.createElement('li');
    message.id = 'noResultsMessageE';
    message.className = 'list-group-item text-center text-muted';
    message.textContent = 'No se encontraron vehículos';
    document.getElementById('vehicleListE').appendChild(message);
  } else if (visibleVehicles > 0 && noResults) {
    noResults.remove();
  }
});

// Selección de vehículo
function selectVehicleE(vehicleId, marca, modelo, matricula) {
  const matriculaInput = document.getElementById('matricula_vehiculoE');
  const selectedVehicleText = document.getElementById('selectedVehicleE');
  const selectedVehicleContainer = document.getElementById('selectedVehicleContainerE');
  const submitButton = document.getElementById('submitEditRentButton');

  matriculaInput.value = vehicleId; // Asignar el ID del vehículo
  selectedVehicleText.innerHTML = `
    <strong>${marca}</strong> - <em>${modelo}</em> <br>
    <small>Matrícula: ${matricula}</small>
  `;
  selectedVehicleContainer.classList.remove('d-none');
  submitButton.disabled = false; // Habilitar el botón de envío
}
</script>