document.addEventListener('DOMContentLoaded', function () {
    iniciarApp7();
})

function iniciarApp7() {
    cargarDatos();
    confirmarRealizarCita();
}

function cargarDatos() {
    $(document).ready(function () {
        // Función para cargar los datos de la fecha actual
        function cargarDatosFechaActual() {
            const fechaActual = $('#fecha-cita').val();

            $.ajax({
                url: '../includes/functions/citas-empleados.php',
                type: 'POST',
                data: { nuevaFecha: fechaActual },
                success: function (data) {
                    
                    $('.cita-seccion').html(data);
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al cargar los datos',
                        showConfirmButton: true,
                    });
                }
            });
        }

        // Manejador de eventos para el cambio de fecha
        $('#form-fecha').on('change', '#fecha-cita', function () {
            var nuevaFecha = $(this).val();
            cargarDatosFechaActual();
        });

        // Cargar los datos de la fecha actual al cargar la página
        cargarDatosFechaActual();
    });

}


function confirmarRealizarCita() {
    const botonCita = $('#botonCita');
    $(document).on('click', '#botonCita', function (e) {
        const idCita = $(e.currentTarget).data('idcita');

        // Muestra una alerta de confirmación
        Swal.fire({
            title: '¿Estás seguro?',
            html: `<p class="p-alertas">Se marcará la cita como realizada.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, realizar cita',
            cancelButtonText: 'Cancelar',
            customClass: {
                icon: 'icono',
                confirmButton: 'btn-confirmar',
                cancelButton: 'btn-cancelar', 
                title: 'titulo'
            },
            width: 'auto',
        }).then((result) => {
            // Si el usuario hace clic en "Aceptar"
            if (result.isConfirmed) {
                // Realiza una solicitud AJAX para cambiar el estado de la cita a "Realizada"
                $.ajax({
                    url: '../includes/functions/realizar-cita.php',  // Reemplaza con la ruta correcta
                    type: 'POST',
                    data: { idCita: idCita },
                    dataType: 'json',
                    success: function (data) {
                        const{success}=data;
                        console.log(data)
                        console.log(success);
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualización Exitosa',
                                html: `<p class="p-alertas">Los datos se han actualizado correctamente.</p>`,
                                customClass: {
                                    icon: 'icono',
                                    confirmButton: 'btn-confirmar',
                                    title: 'titulo',
                                },
                                width: 'auto'
                            }).then(()=>location.reload());
                            
                
                        } else {
                            Swal.fire('Error', 'Hubo un error al marcar la cita como realizada.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Hubo un error al realizar la solicitud.', 'error');
                    }
                });
            }
        });
    });
}



