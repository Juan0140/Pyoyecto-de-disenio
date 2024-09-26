<?php
require '..\db\conexion.php';
session_start();

$correo = $_POST['correo'];
$contrasena = $_POST['contra'];

// Obtener la contraseña almacenada desde la base de datos
$consulta = mysqli_prepare($conexion, "SELECT idCliente, contrasena FROM accesocliente WHERE correo = ?");
mysqli_stmt_bind_param($consulta, "s", $correo);
mysqli_stmt_execute($consulta);
mysqli_stmt_store_result($consulta);

if (mysqli_stmt_num_rows($consulta) > 0) {
    mysqli_stmt_bind_result($consulta, $idCliente, $contrasenaAlmacenada);
    mysqli_stmt_fetch($consulta);

    // Verificar la contraseña ingresada con la almacenada
    if (password_verify($contrasena, $contrasenaAlmacenada)) { // Utilizar password_verify
        $mensajeExito = "Bienvenid@";
        $_SESSION['mensajeExito'] = $mensajeExito;
        $_SESSION['idCliente'] = $idCliente;
        header("location: ../../views/agendarCitas-clie.php");
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
