(function(){
    if(document.querySelector('#dash-cliente')){
        
        const servicio = document.querySelector('#servicio'); //select servicio
        const profesionales = document.querySelector('#profesional');
        const selectdate = document.querySelector('#date');
        //const programar = document.querySelectorAll('.programar');
        const eliminarcitas = document.querySelectorAll('#cancelado');
        const dialogo = document.getElementById("miDialogo");
        selectdate.disabled = true;
        let emplserv;
                

        (async ()=>{
            try {
                const url = "/admin/api/getemployee_services"; //llamado a la API REST para trer la relacion de los servicios con sus profesionales
                const respuesta = await fetch(url); 
                emplserv = await respuesta.json(); 
                validarofertaselected(); 
            } catch (error) {
                console.log(error);
            }
        })();

        deshabilitarfechaanterior();

        function validarofertaselected(){   //validar oferta
            const urlparams = new URLSearchParams(window.location.search);
            const id = urlparams.get('id'); //id del servicio
            const valordcto = urlparams.get('valordcto'); //id del servicio
            if(id){
                servicio.classList.add('servicioselected');
                for(let i = 1; i<=servicio.options.length; i++){
                    if(servicio.options[i].value == id){
                        servicio.options[i].selected = true;
                        let serviciotext = servicio.options[i].textContent.split(" - ");
                        serviciotext = serviciotext[0]+' - '+valordcto;
                        servicio.options[i].textContent = serviciotext;
                        break;
                    }
                }
                crearselectprofesionals(id);
            }
        }

        servicio.addEventListener('change', (e)=>{
            selectdate.disabled = true;
            servicio.classList.remove('servicioselected');
            mostarmodaldiag(e.target.options[e.target.options.selectedIndex].textContent);
            crearselectprofesionals(e.target.value);
        });

        function mostarmodaldiag(serviciovalor){  // funciones para mostrar y cerrar modal dialog
            const temp = serviciovalor.includes('$'); 
            if(!temp){
                dialogo.showModal();
                document.addEventListener("click", cerrarDialogoExterno);
            }
        }
        function cerrarDialogoExterno(event) {
            console.log(event.target);
            if (event.target === dialogo ||event.target.id === "cerrardialog") {
              dialogo.close();
              document.removeEventListener("click", cerrarDialogoExterno);
            }
        }

        function crearselectprofesionals(idservice){
            const empleados = emplserv.filter(Element => Element.idservicio === idservice);
            borrarhtml(profesionales);
            borrarhtml(document.querySelector('#horas'));
            const option = document.createElement('OPTION');
            option.value = '';
            option.textContent = ' -Selecionar- ';
            option.selected = true;
            option.disabled = true;
            profesionales.appendChild(option);
            empleados.forEach(element => {
                const option = document.createElement('OPTION');
                option.value = element.idempleado;  //id de la tabla empleados
                option.textContent = element.nombre;
                option.dataset.id = element.id;   //id de la tabla empserv
                profesionales.appendChild(option);
            });
        }

        function deshabilitarfechaanterior(){
            const fecha = document.querySelector('#date');
            const fechaactual = new Date();  //en fecha actual esta la fecha actual con hora
            /*const year = fechaactual.getFullYear(); // obiene el año actual
            const mes = fechaactual.getMonth() + 1;  //obtiene el mes
            const dia = fechaactual.getDate(); //obitien el dia actual se le suma el dia siguiente
            let deshabilitarfecha = `${year}-${mes}-${dia}`;
            */
           
            fechaactual.setDate(fechaactual.getDate() + 1);
            const year = fechaactual.getFullYear();
            const mes = fechaactual.getMonth() + 1;
            const dia = fechaactual.getDate();
            let deshabilitarfecha = `${year}-${mes}-${dia}`;
            
            if(mes<10&&dia<10){ deshabilitarfecha = `${year}-0${mes}-0${dia}`; }
            if(mes<10&&dia>=10){ deshabilitarfecha = `${year}-0${mes}-${dia}`; }
            if(mes>=10&&dia<10){ deshabilitarfecha = `${year}-${mes}-0${dia}`; }

            fecha.min = deshabilitarfecha; //al input fecha se le agrega atributo min
        }

        function borrarhtml(elemento){
            horasdisponibles = [];
            while(elemento.firstElementChild)elemento.removeChild(elemento.firstElementChild);
        }

        eliminarcitas.forEach(eliminarcita =>{
            eliminarcita.addEventListener('click', (e)=>{
                const tr = e.target.parentElement.parentElement.parentElement;
                /*const fechacita = tr.children[2].textContent;
                const horacita = tr.children[3].textContent;
                const estado = tr.children[6].textContent;

                //var fecha1 = new Date(fechacita+"T00:00:00-05:00");
                let tiempocita = new Date(fechacita+"T"+horacita);
                let hoy = new Date();
                console.log(tiempocita-hoy);

                if(estado === "Pendiente"){
                    if((tiempocita-hoy)>=3600000){*/
                        cancelarcita(e.target.parentElement.dataset.id, tr);
                    /*}else{
                        
                    }
                }*/  
            });
        });


        function cancelarcita(id, tr){ //funcion para cancelar la cita, se ejecuta si la cita esta a mas de una hora de cumplirse
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Desea Cancelar La Cita?',
                text: "SE CANCELARA LA CITA, PUEDES SOLICITAR UNA NUEVA CUANDO LO DESEES.",
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
                                //estado.textContent = "Cancelado"; 
                                tr.remove();
                                Swal.fire('Cita Cancelada', '', 'success')
                            }else{
                                Swal.fire('No se pudo cancelar intentalo nuevamente', '', 'error')
                            }
                        } catch (error) {
                            console.log(error);
                        }
                    })(); 
                } 
            })
        }


        /*
        programar.forEach(element => { //bloque para reprogramar cita desde el dashboard del cliente
            element.addEventListener('click', e=>{
                Swal.fire({
                    customClass: {
                        confirmButton: 'sweetbtnconfirm',
                        cancelButton: 'sweetbtncancel'
                    },
                    title: 'Reprograme su cita',
                    html: `<div class="formulario__campo">
                            <label class="formulario__label" for="date">Consulta fecha disponible:</label>
                            <input class="formulario__input" id="date" type="date" value="">
                            </div>
                            
                            <div class="cliente__campohoras" id="horasmodal">

                            </div>`,
                    showCancelButton: true,
                    showConfirmButton: true,
                    //confirmButtonText: 'Aceptar',
                    //cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                    Swal.fire('Actualizando', '', 'success')
                    //modificar el servicio
                    } 
                })
            });
        });*/
        
    }
})();