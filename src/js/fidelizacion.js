(function(){
    if(document.querySelector('.fidelizacion')){

        const editar = document.querySelectorAll('.programar');
        const eliminar = document.querySelectorAll('.cancelado');
        const sendmsj = document.querySelectorAll('.sendmsj');
        let obj = {

        }

        editar.forEach(Element => {
            Element.addEventListener('click', (e)=>{ 
                const tr = e.target.parentElement.parentElement.parentElement;
                obj.id = e.target.parentElement.dataset.id;
                obj.nombreproducto = tr.children[2].textContent;
                obj.valorservicio = tr.children[3].textContent.slice(1);
                obj.dcto = tr.children[4].textContent;
                obj.valorfinal = tr.children[5].textContent;
                obj.fecha_fin = tr.children[6].textContent;
                formulariocliente(obj);
                //countchars();
            });
        });


        function formulariocliente(obj){
    
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Actualizar Oferta',
                html: `<form class="formulario modalform" action="/admin/fidelizacion/editar_dctoxproduct" method="POST">
                            <input type="hidden" name="id" value="${obj.id}" >
                            
                            <span class="nameuser">${obj.nombreproducto}</span>
                            <span class="precio">Valor: ${obj.valorservicio}</span>
                            <span class="nameuser">Dcto: ${obj.dcto}</span>
                            <span class="nameuser">Valor Final: ${obj.valorfinal}</span>
                            
                            <div class="formulario__campo">
                                <label class="formulario__label" for="tipo">Tipo De Dcto: </label>
                                <select class="formulario__select" name="tipo" id="tipo" required>
                                    <option value="" disabled selected> Seleccionar tipo de dcto</option>
                                    <option value="porcentaje">Porcentaje</option>
                                    <option value="valor">Valor</option>
                                </select>
                            </div>
                            <div class="formulario__campo">
                                <label class="formulario__label" for="dcto1">Descuento</label>
                                <input class="formulario__input" id="dcto1" type="number" min="" max="" name="dcto1" value="" disabled required>
                            </div>
                            <div class="formulario__campo">
                                <label class="formulario__label" for="dcto2" id="dcto2Valor">Valor</label>
                                <input class="formulario__input" id="dcto2" type="number" min="" max="" name="dcto2" value="" readonly required>
                            </div>

                            <div class="formulario__campo">
                                <label class="formulario__label" for="fecha_fin">Fecha De Finalizacion</label>
                                <input class="formulario__input" type="date" id="fecha_fin" name="fecha_fin" value="${obj.fecha_fin}" requiered>
                            </div>
                            
                            <input class="clientes-btn" type="submit" value="Actualizar">
                       </form>`,
                showCancelButton: false,
                showConfirmButton: false,  
            });
            eventos();
        }


        function eventos(){
            const dcto1 = document.querySelector('#dcto1');
            const tipodcto = document.querySelector('#tipo');
            tipodcto.addEventListener('change', (e)=>{
                dcto1.disabled = false;
                imprimirvalor(dcto1.value, tipodcto);
            });
    
            dcto1.addEventListener('input', (e)=>{
                imprimirvalor(e.target.value, tipodcto);  
            });
        }


        function imprimirvalor(valoringresado, tipodcto){
            const dcto1 = document.querySelector('#dcto1');
            const dcto2 = document.querySelector('#dcto2');
            const dcto2Valor = document.querySelector('#dcto2Valor');
            if(tipodcto.options[tipodcto.options.selectedIndex].value == 'porcentaje'){
                dcto1.min = 0;
                dcto1.max = 100;
                dcto2Valor.textContent = "Valor";
                if(valoringresado>100)dcto1.value = 100;
                if(valoringresado<0)dcto1.value = 0;
                dcto2.value = Math.round((obj.valorservicio*dcto1.value)/100);
            }
            if(tipodcto.options[tipodcto.options.selectedIndex].value == 'valor'){
                dcto1.min = 0;
                dcto1.max = obj.valorservicio;
                dcto2Valor.textContent = "Valor (%)";
                if(valoringresado>parseInt(obj.valorservicio))dcto1.value = obj.valorservicio;
                if(valoringresado<0)dcto1.value = 0;
                dcto2.value = Math.round((dcto1.value*100)/obj.valorservicio);
            }  
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
                        //Swal.fire('Eliminado', '', 'success');
                        window.location = `/admin/fidelizacion?id=${id}`;

                    } 
                })
            });
        });

        /////////////////////// enviar msj a clientes ///////////////////////

        sendmsj.forEach(element => {
            element.addEventListener('click', (e)=>{
                const id = e.target.parentElement.dataset.id;
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'clase y metodo de mensaje no configurado!',
                    footer: 'Innovatech quindio'
                  })
            });
        });
    
        //////////////// funcion contadores de caracteres /////////////////////
        
        /*
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
        countchars();*/
    }

})();