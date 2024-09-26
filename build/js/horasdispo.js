document.addEventListener('DOMContentLoaded', function () {
    iniciarApp3();
});

function iniciarApp3() {
    configurarHoraCita();
}

function configurarHoraCita() {
    const selectBarbero = document.querySelector('#barbero');
    const inputFecha = document.querySelector('#fecha-cita');
    const selectHoraCita = document.querySelector('#hora-cita');

    const cargarHorasAlSeleccionar = function () {
        const fechaSeleccionada = inputFecha.value;
        const selectedOption = selectBarbero.options[selectBarbero.selectedIndex];
        const idBarberoSeleccionado = selectedOption.dataset.idBarbero;

        if (!ajaxEnProceso && fechaSeleccionada && idBarberoSeleccionado) {
            realizarLlamadaAjax(fechaSeleccionada, idBarberoSeleccionado);
        }
    };

    inputFecha.addEventListener('change', () => {
        selectBarbero.selectedIndex = 0;
        cargarHorasAlSeleccionar();
        limpiarSelectHora();
    });

    selectBarbero.addEventListener('change', cargarHorasAlSeleccionar);
    inputFecha.addEventListener('change', cargarHorasAlSeleccionar);

    selectHoraCita.addEventListener('change', function () {
        // Aquí puedes realizar alguna lógica adicional si es necesario
    });
}

let ajaxEnProceso = false;

function realizarLlamadaAjax(fechaSeleccionada, idBarberoSeleccionado) {
    ajaxEnProceso = true;

    $.ajax({
        url: '../includes/functions/consultar-horas.php',
        method: 'POST',
        data: { fecha: fechaSeleccionada, idBarbero: idBarberoSeleccionado },
        dataType: 'json',
        success: function (horasDisponibles) {
            console.log('Horas disponibles:', horasDisponibles);
            if (horasDisponibles.length > 0) {
                cargarHorasEnSelect(horasDisponibles);
            } else {
                cargarMensajeSinDatos();
            }
        },
        error: function (error) {
            console.error('Error al cargar horas disponibles', error);
        },
        complete: function () {
            ajaxEnProceso = false;
        }
    });
}

function cargarMensajeSinDatos() {
    const selectHoraCita = document.querySelector('#hora-cita');
    selectHoraCita.innerHTML = '<option value="" disabled selected>--No hay horas disponibles--</option>';
}


function cargarHorasEnSelect(horasDisponibles) {
    const selectHoraCita = document.querySelector('#hora-cita');
    const selectBarbero = document.querySelector('#barbero');
    const nombre=selectBarbero.value
    selectHoraCita.innerHTML =`<option value="" disabled selected>--Selecciona la hora--</option>`;

    horasDisponibles.forEach(hora => {
        const option = document.createElement('option');
        option.value = hora;
        option.text = hora;
        selectHoraCita.appendChild(option);
    });

    const horaFinal = selectHoraCita.value;

}

function limpiarSelectHora() {
    const selectHoraCita = document.querySelector('#hora-cita');
    selectHoraCita.innerHTML = '<option value="" disabled selected>--Selecciona la hora--</option>';
}
  