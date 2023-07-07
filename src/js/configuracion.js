(function(){

    //////////////////////bar progress //////////////////////
    if(document.querySelector('.barraprogreso')){
        const negocios = document.querySelectorAll('#negocio');
        const label = document.querySelector('.barraprogreso__label');
        const barra = document.querySelector('.barraprogreso__barra');

        let bar = 0;
        negocios.forEach(negocio=>{if(negocio.value)bar++;});
        barra.classList.add('animation');
        label.textContent = ((bar*100)/11).toFixed(1)+'%';
        const animationbarra = document.querySelector('.animation');
        animationbarra.style.width = label.textContent; //barra de progreso dinamicamente
        //
    }


    /////////////////// paginacion de empleado, malla, actualizar-empleado //////////////////
    if(document.querySelector('.cambiopaginas')){
        
        const btns_nav_empleados = document.querySelectorAll('.cambiopaginas span');
        btns_nav_empleados[0].classList.add('resaltar'); //resaltamos el primer enlace o btn
        if(document.querySelector('.perfil')){
            document.querySelector('.pagina3').classList.add('mostrarseccion'); 
        }else{
            document.querySelector('.pagina1').classList.add('mostrarseccion'); //mostramos la primera seccion
        }
        btns_nav_empleados.forEach(Element => {
            Element.addEventListener('click', (e)=>{ //cada btn o enlace

                if(!e.target.classList.contains('resaltar')){
                    btns_nav_empleados.forEach(btn=>btn.classList.remove('resaltar'));
                    e.target.classList.add('resaltar');
                }

                const paginas = document.querySelectorAll('.paginas'); //seleccionamos las secciones o paginas a mostrar
                paginas.forEach(pagina => pagina.classList.remove('mostrarseccion')); ////quitamos la class mostrarseccion a todas las secciones
                document.querySelector(`.${e.target.id}`).classList.add('mostrarseccion'); //añadimos la class mostrarseccion a la la seccion o pagina correspondiente
            });
        });
    }
    

    ///////////////////// habilita/deshabiita inputs de los dias de la malla de turnos con el checkbox/////////////
    const dias = document.querySelectorAll("input[type=checkbox]");
    dias.forEach(dia => {
        dia.addEventListener('change', function(e){
            if(this.checked){
                for(let i=0; i<4; i++)
                e.target.parentElement.nextElementSibling.children[i].disabled = false;
            }else{ 
                for(let i=0; i<4; i++)
                e.target.parentElement.nextElementSibling.children[i].disabled = true;
            }
        });
    });


    ////////////////////// carga de horas en los select de la malla////////////////////////
    const entradas = document.querySelectorAll('#entrada');
    entradas.forEach(entrada =>{
        entrada.addEventListener('change', (e)=>{
            let nextselect = e.target.nextElementSibling; //select siguiente
            puthoras(nextselect, e);
        });
    });

    function puthoras(nextselect, e){
        while(nextselect.firstChild)nextselect.removeChild(nextselect.firstChild);
        let subhora = e.target.value.split(':');
        let hora = parseInt(subhora[0]+subhora[1]);

        for(let i = 0; hora<2300; i++){
            let option = document.createElement('OPTION');
            hora+=30;
            if(hora%100 == 60)hora+=40;
            option.value = hora;
            subhora[0]= parseInt(hora/100);
            subhora[1] = hora%100;
            if(subhora[1] === 0)subhora[1] = "00";
            option.textContent = subhora[0]+':'+subhora[1];
            nextselect.appendChild(option);
        }
        nextselect.addEventListener('change', (e)=>{
            let nextselect = e.target.nextElementSibling; //select siguiente
            if(nextselect)puthoras(nextselect, e);
        });
    }

    //////////////// funcion contadores de caracteres /////////////////////
    /*countchars();
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
    }*/

})();