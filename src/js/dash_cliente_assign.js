(function(){
    if(document.querySelector('#dash-cliente')){

        const telcliente = document.querySelector('#telcliente').textContent;
        let gettimeservice=0, horasdisponibles = [], horacitas = [];
        const divhoras = document.querySelector('#horas'); //donde se ponen las horas

        function calcularhorarios(horaInicio, horaFin) {
            const horarioActual = new Date(`01/01/2000 ${horaInicio}`);
            const horarioFinal = new Date(`01/01/2000 ${horaFin}`);
            horarioFinal.setTime(horarioFinal.getTime() - Math.floor(gettimeservice)*60000);
          
            while (horarioActual <= horarioFinal) {
              const hora = horarioActual.getHours().toString().padStart(2, '0');
              const minutos = horarioActual.getMinutes().toString().padStart(2, '0');
              //console.log(`${hora}:${minutos}`);
              const validate = horacitas.includes(`${hora}:${minutos}`);
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


        const professionals = document.querySelector('#profesional');
        const select_date = document.querySelector('#date');
        let fechadesc, malla, citas;
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

        /*(async ()=>{
            try {
                const url = "/admin/api/getcitas"; //llamado a la API REST para trer toda las citas desde la fecha actual hasta posterior
                const respuesta = await fetch(url); 
                citas = await respuesta.json();
                
            } catch (error) {
                console.log(error);
            }
        })(); */
        
        const obtenercitas = async()=>{
            try {
                const url = "/admin/api/getcitas"; //llamado a la API REST para trer toda las citas desde la fecha actual hasta posterior, en controladorcliente.php
                const respuesta = await fetch(url); 
                citas = await respuesta.json();
            } catch (error){
                console.log(error);
            }
        }
        obtenercitas();

        setInterval(() => {
            obtenercitas();
            if(!select_date.value == "" && document.querySelector('.cliente__hora')){
                const dia = new Date(select_date.value);
                borrarhtml(divhoras);
                validarfechaydia(select_date.value, dia.getUTCDay());
            }
            //gethoras(r2)
        }, 3100);

        ///////////seleccionar empleado o profesional //////////////
        professionals.addEventListener('change', (e)=>{
            borrarhtml(divhoras);
            select_date.disabled = false;
            select_date.value = "";
            onlymalla = malla[`empleado_${e.target.value}`]; //obtiene solo la malla de ese empleado
            onlyfechadesc = fechadesc.filter(element => e.target.value === element.empleado_id);
        });

        select_date.addEventListener('input', (evento)=> {
            /////////borrar el campo de las horas//////////
            borrarhtml(divhoras);
            
            const dia = new Date(evento.target.value); //.getUTCDay();  = se estrae el dia de la semana
            validarfechaydia(evento.target.value, dia.getUTCDay());
            /*
            const opciones = {
            weekday: 'long',
            year: 'numeric',
            month: 'long'
            }
            console.log(dia.toLocaleDateString('es-ES', opciones));*/
        });
        

        function validarfechaydia(fecha, dia){
            //al seleccionar fecha ya se ha seleccionado el profesional, y el profesional me contiene su id y el id de la tabla empserv
            const idempleado = professionals.options[professionals.options.selectedIndex].value;
            onlycitas = citas.filter(cita => (cita.idempleado === idempleado&&cita.fecha_fin === fecha&&cita.estado === "Pendiente")); //obtengo las citas deacuerdo al profesional y fecha seleccionada y pendiente
            
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
            //horacitas = onlycitas.map(element =>  element.hora_fin.slice(3)); //obtengo las horas formato 24 de la bd segun fecha y profesional
            horacitas = onlycitas.map(element =>  element.hora_fin);

            for(let i = 0; i<horario1.length-1; i++){
            calcularhorarios(horario1[i], horario1[i+1]);
            }
            for(let i = 0; i<horario2.length-1; i++)
            calcularhorarios(horario2[i], horario2[i+1]);
            
           console.log(horasdisponibles);
           imprimirhorashtml();
        }


        function imprimirhorashtml(){
            horasdisponibles.forEach(hora => {
                const divhora = document.createElement('DIV');
                divhora.classList.add("cliente__hora");
                const parrafohora = document.createElement('P');
                parrafohora.textContent = hora;
                //parrafohora.dataset.hora = hora.replace(':', '');
                parrafohora.dataset.hora = hora;
                parrafohora.onclick = reservarcita;
                parrafohora.classList.add("texthora");
                divhora.appendChild(parrafohora);
                divhoras.appendChild(divhora);
            });
        }

        function reservarcita(e){ 
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Desea Reservar La Cita?',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                
            }).then((result) => {
                if (result.isConfirmed) {
                    let r = enviarcita(e.target.dataset.hora);
                    if(r){
                        Swal.fire('Cita Programada', '', 'success')
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }else{
                        Swal.fire('No fue posible agendar cita, Intentalo de nuevo', '', 'error')
                    }
                } 
            })
        }

        async function enviarcita(horacita){
            const datos = new FormData();
            const id_empserv = professionals.options[professionals.options.selectedIndex]; //id de la relacion empleado_servicio
            const id_servicio = document.querySelector('#servicio').options[document.querySelector('#servicio').options.selectedIndex].value;
            const hoy = new Date();

            datos.append('telcliente', telcliente);
            datos.append('id_empserv', id_empserv.dataset.id);
            //datos.append('fecha_inicio', hoy.toLocaleDateString().replace(/\//g, '-')); //fecha actual
            datos.append('fecha_inicio', hoy.getFullYear()+'-'+(hoy.getMonth()+1)+'-'+hoy.getDate()); //fecha actual de toma de servicio
            datos.append('fecha_fin', select_date.value);
            datos.append('hora', hoy.toLocaleTimeString([], {hour12: false})); //hora actual de toma de servicio
            datos.append('hora_fin', horacita);
            datos.append('idservicio', id_servicio);
            datos.append('nameprofesional', id_empserv.value);
            try {
                const url = "/admin/api/enviarcita";  //llama api en controladorcliente.php
                const respuesta = await fetch(url, {method: 'POST', body: datos}); 
                const resultado = await respuesta.json();  
                if(resultado[0]){
                    return true;
                }
                else{
                    return false;
                }
            } catch (error) {
                console.log(error);
            }
        }


        function borrarhtml(elemento){
            horasdisponibles = [];
            while(elemento.firstElementChild)elemento.removeChild(elemento.firstElementChild);
        }
    }
})();