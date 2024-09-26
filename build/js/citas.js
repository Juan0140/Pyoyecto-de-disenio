//Modelo de cita
const cita = {
    barbero: [],
    fecha: '',
    hora: '',
    hora_fin: '',
    servicios: [],
    idCliente: '',
    subtotal: 0, 
    idCupon: 0,
    descuento: 0,
    total: 0,
}

let horasGlobales = [];
//Modelo de servicios
const servicioSeleccionado = {
    id: '',
    nombre: '',
    precio: '',
};
//Modelo de barbero
const barbero = {
    idBarbero: '',
    nombreBarbero: ''
}
document.addEventListener('DOMContentLoaded', function () {
    iniciarApp2();
})
function iniciarApp2() {
    servicios();
    barberos();
    configurarFecha();
    horas();
    mostrarResumen();
    limpiarResumen();
    reservar();
    obtenerCliente();
}

function obtenerCliente() {
    const idCliente = document.querySelector('#idCliente').dataset.idcliente;
    cita.idCliente = idCliente;
}

//Servicios cliente
function servicios() {
    const servicios = document.querySelectorAll('.listado-servicios .servicio');
    servicios.forEach((servicio) => {
        servicio.addEventListener('click', (e) => {
            // Obtener los datos del servicio clicado
            servicioSeleccionado.id = servicio.dataset.id;
            servicioSeleccionado.nombre = servicio.dataset.nombre;
            servicioSeleccionado.precio = servicio.dataset.precio;

            // Llamar a aplicarServicio y pasar la referencia al objeto servicioSeleccionado
            aplicarServicio(servicioSeleccionado);
        });
    });
}

function aplicarServicio(servicio) {
    const nuevoServicio = {
        id: servicio.id,
        nombre: servicio.nombre,
        precio: servicio.precio,
    };
    const { id } = nuevoServicio;
    const { servicios } = cita;
    const divServicio = document.querySelector(`[data-id="${id}"]`);

    // Obtener la hora actual seleccionada
    const horaInicial = cita.hora;
    //Comprobar si un servicio ya fue agregado o quitarlo
    if (servicios.some(agregado => agregado.id === id)) {
        //Lo quitamos
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {
        //Lo agregamos
        divServicio.classList.add('seleccionado');
        cita.servicios = [...servicios, nuevoServicio];
    }
    calcularHoraFinal(horaInicial, cita.servicios.length);
    console.log(cita);
}

function calcularHoraFinal(horaInicial, cantidadServicios) {
    if (horaInicial && cantidadServicios > 0) {
        // Parsear la hora inicial a un objeto de fecha
        const fechaHoraInicial = new Date(`2000-01-01T${horaInicial}`);

        // Calcular la nueva hora final sumando una hora por cada servicio
        const nuevaHoraFinal = new Date(fechaHoraInicial.getTime() + cantidadServicios * 60 * 60 * 1000);

        // Formatear la nueva hora final en formato '00:00:00'
        const horas = nuevaHoraFinal.getHours().toString().padStart(2, '0');
        const minutos = nuevaHoraFinal.getMinutes().toString().padStart(2, '0');
        const segundos = nuevaHoraFinal.getSeconds().toString().padStart(2, '0');

        // Actualizar la variable cita.hora_fin
        cita.hora_fin = `${horas}:${minutos}:${segundos}`;
    }
}




//Barberos
function barberos() {
    const selectBarbero = document.querySelector('#barbero');
    if (selectBarbero) {
        selectBarbero.addEventListener('change', (e) => {
            cita.hora = '';
            const selectedOption = e.target.options[e.target.selectedIndex];
            if (selectedOption) {
                const idBarbero = selectedOption.dataset.idBarbero;
                const nombreBarbero = selectedOption.dataset.nombreBarbero;

                // Llamar a aplicarBarbero y pasar la referencia al objeto barbero
                aplicarBarbero({ idBarbero: idBarbero, nombreBarbero: nombreBarbero });
            }
        })
    }
}
function aplicarBarbero(barbero) {
    const nuevoBarbero = {
        idBarbero: barbero.idBarbero,
        nombreBarbero: barbero.nombreBarbero,
    };
    // Verificar si ya hay un barbero seleccionado
    if (cita.barbero) {
        // Si hay un barbero seleccionado, reemplazarlo con el nuevo
        cita.barbero = [];
        cita.barbero = nuevoBarbero;
    } else {
        // Si no hay un barbero seleccionado, asignar el nuevo barbero
        cita.barbero = nuevoBarbero;
    }
    console.log(cita);
}

// Fecha de la cita
function configurarFecha() {
    const inputFecha = document.querySelector('#fecha-cita');
    inputFecha.addEventListener('blur', e => {
        const fechaSeleccionada = new Date(e.target.value + 'T23:00:00-06:00');
        const diaSeleccionado = fechaSeleccionada.getDay(); // 0 para domingo, 1 para lunes, etc.
        const fechaActual = new Date();
        const fechaLimite = new Date();
        fechaLimite.setDate(fechaActual.getDate() + 15); // Establecer límite de 15 días en el futuro

        // Validar si la fecha es anterior al día actual
        if (fechaSeleccionada < fechaActual) {
            Swal.fire({
                icon: 'error',
                title: 'Fecha no válida',
                html: `<p class="p-alertas">Selecciona una fecha válida (no anterior a hoy).</p>`,
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });
            e.target.value = ''; // Limpiar el campo de fecha
            cita.fecha = '';
            cita.barbero = [];
            cita.hora = '';
        }

        // Validar si la fecha es un domingo
        if (diaSeleccionado === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Fecha no válida',
                html: `<p class="p-alertas">Selecciona una fecha válida (no un domingo).</p>`,
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });
            e.target.value = ''; // Limpiar el campo de fecha
            cita.fecha = '';
            cita.barbero = [];
            cita.hora = '';
        }

        // Validar si la fecha está más de 15 días en el futuro
        if (fechaSeleccionada > fechaLimite) {
            Swal.fire({
                icon: 'error',
                title: 'Fecha no válida',
                html: `<p class="p-alertas">Selecciona una fecha válida (no más de 15 días en el futuro).</p>`,
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
                width: 'auto'
            });
            e.target.value = ''; // Limpiar el campo de fecha
            cita.fecha = '';
            cita.barbero = [];
            cita.hora = '';
        }

        // Si ninguna de las condiciones anteriores se cumple, aplicar la fecha
        if (fechaSeleccionada >= fechaActual && diaSeleccionado !== 0 && fechaSeleccionada <= fechaLimite) {
            aplicarFecha(fechaSeleccionada);
        }
    })
}

function aplicarFecha(fechaSeleccionada) {
    if (cita.fecha) {
        cita.fecha = '';
        cita.barbero = [];
        cita.hora = '';
    }
    fechaSeleccionada.setDate(fechaSeleccionada.getDate() - 1);
    const soloFecha = fechaSeleccionada.toISOString().split('T')[0];
    cita.fecha = soloFecha;
    console.log(cita);

}

function horas() {
    const selectHoras = document.querySelector('#hora-cita');
    selectHoras.addEventListener('change', e => {
        const horaCita = selectHoras.value;
        aplicarHora(horaCita);
        calcularHoraFinal(horaCita, cita.servicios.length);
    })
}

function aplicarHora(horaSel) {
    if (cita.hora) {
        cita.hora = '';
    }
    cita.hora = horaSel;
    console.log(cita);
}



function mostrarResumen() {
    const mensajeResumen = document.querySelector('#mensaje-resumen');
    const botonReserva = document.querySelector('#botonReserva');
    const botonDescuento= document.querySelector('#botonDescuento');
    botonReserva.classList.add('reservar-cita');
    botonDescuento.classList.add('reservar-cita');

    if (!Object.values(cita).includes('')) {
        if (cita.barbero && cita.barbero.idBarbero) {
            cita.barbero.idBarbero = parseInt(cita.barbero.idBarbero);
        }
        if (cita.servicios.length >= 1) {

            mensajeResumen.textContent = "Verifica los datos de tu cita";
            mensajeResumen.classList.remove('mensaje-error'); 
            limpiarResumen();
            botonReserva.classList.remove('ocultar');
            botonDescuento.classList.remove('ocultar');
            const resumen = document.querySelector('.resumen');
            const { barbero, fecha, hora, hora_fin, servicios, subtotal, descuento } = cita;
            const { idBarbero, nombreBarbero } = barbero;

            const horaFinal = cita.hora_fin.split(':');
            const horaFinalDate = new Date(`2000-01-01T${horaFinal[0]}:${horaFinal[1]}:00`);
            const limiteHoraFinal = new Date(`2000-01-01T18:00:00`);
            const limiteHoraDescanso = new Date(`2000-01-01T14:00:00`);
            const finDescanso = '15:00:00';
            const limiteFinal = '17:00:00';

            const fechaInicio = new Date(`2000-01-01T${hora}`);
            const fechaFin = new Date(`2000-01-01T${hora_fin}`);

            if (horaFinalDate > limiteHoraFinal) {
                mensajeResumen.textContent = "Lo sentimos, cerramos a las 18:00, no se puede reservar despues de las 17:00.";
                mensajeResumen.classList.add('mensaje-error');
                botonReserva.classList.remove('ocultar');
                botonReserva.classList.add('ocultar');
                botonDescuento.classList.remove('ocultar');
                botonDescuento.classList.add('ocultar');
                limpiarResumen();
                return;
            }
            console.log(horaFinalDate);
            console.log(finDescanso);
            if (hora_fin===finDescanso) {
                console.log("aqui")
                mensajeResumen.textContent = "Lo sentimos, no se pueden hacer reservas entre las 14:00 y las 15:00.";
                mensajeResumen.classList.add('mensaje-error');
                botonReserva.classList.remove('ocultar');
                botonReserva.classList.add('ocultar');
                botonDescuento.classList.remove('ocultar');
                botonDescuento.classList.add('ocultar');
                limpiarResumen();
                return;
            }
            realizarAjax(fecha, idBarbero, hora_fin, mensajeResumen, botonReserva);

            const hServcios = document.createElement("H3");
            hServcios.textContent = "Resumen Servicios";
            hServcios.classList.add('h-resumen');
            resumen.appendChild(hServcios);
            let sutotal = 0;
            servicios.forEach(servicio => {
                const { id, nombre, precio } = servicio;
                preciopar = parseInt(precio);
                sutotal = sutotal + preciopar;
                const contenedorServicio = document.createElement('DIV');
                contenedorServicio.classList.add('contendor-servicio');

                const textoServicio = document.createElement('P');
                textoServicio.innerHTML = `<span>Servicio: </span> ${nombre}`;

                const precioServicio = document.createElement('P');
                precioServicio.innerHTML = `<span>Precio: </span> ${precio}`;

                contenedorServicio.appendChild(textoServicio);
                contenedorServicio.appendChild(precioServicio);

                resumen.appendChild(contenedorServicio);
            })
            cita.subtotal=sutotal;
            const hCita = document.createElement("H3");
            hCita.textContent = "Resumen Cita";
            hCita.classList.add('h-resumen');
            resumen.appendChild(hCita);

            const nomBarbero = document.createElement("P");
            nomBarbero.innerHTML = `<span>Barbero: </span> ${nombreBarbero}`;

            const fechaCita = document.createElement("P");
            fechaCita.innerHTML = `<span>Fecha: </span> ${fecha}`;

            const horaCit = document.createElement("P");
            horaCit.innerHTML = `<span>Hora: </span> ${hora}`;

            const horaCitFin = document.createElement("P");
            horaCitFin.innerHTML = `<span>Hora de fin: </span> ${hora_fin}`;

            const subtotalT = document.createElement("P");
            subtotalT.innerHTML = `<span>Subtotal: </span> $${sutotal}`;

            const des = document.createElement("P");
            des.innerHTML = `<span>Descuento: </span> $${descuento}`;
            let totalP=sutotal-descuento;
            const total = document.createElement("P");
            total.innerHTML = `<span>Total: </span> $${totalP}`;
            cita.total=totalP;
            console.log(cita);
            
            resumen.appendChild(nomBarbero);
            resumen.appendChild(fechaCita);
            resumen.appendChild(horaCit);
            resumen.appendChild(horaCitFin);
            resumen.appendChild(subtotalT);
            resumen.appendChild(des);
            resumen.appendChild(total);

        } else {
            mensajeResumen.textContent = "Faltan servicios";
            mensajeResumen.classList.add('mensaje-error');
            botonReserva.classList.remove('ocultar')
            botonReserva.classList.add('ocultar')
            botonDescuento.classList.remove('ocultar');
            botonDescuento.classList.add('ocultar');
            limpiarResumen();
        }
    } else {
        mensajeResumen.textContent = "Faltan datos";
        mensajeResumen.classList.add('mensaje-error');
        botonReserva.classList.remove('ocultar')
        botonReserva.classList.add('ocultar')
        botonDescuento.classList.remove('ocultar');
        botonDescuento.classList.add('ocultar');
        limpiarResumen();

    }
}
function limpiarResumen() {
    const resumen = document.querySelector('.resumen');
    resumen.innerHTML = '';
    const botonReserva = document.querySelector('#botonReserva');
    botonReserva.classList.add('ocultar')
    const botonDescuento = document.querySelector('#botonDescuento');
    botonDescuento.classList.add('ocultar')
}

//Ejecutamos el reservar cita
function reservar() {
    const botonReserva = document.querySelector('#botonReserva');
    botonReserva.addEventListener('click', () => confirmarReserva());
}

function confirmarReserva() {
    Swal.fire({
        icon: 'warning',
        title: '¿Estás seguro de reservar la cita?',
        html: `<p class="p-alertas">Puedes cancelar la cita hasta un dia antes.</p>`,
        showCancelButton: true,
        confirmButtonText: 'Sí, reservar',
        cancelButtonText: 'No, cancelar',
        customClass: {
            icon: 'icono',
            confirmButton: 'btn-confirmar',
            cancelButton: 'btn-cancelar',
            title: 'titulo',
        },
        width: 'auto'
    }).then((result) => {
        if (result.isConfirmed) {
            reservarCita();
        } else {
           
        }
    });
}

function reservarCita() {
    // Verifica que la cita esté completa
    if (!Object.values(cita).includes('')) {
        $.ajax({
            type: 'POST',
            url: '../includes/functions/reservar-cita.php',
            data: { cita: JSON.stringify(cita) },
            success: function (response) {
                // Maneja la respuesta del servidor
                try {
                    const responseData = JSON.parse(response);
                    if (responseData.success) {
                        // Muestra la alerta SweetAlert en caso de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Cita reservada exitosamente',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            customClass: {
                                icon: 'icono'
                            }
                        });

                        // Puedes realizar otras acciones después de la reserva exitosa, si es necesario
                        reiniciarCita();
                    } else {
                        // Muestra una alerta de error si la reserva no fue exitosa
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al reservar la cita',
                            text: responseData.message || 'Ha ocurrido un error',
                            customClass: {
                                icon: 'icono',
                                confirmButton: 'btn-confirmar',
                                title: 'titulo',
                            },
                            width: 'auto'
                        });
                    }
                } catch (error) {
                    console.error('Error al procesar la respuesta del servidor:', error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error en la solicitud AJAX:', errorThrown);
                console.log(jqXHR.responseText);
                console.log(textStatus);
            }
        });
    } else {
        // Muestra un mensaje de error si la cita no está completa
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, completa todos los datos antes de reservar la cita.',
            customClass: {
                icon: 'icono',
                confirmButton: 'btn-confirmar',
                title: 'titulo',
            },
            width: 'auto'
        });
    }
}

function reiniciarCita() {
    cita.barbero = [];
    cita.fecha = '';
    cita.hora = '';
    cita.servicios = [];


    document.querySelector('#fecha-cita').value = '';


    // Restablece el índice del select #barbero a 0
    const barberoSelect = document.querySelector('#barbero');
    barberoSelect.selectedIndex = 0;

    // Restablece el índice del select #hora-cita a 0
    document.querySelector('#hora-cita').selectedIndex = 0;

    // Desselecciona los servicios y quita la clase 'seleccionado'
    const serviciosSeleccionados = document.querySelectorAll('.listado-servicios .servicio.seleccionado');
    serviciosSeleccionados.forEach(servicio => servicio.classList.remove('seleccionado'));
    limpiarResumen(); // Puedes agregar una función para limpiar la visualización del resumen si es necesario
}
function obtenerHorasIntermedias(hora, hora_fin) {
    const horasIntermedias = [];

    // Parsear las horas iniciales y finales a objetos de fecha
    const fechaHoraInicial = new Date(`2000-01-01T${hora}`);
    const fechaHoraFinal = new Date(`2000-01-01T${hora_fin}`);

    // Calcular la diferencia en horas
    const diferenciaHoras = (fechaHoraFinal - fechaHoraInicial) / (1000 * 60 * 60);

    // Generar las horas intermedias y agregarlas a un array
    for (let i = 1; i < diferenciaHoras; i++) {
        const horaIntermedia = new Date(fechaHoraInicial.getTime() + i * 60 * 60 * 1000);
        const horas = horaIntermedia.getHours().toString().padStart(2, '0');
        const minutos = horaIntermedia.getMinutes().toString().padStart(2, '0');
        const segundos = horaIntermedia.getSeconds().toString().padStart(2, '0');
        const horaIntermediaFormateada = `${horas}:${minutos}:${segundos}`;
        horasIntermedias.push(horaIntermediaFormateada);
    }

    return horasIntermedias;
}


let ajaxProceso = false;

function realizarAjax(fechaSel, idBarberoSel, horaFinal, mensajeResumen, botonReserva) {
    ajaxEnProceso = true;

    $.ajax({
        url: '../includes/functions/consultar-horas-fin.php',
        method: 'POST',
        data: { fecha: fechaSel, idBarbero: idBarberoSel },
        dataType: 'json',
        success: function (horasFinales) {
            console.log('Horas fin:', horasFinales);
            if (cita.servicios.length > 1) {
                const horasIntermedias = obtenerHorasIntermedias(cita.hora, cita.hora_fin);
            
                // Filtrar las horas intermedias para excluir la hora de inicio y la hora de fin
                const horasIntermediasExcluidas = horasIntermedias.filter(hora =>
                    hora !== cita.hora && hora !== cita.hora_fin
                );
            
                console.log('Horas intermedias entre hora y hora_fin:', horasIntermediasExcluidas);
            
                let horaOcupada = false;
            
                horasIntermediasExcluidas.forEach(horas => {
                    if (horasFinales.includes(horas)) {
                        mensajeResumen.textContent = `Lo sentimos, la cita que termina a las ${horas} ya está ocupada`;
                    mensajeResumen.classList.add('mensaje-error');
                    botonReserva.classList.remove('ocultar');
                    botonReserva.classList.add('ocultar');
                    limpiarResumen();
                    return;
                    }
                });
                console.log(horaOcupada);
    
            }
            if (horasFinales.length > 0) {
                    if (horasFinales.includes(horaFinal)) {
                        mensajeResumen.textContent = `Lo sentimos la cita que termina a las ${horaFinal} ya esta ocupada`;
                        mensajeResumen.classList.add('mensaje-error');
                        botonReserva.classList.remove('ocultar');
                        botonReserva.classList.add('ocultar');
                        limpiarResumen();
                        return;
                    }

                
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX:', errorThrown);
            console.log(jqXHR.responseText);
            console.log(textStatus);
        },
        complete: function () {
            ajaxEnProceso = false;
        }
    });
}






