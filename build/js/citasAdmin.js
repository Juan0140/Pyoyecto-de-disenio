document.addEventListener('DOMContentLoaded', function(){
    iniciarApp8()
});

function iniciarApp8(){
    cargarDatosAdmin();
    confirmarRealizarCitaAdmin();
    confirmarCancelarAdmin();
}
function cargarDatosAdmin(){
    const selectBarbero = $('#barbero-cita');
    const inputFecha = $('#fecha-cita');

    function cargarDatos() {
        const selectedOption = selectBarbero.find('option:selected');
        if (selectedOption.length > 0) {
            const idBarbero = selectedOption.data('idBarbero');
            const fecha = inputFecha.val();
            if(idBarbero==undefined){
                return;
            }
            $.ajax({
                url: '../includes/functions/citas-empleados.php',
                type: 'POST',
                data: { nuevaFecha: fecha, idBarbero: idBarbero },
                success: function(data) {
                    console.log(idBarbero);
                    $('#cita-admin').html(data);
                    console.log(data);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al cargar los datos',
                        showConfirmButton: true,
                    });
                }
            });
        }
    }

    selectBarbero.on('change', cargarDatos);
    inputFecha.on('input', cargarDatos);
}

function confirmarRealizarCitaAdmin(){
    const botonCita = $('#botonCita');
    $(document).on('click', '#botonCita', function (e) {
        const idCita = $(e.currentTarget).data('idcita');
        
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
                if(result.isConfirmed){
                    $.ajax({
                        url: '../includes/functions/realizar-cita.php',  // Reemplaza con la ruta correcta
                        type: 'POST',
                        data: { idCita: idCita },
                        dataType: 'json',
                        success: function (data) {
                            if(data.success){
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
                            }else{
                                Swal.fire('Error', 'Hubo un error al marcar la cita como realizada.', 'error');
                            }
                        }
                    })
                }
            });
        
    })
}

function confirmarCancelarAdmin(){
    const botonCancelar = $('#botonCancelar');
    $(document).on('click', '#botonCancelar', function (e) {
        const idCita = $(e.currentTarget).data('idcita');
        const idCliente = $(e.currentTarget).data('idcliente');
        
        Swal.fire({
            title: '¿Estás seguro?',
            html: `<p class="p-alertas">Se borrará la cita del sistema.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, cancelar cita',
            cancelButtonText: 'No',
            customClass: {
                icon: 'icono',
                confirmButton: 'btn-confirmar',
                cancelButton: 'btn-cancelar', 
                title: 'titulo'
            },
            width: 'auto',
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: '../includes/functions/cancelar-cita.php',  
                    type: 'POST',
                    data: { idCita: idCita, idCliente: idCliente },
                    dataType: 'json',
                    success: function(data){
                        console.log(data.success);
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
                })
            }
        })

    });
}