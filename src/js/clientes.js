(function(){
    if(document.querySelector('.clientes')){
        const crearcliente = document.querySelector('#crearcliente');
        const editar = document.querySelectorAll('#editar');
        const hab_desh = document.querySelectorAll('#hab_desh');
        let objform = {
            titulo: '',
            url: '',
            submit: '',
            nombre: '',
            apellido: '',
            movil: '',
            email: ''
        }

        crearcliente.addEventListener('click', ()=>{
            objform.titulo = 'Crear Cliente Nuevo';
            objform.url = '/admin/clientes/crear';
            objform.submit = 'Crear Cliente';
            objform.nombre = '';
            objform.apellido = '';
            objform.movil = '';
            objform.email = '';
            objform.password = `<div class="formulario__campo">
                                    <label class="formulario__label" for="password">Password</label>
                                    <div class="formulario__dato">
                                        <input class="formulario__input" type="password" placeholder="Password del cliente" id="password" name="password" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                        <label data-num="12" class="count-charts" for="">12</label>
                                    </div>
                                </div>
                                <div class="formulario__campo">
                                    <label class="formulario__label" for="password2">Repetir Password</label>
                                    <div class="formulario__dato">
                                        <input class="formulario__input" type="password" placeholder="Repetir Password" id="password2" name="password2" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                        <label data-num="12" class="count-charts" for="">12</label>
                                    </div>
                                </div>`;
            formulariocliente(objform, 0);
            countchars();
        });

        editar.forEach(Element => {
            Element.addEventListener('click', (e)=>{
                objform.titulo = 'Actualizar Cliente';
                objform.url = '/admin/clientes/actualizar';
                objform.submit = 'Actualizar';
                const tr = e.target.parentElement.parentElement.parentElement;
                objform.nombre = tr.children[1].textContent;
                objform.apellido = tr.children[2].textContent;
                objform.movil = tr.children[3].textContent;
                objform.email = tr.children[4].textContent;
                objform.password = '';
                formulariocliente(objform, e.target.parentElement.dataset.id);
                countchars();
            });
        });//cierre del foreach


        function formulariocliente(objform, id){
            let { titulo, url, submit, nombre, apellido, movil, email, password} = objform;
            Swal.fire({
                customClass: {
                    confirmButton: 'sweetbtnconfirm',
                    cancelButton: 'sweetbtncancel'
                },
                title: titulo,
                html: `<form class="formulario modalform" action="${url}" method="POST">
                            <input type="hidden" name="id" value="${id}" >

                            <div class="formulario__campo">
                                <label class="formulario__label" for="nombre">Nombre:</label>
                                <div class="formulario__dato">
                                    <input class="formulario__input" type="text" placeholder="Nombre del cliente" id="nombre" name="nombre" value="${nombre}" required>
                                    <label data-num="42" class="count-charts" for="">42</label>
                                </div>
                            </div>
                            <div class="formulario__campo">
                                <label class="formulario__label" for="apellido">Apellido:</label>
                                <div class="formulario__dato">
                                    <input class="formulario__input" type="text" placeholder="Apellido del cliente" id="apellido" name="apellido" value="${apellido}" required>
                                    <label data-num="42" class="count-charts" for="">42</label>
                                </div>
                            </div>
                            <div class="formulario__campo">
                                <label class="formulario__label" for="movil">Movil</label>
                                <div class="formulario__dato">
                                    <input class="formulario__input" type="number" placeholder="Movil del cliente" id="movil" name="movil" value="${movil}" required>
                                    <label data-num="10" class="count-charts" for="">10</label>
                                </div>
                            </div>
                            <div class="formulario__campo">
                                <label class="formulario__label" for="email">Email</label>
                                <div class="formulario__dato">
                                    <input class="formulario__input" type="email" placeholder="Email del cliente" id="email" name="email" value="${email}" required>
                                    <label data-num="40" class="count-charts" for="">40</label>
                                </div>
                            </div>
                            ${password}
                            <input class="clientes-btn" type="submit" value="${submit}">
                       </form>`,
                showCancelButton: false,
                showConfirmButton: false,  
            });
        }

        /////////////// eliminar cliente ////////////////

        hab_desh.forEach(element => {
            element.addEventListener('click', (e)=>{

                let mensaje = "Desea bloquear el cliente?";
                if(e.target.classList.contains('habilitar'))mensaje = "Desea habilitar el cliente?";

                const id = e.target.parentElement.dataset.id;
                Swal.fire({
                    customClass: {
                        confirmButton: 'sweetbtnconfirm',
                        cancelButton: 'sweetbtncancel'
                    },
                    title: mensaje,
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No',
                    
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = `/admin/clientes/hab_desh?id=${id}`;
                    } 
                })
            });
        });
    
        //////////////// funcion contadores de caracteres /////////////////////
        //countchars();
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

    }
})();