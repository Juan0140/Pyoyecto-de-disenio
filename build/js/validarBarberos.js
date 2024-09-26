document.addEventListener('DOMContentLoaded', function () {
    iniciarApp10();
})

function iniciarApp10(){
    formatearTelefono();
    validarFechaBarbero();
    validarRFC();
    validarComision();
    validarCorreo();
}
//Validar telefono
function formatearTelefono() {
    const telefonoInput = document.querySelector('#tel');

    telefonoInput.addEventListener('change', function () {
        // Formatea el número de teléfono inmediatamente después de cada entrada
        let numeroTelefono = telefonoInput.value.replace(/\D/g, ''); // Elimina caracteres no numéricos
        telefonoInput.value = numeroTelefono;

        // Valida que haya exactamente 10 números
        if (numeroTelefono.length !== 10) {
            // Muestra la alerta SweetAlert
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

            // Limpia el valor del campo de teléfono
            telefonoInput.value = '';
        }
    });
}
//Validar fecha de nacimiento barbero
function validarFechaBarbero() {
    const inputFecha = document.querySelector('#fecnac-bar'); // Corregir el selector
    inputFecha.addEventListener('change', () => calcularFecha(inputFecha)); // Pasar la función, no ejecutarla
}

function calcularFecha(inputFecha) {
    // Obtiene la fecha ingresada por el usuario
    const fechaIngresada = new Date(inputFecha.value);
    const fechaLimite = new Date();
    fechaLimite.setFullYear(fechaLimite.getFullYear() - 18);

    // Compara las fechas
    if (fechaIngresada > fechaLimite) {
        // Muestra la alerta SweetAlert
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `<p class="p-alertas">Solo se permiten personas mayores a 18 años.</p>`,
            customClass: {
                icon: 'icono',
                confirmButton: 'btn-confirmar',
                title: 'titulo',
            },
            width: 'auto'
        });

        // Restaura la fecha a la fecha límite permitida
        inputFecha.value='';
         
    }
}

//Validar RFC
function validarRFC() {
    const rfcInput = document.querySelector('#rfc');

    rfcInput.addEventListener('change', function () {
        rfcInput.value = rfcInput.value.toUpperCase();
        const rfcValue = rfcInput.value.trim();

        // Expresión regular para validar el formato de RFC (simplificada)
        const rfcRegex = /^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/;

        if (!rfcRegex.test(rfcValue)) {
            // Muestra la alerta SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `<p class="p-alertas">Formato de RFC no válido, por favor, ingrese uno válido.</p>`,
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto',
            });

            // Limpia el valor del campo de RFC
            rfcInput.value = '';
        }
    });
}

//Validar comision
function validarComision(){
    const comisionInput=document.querySelector('#comision');
    comisionInput.addEventListener('input', ()=>{
        const comision=comisionInput.value;
        if(comision<1 || comision>100){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `<p class="p-alertas">La comision debe estar entre 0-100</p>`,
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto',
            });
            comisionInput.value='';
        }
    });
}

//Validar correo
function validarCorreo() {
    const correoInput = document.querySelector('#correo'); // Reemplaza 'correo' con el ID de tu campo de correo

    correoInput.addEventListener('blur', function () {
        const correoValue = correoInput.value.trim();

        // Expresión regular para validar el formato de correo electrónico
        const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Valida el formato del correo electrónico
        if (!correoRegex.test(correoValue)) {
            // Muestra la alerta SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `<p class="p-alertas">Formato de correo electrónico no válido, por favor, ingresa uno válido.</p>`,
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });

            // Limpia el valor del campo de correo
            correoInput.value = '';
        }
    });
}
