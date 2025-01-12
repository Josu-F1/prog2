let editModals = document.getElementById('editFechaF');
editModals.addEventListener('show.bs.modal', function (event) {
    let button = event.relatedTarget; 
    let alquilerId = button.getAttribute('data-id');
    let fecha_inicio = button.getAttribute('data-fechaInicio');
    let fecha_fin = button.getAttribute('data-fechaFin');
    /*var userName = button.getAttribute('data-nombre'); */

    
    document.getElementById('editRentID').value = alquilerId;
    document.getElementById('fecha_inicio').value = fecha_inicio;
    document.getElementById('fecha_fin').value = fecha_fin;
    /*document.getElementById('editUserName').value = userName;*/
});


$('#editFechaF').on('hidden.bs.modal', function () {
    $('#formFechaF')[0].reset();
});


$('#formFechaF').submit(function(e) {
    e.preventDefault();
    var fecha_fin = $('#fecha_fin').val();
    var fecha_inicio = $('#fecha_inicio').val();
    var id = $('#editRentID').val();
    
    
    $.ajax({
        url: './home_section/scripts/editFF.php',
        type: 'POST',
        data: { fecha_fin: fecha_fin ,id:id,fecha_inicio:fecha_inicio},
        success: function(response) {
            if (response.trim() === 'success') {
                $('#rent-' + id + ' .fecha_fin').text(fecha_fin);
                $('#rent-' + id + ' .fecha_inicio').text(fecha_inicio);
                
                                               
                var modal = bootstrap.Modal.getInstance(editModals);
                modal.hide();

                
                $('#alerta2').removeClass('d-none alert-danger').addClass('alert-success').text('Se ha actualizo la fecha.');
                location.reload();
            } else {
                
                $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text('Error al actualizar la fecha.');
            }
        },
        error: function(xhr, status, error) {
            
            $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text('Error de conexión.');
        }
    });    
});
