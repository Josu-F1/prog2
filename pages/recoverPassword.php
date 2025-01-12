<?php
include '../util/conexion.php';

$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']);

session_start();
include '../util/verificadorSesion.php';


?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles/background.css">
  <link rel="stylesheet" href="../styles/menu.css">
  <link rel="stylesheet" href="../styles/footer.css">
  <style>
  .container-login {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 40px;
    border-radius: 5px; /* Reducido para bordes más cuadrados */
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
    text-align: left;
  }
  .container-login .footer {
    background-color: rgba(255, 255, 255, 0.1);
    color:rgb(19, 20, 21);
  }

  h2 {
    color:rgb(14, 14, 15);
    font-weight: 600;
  }

  .form-control {
    border-radius: 5px; /* Reducido para bordes más cuadrados */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .btn-primary {
    background-color:rgb(15, 15, 16);
    border: none;
    border-radius: 5px; /* Reducido para bordes más cuadrados */
    padding: 10px 20px;
    font-size: 16px;
    transition: background-color 0.3s ease;
  }

  .btn-primary:hover {
    background-color:rgb(17, 18, 19);
  }

  .btn-google {
    background-color: white;
    color:rgb(29, 30, 32);
    border: 2px solidrgb(25, 26, 28);
    border-radius: 5px; /* Reducido para bordes más cuadrados */
    padding: 12px 20px;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
  }

  .btn-google:hover {
    background-color: #f1f1f1;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .btn-google svg {
    width: 24px;
    height: 24px;
  }

  .footer {
    margin-top: 20px;
    text-align: center;
    font-size: 14px;
  }S

  .forgot-password {
    text-align: center;
    margin-top: 15px;
  }

  .forgot-password a {
    text-decoration: none;
    color:rgb(25, 26, 26);
  }

  .forgot-password a:hover {
    text-decoration: underline;
  }

  .focus-ring:focus {
    border-color:rgb(14, 14, 15) !important; /* Azul Bootstrap */
    box-shadow: 0 0 5px rgba(6, 6, 7, 0.75);
    background-color: #eaf4ff; /* Azul claro */
    border-radius: 5px; /* Reducido para bordes más cuadrados */
  }
</style>

</head>

<body>
  <?php include '../util/menu.php'; ?>

  <div class="container mt-5 container-login" style="text-align: left; max-width: 400px; margin: auto;">
    <h2 class="mb-4">Recuperar contraseña</h2>

    <div id="error" class="alert alert-danger" style="display:none;" role="alert"></div>

    <form id="recoverForm" class="w-100">
      <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Ingresa tu email" required>
      </div>

      <div class="d-grid gap-2 mb-3">
        <button type="submit" class="btn btn-primary">Recuperar contraseña</button>
      </div>    
    </form>

    <div class="footer mt-4">
      <p>¿No tienes cuenta? <a href="registrarse.php">Regístrate aquí</a></p>
    </div>
  </div>


  <div class="modal fade" id="codeModal" tabindex="-1" aria-labelledby="codeModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="codeModalLabel">
                    Ingresa el Código de Verificación
                </h5>
            </div>
            <div class="modal-body text-center">
                <p>Por favor, introduce el código de 4 dígitos que te enviamos por correo:</p>
                <div class="d-flex justify-content-center gap-1">
                    <input type="text" class="form-control text-center border border-primary rounded shadow focus-ring" maxlength="1" style="width: 2rem; background-color: #f8f9fa;" id="digit1">
                    <input type="text" class="form-control text-center border border-primary rounded shadow focus-ring" maxlength="1" style="width: 2rem; background-color: #f8f9fa;" id="digit2">
                    <input type="text" class="form-control text-center border border-primary rounded shadow focus-ring" maxlength="1" style="width: 2rem; background-color: #f8f9fa;" id="digit3">
                    <input type="text" class="form-control text-center border border-primary rounded shadow focus-ring" maxlength="1" style="width: 2rem; background-color: #f8f9fa;" id="digit4">
                </div>
                <div id="alertacodigo" class="alert d-none mt-2" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="verifyCode">Verificar</button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">
                    Modificar Contraseña
                </h5>
            </div>
            <div class="modal-body">
                <div id="alertaPassword" class="alert d-none" role="alert"></div>
                <p>Por favor, ingresa tu nueva contraseña y confírmala para continuar con la actualización.</p>
                <!-- Nueva Contraseña -->
                <div class="mb-3">
                    <label for="newPassword" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="newPassword" placeholder="••••••••">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="showNewPassword">
                        <label class="form-check-label" for="showNewPassword">Mostrar Contraseña</label>
                    </div>
                </div>
                <!-- Confirmar Contraseña -->
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="••••••••">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="showConfirmPassword">
                        <label class="form-check-label" for="showConfirmPassword">Mostrar Contraseña</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="cancelarchangecontraseña" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="savePassword">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar/ocultar contraseñas
    document.getElementById('showNewPassword').addEventListener('change', function() {
        const newPasswordField = document.getElementById('newPassword');
        newPasswordField.type = this.checked ? 'text' : 'password';
    });

    document.getElementById('showConfirmPassword').addEventListener('change', function() {
        const confirmPasswordField = document.getElementById('confirmPassword');
        confirmPasswordField.type = this.checked ? 'text' : 'password';
    });
</script>








<div class="modal fade" id="successPasswordModal" tabindex="-1" aria-labelledby="successPasswordModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successPasswordModalLabel">
                    Contraseña Actualizada Exitosamente
                </h5>                
            </div>
            <div class="modal-body">
                <p>Tu contraseña se ha actualizado correctamente. </p>
            </div>
            <div class="modal-footer">
                <button id="cerrarModales" type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>




<?php 

  
  ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/recoverPassword.js"></script>
</body>

</html>





