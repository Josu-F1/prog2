const modal = new bootstrap.Modal(document.getElementById('codeModal'));
const modalPassword = new bootstrap.Modal(document.getElementById('passwordModal'));
const modalConfirmation = new bootstrap.Modal(document.getElementById('successPasswordModal'));

const digit1 = $('#digit1'); 
const digit2 = $('#digit2'); 
const digit3 = $('#digit3'); 

var buttonVerify = document.getElementById('verifyCode');


$(document).ready(function () {
  $('#recoverForm').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: '../util/recover.php',
      data: { email: $('#email').val() },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          $('#error').removeClass('alert-danger').addClass('alert-success').text(response.message).show();
          modal.show();

          /*
          */
          $.ajax({
            type: 'POST',
            url: '../util/email.php',
            data: { email: $('#email').val() },
            dataType: 'json',
            success: function (response) {              
              $('#verifyCode').on('click', function () {
                const digit1 = $('#digit1');
                const digit2 = $('#digit2'); 
                const digit3 = $('#digit3'); 
                console.log("digitos modal"+digit1.val()+digit2.val()+digit3.val());
                console.log("digitos email"+response.digito1+response.digito2+response.digito3);
                
                if (
                  response.digito1 == digit1.val() &&
                  response.digito2 == digit2.val() &&
                  response.digito3 == digit3.val() ) {                  
                  $('#alertacodigo').removeClass('d-none alert-danger').addClass('alert-success').text('El codigo coincide correctamente.');
                  modalPassword.show();                  
                  $('#savePassword').on('click', function () {  
                      const password = $('#newPassword');
                      const passwordConfirm = $('#confirmPassword');                       
                      if(password.val()==passwordConfirm.val()){                        
                        $('#alertaPassword').removeClass('d-none alert-danger').addClass('alert-success').text('Las contraseñas coinciden correctamente.');
                        $.ajax({
                          type: 'POST',
                          url: '../util/changePassword.php',
                          data: { email: $('#email').val(), password: password.val()},
                          dataType: 'json',
                          success: function (response) {
                            if (response.success) {
                            $('#alertaPassword').removeClass('d-none alert-danger').addClass('alert-success').text('La contraseña se ha cambiado correctamente.');
                            modalConfirmation.show();
                            $('#cerrarModales').on('click', function () { 
                              modal.hide();
                              modalPassword.hide();
                             });
                            
                            }else{
                              $('#alertaPassword').removeClass('d-none alert-success').addClass('alert-danger').text(response.message);
                            }

                          },error: function (xhr, status, error) {
                            console.error("Error del servidor:", xhr.responseText);
                            $('#error').text('Ocurrió un error en el servidor: ' + xhr.responseText).show();
                          }                        
                          });                      
                      }else{
                        $('#alertaPassword').removeClass('d-none alert-success').addClass('alert-danger').text('Las contraseñas no coinciden.');
                      }
                  });  
                  $('#cancelarchangecontraseña').on('click',function(){
                    modal.hide();
                  });                
                } else {                  
                  $('#alertacodigo').removeClass('d-none alert-success').addClass('alert-danger').text('El codigo no coincide correctamente .');
                }
              });
            },
            error: function (xhr, status, error) {
              console.error("Error del servidor:", xhr.responseText);
              $('#error').text('Ocurrió un error en el servidor: ' + xhr.responseText).show();
            }
          });
        } else {
          $('#error').removeClass('alert-success').addClass('alert-danger').text(response.message).show();
        }
      },
      error: function (xhr, status, error) {

        console.error("Error del servidor:", xhr.responseText);
        $('#error').text('Ocurrió un error en el servidor: ' + xhr.responseText).show();
      }
    });
  });
});




document.getElementById('toggleNewPassword').addEventListener('click', function() {
  let passwordField = document.getElementById('newPassword');
  let icon = this.querySelector('svg');
  
  if (passwordField.type === 'password') {
      passwordField.type = 'text';
      icon.setAttribute('fill', 'gray'); 
      this.style.backgroundColor='blue';
  } else {
      passwordField.type = 'password';
      icon.setAttribute('fill', 'currentColor'); 
      this.style.backgroundColor='white';
  }
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
  let passwordField = document.getElementById('confirmPassword');
  let icon = this.querySelector('svg');
  
  if (passwordField.type === 'password') {
      passwordField.type = 'text';
      icon.setAttribute('fill', 'gray');
      this.style.backgroundColor='blue';
  } else {
      passwordField.type = 'password';
      icon.setAttribute('fill', 'currentColor');
      this.style.backgroundColor='white';
  }
});
