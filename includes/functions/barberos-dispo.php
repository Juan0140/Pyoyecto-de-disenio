<?php
require '../db/conexion.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$fecha=$_POST['fecha'];
$query =  "SELECT id, nombre FROM barberos 
WHERE ('$fecha' < vac_ini OR '$fecha' > vac_fin)
  OR (vac_ini IS NULL AND vac_fin IS NULL)";
$resultado = mysqli_query($conexion, $query);

$barberosDisponibles = array();

while ($row = mysqli_fetch_assoc($resultado)) {
    $barberosDisponibles[] = $row;
}

echo json_encode($barberosDisponibles);
?>