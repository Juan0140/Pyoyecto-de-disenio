<?php
require '../db/conexion.php';
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (isset($_POST['nuevaFecha'])) {
    $nuevaFecha = $_POST['nuevaFecha'];
    $idAdmin=$_SESSION['idAdmin'];
    
    $queryCitasPendientes = mysqli_query($conexion, "SELECT * FROM cita WHERE idBarbero='$idAdmin' AND fecha='$nuevaFecha' AND estado='Pendiente' ORDER BY hora ASC");
    $citasPendientes = mysqli_fetch_all($queryCitasPendientes, MYSQLI_ASSOC);
    $queryCitasRealizadas = mysqli_query($conexion, "SELECT * FROM cita WHERE idBarbero='$idAdmin' AND fecha='$nuevaFecha' AND estado='Realizada' ORDER BY hora ASC");
    $citasRealizadas = mysqli_fetch_all($queryCitasRealizadas, MYSQLI_ASSOC);

    $todasLasCitas = array_merge($citasPendientes, $citasRealizadas);

    if (empty($todasLasCitas)) {
        echo '<p class="centrar-p">No hay citas disponibles para esta fecha y barbero.</p>';
    } else {
        // Genera el HTML actualizado
        $htmlActualizado = '';
        foreach ($todasLasCitas as $cita) {
            // Obtén información adicional de la cita, el cliente y los servicios
            $idCita = $cita['id'];
            $idCliente = $cita['idCliente'];
            $queryBarbero = mysqli_query($conexion, "SELECT comision FROM barberos WHERE id='$idAdmin'");
            $comisionBarbero = mysqli_fetch_assoc($queryBarbero)['comision'];
            $comision = $comisionBarbero / 100;

            // Información de la cita
            $htmlActualizado .= '<div class="contenido-cita" data-idCita="' . $idCita . '">';
            $htmlActualizado .= '<h1 class="h-resumen">Informacion</h1>';
            $htmlActualizado.='<div class="contenedor-cita ver-citas">';
            $htmlActualizado .= '<div class="contenido-cita">';
            $htmlActualizado .= '<h3 class="h-resumen">Cita</h3>';
            $htmlActualizado .= '<div class="datos-cita">';
            $htmlActualizado .= '<p><span>Estado: </span><span class="estado">' . $cita['estado'] . '</span></p>';
            $htmlActualizado .= '<p><span>Fecha: </span>' . $cita['fecha'] . '</p>';
            $htmlActualizado .= '<p><span>Hora de inicio: </span>' . $cita['hora'] . '</p>';
            $htmlActualizado .= '<p><span>Hora final: </span>' . $cita['hora_fin'] . '</p>';

            // Información del cliente
            $queryCliente = mysqli_query($conexion, "SELECT * FROM clientes WHERE id='$idCliente'");
            $cliente = mysqli_fetch_assoc($queryCliente);
            $htmlActualizado .= '<p data-idCliente="' . $idCliente . '"><span>Cliente: </span>' . $cliente['nombre'] . ' ' . $cliente['apellidos'] . '</p>';
            $htmlActualizado .= '</div></div>';
            // Itera sobre los servicios de la cita
            $htmlActualizado .= '<div class="contenido-servicio">';
            $htmlActualizado .= '<h3 class="h-resumen">Servicios</h3>';
            $htmlActualizado .= '<div class="datos-cita">';

            $queryServicios = mysqli_query($conexion, "SELECT servicios.nombre AS servicio, servicios.precio FROM servicios
            INNER JOIN cita_servicio ON servicios.id = cita_servicio.idServicio
            WHERE cita_servicio.idCita='$idCita'");
            $total = 0;
            while ($servicio = mysqli_fetch_assoc($queryServicios)) {
                $comisionServicio = $comision * $servicio['precio'];
                $total += $comisionServicio;
                $htmlActualizado .= '<p><span>Servicio: </span>' . $servicio['servicio'] . '</p>';
                $htmlActualizado .= '<p><span>Precio: </span>$' . round($servicio['precio']) . '</p>';
                $htmlActualizado .= '<p><span>Comision: </span>$' . round($comisionServicio) . '</p>';
            }
            $htmlActualizado .= '<p><span>Comision total: </span>$' . round($total) . '</p>';
            date_default_timezone_set('America/Mexico_City');
            $fechaHoy = date('Y-m-d');
            
                $htmlActualizado .= '<div class="derecha">';
                $htmlActualizado .= '  <button class="boton" data-idCita="' . $cita['id'] . '" data-idCliente="' . $idCliente . '" id="botonCita">Realizar Cita</button>';
                $htmlActualizado .= '</div>';
                $htmlActualizado .= '<div class="derecha">';
                $htmlActualizado .= '  <button class="boton" data-idCita="' . $cita['id'] . '" data-idCliente="' . $idCliente . '" id="botonCancelar">Cancelar Cita</button>';
                $htmlActualizado .= '</div>';
       
            $htmlActualizado .= '</div></div></div>'; 


        }
        echo $htmlActualizado;
    }
}