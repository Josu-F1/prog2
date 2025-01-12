var editModalVehicle = document.getElementById('editModalVehicle');
editModalVehicle.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; 
    var vehicleId = button.getAttribute('data-id'); 
    var marca = button.getAttribute('data-editMarca'); 
    var modelo = button.getAttribute('data-editModelo');
    var matricula = button.getAttribute('data-editMatricula');
    var tarifa = button.getAttribute('data-editTarifa');    
    var estado = button.getAttribute('data-editEstado');
    var tipo_vehiculo = button.getAttribute('data-editTipo');
    var disponibilidad = button.getAttribute('data-editDisponibilidad');    
    var tipo_transmision = button.getAttribute('data-editTransmision');
    var tipo_combustible = button.getAttribute('data-editCombustible');
    var cilindraje = button.getAttribute('data-editCilindraje');
    var descripcion = button.getAttribute('data-editDescripcion');

    // Llenar los campos del formulario en el modal
    document.getElementById('editVehicleId').value = vehicleId;
    document.getElementById('editMarca').value = marca;
    document.getElementById('editModelo').value = modelo;
    document.getElementById('editMatricula').value = matricula;
    document.getElementById('editTarifa').value = tarifa;
    document.getElementById('editEstado').value = estado;
    document.getElementById('editTipo').value = tipo_vehiculo;
    document.getElementById('editDisponibilidad').value = disponibilidad;
    document.getElementById('editTransmision').value = tipo_transmision;
    document.getElementById('editCombustible').value = tipo_combustible;
    document.getElementById('editCilindraje').value = cilindraje;
    document.getElementById('editDescripcion').value = descripcion;
});

$('#editModalVehicle').on('hidden.bs.modal', function () {
    $('#editFormVehicle')[0].reset(); // Limpiar el formulario
    //$('#alertaed').addClass('d-none'); // Ocultar las alertas
});

$('#editFormVehicle').submit(function (e) {
    e.preventDefault();

    var vehicleId = $('#editVehicleId').val();
    var marca = $('#editMarca').val();
    var modelo = $('#editModelo').val();
    var matricula = $('#editMatricula').val();
    var tarifa = parseFloat($('#editTarifa').val());    
    var estado = $('#editEstado').val();
    var tipo_vehiculo = $('#editTipo').val();
    var disponibilidad = $('#editDisponibilidad').val();
    var tipo_transmision = $('#editTransmision').val();
    var tipo_combustible = $('#editCombustible').val();
    var cilindraje = $('#editCilindraje').val();
    var descripcion = $('#editDescripcion').val();

    // Validación de tarifa
    if (isNaN(tarifa) || tarifa < 0) {
        $('#alertaed').removeClass('d-none alert-success').addClass('alert-danger').text('La tarifa debe ser un número válido y mayor o igual a cero.');
        return;
    }

    // Deshabilitar el botón de enviar mientras se procesa la solicitud
    $('#editFormVehicle button[type="submit"]').attr('disabled', true);

    // Mostrar mensaje de carga (opcional)
    $('#alertaed').removeClass('d-none alert-danger').addClass('alert-info').text('Actualizando vehículo, por favor espere...');

    // Enviar la solicitud AJAX
    $.ajax({
        url: './home_section/scripts/editVehicle.php',
        type: 'POST',
        data: {
            id: vehicleId,
            marca: marca,
            modelo: modelo,
            matricula: matricula,
            tarifa: tarifa,            
            disponibilidad: disponibilidad,
            tipo_vehiculo: tipo_vehiculo,
            estado: estado,
            tipo_transmision: tipo_transmision,
            tipo_combustible: tipo_combustible,
            cilindraje: cilindraje,
            descripcion: descripcion
        },
        dataType: 'json', // Esto le dice a jQuery que la respuesta esperada es JSON
        success: function (response) {
            // Rehabilitar el botón de envío
            $('#editFormVehicle button[type="submit"]').attr('disabled', false);

            if (response.status === 'success') {
                // Actualizar la tarjeta en el DOM
                var tarjeta = $('#vehicle-' + vehicleId);
                tarjeta.find('.card-title').text('Marca: ' + marca);
                tarjeta.find('.card-subtitle').text('Modelo: ' + modelo);
                tarjeta.find('.card-text').html(`
                    <strong>Matrícula:</strong> ${matricula}<br>
                    <strong>Tarifa:</strong> ${tarifa}<br>
                    <strong>Estado:</strong> ${estado}<br>                    
                    <strong>Tipo:</strong> ${tipo_vehiculo}<br>
                    <strong>Disponibilidad:</strong> ${disponibilidad}<br>
                    <strong>Tipo de transmisión:</strong> ${tipo_transmision}<br>
                    <strong>Tipo de combustible:</strong> ${tipo_combustible}<br>
                    <strong>Cilindraje:</strong> ${cilindraje}<br>
                    <strong>Descripción:</strong> ${descripcion}
                `);

                // Actualizar los atributos de datos del botón "Editar"
                var editButton = tarjeta.find('button[data-id="' + vehicleId + '"]');
                editButton.attr({
                    'data-editMarca': marca,
                    'data-editModelo': modelo,
                    'data-editMatricula': matricula,
                    'data-editTarifa': tarifa,                    
                    'data-editTipo': tipo_vehiculo,
                    'data-editDisponibilidad': disponibilidad,
                    'data-editEstado': estado,
                    'data-editTransmision': tipo_transmision,
                    'data-editCombustible': tipo_combustible,
                    'data-editCilindraje': cilindraje,
                    'data-editDescripcion': descripcion
                });

                // Cerrar el modal y mostrar una alerta de éxito
                var modalVehicle = bootstrap.Modal.getInstance(editModalVehicle);
                modalVehicle.hide();

                $('#alertaed').removeClass('d-none alert-danger').addClass('alert-success').text('Vehículo actualizado correctamente.');
            } else {
                $('#alertaed').removeClass('d-none alert-success').addClass('alert-danger').text(response.message);
            }
        },
        error: function (xhr, status, error) {
            $('#alertaed').removeClass('d-none alert-success').addClass('alert-danger').text('Error de conexión. No se pudo actualizar el vehículo.');
            // Rehabilitar el botón de envío en caso de error
            $('#editFormVehicle button[type="submit"]').attr('disabled', false);
        }
    });
});
