(function(){
    if(document.querySelector('.facturacion')){
        let servicios, emplserv;
        (async ()=>{
            try {
                const url = "/admin/api/getservices"; //llamado a la API REST
                const respuesta = await fetch(url); 
                servicios = await respuesta.json(); 
            } catch (error) {
                console.log(error);
            }
        })();

        (async ()=>{
            try {
                const url = "/admin/api/getemployee_services"; //llamado a la API REST para trer la relacion de los servicios con sus profesionales
                const respuesta = await fetch(url); 
                emplserv = await respuesta.json();
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
            formulariopagar();
            obtenermediospago();
            countchars();
        });
        

        function formulariopagar(){
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Pago De Servicio',
                html: `<form class="formulario modalform" action="/admin/facturacion" method="POST">
                            <input type="hidden" name="total" value="" >
                            <input type="hidden" name="dcto" value="0">
                            <input type="hidden" name="valordcto" value="0">

                            <div class="formulario__campo">
                                <label class="formulario__label" for="servicios">Servicios:</label>
                                <select class="formulario__select" name="idservicio" id="selectservice" required>
                                    <option value="" disabled selected> Seleccionar Servicio</option>
                                </select> 
                            </div>

                            <div class="formulario__campo">
                                <label class="formulario__label" for="empleado">Empleado:</label>
                                <select class="formulario__select" name="idempleado" id="selectemployee" required>
                                    <option value="" disabled selected> Seleccionar Empleado </option>
                                </select> 
                            </div>

                            <div class="formulario__campo">
                                <label class="formulario__label" for="valor_servicio">Total a pagar:</label>
                                <input class="formulario__input" type="number" id="valor_servicio" name="valor_servicio" value="" readonly required>
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
    
                            <input class="clientes-btn" type="submit" value="Pagar">
                       </form>`,
                showCancelButton: false,
                showConfirmButton: false,  
            });
            document.querySelector('#recibido').addEventListener('input', calculo);
            cargarservicios();
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

        function cargarservicios(){
            const selectservice = document.querySelector('#selectservice');
            servicios.forEach(element => {
                const option = document.createElement('OPTION');
                option.value = element.id;
                option.textContent = element.nombre;
                selectservice.appendChild(option);
            });
            selectservice.addEventListener('change', e=>{
                cargarempleado(e.target.value);
                cargarvalorservicio(e);
            });
        }

        function cargarempleado(id){
            const selectemployee = document.querySelector('#selectemployee');
            const empleados = emplserv.filter(Element => Element.idservicio === id);
            borrarhtml(selectemployee);
            const option = document.createElement('OPTION');
            option.value = '';
            option.textContent = ' Seleccionar Empleado ';
            option.selected = true;
            option.disabled = true;
            selectemployee.appendChild(option);
            empleados.forEach(element => {
                const option = document.createElement('OPTION');
                option.value = element.idempleado; //id de la tabla empleados
                option.textContent = element.nombre;
                option.dataset.id = element.id;  //id de la tabla empserv
                selectemployee.appendChild(option); 
            });
        }

        function cargarvalorservicio(e){
            const valor_servicio = document.querySelector('#valor_servicio');
            const valorservice = servicios.filter(element =>element.id == e.target.value);
            valor_servicio.value = valorservice[0].precio;
            if(!valorservice[0].precio)document.querySelector('#valor_servicio').readOnly = false;
            document.querySelector('#recibido').value = '';
            document.querySelector('#devolucion').value = '';
        }

        function calculo(){
            const valordcto = document.querySelector('input[name=valordcto]'); //para dcto manual
            const valorservicio = parseInt(document.querySelector('#valor_servicio').value);
            const devolucion = document.querySelector('#devolucion');
            const recibido = parseInt(document.querySelector('#recibido').value); 
                if(recibido>=valorservicio){
                   devolucion.value = recibido-valorservicio;
                   valordcto.value = 0;
                   //devolucion.style.color = "rgb(240, 101, 72)"; 
                }else{
                    devolucion.value = 0;
                    valordcto.value = valorservicio - recibido; //descuento manual
                }
                document.querySelector('input[name=total]').value = recibido - parseInt(devolucion.value);
        }

        function borrarhtml(elemento){
            while(elemento.firstElementChild)elemento.removeChild(elemento.firstElementChild);
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