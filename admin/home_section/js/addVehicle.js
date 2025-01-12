$('#formAddVehicle').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: './home_section/scripts/addVehicle.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            try {
                var data = JSON.parse(response);

                if (data.success) {
                    // Cerrar el modal
                    var modal = bootstrap.Modal.getInstance(document.getElementById('addVehicleModal'));
                    modal.hide();

                    // Crear nueva tarjeta
                    var nuevaTarjeta = `
                        <div class="col-md-4 mb-4" id="vehicle-${data.id}">
                            <div class="card h-100">
                                <img src="../images/autos/${data.imagen}" class="card-img-top" alt="Imagen de ${data.marca}" style="width: 100%; height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">Marca: ${data.marca}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Modelo: ${data.modelo}</h6>
                                    <p class="card-text">
                                        <strong>Matrícula:</strong> ${data.matricula}<br>
                                        <strong>Tarifa:</strong> ${data.tarifa}<br>
                                        <strong>Estado:</strong> ${data.estado}<br>                                        
                                        <strong>Tipo:</strong> ${data.tipo_vehiculo}<br>
                                        <strong>Disponibilidad:</strong> ${data.disponibilidad}<br>
                                        <strong>Tipo de transmision:</strong> ${data.tipo_transmision}<br>
                                        <strong>Tipo de combustible:</strong> ${data.tipo_combustible}<br>
                                        <strong>Cilindraje:</strong> ${data.cilindraje}<br>
                                        <strong>Descripcion:</strong> ${data.descripcion}<br>
                                    </p>
                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModalVehicle" 
                                        data-id="${data.id}" 
                                        data-editMarca="${data.marca}"
                                        data-editModelo="${data.modelo}"
                                        data-editMatricula="${data.matricula}"
                                        data-editTarifa="${data.tarifa}"
                                        data-editEstado="${data.estado}"
                                        data-editTipo="${data.tipo_vehiculo}"
                                        data-editDisponibilidad="${data.disponibilidad}"                                        
                                        data-editTransmision="${data.tipo_transmision}"
                                        data-editCombustible="${data.tipo_combustible}"
                                        data-editCilindraje="${data.cilindraje}"
                                        data-editDescripcion="${data.descripcion}"
                                        >
                                        Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm eliminar" data-id="${data.id}">Eliminar</button>
                                </div>
                            </div>
                        </div>`;

                    // Agregar la tarjeta al contenedor
                    $('#cardsContainer').append(nuevaTarjeta);

                    // Mostrar alerta de éxito
                    $('#alertaadd').removeClass('d-none alert-danger').addClass('alert-success').text('Vehículo agregado correctamente.');
                } else {
                    // Mostrar mensaje de error
                    $('#alertaadd').removeClass('d-none alert-success').addClass('alert-danger').text(data.message);
                }
            } catch (e) {
                // Manejar errores en la respuesta
                $('#alertaadd').removeClass('d-none alert-success').addClass('alert-danger').text('Error al procesar la respuesta del servidor.');
            }
        },
        error: function(xhr, status, error) {
            // Manejar errores de conexión
            $('#alertaadd').removeClass('d-none alert-success').addClass('alert-danger').text('Error de conexión.');
        }
    });
});
