let paso=3;
const pasoInicial=3;
const pasoFinal=5;

document.addEventListener('DOMContentLoaded', function () {
    iniciarApp();
})
function iniciarApp() {
    tabs();
    botonesPaginador();
    paginaSiguiente();
    paginaAnterior();
}
//Tabuladores de seccion
function mostrarSeccion() {
    //Ocultar la seccion anterior
    const seccionAnterior = document.querySelector('.mostrado');
    seccionAnterior.classList.remove('mostrado');

    //Quitar el resalte a la seccion anterior
    const tabAnterior = document.querySelector('.tabs .active');
    tabAnterior.classList.remove('active');


    //Sellecionar seccion con el paso
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add('mostrado');

    //Resalta tab active
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('active');

}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach(boton => {
        boton.addEventListener('click', e => {
            paso = parseInt(e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        })
    })
}

function botonesPaginador(){
    const pagSiguiente=document.querySelector('#siguiente');
    const pagAnterior=document.querySelector('#anterior');
    if(paso===3){
        pagAnterior.classList.add('ocultar')
        pagSiguiente.classList.remove('ocultar');
    }else if (paso===5){
        pagAnterior.classList.remove('ocultar');
        pagSiguiente.classList.add('ocultar');
        mostrarResumen();
    }
    else if(paso===4){
        pagAnterior.classList.remove('ocultar');
        pagSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaAnterior(){
    const anterior=document.querySelector('#anterior');
    anterior.addEventListener('click', ()=>{
        if(paso<=pasoInicial) return;
        paso--;

        botonesPaginador();
    } )
}

function paginaSiguiente(){
    const siguiente=document.querySelector('#siguiente');
    siguiente.addEventListener('click', ()=>{
        if (paso>=pasoFinal) return;
        paso++;
        console.log(paso);
        botonesPaginador();
    })
}


