$('#formAddUser').submit(function(e) {
    e.preventDefault(); 

    var formData = $(this).serialize(); 

    if ($('#password').val() == $('#passwordC').val()) {
        $.ajax({
            type: 'POST',
            url: '../util/verificarCorreoExiste.php',
            data: { email: $('#email').val() },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Solo se muestra el mensaje aquí si el correo no está duplicado.
                    $.ajax({
                        url: './home_section/scripts/addUser.php',  
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            var data = JSON.parse(response);

                            if (data.success) {
                                // Cerrar el modal de agregar usuario
                                var modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                                modal.hide();

                                // Agregar una nueva fila a la tabla
                                var nuevaFila = `<tr id="usuario-${data.id}">
                                    <td class="nombre">${data.nombre}</td>
                                    <td>${data.email}</td>
                                    <td>${data.tipo_usuario}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal" 
                                            data-id="${data.id}" 
                                            data-nombre="${data.nombre}">Editar</button>
                                        <button class="btn btn-danger btn-sm eliminar" data-id="${data.id}">Eliminar</button>
                                    </td>
                                </tr>`;

                                $('#tablaUsuarios').append(nuevaFila);

                                // Mensaje de éxito al agregar el usuario
                                showAlert('success', 'Usuario agregado correctamente.');
                            } else {
                                // Mensaje de error al agregar el usuario
                                showAlert('error', data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            showAlert('error', 'Error de conexión. No se pudo agregar el usuario.');
                        }
                    });
                } else {
                    // Mensaje de error si el correo ya existe
                    showAlert('error', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error del servidor:", xhr.responseText);
                showAlert('error', 'Ocurrió un error en el servidor: ' + xhr.responseText);
            }
        });
    } else {
        // Mensaje de error cuando las contraseñas no coinciden
        showAlert('error', 'Las contraseñas no coinciden.');
    }
});

// Función para mostrar la alerta lateral
function showAlert(type, message) {
    const alerta = $('#alerta2'); // Usamos el contenedor de alerta lateral
    
    // Configurar la clase de la alerta según el tipo
    if (type === 'success') {
        alerta.removeClass('d-none alert-danger').addClass('alert-success alerta-lateral');
    } else if (type === 'error') {
        alerta.removeClass('d-none alert-success').addClass('alert-danger alerta-lateral');
    }

    alerta.html('<i class="fas fa-exclamation-circle"></i> ' + message)
          .fadeIn(500) // Animación de aparición
          .delay(3000) // Mantener visible por 3 segundos
          .fadeOut(500); // Animación de desaparición
}
