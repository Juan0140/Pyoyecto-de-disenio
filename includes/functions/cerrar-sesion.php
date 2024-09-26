<?php
session_start();

    // Código para cerrar sesión aquí
    session_destroy();
    header("location: ../../views/login.php");

