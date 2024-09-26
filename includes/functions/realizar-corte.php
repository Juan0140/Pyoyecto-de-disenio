<?php
require '../db/conexion.php';

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Mexico_City');
$fechaActual = date('Y-m-d');
$fechaManana = date('Y-m-d', strtotime($fechaActual . ' +1 day'));
$idBarbero=$_POST['idBarbero'];

$queryRealizar="UPDATE barberos SET fecha_corte='$fechaManana' WHERE id='$idBarbero'";
$result=$conexion->query($queryRealizar);
if($result){
    $response = array("success" => true);
    echo json_encode($response);
}else{
    $response = array("success" => false);
    echo json_encode($response);
}