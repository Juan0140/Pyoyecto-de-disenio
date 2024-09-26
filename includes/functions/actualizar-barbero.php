<?php
require '../db/conexion.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$idBarbero=$_POST['idBarbero'];
$telefono=$_POST['telefono'];
$comision=$_POST['comision'];
$password=$_POST['password'];
$idAdmin=$_SESSION['idAdmin'];

$selectPasswordSql = "SELECT contra FROM accesobarberos WHERE idBarbero = '$idAdmin'";
$result = $conexion->query($selectPasswordSql);
if ($result) {
    $row = $result->fetch_assoc();
    $storedPassword = $row['contra'];
    if (password_verify($password, $storedPassword)) {
        $queryActualizar = "UPDATE barberos SET telefono='$telefono', comision='$comision' WHERE id='$idBarbero'";
        $result = $conexion->query($queryActualizar);
        $response = array('success' => true, 'message' => 'Datos actualizados correctamente');
        echo json_encode($response);
    }else{
        $response = array('success' => false, 'message' => 'Contraseña incorrecta');

        echo json_encode($response);
    }
}
$conexion->close();