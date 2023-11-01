(function(){
    if(document.querySelector('.creardescuento')){ //crear dcto en admin/fidelizacion
        
        const categoria = document.querySelector('#categoria');
        const product_serv = document.querySelector('#product_serv');
        const tipodcto = document.querySelector('#tipo');
        const dcto1 = document.querySelector('#dcto1');
        const dcto2 = document.querySelector('#dcto2');
        const dcto2Valor = document.querySelector('#dcto2Valor');
        const fechaini = document.querySelector('#fecha_ini');
        let servicios, valorservicio;

        deshabilitarfechaanterior();

        (async()=>{
            try {
                const url = "/admin/api/getservices"; //llamado a la API REST para trer todos los servicios
                const respuesta = await fetch(url); 
                servicios = await respuesta.json();   
            } catch (error) {
                console.log(error);
            }
        })();

        categoria.addEventListener('change', (e)=>{
            borrarhtml(product_serv);
            const option = document.createElement('OPTION');
            option.value = '';
            option.textContent = 'Seleccione Producto/servicio';
            product_serv.appendChild(option);
            tipodcto.disabled = true;
            dcto1.disabled = true;
            if(e.target.value == "servicios"){
                servicios.forEach(servicio => {
                    if(servicio.precio){  //solo aplicar dcto a servicios que tengan un valor fijo mas no personalizado
                        const option = document.createElement('OPTION');
                        option.value = servicio.id;
                        option.textContent = servicio.nombre;
                        product_serv.appendChild(option);
                    }
                });
            }
            if(e.target.value == "productos"){

            }
        });

        product_serv.addEventListener('change', (e)=>{
            const servicio = servicios.filter(servicio => servicio.id == e.target.value);
            valorservicio = servicio[0].precio;
            document.querySelector('#valorservicio').textContent = '$ '+valorservicio;
            tipodcto.disabled = false;
            imprimirvalor(dcto1.value);
        });

        tipodcto.addEventListener('change', (e)=>{
            if(e.target.value == 'porcentaje'){
                dcto1.min = 0;
                dcto1.max = 100;
                dcto2Valor.textContent = "Valor";
            }
            if(e.target.value == 'valor'){
                dcto1.min = 0;
                dcto1.max = valorservicio;
                dcto2Valor.textContent = "Valor (%)";
            }
            dcto1.disabled = false;
            imprimirvalor(dcto1.value);
        });

        dcto1.addEventListener('input', (e)=>{
            imprimirvalor(e.target.value);  
        });

        function imprimirvalor(valoringresado){
            if(tipodcto.options[tipodcto.options.selectedIndex].value == 'porcentaje'){
                if(valoringresado>100)dcto1.value = 100;
                if(valoringresado<0)dcto1.value = 0;
                dcto2.value = Math.round((valorservicio*dcto1.value)/100);
            }
            if(tipodcto.options[tipodcto.options.selectedIndex].value == 'valor'){
                if(valoringresado>parseInt(valorservicio))dcto1.value = valorservicio;
                if(valoringresado<0)dcto1.value = 0;
                dcto2.value = Math.round((dcto1.value*100)/valorservicio);
            }  
        }

        function borrarhtml(elemento){
            horasdisponibles = [];
            while(elemento.firstElementChild)elemento.removeChild(elemento.firstElementChild);
        }

        fechaini.addEventListener('change', (e)=>{
            const fechafin = document.querySelector('#fecha_fin');
            fechafin.disabled = false;
            const fechaposterior = new Date(e.target.value);
            fechaposterior.setDate(fechaposterior.getDate()+2);
            const year = fechaposterior.getFullYear();
            const mes = fechaposterior.getMonth() + 1;
            const dia = fechaposterior.getDate();
            let deshabilitarfecha = `${year}-${mes}-${dia}`;
            if(mes<10&&dia<10){ deshabilitarfecha = `${year}-0${mes}-0${dia}`; }
            if(mes<10&&dia>=10){ deshabilitarfecha = `${year}-0${mes}-${dia}`; }
            if(mes>=10&&dia<10){ deshabilitarfecha = `${year}-${mes}-0${dia}`; }
            fechafin.min = deshabilitarfecha; 
        });

        //////////////////// deshabilitar fecha anterior ////////////////////

        function deshabilitarfechaanterior(){
            const fechaactual = new Date();  //en fecha actual esta la fecha actual con hora
           
            fechaactual.setDate(fechaactual.getDate());
            const year = fechaactual.getFullYear();
            const mes = fechaactual.getMonth() + 1;
            const dia = fechaactual.getDate();
            let deshabilitarfecha = `${year}-${mes}-${dia}`;
            
            if(mes<10&&dia<10){ deshabilitarfecha = `${year}-0${mes}-0${dia}`; }
            if(mes<10&&dia>=10){ deshabilitarfecha = `${year}-0${mes}-${dia}`; }
            if(mes>=10&&dia<10){ deshabilitarfecha = `${year}-${mes}-0${dia}`; }

            fechaini.min = deshabilitarfecha; //al input fecha se le agrega atributo min
        }
    }

})();