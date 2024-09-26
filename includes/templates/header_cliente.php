<?php require '../includes/db/conexion.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón de Belleza</title>
    <link rel="stylesheet" href="../build/css/app.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../build/js/bundle.min.js"></script>
    <script src="../build/js/cerrarSesion.js"></script>
</head>

<body>
    <div class="contenedor-estetica">
        <div class="mobile-menu">
            <img src="../build/img/barras.svg" alt="Icono menu">
        </div>
        <nav class="respon">
            <a href="./agendarCitas-clie.php" class="<?php echo $agendarActive ? 'active' : '' ?>">Agendar citas</a>
            <a href="./citas-clie.php" class="<?php echo $citasCliente ? 'active' : '' ?>">Mis citas</a>
            <a href="./perfil-clie.php" class="<?php echo $perfilCliente ? 'active' : '' ?>">Mi Perfil</a>
            <a href="#" class="cerrar-sesion">Cerrar sesion</a>
        </nav>
        <div class="imagen"></div>
        <div class="app">