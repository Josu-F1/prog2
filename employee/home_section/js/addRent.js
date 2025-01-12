$('#formAddRent').submit(function (e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
        url: './home_section/scripts/addrent.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            var data = JSON.parse(response);

            if (data.success) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('addRentModal'));
                modal.hide();

                var nuevaFila = `<tr>
                    <th>${data.nombre}</th>
                    <th>
                        <img src="../images/autos/${data.imagen}" alt=""
                        style="width: 100px; height: auto; border-radius: 5px;">
                    </th>
                    <th>${data.matricula}</th>
                    <th>${data.marca}</th>
                    <th>${data.modelo}</th>
                    <th>${data.fecha_inicio}</th>
                    <th>${data.fecha_fin}</th>  
                    <th>${data.tarifa}</th>  
                    <th>${data.monto_esperado}</th>  
                    
                    <th>
                        <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#contratoModal"
                            data-id="${data.id}"
                            data-fechaInicio="${data.fecha_inicio}"
                            data-fechaFin="${data.fecha_fin}"
                            data-nombreUsuario="${data.nombre}"
                            data-matricula="${data.matricula}">
                            Ver Contrato
                        </button>
                    </th>
                    <th>
                        <button class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editModalRent"
                            data-id="${data.id}"
                            data-fechaInicio="${data.fecha_inicio}"
                            data-fechaFin="${data.fecha_fin}"
                            data-nombreUsuario="${data.nombre}"
                            data-matricula="${data.matricula}">
                            Editar
                        </button>
                    </th>
                    <th><button class="btn btn-danger btn-sm eliminar" data-id="${data.id}">Eliminar</button></th>
                </tr>`;

                $('#tablaRents').append(nuevaFila);

                $('#alerta2').removeClass('d-none alert-danger').addClass('alert-success').text('Alquiler agregado correctamente.');

                // Eliminar vehículo de la lista si ya fue alquilado
                var rentEliminar = document.getElementById('aV' + `${data.id_vehiculo}`);
                var rentEliminarE = document.getElementById('eV' + `${data.id_vehiculo}`);

                if (rentEliminar) {
                    rentEliminar.remove();
                    rentEliminarE.remove();
                    console.log(`Elemento con ID "aV${data.id_vehiculo}" y "eV${data.id_vehiculo}" eliminado.`);
                } else {
                    console.error(`No se encontró un elemento con ID "aV${data.id_vehiculo}" o "eV${data.id_vehiculo}".`);
                }

                // Limpiar los campos del formulario y deshabilitar el botón
                $('#formAddRent')[0].reset();
                $('#submitRentButton').prop('disabled', true);
                $('#selectedVehicle').text('');

                // Recargar la página
                location.reload();
            } else {
                $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text(data.message);
            }
        },
        error: function (xhr, status, error) {
            $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text('Error de conexión');
        }
    });
});
