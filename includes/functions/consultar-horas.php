<?php
require "../db/conexion.php";

// Recibe la fecha y el ID del barbero desde la solicitud AJAX
$fecha = $_POST['fecha'];
$idBarbero = $_POST['idBarbero'];

// Define las horas disponibles según tu horario (de 9-4 y de 4-6, por ejemplo)
$horasTotales = array('09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '15:00:00', '16:00:00', '17:00:00');

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
    $horasOcupadas = array();

    while ($stmt->fetch()) {
        $horaFormateada = date("H:i:s", strtotime($hora));
        $horaFinFormateada = date("H:i:s", strtotime($hora_fin));
        
        // Agrega las horas ocupadas al array
        $horasOcupadas = array_merge($horasOcupadas, obtenerHorasOcupadas($horaFormateada, $horaFinFormateada, $horasTotales));
    }

    // Calcula las horas disponibles restando las ocupadas
    $horasDisponibles = array_diff($horasTotales, $horasOcupadas);
} else {
    // Si no hay horas ocupadas, todas las horas están disponibles
    $horasDisponibles = $horasTotales;
}

// Cierra la conexión y devuelve las horas disponibles en formato JSON
$stmt->close();
$conexion->close();

echo json_encode(array_values($horasDisponibles));

// Función para obtener las horas ocupadas dentro de las horas totales
function obtenerHorasOcupadas($horaInicio, $horaFin, $horasTotales) {
    $horasOcupadas = array();

    foreach ($horasTotales as $horaTotal) {
        // Verifica si la hora total está dentro del rango de horas ocupadas
        if ($horaTotal >= $horaInicio && $horaTotal < $horaFin) {
            $horasOcupadas[] = $horaTotal;
        }
    }

    return $horasOcupadas;
}
?>
