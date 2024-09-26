document.addEventListener('DOMContentLoaded', function () {
    iniciarApp80();
})
function iniciarApp80() {
    obtenerDatos();
    validarDatos();
    actualizarBarbero();
}
let idBarbero = 0;
let numeroAnt = 0;
let comisionAnt = 0;
function obtenerDatos() {
    const selectBar = document.querySelector('#nombreBarbero');
    if (selectBar) {
        selectBar.addEventListener('change', (e) => {
            const selectOption = e.target.options[e.target.selectedIndex];
            if (selectOption) {
                idBarbero = selectOption.getAttribute('data-idBarbero');
                const telBarbero = selectOption.getAttribute('data-telBarbero');
                const comisionBarbero = parseInt(selectOption.getAttribute('data-comisionBarbero'));

                const inputTel = document.querySelector('#telefonoAct');
                const inputComision = document.querySelector('#comisionAct');
                inputTel.disabled = false;
                inputComision.disabled = false;
                inputTel.value = telBarbero;
                inputComision.value = comisionBarbero;
                validarDatos();
            }
        })
    }
}
function validarDatos() {
    const inputTel = document.querySelector('#telefonoAct');
    const inputComision = document.querySelector('#comisionAct');
    numeroAnt = inputTel.value;
    comisionAnt = inputComision.value;
    inputTel.addEventListener('change', () => validarTel(inputTel))
    inputComision.addEventListener('change', () => validarCom(inputComision));
}
function validarTel(inputTel) {
    const telefono = inputTel.value;
    if (telefono.length !== 10) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `<p class="p-alertas">El número de teléfono debe contener exactamente 10 números.</p>`,
            customClass: {
                icon: 'icono',
                confirmButton: 'btn-confirmar',
                title: 'titulo',
            },
            width: 'auto'
        });
        inputTel.value = numeroAnt;
        return;
    }
}
function validarCom(inputComision) {
    const comision = inputComision.value;
    if (comision < 1 || comision > 100) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `<p class="p-alertas">La comision debe estar entre 1-100</p>`,
            customClass: {
                icon: 'icono',
                confirmButton: 'btn-confirmar',
                title: 'titulo',
            },
            width: 'auto',
        });
        inputComision.value = comisionAnt;
    }
}

function actualizarBarbero() {
    const botonActualizar = document.querySelector('#actualizarBarbero');
    botonActualizar.addEventListener('click', () => {
        if (idBarbero === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Elige a un barbero',
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });
            return;
        }
        Swal.fire({
            icon: 'info',
            title: "Ingresa tu contraseña",
            input: "password",
            inputLabel: "Contraseña",
            inputPlaceholder: "Tu contraseña",
            showCancelButton: true,
            confirmButtonText: "Actualizar",
            cancelButtonText: 'Cancelar',
            inputAttributes: {
                autocapitalize: "off",
                autocorrect: "off"
            },
            customClass: {
                icon: 'icono',
                confirmButton: 'btn-confirmar',
                cancelButton: 'btn-cancelar',
                title: 'titulo',
                input: 'in-alertas',
            },
        }).then((result) => {
            const password = result.value;
            const inputTel = document.querySelector('#telefonoAct');
            const inputComision = document.querySelector('#comisionAct');
            const telefono = inputTel.value;
            const comision = inputComision.value;
            $.ajax({
                url: '../includes/functions/actualizar-barbero.php',
                type: 'POST',
                data: { idBarbero: idBarbero, telefono: telefono, comision: comision, password: password },
                dataType: 'json',
                success: function (data) {
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
                        }).then(() => location.reload());
                    } else {
                        inputTel.value = numeroAnt;
                        inputComision.value = comisionAnt;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: `<p class="p-alertas">Contraseña incorrecta.</p>`,
                            customClass: {
                                icon: 'icono',
                                confirmButton: 'btn-confirmar',
                                title: 'titulo',
                            },
                        })
                    }


                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Error en la solicitud AJAX:', errorThrown);
                    console.log(jqXHR.responseText);
                    console.log(textStatus);
                }
            })
        })
    })
}