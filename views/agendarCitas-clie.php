<?php
session_start();
/* Confirmar si se ha iniciado sesion y cargar la alerta de bienvenida */
if (isset($_SESSION['idCliente'])) {
    date_default_timezone_set('America/Mexico_City');
    $agendarActive = true;
    require '../includes/templates/header_cliente.php';
    $idCliente = $_SESSION['idCliente'];
    $_SESSION['idCliente'] = $idCliente;
    echo "<div id='idCliente' data-idCliente='$idCliente'></div>";
    $query = mysqli_query($conexion, "SELECT nombre FROM clientes WHERE id='$idCliente'");
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
    ?><!-- Aqui empieza el HTML-->
    <div class="texto-arriba">
        <p>Hola <?php echo $nombre . ". ";
                $_SESSION['nombreCliente'] = $nombre; ?>Aqui puedes agendar tus citas</p>
    </div>
    <div class="nav tabs">
        <button class="active" type="button" data-paso="3">Servicios</button>
        <button type="button" data-paso="4">Informacion</button>
        <button type="button" data-paso="5">Resumen</button>
    </div>
    <section id="paso-3" class="seccion-login paginado mostrado">
        <!-- Colocamos los servicios con php -->
        <?php
        $sql = "SELECT * FROM servicios";
        $consulta = mysqli_query($conexion, $sql);
        ?>
        <div class="servicios">
            <h2>Servicios</h2>
            <p class="centrar-p">Elige los servicios que deseas</p>
            <div class="listado-servicios">
                <?php while ($servicio = mysqli_fetch_array($consulta)) { ?>
                    <div class="servicio" data-id="<?php echo $servicio['id']; ?>" data-nombre="<?php echo $servicio['nombre']; ?>" data-precio="<?php echo $servicio['precio']; ?>">
                        <p class="nombre-servicio"><?php echo $servicio['nombre']; ?></p>
                        <p class="precio-servicio"><?php echo "$" . $servicio['precio']; ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>

    </section>
    <section id="paso-4" class="seccion-login paginado">
        <div class="informacion-cita">
            <!--Empezamos con la extraccion de datos de la base de datos-->
            <?php
            //Obtenemos a los barberos
            $sql2 = "SELECT id, nombre FROM barberos";
            $consulta2 = mysqli_query($conexion, $sql2);
            $fechaActual = date('Y-m-d');
            $fechaLimite = date('Y-m-d', strtotime('+14 days'));

            // Convierte las fechas al formato necesario para el atributo 'min' y 'max'
            $fechaActualFormato = date('Y-m-d', strtotime($fechaActual));
            $fechaLimiteFormato = date('Y-m-d', strtotime($fechaLimite));
            ?>
            <h2>Informacion de cita</h2>
            <p class="centrar-p">Elije Barbero y Fecha para confirmar horas y despues elige la hora</p>
            <form action="" class="formulario">
                <div class="campo">
                    <label for="fecha-cita">Fecha</label>
                    <input type="date" 
                    name="fecha-cita"
                    id="fecha-cita"
                    min="<?php echo $fechaActualFormato; ?>" 
                    max="<?php echo $fechaLimiteFormato; ?>" >
                </div>
                <div class="campo">
                    <label for="barbero">Barbero</label>
                    <select name="barbero" id="barbero">
                    <option disabled selected>--Selecciona un Barbero--</option>
                    </select>
                </div>
                <div class="campo">
                    <label for="hora-cita">Hora</label>
                    <select name="hora-cita" id="hora-cita">
                        <option value="" disabled selected>--Selecciona la hora</option>
                    </select>
                </div>
            </form>
        </div>
    </section>
    <section id="paso-5" class="seccion-login paginado contenido-resumen">
        <h2>Resumen</h2>
        <p id="mensaje-resumen" class="centrar-p"></p>
        <div class="resumen"></div>

        <div class="derecha">
            <button class="boton" id="botonDescuento">Codigo de descuento</button>
            <button class="boton" id="botonReserva">Reservar Cita</button>
        </div>
    </section>
    <div class="pagina">
        <button id="anterior" class="boton">
            &laquo; Anterior
        </button>

        <button id="siguiente" class="boton">
            Siguiente &raquo;
        </button>
    </div>

<?php
} else { ?>
    <section class="seccion-login">
        <?php require '../includes/templates/header_out.php'; ?>
        <h1>No has iniciado sesion</h1>
        <p class="centrar-p accionesSin"> <a class="" href=login.php>Vuelve al login</a></p>
    </section>
<?php
}
?>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="../build/js/tabs.js"></script>
<script src="../build/js/citas.js"></script>
<script src="../build/js/barberosDisponibles.js"></script>
<script src="../build/js/horasdispo.js"></script>
</body>

</html>