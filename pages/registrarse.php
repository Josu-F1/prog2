<?php
session_start();
include '../util/verificadorSesion.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrarse</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../styles/background.css">
  <style>
    body {
      background-color: #f0f4f8;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .container-registro {
      display: flex;
      justify-content: space-between;
      width: 100%;
      max-width: 1000px;
      gap: 40px;
    }

    /* Estilo para el formulario */
    .form-col {
      background-color: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      flex: 1;
    }

    h2 {
      color:rgb(29, 30, 32);
      font-weight: bold;
      margin-bottom: 30px;
      text-align: center;
    }

    .form-label {
      font-weight: 600;
    }

    .form-control,
    .form-select {
      border-radius: 8px;
      padding-left: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #0056b3;
      box-shadow: 0 0 8px rgba(0, 102, 204, 0.3);
    }

    .btn-primary {
      background-color:rgb(21, 22, 22);
      border: none;
      border-radius: 5px;
      padding: 12px;
      font-size: 16px;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
      background-color:rgb(14, 13, 13);
      transform: translateY(-2px);
    }

    /* Estilo para la columna de palabras */
    .info-col {
      flex: 1;
      color: white;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: left;
    }

    .info-col h3 {
      font-size: 36px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #fff;
    }

    .info-col p {
      font-size: 20px;
      line-height: 1.6;
      color: white;
      margin-bottom: 25px;
    }

    /* Contenedor de iconos */
    .icon-container {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .icon-container div {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .icon-container i {
      font-size: 35px;
      color: white;
      transition: transform 0.2s;
    }

    .icon-container i:hover {
      transform: scale(1.2);
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
  </style>
</head>

<body>

  <!-- Menú de navegación -->
  <?php include '../util/menu.php'; ?>

  <!-- Contenedor principal del formulario y la información -->
  <div class="container">
    <div class="container-registro">
      
      <!-- Columna del formulario (izquierda) -->
      <div class="form-col">
        <h2>Crear una Cuenta Nueva</h2>

        <!-- Alerta de notificación -->
        <div id="alerta" class="alert alert-danger" style="display:none;" role="alert"></div>

        <!-- Formulario de registro -->
        <form id="registroForm">
          <div class="mb-4">
            <label for="nombre" class="form-label">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Tu nombre completo" required>
          </div>

          <div class="mb-4">
            <label for="email" class="form-label">Correo Electrónico:</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Ejemplo: usuario@dominio.com" required>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Al menos 6 caracteres" required minlength="6">
          </div>

          <div class="mb-4">
            <label for="passwordConfirm" class="form-label">Confirmar Contraseña:</label>
            <input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control" placeholder="Repite tu contraseña" required minlength="6">
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Registrarme</button>
          </div>
        </form>

        <!-- Pie de página con enlace para iniciar sesión -->
        <div class="footer mt-4">
          ¿Ya tienes cuenta? <a href="iniciarSesion.php">Inicia sesión aquí</a>
        </div>
      </div>

      <!-- Columna de palabras e iconos (derecha) -->
<!-- Columna de palabras e iconos (derecha) -->
<div class="info-col">
  <h3>¡Beneficios de Registrarte!</h3>
  <p>Al registrarte, accedes a una experiencia única y muchos beneficios. No dejes pasar la oportunidad.</p>

  <div class="icon-container">
    <div>
      <span style="font-size: 30px;">🔒</span>
      <p><strong>Seguridad:</strong> Tu información estará completamente protegida con los más altos estándares de seguridad.</p>
    </div>
    <div>
      <span style="font-size: 30px;">👍</span>
      <p><strong>Facilidad:</strong> Accede a nuestros servicios de manera sencilla y rápida, sin complicaciones.</p>
    </div>
    <div>
      <span style="font-size: 30px;">👥</span>
      <p><strong>Comunidad:</strong> Únete a una red de usuarios que comparten tus intereses y experiencias.</p>
    </div>
    
</div>



    </div>
  </div>

  <?php 
    include '../util/codigomodal.html';
    include '../util/modalCRegistrar.html';
  ?>

  <!-- Scripts de Bootstrap y jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="../js/registrarse.js"></script>

  <!-- FontAwesome para los iconos -->
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>

</html>

