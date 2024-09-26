<?php
require '../db/conexion.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$nombreServicio=$_POST['nombre'];
$precio=$_POST['precio'];

$queryAgregar="INSERT INTO servicios(nombre, precio) VALUES ('$nombreServicio', '$precio')";
$result=$conexion->query($queryAgregar);
if($result){
    $response=array('success'=>true);
    echo json_encode($response);

}else{
    $response = array('success' => false, 'message' => 'Contraseña incorrecta');

    echo json_encode($response);
}