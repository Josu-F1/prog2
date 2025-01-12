<div class="modal fade" id="editModalRent" tabindex="-1" aria-labelledby="editModalRentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalRentLabel">Editar Alquiler</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body d-flex">
          <!-- Formulario principal -->
          <form id="formEditRent" class="w-50">
            <input type="hidden" name="id" id="editRentID">
            <div class="mb-3">
              <label for="nombre_usuarioE" class="form-label">Nombre Usuario</label>
              <select id="nombre_usuarioE" name="nombre_usuarioE" class="form-select" required>
                <?php foreach($dataUsers as $user):?>                
                <option value="<?php echo $user['id'];?>"><?php echo $user['nombre'];?></option>                
                <?php endforeach;?>
              </select>
            </div>
            <input type="hidden" id="matricula_vehiculoE" name="matricula_vehiculoE" >
            <p id="selectedVehicleE" class="text-success"></p>
            <button type="submit" class="btn btn-primary" id="submitEditRentButton" disabled>Editar Alquiler</button>
          </form>

          <!-- Lista de vehículos con búsqueda y selección -->
          <div class="w-50 ms-3">
            <label for="searchVehicleE" class="form-label">Buscar Vehículo</label>
            <div class="input-group mb-3">
              <input type="text" id="searchVehicleE" class="form-control" placeholder="Buscar por matrícula, marca o modelo">
              <!--<button class="btn btn-outline-secondary" id="searchButtonE" type="button">Buscar</button>-->
            </div>
            <ul id="vehicleListE" class="list-group overflow-auto" style="max-height: 300px;">
              <?php foreach($datavehicles as $vehicle):?>
                <li class="list-group-item d-flex align-items-center vehicle-itemE" 
                    data-id="<?php echo $vehicle['id']; ?>" 
                    data-marca="<?php echo strtolower($vehicle['marca']); ?>" 
                    data-modelo="<?php echo strtolower($vehicle['modelo']); ?>" 
                    data-matricula="<?php echo strtolower($vehicle['matricula']); ?>" 
                    onclick="selectVehicleE('<?php echo $vehicle['id']; ?>', '<?php echo $vehicle['marca']; ?>', '<?php echo $vehicle['modelo']; ?>', '<?php echo $vehicle['matricula']; ?>')">
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

<script>
// Filtrado de vehículos en el modal de edición
document.getElementById('searchVehicleE').addEventListener('input', filterVehiclesE);

function filterVehiclesE() {
  const query = document.getElementById('searchVehicleE').value.toLowerCase().trim(); // Texto buscado
  const vehicles = document.querySelectorAll('.vehicle-itemE'); // Todos los vehículos
  let visibleVehicles = 0;

  vehicles.forEach(vehicle => {
    const marca = vehicle.dataset.marca.toLowerCase();
    const modelo = vehicle.dataset.modelo.toLowerCase();
    const matricula = vehicle.dataset.matricula.toLowerCase();

    // Mostrar solo los vehículos que coinciden con la búsqueda
    if (marca.includes(query) || modelo.includes(query) || matricula.includes(query)) {
      vehicle.style.display = ''; // Mostrar el vehículo
      visibleVehicles++;
    } else {
      vehicle.style.display = 'none'; // Ocultar el vehículo
    }
  });

  // Mensaje cuando no hay resultados
  const listContainer = document.getElementById('vehicleListE');
  const noResultsMessage = document.getElementById('noResultsMessageE');

  if (visibleVehicles === 0) {
    if (!noResultsMessage) {
      const message = document.createElement('li');
      message.id = 'noResultsMessageE';
      message.className = 'list-group-item text-center text-muted';
      message.textContent = 'No se encontraron vehículos';
      listContainer.appendChild(message);
    }
  } else if (noResultsMessage) {
    noResultsMessage.remove();
  }
}

// Selección de vehículo en el modal de edición
function selectVehicleE(vehicleId, marca, modelo, matricula) {
  const matriculaInput = document.getElementById('matricula_vehiculoE');
  const selectedVehicleText = document.getElementById('selectedVehicleE');
  const submitButton = document.getElementById('submitEditRentButton');

  if (!matriculaInput || !selectedVehicleText || !submitButton) {
    console.error('No se encontraron elementos del formulario.');
    return;
  }

  matriculaInput.value = vehicleId; // Asignar el ID del vehículo
  selectedVehicleText.textContent = `Vehículo seleccionado: ${marca} - ${modelo} (Matrícula: ${matricula})`;
  submitButton.disabled = false; // Habilitar el botón de envío
}

// Buscar vehículos a través de un API en el modal de edición
document.getElementById('searchVehicleE').addEventListener('input', function () {
  const query = this.value.trim();

  fetch(`buscarVehiculos.php?query=${encodeURIComponent(query)}`)
  .then(response => response.json())
  .then(data => {
    const listContainer = document.getElementById('vehicleListE');
    listContainer.innerHTML = ''; // Limpiar la lista existente

    data.forEach(vehicle => {
      const vehicleItem = document.createElement('li');
      vehicleItem.className = 'list-group-item d-flex align-items-center vehicle-itemE';
      vehicleItem.setAttribute('data-id', vehicle.id || '');
      vehicleItem.setAttribute('data-marca', (vehicle.marca || '').toLowerCase());
      vehicleItem.setAttribute('data-modelo', (vehicle.modelo || '').toLowerCase());
      vehicleItem.setAttribute('data-matricula', (vehicle.matricula || '').toLowerCase());
      vehicleItem.onclick = () => {
        if (vehicle.id && vehicle.marca && vehicle.modelo && vehicle.matricula) {
          selectVehicleE(vehicle.id, vehicle.marca, vehicle.modelo, vehicle.matricula);
        } else {
          console.error('Datos del vehículo incompletos:', vehicle);
        }
      };

      vehicleItem.innerHTML = `
        <img src="../images/autos/${vehicle.imagen || 'placeholder.jpg'}" alt="Imagen de ${vehicle.marca || 'N/A'}" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
        <div>
          <strong>${vehicle.marca || 'Sin Marca'} - ${vehicle.modelo || 'Sin Modelo'}</strong><br>
          <small>Matrícula: ${vehicle.matricula || 'Sin Matrícula'}</small>
        </div>
      `;

      listContainer.appendChild(vehicleItem);
    });
  })
  .catch(error => console.error('Error fetching vehicles:', error));
});
</script>

