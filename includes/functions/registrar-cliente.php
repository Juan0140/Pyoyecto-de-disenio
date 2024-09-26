<?php
require '../db/conexion.php';
session_start();

$nombre = $_POST['nom-clie'];
$apellidos = $_POST['ape-clie'];
$fecnac = $_POST['nac-clie'];
$telefono = $_POST['tel-clie'];
$correo = $_POST['correo-clie'];
$contrasena = $_POST['contra-clie'];



try {
    $canceladas=0;
    if (Rep($correo, $conexion)) {
        $sql = "INSERT INTO clientes (nombre, apellidos, fecha_nacimiento, telefono, canceladas) VALUES ('$nombre', '$apellidos','$fecnac','$telefono', '$canceladas')";
        $ejesql = mysqli_query($conexion, $sql);

        if ($ejesql) {
            $idCliente = mysqli_insert_id($conexion);
            $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT); // Utilizar password_hash

            $sql2 = "INSERT INTO accesocliente (idCliente, correo, contrasena) VALUES ('$idCliente', '$correo', '$contrasenaHash')";
            
            if (mysqli_query($conexion, $sql2)) {
                $mensaje = "Cliente registrado con Éxito";
                $_SESSION['mensajeExito'] = $mensaje;
                header("location: ../../views/login.php");
                exit();
            } else {
                echo "Error al registrar al cliente";
                mysqli_close($conexion);
                exit();
            }
        }
    } else {
        $mensajeError = "Correo ya registrado";
        $_SESSION['mensajeError'] = $mensajeError;
        header("location: ../../views/crear-cuenta.php");
        exit();
    }
} catch (\Throwable $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

function Rep($correo, $conexion) {
    $sql1 = "SELECT * FROM accesocliente WHERE correo = '$correo'";
    $result = mysqli_query($conexion, $sql1);

    if (mysqli_num_rows($result) > 0) {
        return false;
    } else {
        return true;
    }
}
?>
