<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_end_clean();
require '../db/conexion.php'; // Reemplaza 'tu-archivo-de-conexion.php' con el archivo que contiene la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cita'])) {
    // Recibe la cita en formato JSON y conviértela a un array de PHP
    $cita = json_decode($_POST['cita'], true);
    // Inicia una transacción
    mysqli_begin_transaction($conexion);

    try {
        // Inserta la cita en la tabla 'cita'
        $idCliente=intval($cita['idCliente']);
        $fecha = $cita['fecha'];
        $hora = $cita['hora'];
        $hora_fin=$cita['hora_fin'];
        $idBarbero = intval($cita['barbero']['idBarbero']);
        $estado = 'Pendiente'; // Puedes ajustar este valor según tus necesidades

        $queryCita = "INSERT INTO cita (idCliente, idBarbero, estado, fecha, hora, hora_fin) VALUES ('$idCliente', '$idBarbero', '$estado', '$fecha', '$hora', '$hora_fin')";
        mysqli_query($conexion, $queryCita);

        // Obtiene el idCita generado automáticamente
        $idCita = mysqli_insert_id($conexion);

        // Inserta los servicios en la tabla 'cita_servicio'
        foreach ($cita['servicios'] as $servicio) {
            $idServicio = intval($servicio['id']);
            $queryCitaServicio = "INSERT INTO cita_servicio (idCita, idServicio) VALUES ('$idCita', '$idServicio')";
            mysqli_query($conexion, $queryCitaServicio);
        }

        // Confirma la transacción
        mysqli_commit($conexion);

        $responseData = ['success' => true, 'message' => 'Cita reservada exitosamente'];
        echo json_encode($responseData);
    } catch (Exception $e) {
        // Revierte la transacción en caso de error
        mysqli_rollback($conexion);

        // Devuelve una respuesta al cliente con información sobre el error
        http_response_code(500); // Código de respuesta HTTP 500 (Internal Server Error)
        echo json_encode(['success' => false, 'message' => 'Error al reservar la cita']);
    } finally {
        // Cierra la conexión
        mysqli_close($conexion);
    }
} else {
    // Manejo de error si la solicitud no es de tipo POST o si no se proporciona la cita
    http_response_code(400); // Código de respuesta HTTP 400 (Bad Request)
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
