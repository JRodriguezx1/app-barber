(function(){
    if(document.querySelector('#dashboardcitas')){//en admin/citas/index.php
        
        const eliminarcitas = document.querySelectorAll('.cancelado');
        const finalizarcitas = document.querySelectorAll('.finalizado');
        let valueservice;

        ////////////////////////////cancelar o eliminar cita//////////////////////////////
        eliminarcitas.forEach(eliminarcita =>{
            eliminarcita.addEventListener('click', (e)=>{
                const tr = e.target.parentElement.parentElement.parentElement;
                const id = tr.children[0].textContent;
                const estado = tr.children[7].textContent;
                if(estado === "Pendiente")cancelarcita(id, tr.children[7]); //se pasa el id de la cita y su estado  
            });
        });

        function cancelarcita(id, estado){ //funcion para cancelar la cita.
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Desea Cancelar La Cita?',
                text: "SE CANCELARA LA CITA, PUEDES CREAR UNA NUEVA CUANDO LO DESEES.",
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                
            }).then((result) => {
                if (result.isConfirmed){ 
                    (async ()=>{
                        try {
                            const url = `/admin/api/cancelarcita?id=${id}`;
                            const respuesta = await fetch(url); 
                            const resultado = await respuesta.json();  
                            if(resultado){
                                estado.textContent = "Cancelado"; 
                                Swal.fire('Cita Cancelada', '', 'success')
                            }
                        } catch (error) {
                            console.log(error);
                        }
                    })(); 
                } 
            })
        }

        //////////////////////Finalizar clita /////////////////////////////

        finalizarcitas.forEach(fincita => {
            fincita.addEventListener('click', e=>{
                const tr = e.target.parentElement.parentElement.parentElement;
                const estado = tr.children[7].textContent;
                if(estado === "Pendiente"){ //se pasa el id de la cita y su estado  
                    formulariopagar();
                    cargardatoscliente(e);
                }
            });
        });

        function formulariopagar(){
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Terminar Cita',
                html: `<form class="formulario formclientes" action="/admin/citas/finalizar?pagina=1" method="POST">
                            <input type="hidden" name="id" value="" >
                            <input type="hidden" name="valor_servicio" value="" >
                            <input type="hidden" name="valordcto" value="0" >

                            <p class="orden"></p>
                            <span class="nameuser"></span>
                            <span class="nameservice"></span>
                            <span class="precio"></span>

                            <div class="">

                            <div class="formulario__campo">
                                <label class="formulario__label" for="dcto">Aplicar Dcto:</label>
                                <select class="formulario__select" name="dcto" id="dcto">
                                    <option value="" disabled selected> Seleccionar Dcto</option>
                                    <option value="0"> Sin Descuento</option>
                                </select> 
                            </div>

                            <div class="formulario__campo">
                                <label class="formulario__label" for="total">Total: </label>
                                <input class="formulario__input" type="number" id="total" name="total" value="" readonly required>
                            </div>
                            
                            <div class="formulario__campo-2r">
                                <div class="formulario__campo">
                                    <label class="formulario__label" for="recibido">Recibido:</label>
                                    <input class="formulario__input" type="number" id="recibido" name="recibido" value="" required>
                                </div>
                                <div class="formulario__campo">
                                    <label class="formulario__label" for="devolucion">Devolucion:</label>
                                    <input class="formulario__input" type="number" id="devolucion" name="devolucion" value="" readonly required>
                                </div>
                            </div>
                            
                            <div class="formulario__campo">
                                <label class="formulario__label" for="nota">Nota:</label>
                                <div class="formulario__dato">
                                    <input class="formulario__input" type="text" placeholder="Nota del pago" id="nota" name="nota" value="">
                                    <label data-num="64" class="count-charts" for="">64</label>
                                </div>
                            </div>
                            
                            <div class="formulario__campo">
                                <label class="formulario__label" for="descuento">Imprimir Recibo: </label>
                                <div class="formulario__campo-2r">
                                    <div class="formulario__imprimir">
                                        <label class="formulario__label" for="imprimir_si">Si:</label>
                                        <input id="imprimir_si" type="radio" value="1" name="imprimir">
                                    </div>
                                    <div class="formulario__imprimir">
                                        <label class="formulario__label" for="imprimir_no">No:</label>
                                        <input id="imprimir_no" type="radio" value="0" name="imprimir" checked>
                                    </div>
                                </div>
                            </div>
                            </div>
    
                            <input class="clientes-btn" type="submit" value="Pagar">
                       </form>`,
                showCancelButton: false,
                showConfirmButton: false,  
            });
            document.querySelector('#recibido').addEventListener('input', calculo);
            countchars();
        }

        function cargardatoscliente(e){
            const dcto = e.target.parentElement.dataset.dctogeneral;
            const tr = e.target.parentElement.parentElement.parentElement;
            const idcita = tr.children[0].textContent;
            const nombre = tr.children[1].textContent;
            const servicio = tr.children[3].textContent;
            valueservice = tr.children[3].dataset.precio;

            document.querySelector('input[name=id]').value = idcita;  //id de la cita
            document.querySelector('input[name=valor_servicio]').value = valueservice;
            document.querySelector('.orden').textContent = 'Orden: '+idcita;
            document.querySelector('.nameuser').textContent = nombre;
            document.querySelector('.nameservice').textContent = servicio;
            document.querySelector('.precio').textContent = 'Precio: $'+valueservice;

            const selectdcto = document.querySelector('#dcto');
            const option = document.createElement('option');
            option.textContent = dcto+'%';
            option.value = dcto;
            selectdcto.appendChild(option);
            selectdcto.addEventListener('change', aplicardcto);

            const total = document.querySelector('#total');
            total.value = valueservice;
            total.addEventListener('input', calculo);
        }

        function aplicardcto(e){
            document.querySelector('#total').value = valueservice; //se reinicia el valor original del servicio
            const dcto = parseInt(e.target.value);
            const total = parseInt(document.querySelector('#total').value);
            document.querySelector('#total').value = total - ((dcto*total)/100);
            document.querySelector('#valordcto').value = total - document.querySelector('#total').value;
            calculo();
        }

        function calculo(){
            const devolucion = document.querySelector('#devolucion');
            const inputtotal = parseInt(document.querySelector('#total').value);
            const recibido = parseInt(document.querySelector('#recibido').value); 
                if(recibido>=inputtotal){
                   devolucion.value = recibido-inputtotal;
                   //devolucion.style.color = "rgb(240, 101, 72)"; 
                }else{
                    devolucion.value = '';
                }
        }

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