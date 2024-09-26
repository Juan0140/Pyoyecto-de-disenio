<?php
require '../db/conexion.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


// Obtener los datos del formulario
$idCliente = $_POST['idCliente'];
$password = trim($_POST['password']);
$telefono = $_POST['tel'];
$correo = $_POST['correo'];

// Consultar la contraseña almacenada en la base de datos
$selectPasswordSql = "SELECT contrasena FROM accesocliente WHERE idCliente = '$idCliente'";
$result = $conexion->query($selectPasswordSql);

if ($result) {
    // Obtener la contraseña almacenada
    $row = $result->fetch_assoc();
    $storedPassword = $row['contrasena'];
    // Verificar si la contraseña es correcta
    if (password_verify($password, $storedPassword)) {
        // La contraseña es correcta, actualizar el teléfono y el correo
        $updateTelefonoSql = "UPDATE clientes SET telefono = '$telefono' WHERE id = '$idCliente'";
        $conexion->query($updateTelefonoSql);

        // Actualizar el correo en la tabla accesocliente
        $updateCorreoSql = "UPDATE accesocliente SET correo = '$correo' WHERE idCliente = '$idCliente'";
        $conexion->query($updateCorreoSql);

        // Respuesta AJAX
        $response = array('success' => true, 'message' => 'Datos actualizados correctamente');
        echo json_encode($response);
    } else {
        // La contraseña no es correcta, enviar un mensaje de error
        $response = array('success' => false, 'message' => 'Contraseña incorrecta');

        echo json_encode($response);
    }
} else {
    // Error al consultar la contraseña, enviar un mensaje de error
    $response = array('success' => false, 'message' => 'Error al consultar la contraseña');
    //echo json_encode($response);
}

// Cerrar la conexión a la base de datos
$conexion->close();
