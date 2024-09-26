
document.addEventListener('DOMContentLoaded', function(){
    eventListeners();

});
function eventListeners(){
    const mobileMenu=document.querySelector('.mobile-menu');
    mobileMenu.addEventListener('click', navegacionReponsive);
}
function navegacionReponsive(){
    const navegacion=document.querySelector('.respon');
    navegacion.classList.toggle('mostrar');
}

