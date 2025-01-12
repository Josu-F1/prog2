<?php
include '../util/conexion.php';

$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']);

session_start();
include '../util/verificadorSesion.php';


require_once '../vendor/autoload.php';
require_once '../util/config.php';
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles/background.css">
  <!-- Font Awesome for Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
   /* Estilo general para el contenedor */
.container-main {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  min-height: 100vh;
  padding: 40px;
}

/* Estilo para el contenedor del formulario */
.container-login {
  background-color: rgba(248, 247, 247, 0.95);
  padding: 40px;
  border-radius: 5px; /* Más cuadrado */
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  transition: all 0.3s ease;
}

.container-login:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

h2 {
  color: rgb(7, 7, 7);
  font-weight: 600;
  text-align: center;
}

.form-control {
  border-radius: 5px; /* Más cuadrado */
  padding-left: 45px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
}

.form-group {
  position: relative;
}

.form-control-icon {
  position: absolute;
  top: 50%;
  left: 15px;
  transform: translateY(-50%);
  color: #2a9df4;
}

.btn-primary {
  background-color: rgb(18, 18, 18); 
  border: none;
  border-radius: 5px; /* Más cuadrado */
  padding: 12px 20px;
  font-size: 16px;
  width: 100%;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background-color: rgb(23, 23, 23);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.btn-google {
  background-color: rgb(6, 6, 6);
  color: white;
  border: 2px solid rgb(16, 16, 16);
  border-radius: 5px; /* Más cuadrado */
  padding: 12px 20px;
  font-size: 16px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.btn-google:hover {
  background-color: rgb(21, 21, 22);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.btn-google svg {
  width: 24px;
  height: 24px;
}

.forgot-password {
  text-align: center;
  margin-top: 15px;
}

.forgot-password a {
  text-decoration: none;
  color: #0056b3;
}

.forgot-password a:hover {
  text-decoration: underline;
}

.footer {
  margin-top: 20px;
  text-align: center;
  font-size: 14px;
}

.footer a {
  color: #0056b3;
  text-decoration: none;
}

.footer a:hover {
  text-decoration: underline;
}

/* Estilo para la sección de texto a la derecha */
.text-section {
  max-width: 500px;
  color: white;
}

.text-section h3 {
  font-size: 2rem;
  color: white;
  font-weight: 600;
}

.text-section p {
  font-size: 1.1rem;
  color: white;
}

.text-section .icon-container {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 20px;
  text-align: left;
}

.text-section .icon-container div {
  display: flex;
  align-items: center;
  gap: 10px;
}

.text-section .icon-container i {
  font-size: 3rem;
  color: white;
}

.text-section .icon-container img {
  width: 50px;
  height: 50px;
}

.icon-container span {
  font-size: 40px;
}


  </style>
</head>

<body>
  <?php include '../util/menu.php'; ?>

  <div class="container">
    <div class="row">
      <!-- Agregar un espacio antes del formulario para desplazarlo hacia abajo -->
      <div class="col-12" style="height: 60px;"></div>

      <!-- Sección del formulario en una columna -->
      <div class="col-md-6">
        <div class="container-login">
          <h2>Inicio de Sesión</h2>

          <div id="error" class="alert alert-danger" style="display:none;" role="alert"></div>

          <form id="loginForm" class="w-100">
            <div class="mb-3 form-group">
              <label for="email" class="form-label">Email:</label>
              <div class="input-group">
                <span class="form-control-icon"><i class="fas fa-envelope"></i></span>
                <input type="email" id="email" name="email" class="form-control" placeholder="Ingresa tu email" required>
              </div>
            </div>

            <div class="mb-3 form-group">
              <label for="password" class="form-label">Contraseña:</label>
              <div class="input-group">
                <span class="form-control-icon"><i class="fas fa-lock"></i></span>
                <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa tu contraseña" required>
              </div>
            </div>

            <div class="d-grid gap-2 mb-3">
              <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </div>

            <div class="d-grid gap-2 mb-3">
              <button type="button" class="btn btn-google" onclick="window.location.href='<?php echo $client->createAuthUrl(); ?>'">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48">
                  <path fill="#4285F4" d="M24 9.5c3.24 0 6.16 1.15 8.45 3.04l6.35-6.35C34.15 3.44 29.48 1.5 24 1.5 14.96 1.5 7.15 7.18 4.3 14.95l7.68 5.98C13.64 12.72 18.43 9.5 24 9.5z" />
                  <path fill="#34A853" d="M9.14 28.11C9.7 30.2 10.78 32.09 12.2 33.5l7.68-5.97C17.6 26.92 16.73 24.3 16.73 21c0-1.19.21-2.33.58-3.39L9.14 28.11z" />
                  <path fill="#FBBC05" d="M23.95 40.5c-4.7 0-8.68-2.1-11.36-5.39l-7.67 5.97C10.4 44.91 17.1 48 24 48c5.36 0 10.33-1.8 14.14-4.91l-7.68-5.97C28.61 39.03 26.4 40.5 23.95 40.5z" />
                  <path fill="#EA4335" d="M44.5 24.5c0-1.7-.23-3.34-.64-4.91h-20v9.09h11.36c-.5 2.13-2.03 3.92-4.03 5.02l7.67 5.97c4.47-4.14 6.64-9.93 6.64-15.17z" />
                </svg>
                Iniciar Sesión con Google
              </button>
            </div>

            <div class="forgot-password">
              <a href="recoverPassword.php">¿Olvidaste tu contraseña?</a>
            </div>
          </form>

          <div class="footer mt-4">
            <p>¿No tienes cuenta? <a href="registrarse.php">Regístrate aquí</a></p>
          </div>
        </div>
      </div>

      <!-- Sección de información a la derecha en la otra columna -->
      <div class="col-md-6">
        <div class="text-section">
          <h3>Alquila un Auto con Nosotros</h3>
          <p>¿Estás buscando un alquiler de auto confiable y accesible? ¡Tenemos lo que necesitas!</p>

          <div class="icon-container">
            <!-- Beneficio con Emojis alineado en columna -->
            <div>
              <span>🚗</span>
              <p><strong>Autos de calidad:</strong> Alquila vehículos de primera calidad, bien mantenidos y listos para el viaje.</p>
            </div>
            <div>
              <span>🔧</span>
              <p><strong>Mantenimiento gratuito:</strong> Nuestro servicio incluye mantenimiento para asegurar que tu experiencia sea excelente.</p>
            </div>
            <div>
              <span>📍</span>
              <p><strong>Alquiler en cualquier lugar:</strong> Te ofrecemos la posibilidad de alquilar en múltiples ubicaciones, ¡donde lo necesites!</p>
            </div>
            <div>
              <span>🎉</span>
              <p><strong>Ofertas especiales:</strong> Disfruta de precios exclusivos y promociones solo por ser parte de nuestra red.</p>
            </div>
            <div>
              <span>🌎</span>
              <p><strong>Servicio global:</strong> Nuestros autos están disponibles para alquilar en diversas ciudades del mundo.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/iniciarSesion.js"></script>
</body>

</html>





