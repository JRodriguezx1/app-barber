(function(){
    if(document.querySelector('.pagina5')){
        let arraymediospago = [];
        const inputmediospago = document.querySelectorAll('.mediospago__mediopago input[type="checkbox"]');
        const btnbtnmediopago = document.querySelector('#btnmediopago');
        

        (async()=>{
            try {
                const url = "/admin/api/getmediospago";
                const respuesta = await fetch(url);
                const resultado = await respuesta.json();
                marcarmediospago(resultado);
            } catch (error) {
                console.log(error);
            }
        })();

        function marcarmediospago(resultado){
            inputmediospago.forEach(input =>{
                const r = resultado.find(mediopago=>mediopago.id == input.value); //retorna el obj que conside
                if(r.estado === '1'){ //r es el obj retornado
                    input.checked = true;
                    arraymediospago = [...arraymediospago, r.id];
                } 
            });
        }

        inputmediospago.forEach(input =>{
            input.addEventListener('change', (e)=>{
                if(e.target.checked){
                    arraymediospago = [...arraymediospago, e.target.value];
                }else{
                    arraymediospago = arraymediospago.filter(Element=>Element!=e.target.value);
                }
            });     
        });

        btnbtnmediopago.addEventListener('click', ()=>{
            (async ()=>{
                const datos = new FormData();
                datos.append('ids', arraymediospago);
                try {
                    const url = "/admin/api/setmediospago";
                    const respuesta = await fetch(url, {method: 'POST', body: datos}); 
                    const resultado = await respuesta.json();  
                    if(resultado){
                        imprimirmensaje("Medios de pago actualizados", 'alerta__exito', document.querySelector('.mediospago'));
                    }else{
                        imprimirmensaje("Se produjo un error intentalo nuevamente", 'alerta__error', document.querySelector('.mediospago'));   
                    }
                } catch (error) {
                    console.log(error);
                }
            })();
        });
        ////////************************fin medios de pago*************************//////////

        const formhabilitarempleado = document.querySelector('#formhabilitarempleado');
        const selectempleado = document.querySelector('#dardealtaselectempleado');
        
        selectempleado.addEventListener('change', (e)=>{
            const restriccion = e.target.options[e.target.options.selectedIndex].dataset.restriccion;
            if(document.querySelector('.creacioncuentas__habilitaremp input[name="admin"]:checked'))document.querySelector('.creacioncuentas__habilitaremp input[name="admin"]:checked').checked = false;
            if(restriccion==2)document.querySelector('.creacioncuentas__habilitaremp #roladmin').checked = true;
            if(restriccion==1)document.querySelector('.creacioncuentas__habilitaremp #rolempleado').checked = true;
        });

        formhabilitarempleado.addEventListener('submit', (e)=>{
            e.preventDefault();
            const radioadmin = document.querySelector('input[name="admin"]:checked').value;
            const idempleado = selectempleado.options[selectempleado.options.selectedIndex].value;

            (async ()=>{
                const datos = new FormData();
                datos.append('idempleado', idempleado);
                datos.append('tiporol', radioadmin);
                try {
                    const url = "/admin/api/habilitarempleado";
                    const respuesta = await fetch(url, {method: 'POST', body: datos}); 
                    const resultado = await respuesta.json();  
                    if(resultado){
                        selectempleado.options[selectempleado.options.selectedIndex].dataset.restriccion = radioadmin;
                        imprimirmensaje("Empleado dado de alta en sistema administrativo", 'alerta__exito', document.querySelector('.creacioncuentas__habilitaremp'));
                    }else{
                        imprimirmensaje("Se produjo un error intentalo nuevamente", 'alerta__error', document.querySelector('.creacioncuentas__habilitaremp'));   
                    }
                } catch (error) {
                    console.log(error);
                }
            })();
        });
        ////////*****************fin habilitar empleados*******************//////////

        const formsetearpass = document.querySelector('#formsetearpass');
        const selectsetearpass = document.querySelector('#selectsetearpass');

        formsetearpass.addEventListener('submit', (e)=>{
            e.preventDefault();
            const idempleado = selectsetearpass.options[selectsetearpass.options.selectedIndex].value;
            (async ()=>{
                const datos = new FormData();
                datos.append('idempleado', idempleado);
                try {
                    const url = "/admin/api/setearpass";
                    const respuesta = await fetch(url, {method: 'POST', body: datos}); 
                    const resultado = await respuesta.json(); 
                    console.log(resultado);
                    if(resultado){
                        imprimirmensaje("Password reiniciado por defecto", 'alerta__exito', document.querySelector('.creacioncuentas__setearpass'));
                    }else{
                        imprimirmensaje("Se produjo un error intentalo nuevamente", 'alerta__error', document.querySelector('.creacioncuentas__setearpass'));   
                    }
                } catch (error) {
                    console.log(error);
                }
            })();
        });
        //////////*****************fin Setear Password******************//////////

        const formcolores = document.querySelector('#formcolores');
        formcolores.addEventListener('submit', (e)=>{
            e.preventDefault();
            const color1 = document.querySelector('.coloresapp #principal').value;
            const color2 = document.querySelector('.coloresapp #secundario').value;
            (async ()=>{
                const datos = new FormData();
                datos.append('colorprincipal', color1);
                datos.append('colorsecundario', color2);
                try {
                    const url = "/admin/api/coloresapp";
                    const respuesta = await fetch(url, {method: 'POST', body: datos}); 
                    const resultado = await respuesta.json(); 
                    console.log(resultado);
                    if(resultado){
                        imprimirmensaje("Colores establecidos exitosamente", 'alerta__exito', document.querySelector('.coloresapp'));
                    }else{
                        imprimirmensaje("Se produjo un error intentalo nuevamente", 'alerta__error', document.querySelector('.coloresapp'));   
                    }
                } catch (error) {
                    console.log(error);
                }
            })();
        });

        //////////*****************fin definir colores app******************//////////


        function imprimirmensaje(msjtext, tipo, ubicacion){ //muestra mensaje de exito o error
            const mensaje = document.createElement('P');
            mensaje.textContent = msjtext;
            mensaje.classList.add('alerta', tipo);
            ubicacion.insertBefore(mensaje, ubicacion.querySelector('.configuracion__heading'));
            setTimeout(() => {
                document.querySelector('.alerta').remove();
            }, 5000);
        }
    }  //fin pagina 5 = configuracion
})();