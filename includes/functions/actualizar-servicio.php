<?php
require '../db/conexion.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$idServicio = $_POST['idServicio'];
$precioNew = $_POST['precioNew'];
$idAdmin = $_SESSION['idAdmin'];
$password=$_POST['password'];

$selectPasswordSql = "SELECT contra FROM accesobarberos WHERE idBarbero = '$idAdmin'";
$result = $conexion->query($selectPasswordSql);
if ($result) {
    // Obtener la contraseña almacenada
    $row = $result->fetch_assoc();
    $storedPassword = $row['contra'];
    if (password_verify($password, $storedPassword)) {
        $queryActualizar = "UPDATE servicios SET precio='$precioNew' WHERE id='$idServicio'";
        $result = $conexion->query($queryActualizar);
        $response = array('success' => true, 'message' => 'Datos actualizados correctamente');
        echo json_encode($response);
    }else{
        $response = array('success' => false, 'message' => 'Contraseña incorrecta');

        echo json_encode($response);
    }
}

$conexion->close();
