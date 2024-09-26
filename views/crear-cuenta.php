<?php 
require '../includes/templates/header_out.php';
session_start();
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
<header>
    <h1>Crear Cuenta</h1>
    <p class="centrar-p">Ingresa tus datos para crear una cuenta</p>
    </header>
    <section class="seccion">
    <form action="../includes/functions/registrar-cliente.php" method="post" class="formulario">
        <div class="campo">
            <label for="nom-clie">Nombre</label>
            <input 
            type="text"
            name="nom-clie"
            id="nom-clie"
            placeholder="Teclea tu nombre"
            required
            autofocus>
        </div>
        <div class="campo">
            <label for="ape-clie">Apellidos</label>
            <input 
            type="text"
            name="ape-clie"
            id="ape-clie"
            placeholder="Teclea tus apellidos"
            required
            autofocus>
        </div>
        <?php 
        date_default_timezone_set('America/Mexico_City'); 
        $fechahoy=date('Y-m-d');
        ?>
        <div class="campo">
            <label for="nac-clie">Fecha de nacimiento</label>
            <input 
            type="date" 
            name="nac-clie" 
            id="nac-clie"
            required
            autofocus
            min="1900-01-01"
            max="<?php echo $fechahoy; ?>">
        </div>
        <div class="campo">
            <label for="tel">Telefono</label>
            <input 
            type="tel"
            name="tel-clie"
            id="tel"
            placeholder="xxx-xxx-xxxx"
            required
            autofocus
            >
        </div>
        <div class="campo">
            <label for="correo">Correo</label>
            <input type="email"
            name="correo-clie"
            id="correo"
            placeholder="Tu correo"
            required
            autofocus>
        </div>
        <div class="campo">
            <label for="contra-clie">Contraseña</label>
            <input type="password"
            name="contra-clie"
            id="contra-clie"
            placeholder="Tu contraseña"
            required
            autofocus>
        </div>
        <input type="submit" value="Crear cuenta" class="boton">
    </form>
    <div class="acciones">
        <a href="login.php">¿Ya tienes una cuenta? Inicia sesion</a>
    </div>
    </section>

    <script src="../build/js/validarCliente.js"></script>
</div>
</body>