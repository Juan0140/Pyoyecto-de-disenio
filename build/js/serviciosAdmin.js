document.addEventListener('DOMContentLoaded', function () {
    iniciarApp8();
})
function iniciarApp8() {
    obtenerPrecio();
    validarPrecio();
    actualizar();
    validarAgregado();
    validarPrecioAgregado();
}
let idServicio = 0;

function obtenerPrecio() {
    const selectSer = document.querySelector('#nombreServicio');
    if (selectSer) {
        selectSer.addEventListener('change', (e) => {
            const selectOption = e.target.options[e.target.selectedIndex];
            if (selectOption) {
                idServicio = selectOption.getAttribute('data-idServicio');
                const nombreServicio = selectOption.getAttribute('data-nombreServicio');
                const precioServicio = parseInt(selectOption.getAttribute('data-precioServicio'));

                const inputPrecio = document.querySelector('#precioServicio');
                inputPrecio.value = precioServicio;
                inputPrecio.disabled = false
                validarPrecio();
                return;
            }
        });
    }
}

const selectSer = document.querySelector('#nombreServicio');
selectSer.addEventListener('change', () => {
    obtenerPrecio();
})
let precioAnt = 0;
function validarPrecio() {
    const inputPrecio = document.querySelector('#precioServicio');
    precioAnt = inputPrecio.value;
    inputPrecio.addEventListener('change', () => {
        precio = inputPrecio.value;
        if (precio < 1) {
            Swal.fire({
                icon: 'error',
                title: 'El precio no puede ser menor a $1',
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });
            inputPrecio.value = precioAnt;
            return;
        }
    })

}

function actualizar() {
    const botonActualizar = document.querySelector('#actualizarServicio');
    botonActualizar.addEventListener('click', () => {
        if (idServicio == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Elige un servicio',
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
            if (result.isConfirmed) {
                const password = result.value;
                const inputPrecio = document.querySelector('#precioServicio');
                precioNew = inputPrecio.value;
                console.log(precioNew);
                $.ajax({
                    url: '../includes/functions/actualizar-servicio.php',
                    type: 'POST',
                    data: { idServicio: idServicio, precioNew: precioNew, password: password },
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
                            const inputPrecio = document.querySelector('#precioServicio');
                            inputPrecio.value = precioAnt;
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
                    error: function (error) {
                        console.log(error)
                        Swal.fire('Error', 'Hubo un error al realizar la solicitud.', 'error');
                    }
                })
            }
        })
    })
}

function validarPrecioAgregado() {
    const inputPrecioAgregar = document.querySelector('#preServicio');
    inputPrecioAgregar.addEventListener('change', () => {
        const precioAgregar = inputPrecioAgregar.value;
        if (precioAgregar < 1) {
            Swal.fire({
                icon: 'error',
                title: 'El precio no puede ser menor a $1',
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });
            inputPrecioAgregar.value = '';
            return;
        }
    });

}

function validarAgregado() {
    const botonAgregar = document.querySelector('#agregarServicio');
    botonAgregar.addEventListener('click', () => {
        const inputNombre = document.querySelector('#nomServicio');
        const nombre = inputNombre.value;
        const inputPrecioAgregar = document.querySelector('#preServicio');
        const precioAgregar = inputPrecioAgregar.value;
        if (nombre == '' || precioAgregar == '') {
            Swal.fire({
                icon: 'error',
                title: 'Hay datos vacios',
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });
            inputPrecioAgregar.value = '';
            inputNombre.value = '';
            return;
        }

        $.ajax({
            url: '../includes/functions/agregar-servicio.php',
            type: 'POST',
            data: { nombre: nombre, precio: precioAgregar },
            dataType: 'json',
            success: function (data) {
                if(data.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Agregado',
                        html: `<p class="p-alertas">Se han agregado los datos exitosamente.</p>`,
                        customClass: {
                            icon: 'icono',
                            confirmButton: 'btn-confirmar',
                            title: 'titulo',
                        },
                        width: 'auto'
                    }).then(() => location.reload());
                }
            }
        })
    })
}