<?php
require '../db/conexion.php';

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sanitizar el ID de la cita
$idCita = $_POST['idCita'];
$idCliente = $_POST['idCliente'];
$conexion->begin_transaction();

try {
    $queryEliminarServicios=mysqli_query($conexion, "DELETE  FROM cita_servicio WHERE idCita='$idCita'");
    $queryEliminarCita=mysqli_query($conexion, "DELETE  FROM cita WHERE id='$idCita'");
    $queryObtenerCanceladas=mysqli_query($conexion, "SELECT canceladas FROM clientes WHERE id='$idCliente'");
    while($cliente=mysqli_fetch_array($queryObtenerCanceladas)){
        $cancelada=$cliente['canceladas'];
    }
    $nuevaCancelada=$cancelada+1;
    $queryActualizarCanceladas=mysqli_query($conexion, "UPDATE clientes SET canceladas='$nuevaCancelada' WHERE id='$idCliente'");
    $conexion->commit();
    $respuesta = array("success" => true);
    echo json_encode($respuesta);
} catch (\Throwable $th) {
    throw $th;
    $conexion->rollback();

    // Construir la respuesta JSON
    $respuesta = array("success" => false, "message" => "Error en la transacción: " . $th->getMessage());
    echo json_encode($respuesta);
}