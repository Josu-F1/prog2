$(document).ready(function() {
  $('#loginForm').on('submit', function(e) {
    e.preventDefault(); 

    $.ajax({
      type: 'POST',
      url: '../util/login.php', 
      data: $(this).serialize() + '&action=login',
      dataType: 'json',
      success: function(response) {
        if (response.success) {          
          if (response.tipo_usuario === 'administrador') {
            window.location.href = '../admin/gestion_users.php';
          } else if (response.tipo_usuario === 'empleado') {
            window.location.href = '../employee/gestion_rents.php';
          } else {
            window.location.href = '../client/home_client.php';
          }
        } else {
          
          $('#error').text(response.error).show();
        }
      },
      error: function(xhr, status, error) {
        
        console.error("Error del servidor:", xhr.responseText);
        $('#error').text('Ocurrió un error en el servidor: ' + xhr.responseText).show();
      }
    });
  });
});
