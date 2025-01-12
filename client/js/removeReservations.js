let rentIdToDelete = null; // Variable para almacenar el ID temporalmente
let targetRow = null; // Variable para almacenar la fila temporalmente

$(document).on('click', '.eliminar', function (e) {
    rentIdToDelete = $(this).data('id'); // Almacena el ID del alquiler
    targetRow = $(this).closest('tr'); // Almacena la fila para eliminar
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show(); // Muestra el modal de confirmación
});

$('#confirmDeleteBtn').on('click', function () {
    if (!rentIdToDelete) return;

    $.ajax({
        url: './scripts/removeRev.php', 
        type: 'POST', 
        dataType: 'json', 
        data: { rent_id: rentIdToDelete },
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

    rentIdToDelete = null; // Resetea la variable
    targetRow = null; // Resetea la fila
});
