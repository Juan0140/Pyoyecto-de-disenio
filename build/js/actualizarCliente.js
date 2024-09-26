document.addEventListener('DOMContentLoaded', function () {
    iniciarAp5();
})

function iniciarAp5(){
    actualizarCliente();
    actualizarContraseña();
}
function  actualizarCliente(){
    const actInput= document.querySelector('#btn-actualizar');
    const idCliente = document.querySelector('#clienteData').dataset.idcliente;
    actInput.addEventListener('click', ()=>{
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

                // Obtén los datos del formulario // 
                const telefono = document.querySelector('#tel').value;
                const correo = document.querySelector('#correo').value;
                const intelefono = document.querySelector('#tel');
                const incorreo = document.querySelector('#correo');

                // Enviar la contraseña, idCliente y los datos del formulario al servidor mediante AJAX
                $.ajax({
                    url: '../includes/functions/actualizarClientes.php',  // Ajusta la ruta a tu archivo PHP
                    method: 'POST',
                    data: {
                        idCliente: idCliente,
                        password: password,
                        tel: telefono,
                        correo: correo
                    },
                    dataType: 'json',
                    success: function (respuesta) {
                        
                        if (respuesta.success) {
                            // Actualizar los campos con los nuevos datos
                            incorreo.value=correo;
                            intelefono.value=telefono;
                            // Muestra la alerta de SweetAlert para la actualización exitosa
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualización Exitosa',
                                html: `<p class="p-alertas">Los datos se han actualizado correctamente.</p>`,
                                customClass: {
                                    icon: 'icono',
                                    confirmButton: 'btn-confirmar',
                                    title: 'titulo',
                                },
                            }).then(()=>location.reload())
                        } else {
                            const correoAnterior = document.querySelector('#correo').dataset.anterior || '';
                            const telefonoAnterior = document.querySelector('#tel').dataset.anterior || '';
                            incorreo.value=correoAnterior;
                            intelefono.value=telefonoAnterior;
                            console.log(password)
                            // Muestra la alerta de SweetAlert para contraseña incorrecta
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
                        console.error('Error al actualizar datos', error);
                    },
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // El usuario ha cancelado el cuadro de diálogo
                console.log("Operación cancelada");
            }
        });
    })
}

function actualizarContraseña(){
    const inputContraseña=document.querySelector('#btn-actualizarContra');
    const idCliente = document.querySelector('#clienteData').dataset.idcliente;
    const contraAnt=document.querySelector('#contra-ac');
    const contraNew=document.querySelector('#contraNew');
    inputContraseña.addEventListener('click', ()=>{
        if(contraAnt.value=='' || contraNew.value==''){
            Swal.fire({
                icon: 'error',
                title: 'No has ingresado alguna o ambas contraseñas',
                customClass:{
                    icon: 'icono',
                    title:'titulo',
                    confirmButton:'btn-confirmar', 
                }, width: 'auto'
            });
            return;
        }
        Swal.fire({
            title: '¿Estás seguro que quieres actualizar tu contraseña?',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No',
            icon: 'warning',
            customClass: {
                icon: 'icono',
                confirmButton: 'btn-confirmar',
                cancelButton: 'btn-cancelar'
            }
        }).then(result => {
            if (result.isConfirmed) {
                
                $.ajax({
                    url: '../includes/functions/actualizarContraseña.php',  // Ajusta la ruta a tu archivo PHP
                    method: 'POST',
                    data: {
                        idCliente: idCliente,
                        contraActual: contraAnt.value,
                        contraNew: contraNew.value,
                    },
                    dataType: 'json',
                    success: function (respuesta){
                        if(respuesta.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Actualización Exitosa',
                                html: `<p class="p-alertas">Los contraseña fue actualizada con exito.</p>`,
                                customClass: {
                                    icon: 'icono',
                                    confirmButton: 'btn-confirmar',
                                    title: 'titulo',
                                },
                            })
                            contraAnt.value='';
                            contraNew.value='';
                        }else{
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
                            contraAnt.value='';
                            contraNew.value='';
                        }
                    },
                    error: function (error) {
                        console.error('Error al actualizar datos', error);
                    },
                })
                
            }
        });
    })

}
