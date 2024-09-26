<?php 
session_start();
require '../db/conexion.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Obtenemos los datos
$idCliente=$_POST['idCliente'];
$contraActual=$_POST['contraActual'];
$contraNew=$_POST['contraNew'];

$sqlVer="SELECT contrasena FROM accesocliente WHERE idCliente='$idCliente'";
$result=$conexion->query($sqlVer);
if($result){
    $row=$result->fetch_assoc();
    $contraAlmacenada=$row['contrasena'];
    if(password_verify($contraActual, $contraAlmacenada)){
        $contraNewHash=password_hash($contraNew, PASSWORD_DEFAULT);
        $sql = "UPDATE accesocliente SET contrasena='$contraNewHash' WHERE idCliente='$idCliente'";
        $updateContrasena=$conexion->query($sql);

        if($updateContrasena){
            $response = array('success' => true, 'message' => 'Datos actualizados correctamente');
            echo json_encode($response);
        }else{
            $response = array('success' => false, 'message' => 'Error en la insercion');

            echo json_encode($response);
        }
    }else{
        $response = array('success' => false, 'message' => 'Contraseña incorrecta');

        echo json_encode($response);
    }
}