var editModal = document.getElementById('editModal');

// Evento para cargar los datos en el modal al abrirlo
editModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; 
    var userId = button.getAttribute('data-id'); 
    var userName = button.getAttribute('data-name'); 
    var userType = button.getAttribute('data-type');

    // Asignar los valores al formulario
    document.getElementById('editUserId').value = userId;
    document.getElementById('editNombre').value = userName;
    document.getElementById('editTipoUsuario').value = userType;
});

// Evento para limpiar el formulario al cerrar el modal
$('#editModal').on('hidden.bs.modal', function () {
    $('#editForm')[0].reset();
    $('#alertaE').addClass('d-none'); // Ocultar alertas previas
});

// Manejar el envío del formulario
$('#editForm').submit(function(e) {
    e.preventDefault();
    var userId = $('#editUserId').val();
    var nombre = $('#editNombre').val();
    var tipo_usuario = $('#editTipoUsuario').val();
    var password = $('#password').val();
    var passwordC = $('#passwordC').val();

    if(password === passwordC) {
        var data = {
            id: userId,
            nombre: nombre,
            tipo_usuario: tipo_usuario,
        };

        if(password) {
            data.password = password;
        }

        $.ajax({
            url: './home_section/scripts/editUser.php',
            type: 'POST',
            data: data,
            success: function(response) {
                var alerta = $('#alerta2');
                if (response.trim() === 'success') {
                    alerta
                        .removeClass('d-none alert-danger')
                        .addClass('alert-success alerta-lateral')
                        .html('<i class="fas fa-check-circle"></i> Se ha actualizado el usuario correctamente.')
                        .fadeIn(500)
                        .delay(3000)
                        .fadeOut(500);
                    var modal = bootstrap.Modal.getInstance(editModal);
                    modal.hide();

                    // Actualiza la fila de la tabla correspondiente
                    var userRow = document.querySelector(`tr[data-id='${userId}']`);
                    if (userRow) {
                        userRow.querySelector('.user-name').textContent = nombre;
                        userRow.querySelector('.user-type').textContent = tipo_usuario;
                    }
                } else {
                    alerta
                        .removeClass('d-none alert-success')
                        .addClass('alert-danger alerta-lateral')
                        .html('<i class="fas fa-exclamation-circle"></i> Error al actualizar el usuario.')
                        .fadeIn(500)
                        .delay(3000)
                        .fadeOut(500);
                }
            },
            error: function(xhr, status, error) {
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

