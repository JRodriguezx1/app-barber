(function(){
    if(document.querySelector('.dctoindividual')){
        const editar = document.querySelectorAll('#editar');
        const eliminar = document.querySelectorAll('#eliminar');

        let obj = {
            nombre: '',
            descripcion: '',
            dcto: '',
            fecha_fin: ''
        }
        

        editar.forEach(Element => {
            Element.addEventListener('click', (e)=>{ 
                const id = e.target.parentElement.dataset.id;
                const tr = e.target.parentElement.parentElement.parentElement;
                obj.nombre = tr.children[1].textContent;
                obj.fecha_fin = tr.children[3].textContent;
                obj.descripcion = tr.children[4].textContent;
                obj.dcto = tr.children[5].textContent;
                formulariocliente(id);
                countchars();
            });
        });


        function formulariocliente(id){
    
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Actualizar Oferta',
                html: `<form class="formulario formclientes" action="/admin/fidelizacion/editar_dctoindividual" method="POST">
                            <input type="hidden" name="id" value="${id}" >

                            <div class="formulario__campo">
                                <label class="formulario__label" for="nombre">Nombre:</label>
                                <div class="formulario__dato">
                                    <input class="formulario__input" type="text" value="${obj.nombre}" readonly required>
                                </div>
                            </div>

                            <div class="formulario__campo">
                                <label class="formulario__label" for="descripcion">Descripcion:</label>
                                <div class="formulario__dato">
                                    <input class="formulario__input" type="text" placeholder="Descripcion de la oferta" id="descripcion" name="descripcion" value="${obj.descripcion}" required>
                                    <label data-num="42" class="count-charts" for="">42</label>
                                </div>
                            </div>
                            
                            <div class="formulario__campo">
                                <label class="formulario__label" for="descuento">Descuento</label>
                                <input class="formulario__input" min="1" max="100" type="number" placeholder="Descuento en porcentaje" id="descuento" name="descuento" value="${obj.dcto}" requiered>
                            </div>

                            <div class="formulario__campo">
                                <label class="formulario__label" for="fecha">Fecha</label>
                                <input class="formulario__input" type="date" id="fecha" name="fecha" value="${obj.fecha_fin}" requiered>
                            </div>
                            
                            <input class="clientes-btn" type="submit" value="Actualizar">
                       </form>`,
                showCancelButton: false,
                showConfirmButton: false,  
            });
        }

        /////////////// eliminar oferta del cliente ////////////////

        eliminar.forEach(element => {
            element.addEventListener('click', (e)=>{
                const id = e.target.parentElement.dataset.id;
                Swal.fire({
                    customClass: {
                        confirmButton: 'sweetbtnconfirm',
                        cancelButton: 'sweetbtncancel'
                    },
                    title: 'Desea eliminar la oferta del cliente?',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No',
                    
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = `/admin/fidelizacion/eliminar_dctoindividual?id=${id}`;
                    } 
                })
            });
        });
    
        //////////////// funcion contadores de caracteres /////////////////////
        
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
    }

})();