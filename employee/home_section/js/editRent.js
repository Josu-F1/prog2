var editModalRent = document.getElementById('editModalRent');
editModalRent.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var alquilerId = button.getAttribute('data-id');  
    
    
    document.getElementById('editRentID').value = alquilerId;
});


$('#editModalRent').on('hidden.bs.modal', function () {
    $('#formEditRent')[0].reset(); 
});


$('#formEditRent').submit(function(e) {
    e.preventDefault(); 

    let id_vehiculo = $('#matricula_vehiculoE').val();    
    let id_usuario = $('#nombre_usuarioE').val();   
    let id_alquiler =  $('#editRentID').val();   
    console.log(id_vehiculo);
    console.log(id_usuario);
    console.log(id_alquiler);

    

    $.ajax({
        url: './home_section/scripts/editRent.php',
        type: 'POST',
        dataType: 'json',
        data: { id_alquiler: id_alquiler, id_vehiculo:id_vehiculo, id_usuario:id_usuario },
        success: function(response) {
            if (response.success) {   
                /*console.log(response.usuario_actual.nombre);
                console.log($('#rent-' + id_alquiler + ' .nombre'));*/
                // Actualizar la fila en la tabla sin recargar la página                
                $('#rent-' + id_alquiler + ' .nombre').text(response.usuario_actual.nombre);
                $('#rent-' + id_alquiler + ' .marca').text(response.vehiculo_actual.marca);
                $('#rent-' + id_alquiler + ' .modelo').text(response.vehiculo_actual.modelo);
                $('#rent-' + id_alquiler + ' .matricula').text(response.vehiculo_actual.matricula);
                
                var optionEliminarE = document.getElementById('eV'+response.vehiculo_actual.id);
                var optionEliminarA = document.getElementById('aV'+response.vehiculo_actual.id);
                if(optionEliminarE){
                    optionEliminarE.remove();
                    optionEliminarA.remove();
                    console.log('option eliminado correctamente');
                }else{
                    console.log('este option no existe');
                }
               

                
                $('button[data-id="' + id_alquiler + '"]').attr({                    
                    'data-nombreUsuario': response.usuario_actual.nombre,
                    'data-matricula': response.vehiculo_actual.matricula,                   
                });

                var modal = bootstrap.Modal.getInstance(editModalRent);
                modal.hide();
                
                $('#alerta2').removeClass('d-none alert-danger').addClass('alert-success').text(response.message);

                // Recargar la página
                location.reload(); // Esto recargará la página
            } else {                
                $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text(response.message);
            }
        },
        error: function(xhr, status, error) {            
            $('#alerta2').removeClass('d-none alert-success').addClass('alert-danger').text('Error de conexión.');
        }
    });
});
