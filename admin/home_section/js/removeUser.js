$(document).on('click', '.eliminar', function() {
    const idUsuario = $(this).data('id');
    const alerta = $('#alerta2');  // Alerta lateral
    const row = $(this).closest('tr');

    let formData = new FormData();
    formData.append('id_usuario', idUsuario);

    fetch('./home_section/scripts/removeUser.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes('exitoso')) {
            alerta
                .removeClass('d-none alert-danger')
                .addClass('alert-success alerta-lateral')  // Usar alerta lateral
                .html('<i class="fas fa-check-circle"></i> ¡Usuario eliminado exitosamente!')
                .fadeIn(500)
                .delay(3000)
                .fadeOut(500);
            row.remove();  // Eliminar la fila de la tabla
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
