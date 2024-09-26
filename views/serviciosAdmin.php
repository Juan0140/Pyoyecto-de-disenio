<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (isset($_SESSION['idAdmin'])) {
    $serviciosActive = true;
    require '../includes/templates/header_admin.php';
    $nombre = $_SESSION['nombreAdmin'];
    $idAdmin = $_SESSION['idAdmin'];
?>

    <!-- Aqui empieza el HTML-->
    <div class="texto-arriba">
        <p>Hola <?php echo $nombre . ". ";
                ?>Aqui puedes revisar servicios, actualizarlos y agregarlos</p>
    </div>
    <div class="nav tabs">
        <button class="active" type="button" data-paso="9">Ver servicios</button>
        <button type="button" data-paso="10">Actualizar Servicios</button>
        <button type="button" data-paso="11">Agregar servicios</button>
    </div>
    <section id="paso-9" class="seccion-login paginado mostrado">
        <h1>Ver Servicios</h1>
        <?php
        $queryServicios = "SELECT * FROM servicios ORDER BY id DESC";
        $result = $conexion->query($queryServicios);
        while ($servicio = mysqli_fetch_array($result)) { ?>
            <div class="contenedor-servicio ver-citas">
                <h3 class="h-resumen">Servicio</h3>
                <div class="contenido-cita servicio">
                    <div class="datos-cita">
                        <p><span>Servicio: </span><?php echo $servicio['nombre'] ?> </p>
                        <p><span>Precio: </span>$<?php echo $servicio['precio']  ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>

    </section>
    <section id="paso-10" class="seccion-login paginado">
        <h1>Actualizar</h1>
        <form action="" class="formulario">
            <div class="campo">
                <label for="nombreServicio">Servicio</label>
                <select name="nombreServicio" id="nombreServicio">
                    <option class="opcion" value="" selected disabled>--SELECCIONA SERVICIO--</option>
                    <?php
                    $result1 = $conexion->query($queryServicios);
                    while ($servicio1 = mysqli_fetch_array($result1)) { ?>
                        <option class="opcion" data-idServicio="<?php echo $servicio1['id']; ?>" data-nombreServicio="<?php echo $servicio1['nombre']; ?>" data-precioServicio="<?php echo $servicio1['precio']; ?>">
                            <?php echo $servicio1['nombre']; ?>
                        </option>
                    <?php   }  ?>
                </select>
            </div>

            <div class="campo">
                <label for="precioServicio">Precio</label>
                <input type="number" id="precioServicio" name="precioServicio" disabled min="1">
            </div>

            <input type="button" value="Actualizar" class="boton" id="actualizarServicio">

        </form>
    </section>
    <section id="paso-11" class="seccion-login paginado">
        <h1>Agregar</h1>
        <section>
            <form  class="formualrio" >
                <div class="campo">
                        <label for="nomServicio">Servicio</label>
                        <input 
                        type="text"
                        id="nomServicio"
                        name="idServicio"
                        placeholder="Teclea el nombre del servicio">
                </div>
                <div class="campo">
                        <label for="preServicio">Precio</label>
                        <input type="number" 
                        name="preServicio" 
                        id="preServicio"
                        placeholder="Teclea el precio">
                        
                </div>
                <input type="button" value="Agregar" class="boton" id="agregarServicio">
            </form>
        </section>
    </section>

    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../build/js/tabs.js"></script>
    <script src="../build/js/serviciosAdmin.js"></script>
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
