<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
/* Confirmar si se ha iniciado sesion y cargar la alerta de bienvenida */
if (isset($_SESSION['idCliente'])) { 
    $perfilCliente=true;
    require '../includes/templates/header_cliente.php';
    $idCliente = $_SESSION['idCliente'];
    $nombreCliente = $_SESSION['nombreCliente'];
    
    ?>
    <div id="clienteData" data-idcliente="<?php echo $idCliente; ?>"></div>
    <div class="texto-arriba">
            <header>
                <p class="centrar-p">Hola <?php echo $nombreCliente; ?>. En esta sección puedes revisar tu perfil</p>
            </header>
    </div>
    <div class="nav tabs">
        <button class="active" type="button" data-paso="6">Mis datos</button>
        <button type="button" data-paso="7">Actualizar Datos</button>
        <button type="button" data-paso="8">Actualizar Contraseña</button>
    </div>
    <section id="paso-6" class="seccion-login paginado mostrado">
        <!--Obtenemos los datos del cliente-->
        <?php
            $sql="SELECT clientes.id, clientes.nombre, clientes.apellidos, clientes.fecha_nacimiento, clientes.telefono, accesocliente.correo
            FROM clientes
            JOIN accesocliente ON clientes.id = accesocliente.idCliente
            WHERE clientes.id = '$idCliente'";
            $result=$conexion->query($sql);
            while($row= $result->fetch_assoc()){
                $nombre=$row['nombre'];
                $apellidos=$row['apellidos'];
                $fecnac=$row['fecha_nacimiento'];
                $telefono=$row['telefono'];
                $correo=$row['correo'];
            }
        ?>
        <h1>Datos</h1>
        <div class="contenido-resumen">
        <p><span>Nombre: </span><?php echo $nombre;?></p>
        <p><span>Apellido(s): </span><?php echo $apellidos;?></p>
        <p><span>Fecha de nacimiento: </span><?php echo $fecnac;?></p>
        <p><span>Telefono: </span><?php echo $telefono;?></p>
        <p><span>Correo: </span><?php echo $correo;?></p>
        </div>
    </section>

    <section id="paso-7" class="seccion-login paginado">
        <h1>Actualizar</h1>
            <form action="" class="formulario">
                <div class="campo">
                    <label for="tel">Telefono</label>
                    <input type="number" 
                    name="tel" 
                    id="tel"
                    value="<?php echo $telefono; ?>"
                    data-anterior="<?php echo $telefono; ?>">>
                </div>
                <div class="campo">
                    <label for="correo">Correo</label>
                    <input 
                    type="email" 
                    name="correo" 
                    id="correo"
                    value="<?php echo $correo; ?>"
                    data-anterior="<?php echo $correo; ?>">
                </div>
                <input type="button" value="Actualizar Datos" class="boton" id="btn-actualizar">
            </form>

    </section>

    <section id="paso-8" class="seccion-login paginado">
        <h1>Contraseña</h1>
        <form action="" class="formulario">
                <div class="campo">
                    <label for="cotra-ac">Contraseña actual</label>
                    <input type="password" 
                    name="contra-ac" 
                    id="contra-ac"
                    placeholder="Tu actual contraseña">
                </div>
                <div class="campo">
                    <label for="contraNew">Nueva contraseña</label>
                    <input 
                    type="password" 
                    name="contraNew" 
                    id="contraNew"
                    placeholder="Tu nueva contraseña">
                </div>
                <input type="button" value="Actualizar contraseña" class="boton" id="btn-actualizarContra">
            </form>
    </section>

    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../build/js/tabs.js"></script>
    <script src="../build/js/validarActual.js"></script>
    <script src="../build/js/actualizarCliente.js"></script>
</body>
</html>







<?php }else { ?>
    <section class="seccion-login">
        <?php require '../includes/templates/header_out.php'; ?>
        <h1>No has iniciado sesion</h1>
        <p class="centrar-p accionesSin"> <a class="" href=login.php>Vuelve al login</a></p>
    </section>
<?php
} ?>