<?php require '../includes/templates/header_out.php';
session_start();
if(isset($_SESSION['idCliente'])){
    unset($_SESSION['idCliente']);
}
if(isset($_SESSION['idEmpleado'])){
    unset($_SESSION['idEmpleado']);
}
if(isset($_SESSION['idAdmin'])){
    unset($_SESSION['idAdmin']);
}
if (isset($_SESSION['mensajeExito'])) {
    $mensaje = $_SESSION['mensajeExito']; ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '<?php echo $mensaje  ;?>',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            customClass:{
                icon: 'icono'
            },
            

        })
    </script>
<?php
    unset($_SESSION['mensajeExito']);
}
if(isset($_SESSION['mensajeError'])){ 
    $mensaje=$_SESSION['mensajeError'];?>
    <script>
        Swal.fire({
           icon: 'error',
            title: '<?php echo $mensaje; ?>',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            customClass:{
                icon: 'icono'
            }
        })   
    </script>
<?php
    unset($_SESSION['mensajeError']);
}
?>


<header class="header">
    <h1>¡Bienvenido!</h1>
</header>
<div class="nav tabs">
    <button class="active" type="button" data-paso="1">Clientes</button>
    <button type="button" data-paso="2">Barberos</button>
</div>
<section id="paso-1" class="seccion-login paginado mostrado">
    <h2>Login Clientes</h2>
    <p class="centrar-p">Inicia sesion con tus datos</p>
    <form action="../includes/functions/loguearCliente.php" method="post" class="formulario">
        <div class="campo">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" placeholder="Tu correo" required autofocus>
        </div>
        <div class="campo">
            <label for="contra">Contraseña</label>
            <input type="password" name="contra" id="contra" placeholder="Tu contraseña" required autofocus>
        </div>
        <input type="submit" value="Iniciar sesion" class="boton">
    </form>
    <div class="acciones">
        <a href="crear-cuenta.php">¿Aun no tienes una cuenta? Crea una</a>
    </div>
</section>
<section id="paso-2" class="seccion-login paginado">
    <h2>Login Barberos</h2>
    <p class="centrar-p">Inicia sesion con tus credenciales</p>
    <form action="../includes/functions/loguearBarbero.php" method="post" class="formulario">
        <div class="campo">
            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" placeholder="Tu correo" required autofocus>
        </div>
        <div class="campo">
            <label for="contra">Contraseña</label>
            <input type="password" name="contra" id="contra" placeholder="Tu contraseña" required autofocus>
        </div>
        <input type="submit" value="Iniciar sesion" class="boton">
    </form>
</section>




</div>
</div>
<script src="../build/js/tabs.js"></script>
</body>

</html>