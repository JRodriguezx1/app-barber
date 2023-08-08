(function(){

    if(document.querySelector('.pagina2malla')){
        const selectemployee = document.querySelector('#selectempleadomalla');
        let malla;

        (async ()=>{
            try {
                const url = "/admin/api/getmalla"; //llamado a la API REST para trer toda la malla de todos los empleados
                const respuesta = await fetch(url); 
                malla = await respuesta.json();    
            } catch (error) {
                console.log(error);
            }
        })();

        selectemployee.addEventListener('change', (e)=>{
            const turnosemployee = malla['empleado_'+e.target.value]; //arreglo de obj donde cada obj es el dia con su turno
            limpiarmalla(); //limpiar malla previamente
            if(turnosemployee)
                turnosemployee.forEach(dia => {
                    const {inicioturno, inidescanso, findescanso, finturno} = dia;
                    let horas = new Array(inicioturno, inidescanso, findescanso, finturno);
                    const radio = document.querySelector(`input[data-id="${dia.id_dia}"]`);
                    radio.checked = true;
                    for(let i=0; i<4; i++)radio.parentElement.nextElementSibling.children[i].disabled = false;

                    document.querySelector(`#entrada[data-id="${dia.id_dia}"]`).value = inicioturno.substring(0,2)+':'+inicioturno.substring(2,4);
                    let select = document.querySelector(`#inidescanso[data-id="${dia.id_dia}"]`);
                    
                    for(let i = 0; i<3; i++){
                        putoptions(select, parseInt(horas[i])); //llenar las options de los input select
                        for(let j = 0; j<select.options.length; j++)
                            if(select.options[j].value == parseInt(horas[i+1]))select.options[j].selected = true;
                        select = select.nextElementSibling;
                    }
                });  
        });

        function putoptions(select, hora){
            for(let i = 0; hora<2300; i++){
                let option = document.createElement('OPTION');
                hora+=30;
                if(hora%100 == 60)hora+=40;
                option.value = hora;
                option.textContent = (hora%100==0)?parseInt(hora/100)+':00':parseInt(hora/100)+':'+hora%100;
                select.appendChild(option);
            }
        }

        function limpiarmalla(){
            const elemntcheckbox = document.querySelectorAll('.malla INPUT[type="checkbox"]');
            elemntcheckbox.forEach(Element => {
                if(Element.checked){ Element.checked = false;
                    for(let i=0; i<4; i++){
                        Element.parentElement.nextElementSibling.children[i].disabled = true;
                        if(i===0){
                            Element.parentElement.nextElementSibling.children[0].value = '';
                        }else{
                            Element.parentElement.nextElementSibling.children[i].options[0].selected = true;
                        }
                    }
                }
            });
        }  
        
    }
})();