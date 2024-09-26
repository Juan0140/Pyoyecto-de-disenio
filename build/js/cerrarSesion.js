document.addEventListener('DOMContentLoaded', function () {
    iniciarApp6();
})

function iniciarApp6(){
    cerrarSesion();
}
function cerrarSesion(){
const cerrar = document.querySelector('.cerrar-sesion')
cerrar.addEventListener('click', e => {
    e.preventDefault();
    Swal.fire({
        title: '¿Estás seguro que quieres cerrar sesión?',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'No',
        icon: 'warning',
        customClass: {
            icon: 'icono',
            confirmButton: 'btn-confirmar',
            cancelButton: 'btn-cancelar'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir a la página de cierre de sesión
            window.location.href = '../includes/functions/cerrar-sesion.php';
        }
    });
});
}