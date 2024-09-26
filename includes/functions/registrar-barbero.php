<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../db/conexion.php';


$nombre = $_POST['nombre-bar'];
$apellidos = $_POST['ape-bar'];
$telefono = $_POST['tel-bar'];
$fecnac = $_POST['fecnac-bar'];
$rfc = $_POST['rfc'];
$comision = $_POST['comision'];
$correo = $_POST['correo-bar'];
$contra = $_POST['contra-bar'];
date_default_timezone_set('America/Mexico_City');
$fechaIngreso = date('Y-m-d');
$fechaCorte = date('Y-m-d');
$fechaBaja = NULL;
$estado = "Activo";
$tipo=0;


try {
    if (Rep($correo, $conexion)) {
        $sql = "INSERT INTO barberos(nombre, apellidos, telefono, fecha_nacimiento, rfc, 
        comision, fecha_ingreso, fecha_corte, estado) VALUES ('$nombre', '$apellidos', '$telefono',
        '$fecnac', '$rfc', '$comision', '$fechaIngreso', '$fechaCorte', '$estado')";
        $ejesql = mysqli_query($conexion, $sql);
        if($ejesql){
            $idBarbero= mysqli_insert_id($conexion);
            $contraHash=password_hash($contra, PASSWORD_DEFAULT);
            $sql2="INSERT INTO accesobarberos (idBarbero, correo, contra, tipo) VALUES ('$idBarbero', '$correo',
            '$contraHash', '$tipo')";
            if(mysqli_query($conexion, $sql2)){
                $mensaje="Barbero registrado con exito";
                $_SESSION['mensajeExito']=$mensaje;
                 header("location: ../../views/barberos-admin.php");
                echo "Si";
            }else{
                echo "Error al registrar Barbero";
                mysqli_close($conexion);
                
            }
        }
    }else{
        $mensajeError = "Correo ya registrado";
        $_SESSION['mensajeError'] = $mensajeError;
         header("location: ../../views/barberos-admin.php");
         exit();
        echo "no";
    }
} 
catch (\Throwable $th) {
    throw $th;
}


function Rep($correo, $conexion)
{
    $sql1 = "SELECT * FROM accesobarberos WHERE correo = '$correo'";
    $result = mysqli_query($conexion, $sql1);

    if (mysqli_num_rows($result) > 0) {
        return false;
    } else {
        return true;
    }
}
