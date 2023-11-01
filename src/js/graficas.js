(function(){

    const grafica = document.querySelector('#servicios-grafica');
    if(grafica){
       
        let fechas, cierretotal, totalcitas, citasrealizadas, citascancelado, valorcitas=0; citas100=0, progreso=440;
        (async ()=>{
            try {
                const url1 = "/admin/api/alldays"; //llamado a la API REST
                const respuesta1 = await fetch(url1); 
                const resultado1 = await respuesta1.json(); 
                const url2 = "/admin/api/totalcitas";
                const respuesta2 = await fetch(url2); 
                const resultado2 = await respuesta2.json(); 
            
                resultado1.reverse();
                fechas = resultado1.map(date => date.fecha);
                cierretotal = resultado1.map(total => total.totaldia);

                citascancelado = resultado2.filter(Element=>Element.estado==='Cancelado');
                totalcitas = resultado2.length - citascancelado.length; //solo citas finalizadas o pendientes
                
                citasrealizadas = resultado2.filter(Element=>Element.estado==='Finalizada');

                valorcitas = citasrealizadas.reduce((total, Element)=>total+parseInt(Element.facturacion.total), 0);
                if(totalcitas)citas100 = (citasrealizadas.length*100)/totalcitas;
                progreso = (440*citas100)/100;

                printgrafica1();
                animacioncircle();
            } catch (error) {
                console.log(error);
            }
        })();

        function printgrafica1(){
            let a = ['#ea580c', '#84cc16', '#22d3ee', '#a855f7', '#ef4444', '#14b8a6', '#db2777', '#e11d48', '#7e22ce'];
            //for(let i=0; i<(resultado.length-9); i++)a=[...a, a[i]];     //si los programas supera 9 se repite color 

            const ctx = document.getElementById('servicios-grafica').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: fechas,//resultado.map(programa=>programa.nombre),
                    datasets: [{
                    label: '# of Votes',
                    data: cierretotal,//resultado.map(programa=>programa.total),
                    borderColor: '#00c8c2',
                    //backgroundColor: ['#ea580c', '#84cc16', '#22d3ee', '#a855f7'],
                    backgroundColor: '#ea580c',
                    //tension: 0.3,
                    //stepped: 'middle',
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {legend: {display: false } } //elimina el label del dataset
                }
            });
        }


        function animacioncircle(){
            const circle = document.querySelector('circle');
            circle.style.strokeDashoffset = 440-progreso;
            circle.classList.add('animacion');

            const dailyearning = document.querySelector('.dailyearning').textContent = '$'+valorcitas;
            const numero = document.querySelector('.rueda .numero');
            numero.textContent = Math.round(citas100)+'%';
        }
        

    } //cierre del if
})();