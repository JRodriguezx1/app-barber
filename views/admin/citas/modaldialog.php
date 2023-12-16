<dialog class="midialog" id="miDialogo">
    <h4 class="dashboard__heading2">Crear cita</h4>
    <form id="formcrearcitas" class="formulario" action="/admin/citas/crear" method="POST">
        <input type="hidden" id="id_empserv" name="id_empserv" value="" >
        <input type="hidden" id="hora_fin" name="hora_fin" value="" >
        <input type="hidden" id="tipocita" name="tipocita" value="0" >

        <div class="">
            <div id="cliente1" class="formulario__campo">
                <label class="formulario__label" for="usuario">Seleccione Usuario: </label>
                <select class="formulario__select" name="id_usuario" id="usuario" required>
                    <option value="" disabled selected> -Selecionar- </option>
                </select>
            </div>
            <div id="cliente2" class="formulario__campo ocultar">
                <label class="formulario__label" for="nombrecliente">Nombre del cliente: </label>
                <input class="formulario__input" id="nombrecliente" name="nombrecliente" type="text" required>
                <input id="nousuario" type="hidden" name="id_usuario" value="2">
            </div>
            <div class="formulario__campo">
                <label class="formulario__label" for="servicios">Seleccione Servicio: </label>
                <select class="formulario__select" name="idservicio" id="servicios" required>
                    <option value="0" disabled selected> -Selecionar- </option>
                </select>
            </div>
            <div class="formulario__campo campovalor" style="display: none;">
                <label class="formulario__label" for="valorpersonalizado">Valor personalizado: </label>
                <input class="formulario__input" type="number" id="valorpersonalizado" name="valorpersonalizado" required>
            </div>
            <div class="formulario__campo">
                <label class="formulario__label" for="descripcion">Descripcion: </label>
                <textarea class="formulario__textarea" id="descripcion" name="descripcion" rows="4"></textarea>
            </div>
                
            <div class="formulario__campo">
                <label class="formulario__label" for="date">Seleccionar Fecha:</label>
                <input class="formulario__input" id="date" name="start" type="date" required>
            </div>
            <div class="formulario__campo">
                <label class="formulario__label" for="professionals">Seleccione Profesional: </label>
                <select class="formulario__select" name="nameprofesional" id="professionals" required>
                    <option value="" disabled selected> -Selecionar- </option>
                </select>
            </div>
        </div>
            
        <div class="citas__campohoras" id="horas">

        </div>
        <div class="text-right">
            <button class="btnsmallazulturquia" type="button" value="cancelar">cancelar</button>
            <input class="btnsmallazul" type="submit" value="Enviar">
        </div>
    </form>
</dialog>