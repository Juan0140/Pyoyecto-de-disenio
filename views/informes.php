<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (isset($_SESSION['idAdmin'])) {
    $informesActive = true;
    require '../includes/templates/header_admin.php';
    date_default_timezone_set('America/Mexico_City');
    $idAdmin = $_SESSION['idAdmin'];
    $nombre = $_SESSION['nombreAdmin'];
?>
    <div class="texto-arriba">
        <p>Hola <?php echo $nombre . ". ";
                ?>Aqui puedes generar y marcar como pagados los informes de tus empleados</p>
    </div>
    <section class="seccion-login">
        <form action="" class="formulario">
            <div class="campo">
                <label for="nombreBarbero">Barbero</label>
                <select name="nombreBarbero" id="nombreBarbero">
                    <option value="" class="opcion">--SELECCIONA UN BARBERO</option>
                    <?php $queryBarberos = "SELECT barberos.*
                                FROM barberos
                                JOIN accesobarberos ON barberos.id = accesobarberos.idBarbero
                                WHERE accesobarberos.tipo = 0 AND barberos.estado = 'activo';";
                    $result1 = $conexion->query($queryBarberos);
                    while ($barbero1 = mysqli_fetch_array($result1)) { ?>
                        <option class="opcion" data-idBarbero="<?php echo $barbero1['id']; ?>">
                            <?php echo $barbero1['nombre'] . " " . $barbero1['apellidos'];  ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </form>
        <div class="derecha">
        <input type="button" class="boton paginado" name="reCorte" id="reCorte" value="Realizar Corte">
        <input type="button" class="boton" name="genInforme" id="genInforme" value="Generar Informe">
    </div>
    </section>

    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../build/js/genInformes.js"></script>
    </body>

    </html>


<?php
} else {
?>
    <section class="seccion-login">
        <?php require '../includes/templates/header_out.php'; ?>
        <h1>No has iniciado sesion</h1>
        <p class="centrar-p accionesSin"> <a class="" href=login.php>Vuelve al login</a></p>
    </section>
<?php }

