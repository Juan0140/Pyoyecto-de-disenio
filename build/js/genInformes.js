document.addEventListener('DOMContentLoaded', function () {
    iniciarApp88();
})
function iniciarApp88() {
    obtenerDatos();
    generarInforme();
    realizarCorte();
}
let idBarbero = 0;
function obtenerDatos() {
    const selectBar = document.querySelector('#nombreBarbero');
    if (selectBar) {
        selectBar.addEventListener('change', (e) => {
            const selectOption = e.target.options[e.target.selectedIndex];
            if (selectOption) {
                idBarbero = selectOption.getAttribute('data-idBarbero');
                const inputCorte=document.querySelector('#reCorte');
                    inputCorte.classList.add('paginado');
            }
        });
    }
}
function generarInforme() {
    const inputGenerar = document.querySelector('#genInforme');
    inputGenerar.addEventListener('click', () => {
        if (idBarbero === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `<p class="p-alertas">Selecciona un barbero.</p>`,
                customClass: {
                    icon: 'icono',
                    confirmButton: 'btn-confirmar',
                    title: 'titulo',
                },
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: '../includes/functions/generar-informes.php',
            data: { idBarbero: idBarbero },
            dataType: 'json',
            success: function (data) {
                if (data.success == false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: `<p class="p-alertas">${data.message}</p>`,
                        customClass: {
                            icon: 'icono',
                            confirmButton: 'btn-confirmar',
                            title: 'titulo',
                        },
                    });
                } else {
                    // Descargar el PDF
                    var link = document.createElement('a');
                    link.href = '../pdf/Informe_Citas.pdf'; // Reemplaza 'ruta_del_proyecto' con la ruta correcta
                    link.download = '../pdf/Informe_Citas.pdf';
                    link.click();
                    const inputCorte=document.querySelector('#reCorte');
                    inputCorte.classList.remove('paginado');
                }
            },
            error: function (xhr, status, error) {
                console.log("Error en la solicitud Ajax:", error);
                console.log("Estado de la solicitud:", status);
                console.log("Respuesta completa del servidor:", xhr.responseText);
            }
        });
    });
}

function realizarCorte(){
    const inputCorte=document.querySelector('#reCorte');  
    inputCorte.addEventListener('click', ()=>{
        Swal.fire({
            title: '¿Estás seguro que quieres realizar el corte?',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            icon: 'warning',
            customClass: {
                icon: 'icono',
                confirmButton: 'btn-confirmar',
                cancelButton: 'btn-cancelar'
            }
        }).then((result)=>{
            if(result.isConfirmed){
                console.log("si");
                $.ajax({
                    type: 'POST',
                    url: '../includes/functions/realizar-corte.php',
                    data: { idBarbero: idBarbero },
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.success);
                        if(data.success){
                            console.log(data.success);
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualización Exitosa',
                                html: `<p class="p-alertas">Se ha realizado el corte correctamente.</p>`,
                                customClass: {
                                    icon: 'icono',
                                    confirmButton: 'btn-confirmar',
                                    title: 'titulo',
                                },
                                width: 'auto'
                            }).then(()=>location.reload());
                        }
                    }  
                })
            }
        })
    }) 
}
