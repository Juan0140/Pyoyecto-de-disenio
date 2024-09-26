<?php
require "../db/conexion.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);


// Inicializa las variables
$horasTotales = array('09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00', '15:00:00', '16:00:00', '17:00:00', '18:00:00');
$horasOcupadas = array();

// Recibe la fecha y el ID del barbero desde la solicitud AJAX
$fecha = $_POST['fecha'] ?? '';
$idBarbero = $_POST['idBarbero'] ?? '';

// Verifica que la fecha y el ID del barbero estén presentes
if (!empty($fecha) && !empty($idBarbero)) {
    // Consulta las horas ocupadas en la base de datos
    $sql = "SELECT hora, hora_fin FROM cita WHERE fecha = ? AND idBarbero = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $fecha, $idBarbero);
    $stmt->execute();
    $stmt->store_result();

    // Verifica si hay horas ocupadas
    if ($stmt->num_rows > 0) {
        // Obtiene las horas ocupadas y las almacena en un array
        $stmt->bind_result($hora, $hora_fin);

        while ($stmt->fetch()) {
            $horaFormateada = date("H:i:s", strtotime($hora));
            $horaFinFormateada = date("H:i:s", strtotime($hora_fin));

            // Agrega las horas ocupadas al array
            $horasOcupadas = array_merge($horasOcupadas, obtenerHorasOcupadas($horaFormateada, $horaFinFormateada, $horasTotales));
        }
    }

    // Cierra la conexión y devuelve las horas ocupadas en formato JSON
    $stmt->close();
    $conexion->close();

    echo json_encode(array_values($horasOcupadas));
} else {
    // Devuelve una respuesta adecuada si falta la fecha o el ID del barbero
    echo json_encode(['error' => 'Falta la fecha o el ID del barbero']);
}

// Función para obtener las horas ocupadas dentro de las horas totales
function obtenerHorasOcupadas($horaInicio, $horaFin, $horasTotales) {
    $horasOcupadas = array();

    foreach ($horasTotales as $horaTotal) {
        // Verifica si la hora total está dentro del rango de horas ocupadas
        if ($horaTotal > $horaInicio && $horaTotal <= $horaFin) {
            $horasOcupadas[] = $horaTotal;
        }
    }

    return $horasOcupadas;
}
?>
