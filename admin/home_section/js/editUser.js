var editModal = document.getElementById('editModal');
editModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; 
    var userId = button.getAttribute('data-id'); 

    document.getElementById('editUserId').value = userId;
});

$('#editModal').on('hidden.bs.modal', function () {
    $('#editForm')[0].reset();
});

$('#editForm').submit(function(e) {
    e.preventDefault();
    var userId = $('#editUserId').val();
    var password = $('#password').val();
    var passwordC = $('#passwordC').val();
    
    if(password == passwordC){
        $.ajax({
            url: './home_section/scripts/editUser.php',
            type: 'POST',
            data: { id: userId, password: password },
            success: function(response) {
                var alerta = $('#alerta2'); // Usamos el contenedor de alerta lateral
                
                if (response.trim() === 'success') {
                    // Si la respuesta es exitosa
                    alerta
                        .removeClass('d-none alert-danger')
                        .addClass('alert-success alerta-lateral')
                        .html('<i class="fas fa-check-circle"></i> Se ha cambiado la contraseña correctamente.')
                        .fadeIn(500) // Animación de aparición
                        .delay(3000) // Mantener visible por 3 segundos
                        .fadeOut(500); // Animación de desaparición
                    var modal = bootstrap.Modal.getInstance(editModal);
                    modal.hide();
                } else {
                    // Si hay un error
                    alerta
                        .removeClass('d-none alert-success')
                        .addClass('alert-danger alerta-lateral')
                        .html('<i class="fas fa-exclamation-circle"></i> Error al actualizar usuario.')
                        .fadeIn(500)
                        .delay(3000)
                        .fadeOut(500);
                }
            },
            error: function(xhr, status, error) {
                // Error de conexión
                var alerta = $('#alerta2');
                alerta
                    .removeClass('d-none alert-success')
                    .addClass('alert-danger alerta-lateral')
                    .html('<i class="fas fa-exclamation-circle"></i> Error de conexión.')
                    .fadeIn(500)
                    .delay(3000)
                    .fadeOut(500);
            }
        });
    } else {
        // Si las contraseñas no coinciden
        var alerta = $('#alertaE');
        alerta
            .removeClass('d-none alert-success')
            .addClass('alert-danger alerta-lateral')
            .html('<i class="fas fa-exclamation-circle"></i> Las contraseñas no coinciden.')
            .fadeIn(500)
            .delay(3000)
            .fadeOut(500);
    }
});
