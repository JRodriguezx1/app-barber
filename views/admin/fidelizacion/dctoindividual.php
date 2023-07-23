<div class="creardescuento">
    <div class="creardescuento__contenedor">

        <div class="creardescuento__btnatras"><a class="btnsmall" href="/admin/fidelizacion"><i class="fa-solid fa-arrow-left"></i>Atras</a></div>   

        <div class="creardescuento__form">
        <?php include __DIR__. "/../../templates/alertas.php"; ?>
            <form class="formulario" action="/admin/fidelizacion/creardctoxproduct" method="POST">
                <fieldset class="campos formulario__fieldset">
                    <div class="formulario__campo">
                        <label class="formulario__label" for="categoria">Elegir Categoria: </label>
                        <select class="formulario__select" name="categoria" id="categoria" required>
                            <option value="" disabled selected> Seleccionar Categoria</option>
                            <option value="servicios">Servicios</option>
                            <option value="productos">productos</option>
                        </select> 
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="product_serv">Elegir Producto: </label>
                        <select class="formulario__select" name="product_serv" id="product_serv" required>
                            <option value="" disabled selected> Seleccionar Producto</option>
                        </select> 
                        <label class="valorservicio" id="valorservicio"> $ </label>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="tipo">Tipo De Dcto: </label>
                        <select class="formulario__select" name="tipo" id="tipo" disabled required>
                            <option value="" disabled selected> Seleccionar tipo de dcto</option>
                            <option value="porcentaje">Porcentaje</option>
                            <option value="valor">Valor</option>
                        </select>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="dcto1">Descuento</label>
                        <input class="formulario__input" id="dcto1" type="number" min="" max="" name="dcto1" value="" disabled required>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="dcto2" id="dcto2Valor">Valor</label>
                        <input class="formulario__input" id="dcto2" type="number" min="" max="" name="dcto2" value="" readonly required>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="descripcion">Descripcion</label>
                        <div class="formulario__dato">
                            <input class="formulario__input" type="text" placeholder="Descripcion del dcto" id="descripcion" name="descripcion" value="<?php echo $fidelizacion->descripcion??'';?>" required>
                            <label data-num="64" class="count-charts" for="">64</label>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="fecha_ini">Fecha De Inicio</label>
                        <input class="formulario__input" id="fecha_ini" type="date" name="fecha_ini" required>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="fecha_fin">Fecha De Finalizacion</label>
                        <input class="formulario__input" id="fecha_fin" type="date" name="fecha_fin" required>
                    </div> 
                </fieldset>
                
                
                <input class="formulario__submit" type="submit" value="Guardar">
            </form>
            
        </div> <!-- fin clientes -->
    </div> <!-- fin citas contenedor -->
</div>