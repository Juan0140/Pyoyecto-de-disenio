<?php
header('Content-Type: application/json');
require_once('../../TCPDF/tcpdf.php');
define('K_PATH_FONTS', '../../TCPDF/fonts');
require '../db/conexion.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


$idBarbero = isset($_POST['idBarbero']) ? $_POST['idBarbero'] : null;

if ($idBarbero === null) {
    $response = array('success' => false, 'message' => 'ID de barbero no proporcionado.');
    echo json_encode($response);
    exit;
}

date_default_timezone_set('America/Mexico_City');
ob_start();

$fechaActual = date('Y-m-d');

// Crear instancia de TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
$pdf->SetMargins(10, 10, 10); 
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 9);

// Consulta SQL para obtener las citas
$query = "SELECT c.id, c.hora, c.hora_fin, c.fecha, cl.nombre AS nombre_cliente, cl.apellidos AS apellidos_cliente,
s.nombre AS nombre_servicio, s.precio, b.nombre AS nombre_barbero, b.apellidos AS apellidos_barbero, b.comision, b.fecha_corte
FROM cita c
JOIN clientes cl ON c.idCliente = cl.id
JOIN cita_servicio cs ON c.id = cs.idCita
JOIN servicios s ON cs.idServicio = s.id
JOIN barberos b ON c.idBarbero = b.id
WHERE c.idBarbero = $idBarbero
AND c.estado = 'realizada'
AND c.fecha BETWEEN b.fecha_corte AND '$fechaActual'
ORDER BY c.fecha, c.hora;";

$resultado = $conexion->query($query);



$primerCita = $resultado->fetch_assoc();

if ($resultado->num_rows === 0 || $primerCita['fecha_corte'] > $fechaActual) {
    $response = array('success' => false, 'message' => 'No se encontraron datos.');
    echo json_encode($response);
    exit;
}

// Verificar si hay al menos una cita
if ($primerCita) {
    // Información del barbero
    $nombreBarbero = $primerCita['nombre_barbero'] . ' ' . $primerCita['apellidos_barbero'];
    $comisionBarbero = $primerCita['comision'];
}
$pdf->Cell(0, 10, 'Informe de Citas', 0, 1, 'C');
// Agregar encabezado al PDF
$periodoBusqueda = "Desde " . $primerCita['fecha_corte'] . " hasta " . $fechaActual;
    
// Encabezado con información del barbero y periodo de búsqueda
$pdf->Cell(0, 10, 'Barbero: ' . $nombreBarbero . ' - Comisión: ' . $comisionBarbero . '% - ' . $periodoBusqueda, 0, 1, 'C');

$pdf->Ln();

// Agregar encabezados de columna al PDF
$pdf->Cell(25, 10, 'Fecha', 1);
$pdf->Cell(25, 10, 'Hora Inicio', 1);
$pdf->Cell(25, 10, 'Hora Fin', 1);
$pdf->Cell(35, 10, 'Cliente', 1);
$pdf->Cell(40, 10, 'Servicio', 1);
$pdf->Cell(20, 10, 'Precio', 1);
$pdf->Cell(20, 10, 'Comisión', 1);
$comisionesTotales=0;
// Agregar datos al PDF
while ($cita = $resultado->fetch_assoc()) {
    $comision = $cita['comision'] / 100;
    $comi = $cita['precio'] * $comision;
    $comisionesTotales+=$comi;
    $pdf->Ln(); // Salto de línea para la siguiente fila
    $pdf->Cell(25, 10, $cita['fecha'], 1);
    $pdf->Cell(25, 10, $cita['hora'], 1);
    $pdf->Cell(25, 10, $cita['hora_fin'], 1);
    $pdf->Cell(35, 10, $cita['nombre_cliente'] . ' ' . $cita['apellidos_cliente'], 1);
    $pdf->Cell(40, 10, $cita['nombre_servicio'], 1);
    $pdf->Cell(20, 10, '$' . intval($cita['precio']), 1);
    $pdf->Cell(20, 10, '$' . $comi, 1);
}

// Agregar fila para el total de las comisiones
$pdf->Ln();
$pdf->Cell(50, 10, 'Total Comisiones', 1);
$pdf->Cell(25, 10, '$' . number_format($comisionesTotales, 2), 1);

$rutaPdf = __DIR__ . '/../../pdf/Informe_Citas.pdf';

// Guardar el PDF en la carpeta especificada
$pdf->Output($rutaPdf, 'F');

// Devolver la respuesta JSON indicando éxito y la ruta del archivo
$response = array('success' => true, 'pdfPath' => $rutaPdf);
echo json_encode($response);
exit;

ob_end_clean();
?>
