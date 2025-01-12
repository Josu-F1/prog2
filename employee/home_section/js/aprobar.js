let rentIdToDelete2 = null; // Variable para almacenar el ID temporalmente
let targetRow2 = null; // Variable para almacenar la fila temporalmente

$(document).on('click', '.editar', function (e) {
    rentIdToDelete2 = $(this).data('id'); // Almacena el ID del alquiler
    targetRow2 = $(this).closest('tr'); // Almacena la fila para eliminar
    const modal = new bootstrap.Modal(document.getElementById('confirmAprobModal'));
    modal.show(); // Muestra el modal de confirmación
});

$('#confirmAprobBtn').on('click', function () {
    if (!rentIdToDelete2) return;

    $.ajax({
        url: './home_section/scripts/aprobar.php',
        type: 'POST',
        dataType: 'json',
        data: { rent_id: rentIdToDelete2 },
        success: function (response) {
            const alerta = $('#alerta2');

            if (response.success) {
                alerta.removeClass('d-none alert-danger').addClass('alert-success').text('Alquiler eliminado correctamente');




                location.reload();
            } else {
                alerta.removeClass('d-none alert-success').addClass('alert-danger').text('Error: ' + response.message);
            }

            // Cierra el modal
            $('#confirmDeleteModal').modal('hide');
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            const alerta = $('#alerta2');
            alerta.removeClass('d-none alert-success').addClass('alert-danger').text('Ocurrió un error inesperado.');

            // Cierra el modal
            $('#confirmDeleteModal').modal('hide');
        }
    });

    rentIdToDelete2 = null; // Resetea la variable
    targetRow2 = null; // Resetea la fila
});
