document.addEventListener('DOMContentLoaded', function () {
    iniciarApp81()
});

function iniciarApp81() {
    confirmarCan();
}

function confirmarCan() {
    const botonCancelar = $('#botonCancelar');
    $(document).on('click', '#botonCancelar', function (e) {
        const contenedorCita = $(e.currentTarget).closest('.contenedor-cita');

        // Obtener los valores de los atributos data
        const idCita = contenedorCita.data('id-cita');
        const idCliente = contenedorCita.data('id-cliente');
    
        console.log(idCita);
        console.log(idCliente);
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
            if (result.isConfirmed) {
                $.ajax({
                    url: '../includes/functions/eliminar-cita.php',
                    type: 'POST',
                    data: { idCita: idCita, idCliente: idCliente },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cancelación Exitosa',
                                html: `<p class="p-alertas">La cita se ha cancelado con exito.</p>`,
                                customClass: {
                                    icon: 'icono',
                                    confirmButton: 'btn-confirmar',
                                    title: 'titulo',
                                },
                                width: 'auto'
                            }).then(() => location.reload());
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                        console.log('Respuesta del servidor:', jqXHR.responseText);
                        Swal.fire('Error', 'Hubo un error al realizar la solicitud. Por favor, inténtalo de nuevo más tarde.', 'error');
                    }
                })

            }
        })
    })
}