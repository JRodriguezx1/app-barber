(function(){

    //////////////////// los skills de los empleados //////////////////////
    if(document.querySelector('.formulario__fieldset--servicios')){
        const divcampostags1 = document.querySelector('.pagina1 .formulario__campostags'); //div en donde se carga los skills y el select de servicios en crear empleado
        const divcampostags2 = document.querySelector('.pagina4 .formulario__campostags'); //div en donde se carga los skills y el select de servicios en actualizar empleado
        const tagselect1 = document.querySelector('.pagina1 .formulario__tagselect'); //select de los servicios
        const tagselect2 = document.querySelector('.pagina4 .formulario__tagselect'); //select de los servicios
        const tagsinputhidden1 = document.querySelector('.pagina1 [name="servicios"]'); //input oculto donde se carga los id de los servicios
        const tagsinputhidden2 = document.querySelector('.pagina4 [name="servicios"]'); //input oculto donde se carga los id de los servicios
        const selectemployee = document.querySelector('#selectemployee'); //select del los empleados en update data
        const eliminaremployee = document.querySelector('#eliminaremployee'); //<a>btn liminar</a>
        let totalservicios = [], empleadosapi, tags1 = [], tags2 = [];

        tagselect1.classList.add('selectskills1');
       // let copytagselect2 = tagselect2.cloneNode(true);

       //////obtoner todos los servicios del select en arreglo totalservicios///////
        for(let i=1; i<tagselect2.options.length; i++){
            let objservicio = {
                id: tagselect2.options[i].value,
                nombre: tagselect2.options[i].textContent
            }
            totalservicios = [...totalservicios, objservicio];
        }

        /////////traer todos los empleados con sus skills //////////
        (async ()=>{
            try {
                const url = "/admin/api/getAllemployee"; //llamado a la API REST
                const respuesta = await fetch(url); 
                empleadosapi = await respuesta.json(); 
            } catch (error) {
                console.log(error);
            }
        })();


        tagselect1.addEventListener('change', (e)=>{ //envento al select de los servicios
            tags1 = [...tags1, e.target.options[tagselect1.options.selectedIndex].value];  //en este arreglo guardamos los id de los servicios seleccionados
            
            let nombreservicio = e.target.options[tagselect1.options.selectedIndex].textContent;
            let idservicio = e.target.options[tagselect1.options.selectedIndex].value;
            
            let divtag = mostrarservicio(nombreservicio, idservicio, tagselect1);
            //divcampostags1.insertBefore(divtag, document.querySelector('.pagina1 .formulario__tagselect'));
            divcampostags1.appendChild(divtag);
            tagselect1.options[tagselect1.options.selectedIndex].remove();
            tagselect1.options[0].selected = true;
            actualizarinputhidden();
        });

        function mostrarservicio(nombreservicio, idservicio, tagselect){
            //const divcampotag = document.createElement('DIV');
            //divcampotag.classList.add('formulario__campotag');
            const divtag = document.createElement('DIV');
            divtag.classList.add('formulario__tag');
            const span = document.createElement('SPAN');
            span.textContent = nombreservicio;
            span.id = idservicio;
            const icon = document.createElement('I');
            icon.classList.add('fa-solid');
            icon.classList.add('fa-rectangle-xmark');
            icon.onclick = function(e){ borrarservicio(e, tagselect); } //al dar click en la x elimina el servicio del empleado
            divtag.appendChild(span);
            divtag.appendChild(icon);
            //divcampotag.appendChild(divtag);
            return divtag;
        }

        function borrarservicio(e, tagselect){ //al dar click en la x lo muestra en el select, y elimina el servicio o skill elegido del empleado
            let span = e.target.previousElementSibling;
            cargarselects(tagselect, {id: span.id, nombre: span.textContent});
            e.target.parentElement.remove();

            if(tagselect.classList.contains('selectskills1')){
                tags1 = tags1.filter(tag => tag!=span.id);
            }else{
                tags2 = tags2.filter(tag => tag!=span.id);
            }
            actualizarinputhidden();
        }

        function actualizarinputhidden(){
            tagsinputhidden1.value = tags1.toString(); // el arreglo tags lo convierte a string
            tagsinputhidden2.value = tags2.toString(); // el arreglo tags lo convierte a string
        }

//////////////////////

        selectemployee.addEventListener('change', (e)=>{ //evento al select de empleados en update data
            let empleado;
            const divtags = document.querySelectorAll('.pagina4 .formulario__tag');
            divtags.forEach(divtag => {divtag.remove()}); //eliminar previamente los skills para cargarlos nuevamente 
            eliminaremployee.href = "/admin/adminconfig/eliminaremployee?id="+e.target.value;
            empleado = empleadosapi.filter(element => element.id == e.target.value); //obtengo solo un empleado elegido
            cargarempleado(empleado);
        });

        function cargarempleado(empleado){ //empleado es un arreglo con un solo obj
            const{servicios} = empleado[0]; //servicios es el arreglo con solo skills
            document.querySelector('.pagina4 #nombre').value = empleado[0].nombre;
            document.querySelector('.pagina4 #apellido').value = empleado[0].apellido;
            document.querySelector('.pagina4 #movil').value = empleado[0].movil;
            document.querySelector('.pagina4 #email').value = empleado[0].email;
            document.querySelector('.pagina4 #departamento').value = empleado[0].departamento;
            document.querySelector('.pagina4 #ciudad').value = empleado[0].ciudad;
            document.querySelector('.pagina4 #direccion').value = empleado[0].direccion;

            tags2 = [];

            //mostrar todos los servicios en el select//
            totalservicios.forEach(servicio => {
                let i = 1;
                for( ; i<tagselect2.options.length; i++)
                    if(servicio.id == tagselect2.options[i].value)break;
                if(i === tagselect2.options.length)cargarselects(tagselect2, {id: servicio.id, nombre: servicio.nombre});
            });
            
            servicios.forEach(servicio => { //arreglo de servicios
                tags2 = [...tags2, servicio.id];
                let divtag = mostrarservicio(servicio.nombre, servicio.id, tagselect2);
                //divcampostags2.insertBefore(divtag, document.querySelector('.pagina4 .formulario__tagselect'));
                divcampostags2.appendChild(divtag);
                ///quitar del select los servicios que se mostraron//
               //document.querySelector(`.pagina4 .formulario__tagselect option[value="${servicio.id}"`).remove();
                if(tagselect2.querySelector(`option[value="${servicio.id}"`))
                    tagselect2.querySelector(`option[value="${servicio.id}"`).remove(); 
            });
            tagselect2.options[0].selected = true;
            actualizarinputhidden();
        }

        tagselect2.addEventListener('change', (e)=>{ //envento al select de los servicios
            tags2 = [...tags2, e.target.options[tagselect2.options.selectedIndex].value];  //en este arreglo guardamos los id de los servicios seleccionados
            let nombreservicio = e.target.options[tagselect2.options.selectedIndex].textContent;
            let idservicio = e.target.options[tagselect2.options.selectedIndex].value;
            
            let divtag = mostrarservicio(nombreservicio, idservicio, tagselect2);
            //divcampostags2.insertBefore(divtag, document.querySelector('.pagina4 .formulario__tagselect'));
            divcampostags2.appendChild(divtag);
            tagselect2.options[tagselect2.options.selectedIndex].remove();
            tagselect2.options[0].selected = true;
            actualizarinputhidden();
        });

        function cargarselects(tagselect, skill){
            const option = document.createElement('OPTION');
            option.value = skill.id;
            option.textContent = skill.nombre;
            tagselect.appendChild(option);
        }


        /////eliminar empleado//////
        eliminaremployee.addEventListener('click', (e)=>{
            e.preventDefault();
            if(e.target.href.includes('?id=')){
                Swal.fire({
                    customClass: {
                        confirmButton: 'sweetbtnconfirm',
                        cancelButton: 'sweetbtncancel'
                    },
                    title: 'Desea eliminar el empleado?',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No',
                    
                }).then((result) => {
                    if (result.isConfirmed)
                        window.location = e.target.href;
                })
            }
        });

    }

    


})();