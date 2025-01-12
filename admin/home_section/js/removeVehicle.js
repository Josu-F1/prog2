$(document).on('click', '.eliminar', function (e) {
    e.preventDefault(); // Prevenir comportamiento por defecto del botón

    const idVehicle = $(this).data('id'); // Obtener el ID del vehículo
    const alerta = $('#alertaVehicle'); // Selector para la alerta lateral
    const card = $(this).closest('.col-md-4'); // Seleccionar la tarjeta correspondiente

    if (!confirm('¿Estás seguro de que deseas eliminar este vehículo?')) {
        return; // Salir si el usuario cancela la confirmación
    }

    let formData = new FormData();
    formData.append('id_vehicle', idVehicle);

    fetch('./home_section/scripts/removeVehicle.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            if (data.includes('exitoso')) {
                alerta
                    .removeClass('d-none alert-danger')
                    .addClass('alert-success alerta-lateral')  // Usar alerta lateral
                    .html('<i class="fas fa-check-circle"></i> ¡Vehículo eliminado exitosamente!')
                    .fadeIn(500)
                    .delay(3000)
                    .fadeOut(500);

                card.fadeOut(300, function () {
                    $(this).remove(); // Remover la tarjeta del DOM después de la animación
                });
            } else {
                alerta
                    .removeClass('d-none alert-success')
                    .addClass('alert-danger alerta-lateral')
                    .html('<i class="fas fa-exclamation-circle"></i> Error: ' + data)
                    .fadeIn(500)
                    .delay(3000)
                    .fadeOut(500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alerta
                .removeClass('d-none alert-success')
                .addClass('alert-danger alerta-lateral')
                .html('<i class="fas fa-exclamation-circle"></i> Hubo un problema al procesar la solicitud.')
                .fadeIn(500)
                .delay(3000)
                .fadeOut(500);
        });
});
