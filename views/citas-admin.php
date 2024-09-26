<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (isset($_SESSION['idAdmin'])) {
    $citasActive=true;
    require '../includes/templates/header_admin.php';
    date_default_timezone_set('America/Mexico_City');
    $idAdmin = $_SESSION['idAdmin'];
    $query = mysqli_query($conexion, "SELECT nombre FROM barberos WHERE id='$idAdmin'");
    while ($consultar = mysqli_fetch_array($query)) {
        $nombre = $consultar['nombre'];
        $_SESSION['nombreAdmin']=$nombre;
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
                $_SESSION['nombreAdmin'] = $nombre; ?>Aqui puedes revisar tus citas y las de tus empleado</p>
    </div>
  
    <section id="paso-9" class="seccion-login paginado mostrado">
        <form action="" class="formulario" id="form-fecha">
            <div class="campo">
            <?php
            //Obtenemos a los barberos
            $sql2 = "SELECT id, nombre FROM barberos";
            $consulta2 = mysqli_query($conexion, $sql2);
            ?>
                <label for="barbero-cita">Barbero</label>
                <select name="barbero-cita" id="barbero-cita">
                    <option class="opcion" selected disabled>--SELECCIONA UN BARBERO--</option>
                <?php   while($barbero= mysqli_fetch_array($consulta2)){?>
                            <option class="opcion" data-id-barbero="<?php echo $barbero['id']; ?>" data-nombre-barbero="<?php echo $barbero['nombre']; ?>"><?php echo $barbero['nombre']; ?></option>
                            <?php } ?>"
                </select>
            </div>
            <div class="campo">
                <label for="fecha-cita">Fecha</label>
                <input type="date" name="fecha-cita" id="fecha-cita" value="<?php echo date('Y-m-d'); ?>">
            </div>
        </form>
        <section class="cita-seccion" id="cita-admin">

        </section>
    </section>

    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="../build/js/citasAdmin.js"></script>
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