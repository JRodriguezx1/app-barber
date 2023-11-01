(function(){ //para ver el detalle del pago de cada cita y su anulacion
    if(document.querySelector('#dashboardcitas')){  
        const detallefactura = document.querySelectorAll('.detallepagocita');

        detallefactura.forEach(Element=>{
            Element.addEventListener('click', (e)=>{
                const tr = e.target.parentElement.parentElement.parentElement;
                const estado = tr.children[6].textContent;
                const idcita = tr.children[0].textContent;
                if(estado === "Finalizada"){ 
                    (async ()=>{
                        try {
                            const url = `/admin/api/detallepagoxcita?id=${idcita}`;
                            const respuesta = await fetch(url); 
                            const resultado = await respuesta.json();  
                                formulariofactura(resultado, tr);
                        } catch (error) {
                            console.log(error);
                        }
                    })();  
                }
            });
        });

        function formulariofactura(resultado, tr){
            console.log(resultado);
            const {id, idcita, recibido, devolucion, fecha_pago, hora_pago, dcto, valordcto, valorpromo, valor_servicio, total} = resultado;
            console.log(recibido);
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: 'Detalle Del Pago',
                html: `
                    <h4 class="orden">App Barber</h4>
                    <div class="recibo">
                        <p class="recibo__titulo">Nº De Pago: ${id}</p>
                        <p class="recibo__titulo">Orden Cita: ${idcita}</p>
                        <div class="recibo__datos">
                            <div class="recibo__dato">
                                <p class="recibo__titulo">Cliente:</p>
                                <p class="recibo__texto">${tr.children[1].textContent}</p>
                            </div>
                            <div class="recibo__dato">
                                <p class="recibo__titulo">profesional:</p>
                                <p class="recibo__texto">${tr.children[3].textContent}</p>
                            </div>
                            <div class="recibo__dato">
                                <p class="recibo__titulo">servicio:</p>
                                <p class="recibo__texto">${tr.children[2].textContent}</p>
                            </div>
                        </div>
                        <div class="recibo__datos">
                            <div class="recibo__dato">
                                <p class="recibo__titulo">Fecha:</p>
                                <p class="recibo__texto">${fecha_pago}, ${hora_pago}</p>
                            </div>
                            <div class="recibo__dato">
                                <p class="recibo__titulo">Valor Servicio:</p>
                                <p class="recibo__texto">$${valor_servicio}</p>
                            </div>
                            <div class="recibo__dato">
                                <p class="recibo__titulo">Recibido:</p>
                                <p class="recibo__texto">$${recibido}</p>
                            </div>
                            <div class="recibo__dato">
                                <p class="recibo__titulo">Devolucion:</p>
                                <p class="recibo__texto">$${devolucion}</p>
                            </div>
                            <div class="recibo__dato">
                                <p class="recibo__titulo">Promo Aplicado:</p>
                                <p class="recibo__texto">$${valorpromo}</p>
                            </div>
                            <div class="recibo__dato">
                                <p class="recibo__titulo">Dcto Manual:</p>
                                <p class="recibo__texto">$${valordcto}</p>
                            </div>
                            <div class="recibo__dato">
                                <p class="recibo__titulo">Total:</p>
                                <p class="recibo__texto">$${total}</p>
                            </div>
                        </div>
                    </div>`,
                width: 'auto',
                showCancelButton: true,
                confirmButtonText: 'Anular Pago'
            }).then((result) => {
                if (result.isConfirmed){
                    Swal.fire({
                        customClass: {
                            confirmButton: 'sweetbtnconfirm',
                            cancelButton: 'sweetbtncancel'
                        },
                        title: 'Desea anular el pago?',
                        showCancelButton: true,
                        confirmButtonText: 'Si',
                        cancelButtonText: 'No',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            anularpagofact(id).then(r=>{
                                if(r){
                                    actualizarfilacita(tr);
                                    Swal.fire('Anulado!', '', 'success')
                                }
                                else{
                                    Swal.fire('No se permite de dias anteriores!', '', 'error')
                                }
                            });
                        } 
                      })
                } 
              }); ////// fin Swal //////
        } ////////fin funcion formulariofactura ////////

        async function anularpagofact(id){
            try {
                const url = `/admin/api/anularpagoxcita?id=${id}`;
                const respuesta = await fetch(url); 
                const resultado = await respuesta.json();  
                return resultado; 
            } catch (error) {
                console.log(error);
            }
        }

        function actualizarfilacita(tr){
            tr.children[6].textContent = "Pendiente";
            tr.classList.remove('tr-resaltar');
        }
    }

})();