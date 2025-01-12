<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'administrador') {
    header("Location: ../pages/iniciarSesion.php");
    exit();
}
include '../util/conexion.php';
$sql = "SELECT alquileres.id, alquileres.fecha_inicio, alquileres.fecha_fin, alquileres.estado, usuarios.nombre AS nombre_usuario, vehiculos.matricula, vehiculos.marca, vehiculos.modelo
            FROM alquileres
            JOIN usuarios ON alquileres.usuario_id = usuarios.id
            JOIN vehiculos ON alquileres.vehiculo_id = vehiculos.id";
$result = mysqli_query($conn, $sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $data[] = array(
            'id' => $row['id'],
            'fecha_inicio' => $row['fecha_inicio'],
            'fecha_fin' => $row['fecha_fin'],
            'matricula' => $row['matricula'],
            'marca' => $row['marca'],
            'modelo' => $row['modelo'],
            'nombre_usuario' => $row['nombre_usuario'],
            'estado' => $row['estado']
        );
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">  
<//link rel="stylesheet" href="../styles/footer.css">


    <link rel="stylesheet" href="../styles/home_admin.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .xd {
            display: flex;
            width: 90%;
            height: 90%;
            background-color: aliceblue;
            border-radius: 20px;
            padding: 10px;
            overflow-x: scroll;
        }
        .rental-card {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    font-size: 0.9rem;
    color: #333;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease;
    position: relative;
}

.rental-card-header {
    background-color: #000;  /* Fondo negro */
    color: white;  /* Texto blanco */
    padding: 8px 15px;
    border-radius: 8px 8px 0 0;  /* Bordes redondeados en la parte superior */
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    text-align: center;
    font-weight: bold;
    font-size: 1rem;
}

.rental-card-id {
    font-size: 1rem;
}

        .rental-card h5 {
            font-weight: bold;
            margin-bottom: 12px;
            font-size: 1.1rem;
        }
        .rental-card .btn {
            font-size: 0.85rem;
            transition: background-color 0.3s;
        }
        .rental-card .btn:hover {
            background-color: #f39c12;
        }
        #tablaRents {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .search-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        .search-container input {
            border-radius: 50px;
            padding: 10px;
            font-size: 1rem;
            width: 75%;
        }
        .search-container button {
            border-radius: 50px;
            padding: 10px 20px;
            background-color:rgb(0, 0, 0);
            color: white;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
        .rental-card .contract-section {
            background-color: #f5f5f5;  /* Fondo más claro */
            border-top: 2px solid #ddd;  /* Borde gris */
            padding: 10px 0;
            margin-top: auto; 
            border-radius: 0 0 12px 12px; /* Redondeo en la parte inferior */
        }
        .rental-card .contract-section .btn-contract {
            background-color:rgba(225, 218, 8, 0.9);  /* Botón azul */
            color: white;
            width: 100%;
            text-align: center;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            padding: 10px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .rental-card .contract-section .btn-contract:hover {
            background-color:rgb(67, 96, 127);
            transform: scale(1.02);
        }
    </style> 
</head>

<body>
   


<div class="container-fluid vh-100 d-flex flex-column overflow-hidden">
        <div class="row flex-grow-1">
            <?php include './home_section/scripts/menu.php'; ?>
            <main class="col-12 mx-auto px-4 main-content d-flex flex-column h-100">
                <!-- Alert Section -->
                <div id="alerta2" class="alert d-none" role="alert"></div>

                <!-- Search Bar -->
                <div class="search-container">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                    <button id="clearSearch" class="btn btn-secondary">Limpiar</button>
                </div>

                <!-- Rentals Display -->
                <div id="tablaRents">
                    <?php foreach ($data as $rent): ?>
                        <div class="rental-card">
    <div class="rental-card-header">
        <span class="rental-card-id">ID: <?php echo $rent['id']; ?></span>
    </div>
    <h5>Alquiler ID: <?php echo $rent['id']; ?></h5>
    <p><strong>Fecha Inicio:</strong> <?php echo $rent['fecha_inicio']; ?></p>
    <p><strong>Fecha Fin:</strong> <?php echo $rent['fecha_fin']; ?></p>
    <p><strong>Estado:</strong> <?php echo $rent['estado']; ?></p>
    <p><strong>Cliente:</strong> <?php echo $rent['nombre_usuario']; ?></p>
    <p><strong>Matricula:</strong> <?php echo $rent['matricula']; ?></p>
    <p><strong>Marca:</strong> <?php echo $rent['marca']; ?></p>
    <p><strong>Modelo:</strong> <?php echo $rent['modelo']; ?></p>
</div>

                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>


   
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>    
     <!-- script src="../employee/home_section/js/contrato.js"></script> -->
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.rental-card');

            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                card.style.display = text.includes(query) ? '' : 'none';
            });
        });

        // Clear search functionality
        document.getElementById('clearSearch').addEventListener('click', function () {
            document.getElementById('searchInput').value = '';
            const cards = document.querySelectorAll('.rental-card');
            cards.forEach(card => card.style.display = '');
        });
    </script>

</body>

</html>