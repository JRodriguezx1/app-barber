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
                    console.log(resultado);
                } catch (error) {
                    console.log(error);
                }
            })();
        });


    }  //fin pagina 5 = configuracion
})();