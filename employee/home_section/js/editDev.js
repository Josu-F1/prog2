var editModalRent = document.getElementById('editModalRent');


editModalRent.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var alquilerId = button.getAttribute('data-id');
    var fecha_D = button.getAttribute('data-fechaDevolucion');
    var cargosExtra = button.getAttribute('data-cargosExtra');
    var descripcionDevolucion = button.getAttribute('data-descripcionDevolucion');
    var devuelto = button.getAttribute('data-devuelto');
    var metodoP = button.getAttribute('data-metodo');

    
    document.getElementById('editRentID').value = alquilerId;
    document.getElementById('fecha_devolucion').value = fecha_D;
    document.getElementById('cargos_extra').value = cargosExtra;
    document.getElementById('descripcion_devolucion').value = descripcionDevolucion;
    document.getElementById('devuelto').value = devuelto;
    document.getElementById('metodo_pago').value = metodoP;
});


$('#editModalRent').on('hidden.bs.modal', function () {
    $('#formeEditDev')[0].reset();
});

$('#formeEditDev').submit(function (e) {
    e.preventDefault();

    var editRentID = $('#editRentID').val();
    var descripcion_devolucion = $('#descripcion_devolucion').val();
    var fecha_devolucion = $('#fecha_devolucion').val();
    var devuelto = $('#devuelto').val();
    var cargos_extra = $('#cargos_extra').val();
    var metodo_pago = $('#metodo_pago').val();
    console.log(metodo_pago+"m")
    
    $.ajax({
        url: './home_section/scripts/editDev.php',
        type: 'POST',
        dataType: 'json',
        data: {
            editRentID: editRentID,
            descripcion_devolucion: descripcion_devolucion,
            fecha_devolucion: fecha_devolucion,
            devuelto: devuelto,
            metodo_pago:metodo_pago,
            cargos_extra: cargos_extra
        },
        success: function (response) {
            if (response.success) {
                // Actualizar la fila en la tabla
                $('#rent-' + editRentID + ' .fecha').text(fecha_devolucion);
                $('#rent-' + editRentID + ' .cargos').text(cargos_extra);
                $('#rent-' + editRentID + ' .descripcion').text(descripcion_devolucion);
                $('#rent-' + editRentID + ' .devuelto').text(devuelto);
                $('#rent-' + editRentID + ' .metodo').text(metodo_pago);
                $('#rent-' + editRentID + ' .monto').text(response.monto_tarifa);
                $('#rent-' + editRentID + ' .montoT').text(response.monto_total);
                var modal = bootstrap.Modal.getInstance(editModalRent);
                modal.hide();
                // Mostrar mensaje de éxito                
                $('#alerta2')
                    .removeClass('d-none alert-danger')
                    .addClass('alert-success')
                    .text('Se actualizo correctamente:'+response.message);
            } else {
                // Mostrar mensaje de error
                $('#alerta2')
                    .removeClass('d-none alert-success')
                    .addClass('alert-danger')
                    .text(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la conexión AJAX:", error);
            console.error("Estado:", status);
            console.error("Respuesta del servidor:", xhr.responseText);

            // Mostrar mensaje de error
            $('#alerta2')
                .removeClass('d-none alert-success')
                .addClass('alert-danger')
                .text('Error de conexión.');
        }
    });
});
