var editModalRent = document.getElementById('addRevModal');

editModalRent.addEventListener('show.bs.modal', function (event) {
    // Obtén el botón que activó el modal
    var button = event.relatedTarget;

    // Obtén los datos del botón
    var vehiculoId = button.getAttribute('data-idV');
    var usuarioId = button.getAttribute('data-idU');

    // Establece los valores en los campos ocultos
    document.getElementById('id_usuario').value = usuarioId;
    document.getElementById('id_vehiculo').value = vehiculoId;

    // Busca el contenedor para la información del vehículo
    var vehicleInfoDiv = document.getElementById('vehicleInfo');

    // Limpia el contenido previo del modal
    vehicleInfoDiv.innerHTML = '';

    // Busca el vehículo correspondiente
    console.log("Buscando vehículo con ID:", vehiculoId);
    var vehicle = document.querySelector(`.vehicle-item[data-id="${vehiculoId}"]`);

    if (vehicle) {
        console.log("Vehículo encontrado:", vehicle);

        // Obtén la información del vehículo
        var marca = vehicle.getAttribute('data-marca');
        var modelo = vehicle.getAttribute('data-modelo');
        var matricula = vehicle.getAttribute('data-matricula');
        var imagen = vehicle.querySelector('img').src;

        // Actualiza la información del vehículo en el modal
        vehicleInfoDiv.innerHTML = `
          <div class="d-flex align-items-center">
            <img src="${imagen}" alt="${marca} ${modelo}" class="me-3" style="width: 100px; height: 100px; object-fit: cover;">
            <div>
              <strong>${marca} - ${modelo}</strong><br>
              <small>Matrícula: ${matricula}</small>
            </div>
          </div>
        `;
    } else {
        console.error("No se encontró un vehículo con ID:", vehiculoId);
        vehicleInfoDiv.innerHTML = `<p class="text-danger">No se encontró información del vehículo.</p>`;
    }
});




$('#addRevModal').on('hidden.bs.modal', function () {
    $('#formAddRent')[0].reset(); 
});


$('#formAddRent').submit(function (e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
        url: './scripts/addReservations.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            location.reload();
            try {
                // Intentar parsear la respuesta como JSON
                var data = JSON.parse(response);

                if (data.success) {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('addRevModal'));
                    modal.hide();

                  
                   

                    $('#alerta2').removeClass('d-none alert-danger').addClass('alert-success').text('Alquiler agregado correctamente.');
                    

                    // Limpiar los campos del formulario y deshabilitar el botón
                    $('#formAddRent')[0].reset();
                    $('#submitRentButton').prop('disabled', true);
                    $('#selectedVehicle').text('');

                    // Recargar la página
                    location.reload();
                } else {
                    $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text(data.message);
                }
            } catch (error) {
                console.error('Error al procesar la respuesta del servidor:', error);
                console.error('Respuesta recibida:', response);
                $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text('Error en la respuesta del servidor. Consulte la consola para más detalles.');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error de conexión:', status, error);
            $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text('Error de conexión con el servidor.');
        }
    });
});
