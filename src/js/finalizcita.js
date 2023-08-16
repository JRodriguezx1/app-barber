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
                if(estado === "Pendiente" || estado === "Out")cancelarcita(id, tr.children[7]); //se pasa el id de la cita y su estado  
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
                if(estado === "Pendiente"){   
                    formulariopagar();
                    obtenermediospago();
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
                html: `<form class="formulario modalform" action="/admin/citas/finalizar?pagina=1" method="POST">
                            <input type="hidden" name="id" value="" >
                            <input type="hidden" name="valor_servicio" value="" >
                            <input type="hidden" name="idempleado" value="" >
                            <input type="hidden" name="valordcto" value="0" >
                            <input type="hidden" name="promodcto" value="0" >
                            <input type="hidden" name="total" value="" >

                            <p class="orden"></p>
                            <span class="nameuser"></span>
                            <span class="nameservice"></span>
                            <span class="precio"></span>

                            <div class="">

                            <div class="formulario__campo">
                                <label class="formulario__label" for="valorpromodcto">Aplicar Promocion:</label>
                                <select class="formulario__select" name="valorpromo" id="valorpromodcto">
                                    <option value="" disabled selected> Seleccionar Dcto</option>
                                    <option data-dcto="0" value="0"> Sin Descuento</option>
                                </select> 
                            </div>

                            <div class="formulario__campo">
                                <label class="formulario__label" for="totalpagar">Total A Pagar: </label>
                                <input class="formulario__input" type="number" id="totalpagar" name="totalpagar" value="" readonly required>
                            </div>

                            <div class="formulario__campo">
                                <label class="formulario__label" for="mediopago">Elegir Medio De Pago:</label>
                                <select class="formulario__select" name="idmediospago" id="mediopago">
                                    <option value="" disabled selected> Seleccionar medio pago</option>
                                </select> 
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

        ///////////////////////////traer los medios de pago /////////////////////////
        const obtenermediospago = async()=>{
            try {
                const url = "/admin/api/getmediospago";
                const respuesta = await fetch(url);
                const mediospago = await respuesta.json();
                cargarmediospago(mediospago);
            } catch (error) {
                console.log(error);
            }
        }

        function cargarmediospago(mediospago){
            const selectmediospago = document.querySelector('#mediopago');
            mediospago.forEach(element => {
                if(element.estado==='1'){
                    const option = document.createElement('OPTION');
                    option.value = element.id;
                    option.textContent = element.mediopago;
                    selectmediospago.appendChild(option);
                }
            });
        }

        function cargardatoscliente(e){
            const promodcto = e.target.parentElement.dataset.promodcto||0; //dcto en porcentaje
            const valorpromodcto = e.target.parentElement.dataset.promodctovalor||0;  //dcto de promocion en valor
            const tr = e.target.parentElement.parentElement.parentElement;
            const idcita = tr.children[0].textContent;
            const nombre = tr.children[1].textContent;
            const servicio = tr.children[3].textContent;
            valueservice = tr.children[3].dataset.precio;

            document.querySelector('input[name=id]').value = idcita;  //id de la cita
            document.querySelector('input[name=valor_servicio]').value = valueservice;
            document.querySelector('input[name=idempleado]').value = tr.children[4].dataset.idempleado;
            document.querySelector('.orden').textContent = 'Orden: '+idcita;
            document.querySelector('.nameuser').textContent = nombre;
            document.querySelector('.nameservice').textContent = servicio;
            document.querySelector('.precio').textContent = 'Precio: $'+valueservice;

            const selectdcto = document.querySelector('#valorpromodcto');
            if(parseInt(promodcto)){
                const option = document.createElement('option');
                option.textContent = promodcto+'%'+' - '+'$'+valorpromodcto;
                option.value = valorpromodcto; //dcto en valor
                option.dataset.dcto = promodcto;
                selectdcto.appendChild(option);
            }
            selectdcto.addEventListener('change', aplicarpromo);
            const totalpagar = document.querySelector('#totalpagar');
            totalpagar.value = valueservice;
            totalpagar.addEventListener('input', calculo);
        }

        function aplicarpromo(e){
            document.querySelector('#totalpagar').value = valueservice; //se reinicia el valor original del servicio
            const valorpromodcto = parseInt(e.target.value);
            const totalpagar = parseInt(document.querySelector('#totalpagar').value);
            document.querySelector('#totalpagar').value = totalpagar - valorpromodcto;
            const promodcto = e.target.options[this.options.selectedIndex].dataset.dcto;
            document.querySelector('input[name=promodcto]').value = promodcto;  //input hidden para enviar dcto en porcentaje a bd
            calculo();
        }

        function calculo(){
            const valordcto = document.querySelector('input[name=valordcto]'); //para dcto manual
            const inputtotalpagar = parseInt(document.querySelector('#totalpagar').value);
            const devolucion = document.querySelector('#devolucion');
            const recibido = parseInt(document.querySelector('#recibido').value); 
            if(recibido>=inputtotalpagar){
                devolucion.value = recibido-inputtotalpagar;
                //devolucion.style.color = "rgb(240, 101, 72)"; 
                valordcto.value = 0; //descuento manual
            }else{
                devolucion.value = 0;
                valordcto.value = inputtotalpagar - recibido; //descuento manual
            }
            document.querySelector('input[name=total]').value = recibido - parseInt(devolucion.value);
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