<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
/* Confirmar si se ha iniciado sesion y cargar la alerta de bienvenida */
if (isset($_SESSION['idAdmin'])) {
    $barberosActive = true;
    require '../includes/templates/header_admin.php';
    date_default_timezone_set('America/Mexico_City');
    // Obtén la fecha actual
    $fechaActual = new DateTime();
    // Resta 18 años a la fecha actual
    $fechaLimite = $fechaActual->sub(new DateInterval('P18Y'));
    // Formatea la fecha límite como una cadena en formato ISO (YYYY-MM-DD)
    $fechaLimiteStr = $fechaLimite->format('Y-m-d');
    $nombre = $_SESSION['nombreAdmin'];

    if (isset($_SESSION['mensajeExito'])) {
        $mensaje = $_SESSION['mensajeExito']; ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '<?php echo $mensaje; ?>',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                customClass: {
                    icon: 'icono'
                },


            })
        </script>
    <?php
        unset($_SESSION['mensajeExito']);
    }

    if (isset($_SESSION['mensajeError'])) {
        $mensaje = $_SESSION['mensajeError']; ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: '<?php echo $mensaje; ?>',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                customClass: {
                    icon: 'icono'
                }
            })
        </script>
    <?php
        unset($_SESSION['mensajeError']);
    }
    ?>
    <div class="texto-arriba">
        <p>Hola <?php echo $nombre . ". ";
                ?>Aqui puedes revisar a tus empleados y registrarlos</p>
    </div>
    <div class="nav tabs">
        <button class="active" type="button" data-paso="12">Ver Barberos</button>
        <button type="button" data-paso="13">Actualizar Datos</button>
        <button type="button" data-paso="14">Agregar Barberos</button>
    </div>
    <section id="paso-12" class="seccion-login paginado mostrado">
        <h1>Ver Barberos</h1>
        <?php $queryBarberos = "SELECT barberos.*
                                FROM barberos
                                JOIN accesobarberos ON barberos.id = accesobarberos.idBarbero
                                WHERE accesobarberos.tipo = 0 AND barberos.estado = 'activo';";

        $queryBarberosV = "SELECT barberos.*
                          FROM barberos
                        JOIN accesobarberos ON barberos.id = accesobarberos.idBarbero
                        WHERE barberos.estado = 'activo';";
        $result = $conexion->query($queryBarberosV);
        while ($barbero = mysqli_fetch_array($result)) { ?>
            <div class="contenedor-servicio ver-citas">
                <h3 class="h-resumen">Barbero</h3>
                <div class="contenido-cita servicio">
                    <div class="datos-cita">
                        <p><span>Nombre: </span><?php echo $barbero['nombre'] . " " . $barbero['apellidos'] ?> </p>
                        <p><span>Telefono: </span><?php echo $barbero['telefono']  ?></p>
                        <p><span>RFC: </span><?php echo $barbero['rfc']  ?></p>
                        <p><span>Fecha de nacimiento: </span><?php echo $barbero['fecha_nacimiento']  ?></p>
                        <p><span>Fecha de ingreso: </span><?php echo $barbero['fecha_ingreso']  ?></p>
                        <p><span>Ultimo corte: </span><?php echo $barbero['fecha_corte']  ?></p>
                        <p><span>Comision: </span><?php echo intval($barbero['comision'])  ?>%</p>
                        <p><span>Estado: </span><?php echo $barbero['estado']  ?></p>
                        <?php $vac_ini=isset($barbero['vac_ini']) ? $barbero['vac_ini'] : 'No tiene'; 
                         $vac_fin=isset($barbero['vac_fin']) ? $barbero['vac_fin'] : 'No tiene'; ?>
                        <p><span>Inicio vacaciones: </span><?php echo $vac_ini ?></p>
                        <p><span>Fin vacaciones: </span><?php echo $vac_fin  ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>

    </section>
    <section id="paso-13" class="seccion-login paginado">
        <?php
        $fechaMin = date('Y-m-d', strtotime('+15 days'));
        ?>
        <div class="seccion-login vacaciones">
            <h1>Vacaciones</h1>
            <form action="" class="formulario">
                <div class="campo">
                    <label for="nombreBarberoV">Barbero</label>
                    <select name="nombreBarberoV" id="nombreBarberoV">
                        <option value="" class="opcion">--SELECCIONA UN BARBERO--</option>
                        <?php $result2 = $conexion->query($queryBarberosV);
                        while ($barbero2 = mysqli_fetch_array($result2)) { ?>
                            <option class="opcion" data-idBarbero="<?php echo $barbero2['id']; ?>">
                                <?php echo $barbero2['nombre'] . " " . $barbero2['apellidos'];  ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="campo">
                    <label for="fec_ini">Fecha de inicio</label>
                    <input type="date" name="fec_ini" id="fec_ini" min="<?php echo $fechaMin  ?>">
                </div>
                <div class="campo">
                    <label for="fec_fin">Fecha de fin</label>
                    <input type="date" name="fec_fin" id="fec_fin" min="<?php echo $fechaMin  ?>">
                </div>
                <div class="derecha">
                    <input type="button" value="Asignar vacaciones" class="boton btn-vac" id="asignarVacaciones">
                </div>
            </form>
        </div>
        <div class="seccion-login">
            <h1>Actualizar Datos</h1>
            <form action="" class="formulario">
                <div class="campo">
                    <label for="nombreBarbero">Barbero</label>
                    <select name="nombreBarbero" id="nombreBarbero">
                        <option value="" class="opcion">--SELECCIONA UN BARBERO--</option>
                        <?php $result1 = $conexion->query($queryBarberos);
                        while ($barbero1 = mysqli_fetch_array($result1)) { ?>
                            <option class="opcion" data-idBarbero="<?php echo $barbero1['id']; ?>" data-telBarbero="<?php echo $barbero1['telefono']; ?>" data-comisionBarbero="<?php echo $barbero1['comision']; ?>">
                                <?php echo $barbero1['nombre'] . " " . $barbero1['apellidos'];  ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="campo">
                    <label for="telefonoAct">Telefono</label>
                    <input disabled type="tel" id="telefonoAct" name="telefonoAct">
                </div>
                <div class="campo">
                    <label for="comisionAct">Comision</label>
                    <input disabled type="number" id="comisionAct" name="comisionAct">
                </div>
                <div class="derecha">
                    <input type="button" value="Actualizar" class="boton" id="actualizarBarbero">
                </div>
            </form>
        </div>
    </section>
    <section id="paso-14" class="seccion-login paginado">
        <h1>Registro de Barbero</h1>
        <p class="centrar-p">Registra a los empleados</p>

        <section class="seccion-login">
            <form action="../includes/functions/registrar-barbero.php" method="post">
                <div class="campo">
                    <label for="nombre-bar">Nombre</label>
                    <input type="text" name="nombre-bar" id="nombre-bar" placeholder="Teclea el nombre del barbero" required autofocus>
                </div>
                <div class="campo">
                    <label for="ape-bar">Apellidos</label>
                    <input type="text" name="ape-bar" id="ape-bar" placeholder="Teclea los apellidos del barbero" required autofocus>
                </div>
                <div class="campo">
                    <label for="tel">Telefono</label>
                    <input type="tel" name="tel-bar" id="tel" placeholder="xxx-xxx-xxxx" required autofocus>
                </div>
                <div class="campo">
                    <label for="fecnac-bar">Fecha de nacimiento</label>
                    <input type="date" name="fecnac-bar" id="fecnac-bar" required autofocus max="<?php echo $fechaLimiteStr; ?>" min="1900-01-01">
                </div>
                <div class="campo">
                    <label for="rfc">RFC</label>
                    <input type="text" name="rfc" id="rfc" placeholder="Teclea el RFC" required autofocus>
                </div>
                <div class="campo">
                    <label for="comision">Comision</label>
                    <input type="number" name="comision" id="comision" placeholder="Comision (%)" required autofocus max="100" min="1">
                </div>
                <div class="campo">
                    <label for="correo">Correo</label>
                    <input type="email" name="correo-bar" id="correo" placeholder="Tu correo" required autofocus>
                </div>
                <div class="campo">
                    <label for="contra-bar">Contraseña</label>
                    <input type="password" name="contra-bar" id="contra-bar" placeholder="Tu contraseña" required autofocus>
                </div>
                <input type="submit" value="Registrar Barbero" class="boton">
            </form>
        </section>
    </section>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../build/js/validarBarberos.js"></script>
    <script src="../build/js/actualizarBarberos.js"></script>
    <script src="../build/js/tabs.js"></script>
    <script src="../build/js/vacaciones.js"></script>
    </body>

    </html>

<?php } else {
?>
    <section class="seccion-login">
        <?php require '../includes/templates/header_out.php'; ?>
        <h1>No has iniciado sesion</h1>
        <p class="centrar-p accionesSin"> <a class="" href=login.php>Vuelve al login</a></p>
    </section>
<?php }
