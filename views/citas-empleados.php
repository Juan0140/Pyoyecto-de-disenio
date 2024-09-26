<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (isset($_SESSION['idEmpleado'])) {
    require '../includes/templates/header_empleado.php';
    date_default_timezone_set('America/Mexico_City');
    $idEmpleado = $_SESSION['idEmpleado'];
    $query = mysqli_query($conexion, "SELECT nombre FROM barberos WHERE id='$idEmpleado'");
    while ($consultar = mysqli_fetch_array($query)) {
        $nombre = $consultar['nombre'];
    }
    if (isset($_SESSION['mensajeExito'])) {
        $mensaje = $_SESSION['mensajeExito']; ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '<?php echo $mensaje . " " . $nombre; ?>',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                customClass: {
                    icon: 'icono'
                }
            })
        </script>
    <?php
        unset($_SESSION['mensajeExito']);
    }
    ?>
    <!-- Aqui empieza el HTML-->
    <div class="texto-arriba">
        <p>Hola <?php echo $nombre . ". "; 
            $_SESSION['nombreEmpleado']=$nombre; ?>Aqui puedes revisar tus citas</p>
    </div>
    <form action="" class="formulario" id="form-fecha">
        <div class="campo">
            <label for="fecha-cita">Fecha</label>
            <input 
            type="date" 
            name="fecha-cita" 
            id="fecha-cita"
            value="<?php echo date('Y-m-d');?>" >
        </div>
    </form>
    <section class="cita-seccion">
    
    </section>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="../build/js/citasEmpleados.js"></script>
    <script src="../build/js/cerrarSesion.js"></script>

</body>
</html>




<?php } else { ?>
    <section class="seccion-login">
        <?php require '../includes/templates/header_out.php'; ?>
        <h1>No has iniciado sesion</h1>
        <p class="centrar-p accionesSin"> <a class="" href=login.php>Vuelve al login</a></p>
    </section>
<?php }
?>