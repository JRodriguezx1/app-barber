(function(){
    if(document.querySelector('.creardescuento')){ //crear dcto en admin/fidelizacion
        
        //////////////// funcion contadores de caracteres /////////////////////
        
        /*
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
        countchars();*/
        const categoria = document.querySelector('#categoria');
        const product_serv = document.querySelector('#product_serv');
        const tipodcto = document.querySelector('#tipo');
        const dcto1 = document.querySelector('#dcto1');
        const dcto2 = document.querySelector('#dcto2');
        const dcto2Valor = document.querySelector('#dcto2Valor');
        let servicios, valorservicio;

        (async()=>{
            try {
                const url = "/admin/api/getservices"; //llamado a la API REST para trer todos los servicios
                const respuesta = await fetch(url); 
                servicios = await respuesta.json(); 
                console.log(servicios);  
            } catch (error) {
                console.log(error);
            }
        })();

        categoria.addEventListener('change', (e)=>{
            borrarhtml(product_serv);
            const option = document.createElement('OPTION');
            option.value = '';
            option.textContent = 'Seleccionar Producto';
            product_serv.appendChild(option);
            tipodcto.disabled = true;
            dcto1.disabled = true;
            if(e.target.value == "servicios"){
                servicios.forEach(servicio => {
                    const option = document.createElement('OPTION');
                    option.value = servicio.id;
                    option.textContent = servicio.nombre;
                    product_serv.appendChild(option);
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
    }

})();