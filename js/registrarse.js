const modal = new bootstrap.Modal(document.getElementById('codeModal'));

const modalConfirmation = new bootstrap.Modal(document.getElementById('successConfirmModal'));

const digit1 = $('#digit1');
const digit2 = $('#digit2');
const digit3 = $('#digit3');

var buttonVerify = document.getElementById('verifyCode');

var passwordM = document.getElementById('password');
var passwordConfirmM = document.getElementById('passwordConfirm');
var tipoUsuario = 'cliente';

$(document).ready(function () {
  $('#registroForm').on('submit', function (e) {
    e.preventDefault();
    if (passwordM.value == passwordConfirmM.value) {
      $.ajax({
        type: 'POST',
        url: '../util/verificarCorreoExiste.php',
        data: { email: $('#email').val() },
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            $('#alerta').removeClass('alert-danger').addClass('alert-success').text(response.message).show();
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
                  console.log("digitos modal" + digit1.val() + digit2.val() + digit3.val());
                  console.log("digitos email" + response.digito1 + response.digito2 + response.digito3);

                  if (
                    response.digito1 == digit1.val() &&
                    response.digito2 == digit2.val() &&
                    response.digito3 == digit3.val()) {
                    $('#alertacodigo').removeClass('d-none alert-danger').addClass('alert-success').text('El codigo proporcionado es correcto.');

                    $.ajax({
                      type: 'POST',
                      url: '../util/registrar_usuario.php',
                      data: { email: $('#email').val(), password: passwordM.value, nombre: $('#nombre').val() },
                      dataType: 'json',
                      success: function (response) {
                        if (response.success) {                          
                          modalConfirmation.show();
                          $('#cerrarModales').on('click', function () {
                            modal.hide();                            
                          });

                        } else {
                          $('#alertacodigo').removeClass('d-none alert-success').addClass('alert-danger').text(response.message);
                        }

                      }, error: function (xhr, status, error) {
                        console.error("Error del servidor:", xhr.responseText);
                        $('#alertacodigo').text('Ocurrió un error en el servidor: ' + xhr.responseText).show();
                      }
                    });



                  } else {
                    $('#alertacodigo').removeClass('d-none alert-success').addClass('alert-danger').text('El código proporcionado no es correcto.');
                  }
                });
              },
              error: function (xhr, status, error) {
                console.error("Error del servidor:", xhr.responseText);
                $('#alerta').text('Ocurrió un error en el servidor: ' + xhr.responseText).show();
              }
            });
          } else {
            $('#alerta').removeClass('alert-success').addClass('alert-danger').text(response.message).show();            
          }
        },
        error: function (xhr, status, error) {

          console.error("Error del servidor:", xhr.responseText);
          $('#alerta').text('Ocurrió un error en el servidor: ' + xhr.responseText).show();
        }
      });

    } else {
      $('#alerta').removeClass('alert-success').addClass('alert-danger').text('Las contraseñas no son las mismas').show();
      console.log('las contrasenas no coinciden')
    }
  });
});




/*document.getElementById('toggleNewPassword').addEventListener('click', function () {
  let passwordField = document.getElementById('newPassword');
  let icon = this.querySelector('svg');

  if (passwordField.type === 'password') {
    passwordField.type = 'text';
    icon.setAttribute('fill', 'gray');
    this.style.backgroundColor = 'blue';
  } else {
    passwordField.type = 'password';
    icon.setAttribute('fill', 'currentColor');
    this.style.backgroundColor = 'white';
  }
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
  let passwordField = document.getElementById('confirmPassword');
  let icon = this.querySelector('svg');

  if (passwordField.type === 'password') {
    passwordField.type = 'text';
    icon.setAttribute('fill', 'gray');
    this.style.backgroundColor = 'blue';
  } else {
    passwordField.type = 'password';
    icon.setAttribute('fill', 'currentColor');
    this.style.backgroundColor = 'white';
  }
});*/
