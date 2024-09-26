<?php
require '../db/conexion.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Obtenemos lo mandado por ajax
$idBarbero=$_POST['idBarbero'];
$vacIni=$_POST['fecIni'];
$vacFin=$_POST['fecFin'];

$queryVac="UPDATE barberos SET vac_ini='$vacIni', vac_fin='$vacFin' WHERE id='$idBarbero'";
$result=$conexion->query($queryVac);
if($result){
    $response = array('success' => true);
    echo json_encode($response);
}else{
    $response = array('success' => false);
    echo json_encode($response);
}