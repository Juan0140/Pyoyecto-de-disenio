document.addEventListener('DOMContentLoaded', function () {
    iniciarApp20();
})
function iniciarApp20() {
    validarVacacionesInicio();
    validarVacaciones();
    obtenerBarbero();
}
let idBarbero1 = 0;
function obtenerBarbero() {
    const selectBar1 = document.querySelector('#nombreBarberoV');
    if (selectBar1) {
        selectBar1.addEventListener('change', (e) => {
            const selectOption = e.target.options[e.target.selectedIndex];
            if (selectOption) {
                idBarbero1 = selectOption.getAttribute('data-idBarbero');
            }
        })
    }
}
function validarVacacionesInicio() {
    const inputIni = document.querySelector('#fec_ini');
    inputIni.addEventListener('blur', e => {
        const fechaSelec = new Date(e.target.value + 'T23:00:00-06:00');
        const fechaActual1 = new Date();
        const fechaLimite1 = new Date();
        fechaLimite1.setDate(fechaActual1.getDate() + 15);
        console.log(fechaSelec);
        console.log(fechaLimite1);
        if (fechaSelec < fechaLimite1) {
            Swal.fire({
                icon: 'error',
                title: 'Fecha no válida',
                html: `<p class="p-alertas">Las vacaciones se deben pedir con 15 dias de anticipacion</p>`,
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });
            inputIni.value = '';
        }
    })
}

function validarVacaciones() {
    const btnAsig = document.querySelector('#asignarVacaciones');
    btnAsig.addEventListener('click', () => {
        const inputIniC = document.querySelector('#fec_ini');
        const inputFin = document.querySelector('#fec_fin');
        const fecIni = inputIniC.value;
        const fecFin = inputFin.value;
        console.log(fecFin);
        console.log(fecIni);
        if (idBarbero1 == 0 || fecIni == '' || fecFin == '') {
            Swal.fire({
                icon: 'error',
                title: 'Llena todos los campos',
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });

            return;
        }
        if (fecFin < fecIni) {
            Swal.fire({
                icon: 'error',
                title: 'Fecha de fin invalida',
                html: `<p class="p-alertas">La fecha de fin no puede ser antes que la de inicio.</p>`,
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });
            inputFin.value = '';
            return;
        }

        Swal.fire({
            title: '¿Estás seguro?',
            html: `<p class="p-alertas">Si ya hay unas asignadas se remplazaran.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, asignarlas',
            cancelButtonText: 'Cancelar',
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
                    url: '../includes/functions/asignar-vacaciones.php',  // Reemplaza con la ruta correcta
                    type: 'POST',
                    data: { idBarbero: idBarbero1, fecIni: fecIni, fecFin: fecFin },
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Asignación Exitosa',
                                html: `<p class="p-alertas">Las vacaciones fueron asignadas con exito.</p>`,
                                customClass: {
                                    icon: 'icono',
                                    confirmButton: 'btn-confirmar',
                                    title: 'titulo',
                                },
                            }).then(() => location.reload());
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX:', errorThrown);
                        console.log(jqXHR.responseText);
                        console.log(textStatus);
                    }
                })
            }
        })
    })
}