(function(){
    
    const servicios = document.querySelectorAll('#servicio');
    const servicios__eliminar = document.querySelectorAll('.servicios__eliminar'); //<a></a>
    
    servicios__eliminar.forEach(Element=>{ //para eliminar los servicios
        Element.addEventListener('click', (e)=>{

            let id;
           e.target.href = "/admin/servicios/eliminar";
           e.preventDefault();
         
           if(e.target.tagName === "I"){ 
               id = e.target.parentElement.parentElement.dataset.id;
            }else{
                id = e.target.parentElement.dataset.id;
            }
            //window.location = e.target.href;
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Desea eliminar el servcio?',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Eliminado', '', 'success') 

                    
                    async function eliminarservicio(){ //
                        const datos = new FormData();
                        console.log(id);
                        datos.append('id', id);
                        try {
                            const url = "/admin/api/eliminarservicio";
                            const respuesta = await fetch(url, {method: 'POST', body: datos}); 
                            const resultado = await respuesta.json(); 
                            console.log(resultado); 

                            const divservicio = document.querySelector(`div[data-id="${id}"]`);
                            divservicio.remove();
                            return resultado;
                        } catch (error) {
                            console.log(error);
                        }
                    }

                    eliminarservicio();
                } 
            })
    
        });
    });

    
    servicios.forEach(Element =>{   // para editar los servicios
        Element.addEventListener('click', (e)=>{
            
            if(e.target.tagName === "DIV" ||e.target.tagName === "P"|| e.target.tagName === "H4" || e.target.tagName === "SPAN"){
    
                let nombre, precio, duracion, id, user;
                
                id = id = e.target.dataset.id;
                user = e.target.dataset.user;
                if(e.target.tagName === "P"|| e.target.tagName === "H4"){
                    id = e.target.parentElement.dataset.id;
                    user = e.target.parentElement.dataset.user;
                }
                if(e.target.tagName === "SPAN"){
                    id = e.target.parentElement.parentElement.dataset.id;
                    user = e.target.parentElement.parentElement.dataset.user;
                }

                if(parseInt(user)>1){
                    nombre = document.querySelector(`div[data-id="${id}"] h4`).textContent;
                    precio = parseInt(document.querySelector(`div[data-id="${id}"] #precio`).textContent);
                    duracion = parseInt(document.querySelector(`div[data-id="${id}"] #duracion`).textContent);

                    Swal.fire({
                        customClass: {
                            confirmButton: 'sweetbtnconfirm',
                            cancelButton: 'sweetbtncancel'
                        },
                        title: 'Desea editar el servcio?',
                        html: `<form class="formulario" action="/admin/servicios/editar" method="POST">
                                    <input type="hidden" name="id" value="${id}" >
                                    <div class="formulario__campo">
                                        <label class="formulario__label" for="nombre">Nombre:</label>
                                        <div class="formulario__dato">
                                            <input class="formulario__input" type="text" placeholder="Nombre del servicio" id="nombre" name="nombre" value="${nombre}" required>
                                            <label data-num="25" class="count-charts" for="">25</label>
                                        </div>
                                    </div>
                                    
                                    <div class="formulario__campo">
                                        <label class="formulario__label" for="precio">Precio:</label>
                                        <div class="formulario__dato">
                                            <input class="formulario__input" type="number" placeholder="Precio del servicio" id="precio" name="precio" value="${precio}" requiered>
                                            <label for="">$</label>
                                        </div>
                                    </div>
                                    <div class="formulario__campo">
                                        <label class="formulario__label" for="duracion">Duracion:</label>
                                        <div class="formulario__dato">
                                            <input class="formulario__input" type="number" min="10" max="120" placeholder="Duracion en minutos del servicio" id="duracion" name="duracion" value="${duracion}" disabled>
                                            <label for="">min</label>
                                        </div>
                                    </div>
                                    <input class="formulario__submit--servicio" type="submit" value="Actualizar">
                            </form>`,
                        showCancelButton: true,
                        showConfirmButton: false,
                        cancelButtonText: 'Cancelar',
                        
                    });
                    countchars();
                }
            } //cierre del if   
        }); //cierre de addeventlistener
    }); //cierre del foreach

    
    //////////////// funcion contadores de caracteres /////////////////////
    countchars();      //// se aplicado casi de manera general
    function countchars(){
        const numinput = document.querySelectorAll('.count-charts');  
        numinput.forEach(element =>{ //element es cada label
            element.textContent = element.dataset.num-element.previousElementSibling.value.length;
            element.previousElementSibling.addEventListener('input', (e)=>{ //seleccionamos el input o el textarea en donde se escribe y se le da el evento de teclas
                element.textContent = element.dataset.num-e.target.value.length;
                  
                if(element.dataset.num-e.target.value.length <= 0){
                    let cadena = e.target.value.slice(0, element.dataset.num);
                    e.target.value = cadena;
                    element.textContent = 0;
                }
            });
        });
    }
     
    if(document.querySelector('.alerta')){  //se aplica de manera global
        setTimeout(() => {
            document.querySelector('.alerta').remove();
        }, 5000);
    }
    
    })();