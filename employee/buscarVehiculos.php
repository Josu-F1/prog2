<?php
include '../util/conexion.php'; // Incluye tu archivo de conexión

// Obtén el término de búsqueda desde la solicitud GET
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Prepara la consulta SQL con parámetros
$stmt = $conn->prepare("SELECT marca, modelo, matricula, imagen, id 
                        FROM vehiculos 
                        WHERE disponibilidad = 'Disponible' 
                        AND (LOWER(marca) LIKE ? 
                        OR LOWER(modelo) LIKE ? 
                        OR LOWER(matricula) LIKE ?)");
$searchQuery = '%' . strtolower($query) . '%';
$stmt->bind_param('sss', $searchQuery, $searchQuery, $searchQuery);
$stmt->execute();
$result = $stmt->get_result();

$datavehicles = [];

// Procesa los resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $datavehicles[] = [
            'id' => $row['id'] ?? null,
            'marca' => $row['marca'] ?? '',
            'modelo' => $row['modelo'] ?? '',
            'matricula' => $row['matricula'] ?? '',
            'imagen' => $row['imagen'] ?? 'placeholder.jpg', // Imagen predeterminada si no está presente
        ];
    }
}

// Configura el encabezado y devuelve el JSON
header('Content-Type: application/json');
echo json_encode($datavehicles);
?>
