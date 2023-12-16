////////////////////full calender//////////////////////

(function(){
  if(document.querySelector('.calendario')){

    const btnscrearcita = document.querySelectorAll('.btncrearcita');
    let totalcitas, citaspending, filtradas = [], usuarios, servicios, malla=[], emplserv, fechadesc, onlymalla;
    let Idpxtemp=0, gettimeservice=0, horasdisponibles = [], horacitas=[];
          
  (async ()=>{
      try {
          const [getservices, timeservice, allclientes, getmalla, getemplserv, getfechadesc] = await Promise.all([fetch("/admin/api/getservices"), fetch("/admin/api/gettimeservice"), fetch("/admin/api/allclientes"), fetch("/admin/api/malla"), fetch("/admin/api/getemployee_services"), fetch("/admin/api/getfechadesc")]); 
          servicios = await getservices.json(); 
          gettimeservice = await timeservice.json();
          usuarios = await allclientes.json();
          malla = await getmalla.json();
          emplserv = await getemplserv.json();
          fechadesc = await getfechadesc.json();
          cargarusuarios();
          cargaservicios();
          deshabilitarfechaanterior();
      } catch (error) {
          console.log(error);
      }
  })();

  (async ()=>{
    try{
        const url = "/admin/api/getallcitas"; //llamado a la API REST para traer todas las citas
        const respuesta = await fetch(url); 
        totalcitas = await respuesta.json(); 
        console.log(totalcitas);
        citaspending = totalcitas.filter(cita => cita.estado == "Pendiente");
        rendercalendar();
    } catch (error) {
        console.log(error);
    }
  })();

  function rendercalendar(){
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      theme: true,
      initialView: 'dayGridMonth',
      locale: 'es',
      events: citaspending,
      //[{title: "cita", start: "2023-11-24", display: "background", color: "#ff0000"}, {title: "lupe", start: "2023-11-27", display: "background"}, {title: "lupe", start: "2023-11-30", color: 'yellow',}],
      dateClick: (x)=>{  mostrarcitas(x.dateStr); }
    });
    calendar.render();
  }


  ////////////////// filtrar citas por estado de pendiente, finalizadas o todas//////////////////
  const filtros = document.querySelectorAll('.citas__ctrlsfiltros input[type="radio"]');  //seleccionamos los 3 inputs tipo radio
  filtros.forEach(inputradio=>{
    inputradio.addEventListener('input', (e)=>{filtrarcitas(e.target.value);});
  });

  function filtrarcitas(filtro){ //filtro puede tomar un string 'Todas', 'Finalizada', 'Pendiente'..
    filtradas = filtro=='Todas'?totalcitas:totalcitas.filter(elemento => elemento.estado == filtro);
    mostrarcitas(document.querySelector('.fechaformateada').dataset.fecha);
  }

  function mostrarcitas(x){
    if(!filtradas.length)filtradas = citaspending;
    document.querySelector('.fechaformateada').dataset.fecha = x;

    const fecha = new Date(x+'T00:00:00-05:00');
    //var opcionesDeFormato = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.querySelector('.diasemana').textContent = fecha.toLocaleDateString('es-ES', {weekday: 'long'});
    document.querySelector('.dia').textContent = fecha.toLocaleDateString('es-ES', {day: 'numeric'});
    document.querySelector('.mesaño').textContent = fecha.toLocaleDateString('es-ES', {month: 'long'})+" "+fecha.toLocaleDateString('es-ES', {year: 'numeric'});

    borrarhtml(document.querySelector('.citas__lista'));

    const citasxdia = filtradas.filter(cita => cita.start === x);

    citasxdia.forEach(cita => {
      const li = document.createElement('LI');
      li.classList.add("listacita");
      const divcontent = document.createElement('DIV');
      divcontent.classList.add('listacita-content');
      const divcol1 = document.createElement('DIV');
      divcol1.classList.add('col1');
      const divcol2 = document.createElement('DIV');
      divcol2.classList.add('col2');
      const nomrecliente = document.createElement('P');
      nomrecliente.classList.add('nombrecliente');
      nomrecliente.textContent = cita.nombrecliente;
      const fecha = document.createElement('P');
      fecha.classList.add('fecha');
      fecha.textContent = cita.start;
      const servicio = document.createElement('P');
      servicio.classList.add('servicio');
      servicio.textContent = cita.nameservicio;
      const estadocita = document.createElement('P');
      estadocita.classList.add('estadocita', cita.estado=='Pendiente'?'citapendiente':'citafinalizada');
      estadocita.textContent = cita.estado;
      const hora = document.createElement('P');
      hora.classList.add('hora');
      const citahora12 = new Date("2000-01-01T" + cita.hora_fin).toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
      hora.textContent = citahora12;
      const reprogramar = document.createElement('I');
      reprogramar.classList.add('fa-solid', 'fa-clock');
      reprogramar.id = "reprogramar";
      reprogramar.dataset.id = cita.id;
      
      divcol1.appendChild(nomrecliente);
      divcol1.appendChild(fecha);
      divcol1.appendChild(servicio);
      divcol2.appendChild(estadocita);
      divcol2.appendChild(hora);
      divcol2.appendChild(reprogramar);
      divcontent.appendChild(divcol1);
      divcontent.appendChild(divcol2);
      li.appendChild(divcontent);
      document.querySelector('.citas__lista').appendChild(li);
    });
    functReprogramar();
  }

////////////////////// modal dialog /////////////////////////
  const dialogo = document.getElementById("miDialogo");
  btnscrearcita.forEach(crear=>crear.addEventListener('click', (e)=>{
    e.preventDefault();
    dialogo.showModal();
    if(e.target.id==="crearcita"){
      document.querySelector('#cliente1').classList.remove('ocultar');
      document.querySelector('#cliente2').classList.add('ocultar');
      document.querySelector('#nombrecliente').removeAttribute("required");
      document.querySelector('#usuario').required = true;
      document.querySelector('#nousuario').setAttribute('disabled', true);
      limpiarformdialog();
    }else{
      document.querySelector('#cliente1').classList.add('ocultar');
      document.querySelector('#cliente2').classList.remove('ocultar');
      document.querySelector('#usuario').removeAttribute("required");
      document.querySelector('#nombrecliente').required = true;
      document.querySelector('#nombrecliente').readOnly = false; 
      document.querySelector('#nousuario').removeAttribute('disabled');
      limpiarformdialog();
    }
    document.addEventListener("click", cerrarDialogoExterno);
  }));
  function cerrarDialogoExterno(event) {
    if (event.target === dialogo || event.target.value === 'cancelar') {
      dialogo.close();
      document.removeEventListener("click", cerrarDialogoExterno);
    }
  }

  //////////////////////cargar usuarios//////////////////////
  function cargarusuarios(){
    const inputusuario = document.querySelector('#usuario');
    usuarios.forEach(element => {
        if(element.id!=2){  //no muestra el registro de Usuario No Registrado
            const option = document.createElement('OPTION');
            option.value = element.id;
            option.textContent = element.nombre+' '+element.apellido;
            inputusuario.appendChild(option);
        }
    });
    $('#usuario').select2({dropdownParent: $("#miDialogo"), width: '100%'}); 
  }

  //////////////////////cargar servicios//////////////////////
  function cargaservicios(){
    const selectservicios = document.querySelector('#servicios');
    servicios.forEach(element => {
        const option = document.createElement('OPTION');
        option.value = element.id; //id de la tabla servicio
        option.textContent = element.nombre;
        selectservicios.appendChild(option);
    });
    $('#servicios').select2({dropdownParent: $("#miDialogo"), width: '100%'});
    $("#servicios").on('change', e=>llamadoempleados(e.target.value));
  }

  function mostrarcampovalor(id){
    const divcampovalor = document.querySelector('.campovalor');
    servicios.forEach(element => {
      if(element.id === id){
        if(element.precio === null){
          divcampovalor.style = 'display: flex';
            document.querySelector('#valorpersonalizado').required = true;
            document.querySelector('#tipocita').value = 0;  // 0 = tipocita es personalizado sin valor fijo
        }else{
          divcampovalor.style = 'display: none';
          document.querySelector('#valorpersonalizado').required = false;
          document.querySelector('#tipocita').value = 1; // 1 = tipocita es de valor fijo
        }
      }
    });
  }

  const select_date = document.querySelector('#date');
  select_date.addEventListener('input', (evento)=> {
    borrarhtml(document.querySelector('#horas')); //donde se ponen las horas
    Idpxtemp=0;
    const dia = new Date(evento.target.value); //.getUTCDay();  = se estrae el dia de la semana 1 = lunes, 6 = sabado, 0 = domingo
    llamadoempleados(document.querySelector('#servicios').value); //se envia id de la tabla servicios
  });


  function llamadoempleados(id_servicio){
    mostrarcampovalor(id_servicio);
    //const idempleados = emplserv.filter(element => element.idservicio == id_servicio).map(element => element.idempleado);
    const idempleados = emplserv.reduce((result, obj)=>{ 
      if(obj.idservicio == id_servicio)result = [...result, obj.idempleado];
        return result;
    }, []); //los ids de los empleados asociados al servicio elegido
    ///obtenr el id del empleado que en la fecha seleccionada tiene descanso
    const x = fechadesc.filter(element => (element.fecha === select_date.value)&&(idempleados.includes(element.empleado_id))).map(element=>element.empleado_id);
    ////obtener la malla horaria de los empleados ya filtrados
    const dia = new Date(select_date.value).getUTCDay();
    onlymalla = malla.filter(obj => (idempleados.includes(obj.id_empleado)&&obj.id_dia==dia)&&!x.includes(obj.id_empleado));
    ///////////cargar los profesionales//////////////
    cargarprofesionales();
    const professionals = document.querySelector('#professionals');
    if(idempleados.includes(Idpxtemp)){
      for(let i = 1; i<professionals.options.length; i++)
        if(professionals.options[i].value === Idpxtemp)
            professionals.options[i].selected = true;
    }else{
      borrarhtml(document.querySelector('#horas'));
      Idpxtemp=0;
    }
  }

  function cargarprofesionales(){
    const professionals = document.querySelector('#professionals');
    borrarhtml(professionals);
    const option = document.createElement('OPTION');
    option.value = '';
    option.textContent = ' -Selecionar- ';
    option.selected = true;
    option.disabled = true;
    professionals.appendChild(option);
    onlymalla.forEach(element => {
        const option = document.createElement('OPTION');
        option.value = element.id_empleado; //id de la tabla empleados
        option.textContent = emplserv.find(x=>x.idempleado===element.id_empleado).nombre;
        professionals.appendChild(option); 
    });
  }
  document.querySelector('#professionals').addEventListener('change', (e)=>{
    Idpxtemp = e.target.value;  //en esta variables guadamos al empleado elegido
    borrarhtml(document.querySelector('#horas')); //borra en donde se ponen las horas
    gethoras(onlymalla.find(x=>x.id_empleado===e.target.value));
  });


  function gethoras(objmalla){
    const fecha = select_date.value;
    let horario = [objmalla.inicioturno, objmalla.inidescanso, objmalla.findescanso, objmalla.finturno];
    horario = horario.map(hora =>hora = hora.substring(0,2)+':'+hora.substring(2,4));
    horacitas = citaspending.filter(cita => (cita.idempleado === objmalla.id_empleado&&cita.start === fecha)).map(element =>  element.hora_fin);
    calcularhorarios(horario[0], horario[1]);
    calcularhorarios(horario[2], horario[3]);
    imprimirhorashtml();
  }

  function calcularhorarios(horaInicio, horaFin) {
    const horarioActual = new Date(`01/01/2000 ${horaInicio}`);
    const horarioFinal = new Date(`01/01/2000 ${horaFin}`);               //1 minuto 60segundos
    //horarioFinal.setTime(horarioFinal.getTime() - Math.floor(gettimeservice)*60000); //evitar que se programe citas mas alla de las horas de salida
    
    horarioFinal.setTime(horarioFinal.getTime() - ((gettimeservice*80)/100)*60000);

    while (horarioActual <= horarioFinal) {
      const hora = horarioActual.getHours().toString().padStart(2, '0');
      const minutos = horarioActual.getMinutes().toString().padStart(2, '0');
      //console.log(`${hora}:${minutos}`);
      const validate = horacitas.includes(`${hora}:${minutos}:00`); //horacitas arreglo con las horas ya seleccionadas
      if(!validate){
        const timepohora = new Date("2000-01-01T" + `${hora}:${minutos}`);
        // Obtener la hora en formato de 12 horas
        const hora12 = timepohora.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
        horasdisponibles = [...horasdisponibles, {horaformat12: hora12, horaformat24: `${hora}:${minutos}:00`}];
      }
  
      horarioActual.setTime(horarioActual.getTime() + gettimeservice * 60000); // Agregar 30 minutos al horario actual, 60.000 milisegundos tiene un minuto
    }
  }

  function imprimirhorashtml(){
    const divhoras = document.querySelector('#horas'); //donde se ponen las horas
    divhoras.classList.remove('dianodisponible-js');
    horasdisponibles.forEach(hora => {
        const divhora = document.createElement('DIV');
        divhora.classList.add("citas__hora");
        const parrafohora = document.createElement('P');
        parrafohora.textContent = hora.horaformat12;
        parrafohora.dataset.hora24 = hora.horaformat24;
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
    document.querySelector('#hora_fin').value = e.target.dataset.hora24;
  }

  //////////////////////////////REPROGRAMAR CITAS/////////////////////////////////////////
  functReprogramar();
  function functReprogramar(){
    const reProgramar = document.querySelectorAll('#reprogramar');
    reProgramar.forEach(btn => btn.addEventListener('click', (e)=>{
      const c = citaspending.find(cita=>cita.id===e.target.dataset.id); //solo citas pendiente
      if(c){  //si se programa una cita no pendiente no muestra el modal
        dialogo.showModal();
        document.addEventListener("click", cerrarDialogoExterno);
        document.querySelector('#cliente1').classList.add('ocultar'); //oculta el eliv donde esta el select de los usuarios
        document.querySelector('#cliente2').classList.remove('ocultar'); //muestra el div donde esta el input de los usuarios no registrados
        cargardatoscliente(c);
      }
    }));
  }
  function cargardatoscliente(c){
    const professionals = document.querySelector('#professionals');
    document.querySelector('#nombrecliente').value = c.nombrecliente;
    document.querySelector('#nombrecliente').readOnly = true; 
    document.querySelector('#valorpersonalizado').value = c.valorcita;
    document.querySelector('#date').value = c.start;
    $('#servicios').val(c.idservicio).trigger('change'); //selecciona el select y segun su value lo elige, y dispara el evento por jquery
    for(let i = 1; i<professionals.options.length; i++){
      if(professionals.options[i].value === c.idempleado){
          professionals.options[i].selected = true;
          document.querySelector('#id_empserv').value = c.id; //se carga el id_empserv
      }
    }
    const mallaRepro = onlymalla.find(x=>x.id_empleado===c.idempleado); //cuando a un empleado se le quita o se cambia un dia ya su id no se encuentra en la malla de ese dia por lo tanto aqui, se envia obj undefine
    if(mallaRepro)gethoras(mallaRepro);  
    const divhoras = document.querySelector('#horas');
    divhoras.insertAdjacentHTML('afterbegin', `<div class="citas__hora horaselected"><p class="texthora">${c.hora_fin}</p></div>`);
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

  ///////////////////// borrar html ////////////////////////
  function borrarhtml(elemento){
    horasdisponibles = [];
    while(elemento.firstElementChild)elemento.removeChild(elemento.firstElementChild);
  }

  ////////////// Limpiar formulario de crear citas del dialog/////////////////
  function limpiarformdialog(){
    document.querySelector('#formcrearcitas').reset();
    $('#servicios').val(0).trigger('change');
  }
    
}
})();