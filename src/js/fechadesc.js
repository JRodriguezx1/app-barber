(function(){
    if(document.querySelector('.descpagina3')){

        const selectemployee = document.querySelector('#selectemployeedate');
        const viewdates = document.querySelector('.viewdates');
        let fechas;

        (async ()=> {
            try {
                const url = "/admin/api/getfechadesc";
                const respuesta = await fetch(url);
                fechas = await respuesta.json();
            } catch (error) {
               console.log(error); 
            }
        })();


        selectemployee.addEventListener('change', (e)=>{
            const dates = fechas.filter(fecha => fecha.empleado_id == e.target.value);
            limpiarfechas();
            imprimirfechas(dates);
        });

        function limpiarfechas(){
            while(viewdates.firstChild)viewdates.removeChild(viewdates.firstChild);
        }

        function imprimirfechas(dates){
            dates.forEach(element => {
                const div = document.createElement('DIV');
                div.classList.add('viewdates__viewdate'); 
                div.dataset.id = element.id;
                const parrafo = document.createElement('P');
                parrafo.classList.add('viewdates__fecha');
                parrafo.textContent = element.fecha;
                const icondelete = document.createElement('I');
                icondelete.classList.add('fa-solid', 'fa-rectangle-xmark');
                icondelete.id = element.id;
                icondelete.onclick = eliminarfecha;
                div.appendChild(parrafo);
                div.appendChild(icondelete);
                viewdates.appendChild(div);
            });
        }

        async function eliminarfecha(e){
            console.log(e.target.id);
            try {
                const url = `/admin/api/deletefechadesc?id=${e.target.id}`;
                const respuesta = await fetch(url); 
                const resultado = await respuesta.json();  
                if(resultado == 1)document.querySelector(`i[id="${e.target.id}"]`).parentElement.remove();
            } catch (error) {
                console.log(error);
            }
        }
    }
})();