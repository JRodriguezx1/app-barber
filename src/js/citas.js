(function(){

    if(document.querySelector('#dashboardcitas')){//en admin/citas/index.php
        
        const filtros = document.querySelector('#filtros-personalizado');
        const selectfiltro = document.querySelector('#selectprofesional');
        const selectfecha = document.querySelector('#fecha'); //filtro de solo fechas
        const crearcita = document.querySelector('#crearcita');
        const programar = document.querySelectorAll('.programar');
    
        if(filtros){
            filtros.addEventListener('click', ()=>{
                let idprofesional = selectfiltro.options[selectfiltro.options.selectedIndex].value; //toma el id del select profesional
        
                if(idprofesional){
                    Swal.fire({
                        customClass: {
                        //confirmButton: 'sweetbtnconfirm',
                        // cancelButton: 'sweetbtncancel'
                        },
                        title: 'Consulta el profesional por fecha:',
                        html: `<form class="formulario" action="/admin/citas/consultaxprofesxfecha?pagina=1" method="POST">    
                                    <div class="formulario__campo">
                                        <input class="formulario__input" id="fecha" type="date" name="fecha" value=" " required>
                                    </div>
                                    <input type="hidden" name="profesional" value="${idprofesional}">
                                    <input class="btnsmall" type="submit" value="Consultar">
                            </form>`,
                        showCancelButton: false,
                        showConfirmButton: false,
                        //confirmButtonText: 'Aceptar',
                        //cancelButtonText: 'Cancelar',
                    })/*.then((result) => {
                        if (result.isConfirmed) {
                        Swal.fire('Actualizando', '', 'success')
                        //modificar el servicio
                        } 
                    })*/
                }
            });
        }

        selectfecha.addEventListener('input', e => {
            /*(async ()=>{
                const datos = new FormData();
                datos.append('fecha', e.target.value);
                try {
                    const url = "/admin/api/filtroxfecha";
                    const respuesta = await fetch(url, {method: 'POST', body: datos}); 
                    const resultado = await respuesta.json();  
                    console.log(resultado);
                } catch (error) {
                    console.log(error);
                }
            })();*/
            window.location = `/admin/citas/filtroxfecha?pagina=1&fecha=${e.target.value}`;
        });


        /////////////////////////////////////////////////////////////////////////////////////////

        let gettimeservice=0, horasdisponibles = [], horacitas = [];
        //const divhoras = document.querySelector('#horas'); //donde se poenen las horas

        let usuarios, servicios, emplserv, fechadesc, malla, citas;
        let onlyfechadesc, onlymalla, onlycitas;

        (async ()=>{
            try {
                const url = "/admin/api/gettimeservice"; //llamado a la API REST
                const respuesta = await fetch(url); 
                gettimeservice = await respuesta.json(); //traer el tiempo de duracion del servicio
            } catch (error) {
                console.log(error);
            }
        })();

        (async ()=>{
            try {
                const url = "/admin/api/allclientes"; //llamado a la API REST
                const respuesta = await fetch(url); 
                usuarios = await respuesta.json(); 
            } catch (error) {
                console.log(error);
            }
        })();
                
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

        (async ()=>{
            try {
                const url = "/admin/api/getfechadesc"; //llamado a la API REST para trer todas las fechas de descanso de todos los empleados.
                const respuesta = await fetch(url); 
                fechadesc = await respuesta.json(); 
            } catch (error) {
                console.log(error);
            }
        })();

        (async ()=>{
            try {
                const url = "/admin/api/getmalla"; //llamado a la API REST para trer toda la malla
                const respuesta = await fetch(url); 
                malla = await respuesta.json();   
            } catch (error) {
                console.log(error);
            }
        })();

        (async ()=>{
            try {
                const url = "/admin/api/getcitas"; //llamado a la API REST para trer toda las citas desde la fecha actual hasta posterior
                const respuesta = await fetch(url); 
                citas = await respuesta.json(); //se trae las citas de hoy en el backend se trae hora y min ej: 08:00
            } catch (error) {
                console.log(error);
            }
        })();

        
        crearcita.addEventListener('click', (e)=>{ ////crear cita
            e.preventDefault();
            const usuario = `<div class="formulario__campo">
                                <label class="formulario__label" for="usuario">Seleccione Usuario: </label>
                                <select class="formulario__select" name="id_usuario" id="usuario" required>
                                    <option value="" disabled selected> -Selecionar- </option>
                                </select>
                            </div>`;
            formulariocliente('Crear Cita', 'Crear', '/admin/citas/crear?pagina=1', usuario);
            cargarusuarios();
            cargaservicios();
            eventofecha();
        });
        programar.forEach(element =>{
            element.addEventListener('click', (e)=>{ ///programar cita
                const valstatus = e.target.parentElement.parentElement.previousElementSibling.textContent;
                const usuario = `<span class="namecliente"></span><span id="horacita"></span>
                                <input type="hidden" id="id" name="id" value="" >`;
                if(valstatus === "Pendiente" || valstatus === "Out")
                {   formulariocliente('Reprogramar Cita A:', 'Enviar', '/admin/citas?pagina=1', usuario);
                    cargarcita(e.target);
                }
            });
        });
        

        function formulariocliente(titulo, submit, action, usuario){
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: titulo,
                html: `<form class="formulario modalform" action="${action}" method="POST">
                            <input type="hidden" id="id_empserv" name="id_empserv" value="" >
                            <input type="hidden" id="hora_fin" name="hora_fin" value="" >

                            <div class="">
                                ${usuario}
                                <div class="formulario__campo">
                                    <label class="formulario__label" for="servicios">Seleccione Servicio: </label>
                                    <select class="formulario__select" name="idservicio" id="servicios" required>
                                        <option value="" disabled selected> -Selecionar- </option>
                                    </select>
                                </div>
                                <div class="formulario__campo">
                                    <label class="formulario__label" for="professionals">Seleccione Profesional: </label>
                                    <select class="formulario__select" name="nameprofesional" id="professionals" required>
                                        <option value="" disabled selected> -Selecionar- </option>
                                    </select>
                                </div>
                                <div class="formulario__campo">
                                    <label class="formulario__label" for="date">Seleccionar Fecha:</label>
                                    <input class="formulario__input" id="date" name="fecha_fin" type="date" disabled required>
                                </div>
                            </div>
                            
                            <div class="cliente__campohoras" id="horas">

                            </div>
                            <input class="clientes-btn" type="submit" value="${submit}">
                       </form>`,
                showCancelButton: true,
                showConfirmButton: false,  
            });
            //deshabilitarfechaanterior();
        }


        function cargarusuarios(){
            const inputusuario = document.querySelector('#usuario');
            usuarios.forEach(element => {
                const option = document.createElement('OPTION');
                option.value = element.id;
                option.textContent = element.nombre+' '+element.apellido;
                inputusuario.appendChild(option);
            }); 
        }

        function cargaservicios(){
            const selectservicios = document.querySelector('#servicios');
            servicios.forEach(element => {
                const option = document.createElement('OPTION');
                option.value = element.id; //id de la tabla servicio
                option.textContent = element.nombre;
                selectservicios.appendChild(option);
            });
            selectservicios.addEventListener('change', e=>cargarprofesionales(e.target.value));
        }

        function cargarprofesionales(id){
            const professionals = document.querySelector('#professionals');
            const empleados = emplserv.filter(Element => Element.idservicio === id);
            borrarhtml(professionals);
            document.querySelector('#date').disabled = true;
            borrarhtml(document.querySelector('#horas'));
            const option = document.createElement('OPTION');
            option.value = '';
            option.textContent = ' -Selecionar- ';
            option.selected = true;
            option.disabled = true;
            professionals.appendChild(option);
            empleados.forEach(element => {
                const option = document.createElement('OPTION');
                option.value = element.idempleado; //id de la tabla empleados
                option.textContent = element.nombre;
                option.dataset.id = element.id;  //id de la tabla empserv
                professionals.appendChild(option); 
            });
            professionals.addEventListener('change', (e)=>{
                document.querySelector('#date').disabled = false;
                document.querySelector('#date').value = '';
                deshabilitarfechaanterior();
                borrarhtml(document.querySelector('#horas')); //borra en donde se ponen las horas
            });
        }

        function eventofecha(){
            const select_date = document.querySelector('#date');
            select_date.addEventListener('input', (evento)=> {
                /////////borrar el campo de las horas//////////
                const divhoras = document.querySelector('#horas'); //donde se ponen las horas
                borrarhtml(divhoras);
                
                const dia = new Date(evento.target.value); //.getUTCDay();  = se estrae el dia de la semana
                validarfechaydia(evento.target.value, dia.getUTCDay());
            });
        }

        
        function validarfechaydia(fecha, dia){
            //al seleccionar fecha ya se ha seleccionado el profesional, y el profesional me contiene su id y el id de la tabla empserv
            const professionals = document.querySelector('#professionals');
            const idempleado = professionals.options[professionals.options.selectedIndex].value;
             ////cargar el input type hidden name=id_empserv
             document.querySelector('#id_empserv').value = professionals.options[professionals.options.selectedIndex].dataset.id;

            onlymalla = malla[`empleado_${idempleado}`]; //obtiene solo la malla de ese empleado
            onlyfechadesc = fechadesc.filter(element => idempleado === element.empleado_id);
            onlycitas = citas.filter(cita => (cita.idempleado === idempleado&&cita.fecha_fin === fecha&&cita.estado === "Pendiente")); //obtengo las citas deacuerdo al profesional y fecha seleccionada y pendiente
            console.log(citas);
            const r1 = onlyfechadesc.some(element => element.fecha === fecha);
            if(r1){
                Swal.fire(
                    'Este dia no esta disponible!',
                    'You clicked the button!',
                    'error'
                  )
            }else{
                const r2 = onlymalla.filter(element => element.id_dia == (dia===0?7:dia));
                if(r2.length){ //es pq el dia si esta en la malla
                    gethoras(r2[0]); //se envia solo el dia seleccionado (obj)
                }
                else{ //dia no esta en la malla
                    Swal.fire(
                        'Este dia no esta disponible!',
                        'You clicked the button!',
                        'error'
                    )
                }
            }
        }

        function gethoras(r2){
            let horario1 = [], horario2 = [];

            horario1 = [...horario1, r2.inicioturno, r2.inidescanso];
            horario2 = [...horario2, r2.findescanso, r2.finturno];

            // me traigo un arreglo de la tabla citas de la DB con su hora segun fecha seleccionada y profesional seleccionado
            //// comparo cada hora del arreglo traio con r2.iniciodescanso y si es menor lo agrego al arreglo horario1
            ///luego comparo con r2.finturno y si es mayor lo agrego a arreglo horario2

            horario1 = horario1.map(element =>{
                element = element.substring(0,2)+':'+element.substring(2,4);
                return element;
            });
            horario2 = horario2.map(element =>{
                element = element.substring(0,2)+':'+element.substring(2,4);
                return element;
            });
            //obtengo las horas formato 24 de la bd segun fecha y profesional
            //horacitas = onlycitas.map(element =>  element.hora_fin.slice(3));
            horacitas = onlycitas.map(element =>  element.hora_fin);

            for(let i = 0; i<horario1.length-1; i++){
            calcularhorarios(horario1[i], horario1[i+1]);
            }
            for(let i = 0; i<horario2.length-1; i++)
            calcularhorarios(horario2[i], horario2[i+1]);
            
           console.log(horasdisponibles);
           //podemos validar horasdisponibles si esta vacio es pq no hay espacion o agenda llena
           //mostrar alerta y no permitir pedir cita, por ahora se valida en backend
           imprimirhorashtml();
        }

        function calcularhorarios(horaInicio, horaFin) {
            const horarioActual = new Date(`01/01/2000 ${horaInicio}`);
            const horarioFinal = new Date(`01/01/2000 ${horaFin}`);
          
            while (horarioActual < horarioFinal) {
              const hora = horarioActual.getHours().toString().padStart(2, '0');
              const minutos = horarioActual.getMinutes().toString().padStart(2, '0');
              //console.log(`${hora}:${minutos}`);
              const validate = horacitas.includes(`${hora}:${minutos}`); //horacitas arreglo con las horas ya seleccionadas
              if(!validate){
                  //hora1 = ${hora}:${minutos};
                  //horatemp = hora1; + min-servicio, 
                  //preguntar si horatemp es menor o igual al elemento del arreglo horacitas
                  //si es si
                  //imprimir hora1, hacer hora1 = horatemp;
                  //si es no
                  //hora1 = elemento del arreglo + su duracion-serv
                  //aumentar en uno al arreglo de horacitas
                  //valido arribo si es si, vuelvo y repito esto
                  
                

              horasdisponibles = [...horasdisponibles, `${hora}:${minutos}`];
              }
          
              horarioActual.setTime(horarioActual.getTime() + gettimeservice * 60000); // Agregar 30 minutos al horario actual, 60.000 milisegundos tiene un minuto
            }
        }

        function imprimirhorashtml(){
            const divhoras = document.querySelector('#horas'); //donde se ponen las horas
            horasdisponibles.forEach(hora => {
                const divhora = document.createElement('DIV');
                divhora.classList.add("cliente__hora");
                const parrafohora = document.createElement('P');
                parrafohora.textContent = hora;
                //parrafohora.dataset.hora = hora.replace(':', '');
                parrafohora.dataset.hora = hora;
                parrafohora.onclick = seleccionarhora;
                parrafohora.classList.add("texthora");
                divhora.appendChild(parrafohora);
                divhoras.appendChild(divhora);
            });
        }

        function seleccionarhora(e){
            const deshabilthoraprevia = document.querySelector('.horaselect');
            if(deshabilthoraprevia)
                deshabilthoraprevia.classList.remove('horaselect');
            e.target.parentElement.classList.add('horaselect'); //class agregada en dash-cliente/_cliente.scss
            //cargar el input type hidden name = hora_fin del formulario para el envio
            document.querySelector('#hora_fin').value = e.target.textContent;
        }

        //////////////////////////////reprogramar//////////////////////////////////


        function cargarcita(element){
            cargaservicios();
            const tr = element.parentElement.parentElement.parentElement;
            const idcita = tr.children[0].textContent;
            const nombre = tr.children[1].textContent;
            const nameservice = tr.children[3].textContent;
            const namepro = tr.children[4].textContent;
            const fechacita = tr.children[5].textContent;
            const horacita = tr.children[6].textContent;
            const namecliente = document.querySelector('.namecliente');
            const selectservice = document.querySelector('#servicios'); //del formulario de arriba
            const professionals = document.querySelector('#professionals'); //del formulario de arriba
            const inputdate = document.querySelector('#date'); //del formulario de arriba

            namecliente.textContent = nombre+' - ';
            document.querySelector('#horacita').textContent = horacita;
            document.querySelector('#id').value = idcita;
            document.querySelector('#hora_fin').value = horacita;
            
            for(let i = 0; i<selectservice.options.length; i++)
                if(selectservice.options[i].textContent === nameservice)
                    selectservice.options[i].selected = true;

            const id = selectservice.options[selectservice.options.selectedIndex].value;
            cargarprofesionales(id);

            for(let i = 0; i<professionals.options.length; i++){
                if(professionals.options[i].textContent === namepro){
                    professionals.options[i].selected = true;
                    document.querySelector('#id_empserv').value = professionals.options[i].dataset.id; //se carga el id_empserv
                }
            }

            inputdate.value = fechacita;
            eventofecha();

            const dia = new Date(fechacita);
            validarfechaydia(fechacita, dia.getUTCDay());
        }
        

        function borrarhtml(elemento){
            horasdisponibles = [];
            while(elemento.firstElementChild)elemento.removeChild(elemento.firstElementChild);
        }


        function deshabilitarfechaanterior(){
            const inputfecha = document.querySelector('#date');
            const fechaactual = new Date();  //en fecha actual esta la fecha actual con hora
            const year = fechaactual.getFullYear(); // obiene el año actual
            const mes = fechaactual.getMonth() + 1;  //obtiene el mes
            const dia = fechaactual.getDate(); //obitien el dia actual se le suma el dia siguiente
            let deshabilitarfecha = `${year}-${mes}-${dia}`;
            if(mes<10){ deshabilitarfecha = `${year}-0${mes}-${dia}`; }
            if(mes<10&&dia<10){ deshabilitarfecha = `${year}-0${mes}-0${dia}`; }
            if(mes>=10&&dia<10){ deshabilitarfecha = `${year}-${mes}-0${dia}`; }
            inputfecha.min = deshabilitarfecha; //al input fecha se le agrega atributo min
        }

    }

})();