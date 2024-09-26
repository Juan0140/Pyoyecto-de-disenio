document.addEventListener('DOMContentLoaded', function () {
    iniciarApp17();
})
function iniciarApp17() {
    obtenerFecha();
}
function obtenerFecha(){
    const inputFecha=document.querySelector('#fecha-cita');
    inputFecha.addEventListener('change', ()=>{
        const fechaSelecc=inputFecha.value;
        const selectBarbero = document.querySelector('#barbero');
        $.ajax({
            url: '../includes/functions/barberos-dispo.php',  // Reemplaza con la ruta correcta
            type: 'POST',
            data: { fecha: fechaSelecc},
            dataType: 'json',
            success: function (barberosDisponibles) {
                while (selectBarbero.firstChild) {
                    selectBarbero.removeChild(selectBarbero.firstChild);
                }
                // Agregar las opciones disponibles
                console.log(barberosDisponibles);
                selectBarbero.innerHTML = '<option disabled selected>--Selecciona un Barbero--</option>';
                barberosDisponibles.forEach(barbero => {
                    const option = document.createElement('option');
                    option.className = 'opcion';
                    option.dataset.idBarbero = barbero.id;
                    option.dataset.nombreBarbero = barbero.nombre;
                    option.textContent = barbero.nombre;
                    selectBarbero.appendChild(option);
                });
                selectBarbero.selectedIndex=0;
                
            }
            
        })
    })
}