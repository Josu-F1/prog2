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
// Crear el PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('FACTURA');
$pdf->SetTitle('Factura de Alquiler y Devolución');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

// Validar y asignar valores predeterminados si las claves no existen
$data['factura_numero'] = $data['factura_numero'] ?? '0001';
$data['fecha_emision'] = $data['fecha_emision'] ?? date('Y-m-d');

$extraCharge = rand(30, 70);
$totalAmount = $data['monto_esperado'] + $extraCharge;
$totalAmount2 = $data['monto_esperado'] - $extraCharge;
$additionalAmount = $totalAmount - $data['monto_esperado'];

// Contenido del PDF con estructura renovada
$html = <<<EOD
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    .invoice-container {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        padding: 40px;
    }
    .invoice-header {
        display: flex;
        justify-content: space-between;
        border-bottom: 2px solid #000;
        padding-bottom: 25px;
        margin-bottom: 50px;
    }
    .invoice-title {
        font-size: 26px;
        font-weight: bold;
        color: #333;
    }
    .invoice-info {
        text-align: right;
        font-size: 16px;
        color: #666;
    }
    .section-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 30px;
        margin-top: 30px;
        border-bottom: 3px solid #000;
        padding-bottom: 10px;
        color: #333;
    }
    .invoice-table {
        width: 100%;
        font-size: 16px;
        border-collapse: collapse;
        margin-bottom: 50px;
    }
    .invoice-table td {
        padding: 18px 15px;
        vertical-align: top;
        border-bottom: 2px solid #ddd;
    }
    .invoice-table td strong {
        font-weight: bold;
        color: #333;
    }
    .centered {
        text-align: center;
        font-size: 16px;
        color: #666;
        margin-top: 35px;
    }
    .total-row td {
        font-weight: bold;
        color: #000;
    }
</style>

<div class="invoice-container">
    <table class="invoice-header">
        <tr>
            <td class="invoice-title">FACTURA</td>
            <td class="invoice-info">
                <strong>Factura Nº:</strong> {$data['factura_numero']}<br>
                <strong>Fecha de Emisión:</strong> {$data['fecha_emision']}
            </td>
        </tr>
    </table>

    <div class="section-title">Datos del Cliente</div>
    <table class="invoice-table">
        <tr>
            <td><strong>Nombre:</strong> {$data['nombre_usuario']}</td>
            <td><strong>Email:</strong> {$data['email_usuario']}</td>
        </tr>
    </table>

    <div class="section-title">Datos del Vehículo</div>
    <table class="invoice-table">
        <tr>
            <td><strong>Matrícula:</strong> {$data['matricula']}</td>
            <td><strong>Marca:</strong> {$data['marca']}</td>
        </tr>
        <tr>
            <td><strong>Modelo:</strong> {$data['modelo']}</td>
            
        </tr>
    </table>

    <div class="section-title">Detalles del Alquiler</div>
    <table class="invoice-table">
        <tr>
            <td><strong>Fecha de Alquiler:</strong> {$data['fecha_inicio']}</td>
            <td><strong>Fecha de Finalización:</strong> {$data['fecha_fin']}</td>
        </tr>
        <tr>
            <td><strong>Alquiler:</strong> {$data['estado_alquiler']}</td>
             <td><strong>Monto:</strong> \$ {$totalAmount2}</td>
        </tr>
    </table>

    <div class="section-title">Detalles de la Devolución</div>
    <table class="invoice-table">
        <tr>
            <td><strong>Fecha de Devolución:</strong> {$data['fecha_devolucion']}</td>
            <td><strong>Estado del Vehículo:</strong> {$data['estado_vehiculo']}</td>
        </tr>
        <tr>
            <td><strong>Lavado:</strong> {$data['limpieza']}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Daños:</strong> {$data['daños_visibles']}</td>
        </tr>
    </table>

    <div class="section-title">Costos y Observaciones</div>
    <table class="invoice-table">
        <tr>
            <td><strong>Costos Adicionales:</strong> \$ {$additionalAmount}</td>
            <td><strong>Costo Total:</strong> \$ {$data['monto_esperado']}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Observaciones:</strong> {$data['observaciones']}</td>
        </tr>
    </table>

    <div class="total-row">
        <table class="invoice-table">
            <tr>
                <td><strong>Total a Pagar:</strong> \$ {$totalAmount}</td>
            </tr>
        </table>
    </div>

    <hr>
</div>
EOD;



$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("Factura_Alquiler_Devolucion_{$devolucion_id}.pdf", 'D'); // Forzar descarga
?>
