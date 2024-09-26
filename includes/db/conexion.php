<?php
$nombre="localhost";
$usuario="root";
$password="root";
$db="proyecto";
$conexion = mysqli_connect($nombre, $usuario, $password, $db);
if (!$conexion) {
    echo "error";
    exit;
}