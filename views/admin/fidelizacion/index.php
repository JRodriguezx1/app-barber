<div class="fidelizacion">
    <h2 class="text-center">Fidelizacion</h2>
    
    <div class="fidelizacion__contenedor">
        <div class="fidelizacion__nuevo">
            <form class="formulario" action="/admin/fidelizacion/crear" method="POST">
                <H4 class="fidelizacion__heading">Ingresar Descuento</H4>
                <fieldset class="formulario__fieldset">
                    <div class="formulario__campo">
                        <label class="formulario__label" for="idservicio">Servicio</label>
                        <select class="formulario__select" name="idservicio" id="idservicio" required>
                            <option value="" disabled selected> Seleccionar Servicio</option>
                            <?php foreach($servicios as $servicio): ?>
                                <option value="<?php echo $servicio->id??''; ?>"><?php echo $servicio->nombre??''; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__label" for="descripcion">Descripcion</label>
                        <div class="formulario__dato">
                            <input id="negocio" class="formulario__input" type="text" placeholder="Descripcion del descuento" id="descripcion" name="descripcion" value="<?php echo $fidelizacion->descripcion??''; ?>" required>
                            <label data-num="42" class="count-charts" for="">42</label>
                        </div>
                    </div>
            
                    <div class="formulario__campo">
                        <label class="formulario__label" for="descuento">Descuento</label>
                        <input id="negocio" class="formulario__input" type="number" min="1" max="100" placeholder="Descuento en porcentaje %" id="descuento" name="descuento" value="<?php echo $fidelizacion->descuento ?? '';?>" required>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__label" for="fecha">Fecha De Finalizacion:</label>
                        <input class="formulario__input" id="fecha" type="date" name="fecha">
                    </div>
                </fieldset>
                
                <input class="formulario__submit" type="submit" value="Ingresar">
            </form>
            <div class="fidelizacion__dctoindividual text-center">
                <p>Ver descuentos individuales</p>
                <a class="btnsmall" href="/admin/fidelizacion/dctoindividual">Ingresar</a>
            </div>
        </div> <!-- fin nuevo -->
        <div class="fidelizacion__registradas">
            <H4 class="fidelizacion__heading text-center">Historial de descuentos globales</H4>
        
            <ul class="fidelizacion__ul">
                <li class="fidelizacion__li">
                <p>Descuento del mes del 25% en corte para hombre</p><div class="opciones"><p>Fecha: </p><p>15-7-2023</p><p>15-7-2023</p><button class="btnmini">Eliminar</button></div>   
                </li>
            </ul>
        </div>
    </div>

</div>