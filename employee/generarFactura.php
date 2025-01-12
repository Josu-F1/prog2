<?php
require_once '../vendor/tecnickcom/tcpdf/tcpdf.php'; // Ajusta la ruta a TCPDF según tu instalación
include '../util/conexion.php'; // Incluye la conexión a la base de datos

// Verifica si el ID fue enviado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID de devolución no proporcionado');
}

$devolucion_id = intval($_GET['id']);

// Consulta para obtener los datos completos
$sql = "SELECT 
            devoluciones.*, 
            alquileres.fecha_inicio, alquileres.fecha_fin, alquileres.estado AS estado_alquiler, alquileres.monto_esperado,
            usuarios.nombre AS nombre_usuario, usuarios.email AS email_usuario,
            vehiculos.matricula, vehiculos.marca, vehiculos.modelo, vehiculos.color
        FROM devoluciones
        JOIN alquileres ON devoluciones.alquiler_id = alquileres.id
        JOIN usuarios ON alquileres.usuario_id = usuarios.id
        JOIN vehiculos ON alquileres.vehiculo_id = vehiculos.id
        WHERE devoluciones.id = $devolucion_id";

$result = mysqli_query($conn, $sql);
if ($result->num_rows === 0) {
    die('Devolución no encontrada');
}

$data = mysqli_fetch_assoc($result);

// Crear el PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Gestión de Alquileres');
$pdf->SetTitle('Factura de Alquiler y Devolución');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

// Contenido del PDF
$html = <<<EOD
<h1 style="text-align: center;">Factura de Alquiler y Devolución</h1>
<hr>
<h2>Datos del Cliente</h2>
<p><strong>Nombre:</strong> {$data['nombre_usuario']}</p>
<p><strong>Email:</strong> {$data['email_usuario']}</p>

<h2>Datos del Vehículo</h2>
<p><strong>Matrícula:</strong> {$data['matricula']}</p>
<p><strong>Marca:</strong> {$data['marca']}</p>
<p><strong>Modelo:</strong> {$data['modelo']}</p>
<p><strong>Color:</strong> {$data['color']}</p>

<h2>Detalles del Alquiler</h2>
<p><strong>Fecha de Inicio:</strong> {$data['fecha_inicio']}</p>
<p><strong>Fecha de Fin:</strong> {$data['fecha_fin']}</p>
<p><strong>Estado del Alquiler:</strong> {$data['estado_alquiler']}</p>
<p><strong>Monto Esperado:</strong> \$ {$data['monto_esperado']}</p>

<h2>Detalles de la Devolución</h2>
<p><strong>Fecha de Devolución:</strong> {$data['fecha_devolucion']}</p>
<p><strong>Estado del Vehículo:</strong> {$data['estado_vehiculo']}</p>
<p><strong>Nivel de Combustible:</strong> {$data['nivel_combustible']}</p>
<p><strong>Limpieza:</strong> {$data['limpieza']}</p>
<p><strong>Daños Visibles:</strong> {$data['daños_visibles']}</p>
<p><strong>Costos Adicionales:</strong> \$ {$data['cargos_adicionales']}</p>
<p><strong>Costo Total:</strong> \$ {$data['costo_total']}</p>
<p><strong>Observaciones:</strong> {$data['observaciones']}</p>

<hr>
<p style="text-align: center;">Gracias por utilizar nuestros servicios</p>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("Factura_Alquiler_Devolucion_{$devolucion_id}.pdf", 'D'); // Forzar descarga
?>
