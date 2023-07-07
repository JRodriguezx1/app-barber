(function(){
    if(document.querySelector('.facturacion')){
        let servicios;
        (async ()=>{
            try {
                const url = "/admin/api/getservices"; //llamado a la API REST
                const respuesta = await fetch(url); 
                servicios = await respuesta.json(); 
            } catch (error) {
                console.log(error);
            }
        })();

        const filtrarfecha = document.querySelector('#fecha');
        filtrarfecha.addEventListener('input', (e)=>{
            window.location = `/admin/facturacion/buscarxfecha?pagina=1&fecha=${e.target.value}`;
        });

        const pagar = document.querySelector('#pagar');
        pagar.addEventListener('click', ()=>{
            formulariopagar(0);
            countchars();
        });
        

        function formulariopagar(id){
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Pago De Servicio',
                html: `<form class="formulario formclientes" action="/admin/facturacion" method="POST">
                            <input type="hidden" name="idcita" value="1">
                            <input type="hidden" name="valor_servicio" value="" >
                            <input type="hidden" name="dcto" value="0">
                            <input type="hidden" name="valordcto" value="0">

                            <div class="formulario__campo">
                                <label class="formulario__label" for="servicios">Servicios:</label>
                                <select class="formulario__select" name="idservicio" id="selectservice" required>
                                    <option value="" disabled selected> Seleccionar Servicio</option>
                                </select> 
                            </div>

                            <div class="formulario__campo">
                                <label class="formulario__label" for="total">Total a pagar:</label>
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
    
                            <input class="clientes-btn" type="submit" value="Pagar">
                       </form>`,
                showCancelButton: false,
                showConfirmButton: false,  
            });
            document.querySelector('#recibido').addEventListener('input', calculo);
            cargarservicios();
        }

        function cargarservicios(){
            const selectservice = document.querySelector('#selectservice');
            servicios.forEach(element => {
                const option = document.createElement('OPTION');
                option.value = element.id;
                option.textContent = element.nombre;
                selectservice.appendChild(option);
            });
            selectservice.addEventListener('change', e=>cargarvalorservicio(e));
        }
        function cargarvalorservicio(e){
            const total = document.querySelector('#total');
            const valorservice = servicios.filter(element =>element.id == e.target.value);
            total.value = valorservice[0].precio;
            document.querySelector('input[name=valor_servicio]').value = total.value;
            document.querySelector('#recibido').value = '';
            document.querySelector('#devolucion').value = '';
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
        //countchars();
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