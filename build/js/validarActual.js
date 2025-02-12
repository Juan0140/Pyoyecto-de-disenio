document.addEventListener('DOMContentLoaded', function () {
    iniciarApp3();
})
function iniciarApp3() {
    formatearTelefono();
    validarCorreo();
}

//Validar telefono
function formatearTelefono() {
    const telefonoInput = document.querySelector('#tel');

    telefonoInput.addEventListener('change', function () {
        // Formatea el número de teléfono inmediatamente después de cada entrada
        let numeroTelefono = telefonoInput.value.replace(/\D/g, ''); // Elimina caracteres no numéricos
        telefonoInput.value = numeroTelefono;
        const telefonoAnterior = document.querySelector('#tel').dataset.anterior || '';
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
            telefonoInput.value = telefonoAnterior;
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
        const correoAnterior = document.querySelector('#correo').dataset.anterior || '';
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
            correoInput.value = correoAnterior;
        }
    });
}

