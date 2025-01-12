<?php
include './util/conexion.php';

session_start();

if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['tipo_usuario'] === 'administrador') {
        header("Location: admin/gestion_users.php");
        exit();
    } elseif ($_SESSION['tipo_usuario'] === 'empleado') {
        header("Location: employee/home_employee.php");
        exit();
    } else {
        header("Location: client/home_client.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <title>Alquiler de Autos Premium</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }

        .fullscreen-video {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .fullscreen-video video {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: 'Montserrat', sans-serif;
            z-index: 1;
            text-align: center;
        }

        .overlay-title {
            font-size: 3.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
            margin-bottom: 20px;
        }

        .overlay-description {
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.7);
        }

        .cta-button {
            display: inline-block;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: bold;
            background-color:rgb(11, 11, 11);
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #0056b3;
        }

        .icon-container {
            display: flex;
            gap: 30px;
            margin-top: 40px;
        }

        .icon {
            font-size: 2rem;
            color: white;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.7);
        }
    </style>
</head>

<body>
    <!-- Menú -->
    <div class="menu-container">
        <?php include './util/menuindex.php'; ?>
    </div>

    <!-- Video -->
    <div class="fullscreen-video">
        <video autoplay muted loop>
            <source src="./images/videos/auto_en_movimiento.mp4" type="video/mp4">
            Tu navegador no soporta la reproducción de video.
        </video>

        <!-- Overlay Content -->
        <div class="overlay-container">
            <h1 class="overlay-title">Redescubre el Placer de Conducir</h1>
            <p class="overlay-description">Sumérgete en nuestra exclusiva selección de autos de lujo, cada uno diseñado para brindarte una experiencia única.</p>
            <a href="./pages/catalogo.php" class="cta-button">Explora nuestro Catálogo</a>

            <!-- Icon Section -->
            <div class="icon-container">
                <div class="icon">
                    🚗 <br>
                    <small>Autos Deportivos</small>
                </div>
                <div class="icon">
                    ⚡ <br>
                    <small>Vehículos Eléctricos</small>
                </div>
                <div class="icon">
                    🛠️ <br>
                    <small>100% Garantizados</small>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
