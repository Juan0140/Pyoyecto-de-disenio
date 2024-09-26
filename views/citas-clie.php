<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
/* Confirmar si se ha iniciado sesion y cargar la alerta de bienvenida */
if (isset($_SESSION['idCliente'])) {
    date_default_timezone_set('America/Mexico_City');
    $citasCliente = true;
    require '../includes/templates/header_cliente.php';
    $idCliente = $_SESSION['idCliente'];
    $nombreCliente = $_SESSION['nombreCliente'];
    $query = "SELECT canceladas FROM clientes WHERE id='$idCliente'";
    $consulta = mysqli_query($conexion, $query);
    while ($consultar = mysqli_fetch_array($consulta)) {
        $canceladas = $consultar['canceladas'];
    }
    //Obtner los numeros de citas
    $sql = "SELECT
    SUM(CASE WHEN ci.estado = 'pendiente' THEN 1 ELSE 0 END) AS numCitasPendientes,
    SUM(CASE WHEN ci.estado = 'realizada' THEN 1 ELSE 0 END) AS numCitasRealizadas
    FROM clientes c
    LEFT JOIN cita ci ON c.id = ci.idCliente
    WHERE c.id = '$idCliente'";

    $result = $conexion->query($sql);
    $row = $result->fetch_assoc();
    $pendientes = $row['numCitasPendientes'];
    $realizadas = $row['numCitasRealizadas'];

    //Obtener los datos de las citas
    $sql = "SELECT
            ci.id AS citaId,
            ci.estado,
            ci.fecha,
            ci.hora,
            ci.hora_fin,
            b.nombre AS nombreBarbero,
            s.id AS servicioId,
            s.nombre AS nombreServicio,
            s.precio
        FROM cita ci
        INNER JOIN barberos b ON ci.idBarbero = b.id
        LEFT JOIN cita_servicio cs ON ci.id = cs.idCita
        LEFT JOIN servicios s ON cs.idServicio = s.id
        WHERE ci.idCliente = '$idCliente'
        ORDER BY ci.fecha desc";

    $result = $conexion->query($sql);

    // Verificar si hay resultados antes de mostrar la información
    if ($result->num_rows > 0) {
        $citas = array();

        // Organizar los resultados por cita
        while ($row = $result->fetch_assoc()) {
            $citaId = $row['citaId'];
            $citas[$citaId]['estado'] = $row['estado'];
            $citas[$citaId]['fecha'] = $row['fecha'];
            $citas[$citaId]['hora'] = $row['hora'];
            $citas[$citaId]['hora_fin'] = $row['hora_fin'];
            $citas[$citaId]['nombreBarbero'] = $row['nombreBarbero'];

            // Cada servicio asociado a la cita
            $citas[$citaId]['servicios'][] = array(
                'servicioId' => $row['servicioId'],
                'nombreServicio' => $row['nombreServicio'],
                'precio' => $row['precio']
            );
        }
?>

        <!-- Aqui empieza el HTML-->
        <div class="texto-arriba">
            <header>
                <h1>Mis citas</h1>
                <p class="centrar-p">Hola <?php echo $nombreCliente; ?>. En esta sección puedes revisar tus citas, recuerda que tienes hasta un dia antes para cancelar tu cita.</p>
                <div class="num-citas">
                    <p><span>Realizadas: </span><?php echo $realizadas;  ?></p>
                    <p><span>Pendientes: </span><?php echo $pendientes;  ?></p>
                    <p><span>Canceladas: </span><?php echo $canceladas;  ?></p>
                </div>
            </header>
        </div>
        <section>
            <?php
            foreach ($citas as $citaId => $cita) {
            ?>
                <h1 class="h-resumen">Informacion</h1>
                <div class="contenedor-cita ver-citas" data-id-cita="<?php echo $citaId; ?>" data-id-cliente="<?php echo $idCliente; ?>">
                    <div class="contenido-cita">
                        <h3 class="h-resumen">Cita</h3>
                        <div class="datos-cita">
                            <p><span>Estado: </span><span class="estado"><?php echo $cita['estado']; ?></span></p>
                            <p><span>Fecha: </span><?php echo $cita['fecha']; ?></p>
                            <p><span>Hora de inicio: </span><?php echo $cita['hora']; ?></p>
                            <p><span>Hora final: </span><?php echo $cita['hora_fin']; ?></p>
                            <p><span>Barbero: </span><?php echo $cita['nombreBarbero']; ?></p>
                        </div>
                    </div>
                    <!-- Iterar sobre los servicios de la cita -->
                    <div class="contenido-servicio">
                        <h3 class="h-resumen">Servicios</h3>
                        <div class="datos-cita">
                            <?php $total = 0;
                            foreach ($cita['servicios'] as $servicio) {
                                $total += $servicio['precio']; ?>
                                <p><span>Servicio: </span><?php echo $servicio['nombreServicio']; ?></p>
                                <p><span>Precio: </span>$<?php echo $servicio['precio']; ?></p>
                            <?php } ?>
                            <p><span>Total: </span>$<?php echo $total; ?></p>
                        </div>
                        <div class="derecha">
                        <?php
                        $fechaHoy=date('Y-m-d');
                    if ($cita['estado'] == "pendiente" && $cita['fecha']!=$fechaHoy) { ?>
                        <button class="boton" id="botonCancelar">Cancelar Cita</button>
                    <?php  }
                    ?>
                    </div>
                    </div>
                </div>
        </section>

    <?php
            }
        } else { ?>
    <h1>No tienes citas agendadas</h1>
<?php  }
?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="../build/js/citasClie.js"></script>

<?php } else { ?>
    <section class="seccion-login">
        <?php require '../includes/templates/header_out.php'; ?>
        <h1>No has iniciado sesion</h1>
        <p class="centrar-p accionesSin"> <a class="" href=login.php>Vuelve al login</a></p>
    </section>
<?php
} ?>
</div>
</div>
</body>

</html>