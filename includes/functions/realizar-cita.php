<?php
require '../db/conexion.php';

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

    // Sanitizar el ID de la cita
    $idCita = $_POST['idCita'];
    // Actualizar el estado de la cita a "Realizada"
    $queryActualizar = mysqli_query($conexion, "UPDATE cita SET estado='realizada' WHERE id='$idCita'");

    if ($queryActualizar) {
        // Operación exitosa
        $response = array('success' => true);
        echo json_encode($response);
        exit;
    } else {
        // Error al actualizar
        $response = array("success" => false, 'error' => 'Error al actualizar la cita');
        echo json_encode($response);
        exit;
    }

?>
