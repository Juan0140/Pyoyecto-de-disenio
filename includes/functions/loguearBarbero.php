<?php
require '..\db\conexion.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$correo = $_POST['correo'];
$contrasena = $_POST['contra'];

// Obtener la contraseña almacenada desde la base de datos
$consulta = mysqli_prepare($conexion, "SELECT idBarbero, tipo, contra FROM accesobarberos WHERE correo = ?");
mysqli_stmt_bind_param($consulta, "s", $correo);
mysqli_stmt_execute($consulta);
mysqli_stmt_store_result($consulta);

if (mysqli_stmt_num_rows($consulta) > 0) {
    mysqli_stmt_bind_result($consulta, $idBarbero, $tipo, $contraAlmacenada);
    mysqli_stmt_fetch($consulta);

    // Verificar la contraseña ingresada con la almacenada
    if (password_verify($contrasena, $contraAlmacenada)) { // Utilizar password_verify
        // Redireccionar según el tipo
        $mensajeExito = "Bienvenid@";
        $_SESSION['mensajeExito'] = $mensajeExito;
        if ($tipo == 1) {
            // Tipo 1, enviar a una vista
            $_SESSION['idAdmin'] = $idBarbero;
            header("location: ../../views/citas-admin.php");
        } else {
            // Tipo 0, enviar a otra vista
            $_SESSION['idEmpleado'] = $idBarbero;
            header("location: ../../views/citas-empleados.php");
        }
    } else {
        $mensajeError = "Datos incorrectos";
        $_SESSION['mensajeError'] = $mensajeError;
        header("location: ../../views/login.php");
    }
} else {
    $mensajeError = "Datos incorrectos";
    $_SESSION['mensajeError'] = $mensajeError;
    header("location: ../../views/login.php");
}

mysqli_stmt_close($consulta);
mysqli_close($conexion);
?>
