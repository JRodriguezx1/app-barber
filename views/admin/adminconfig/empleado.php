
    <fieldset class="formulario__fieldset formulario__fieldset--personal">
        <div class="formulario__campo">
            <label class="formulario__label" for="nombre">Nombre</label>
            <div class="formulario__dato">
                <input class="formulario__input" type="text" placeholder="Nombre del empleado" id="nombre" name="nombre" value="<?php echo $trabajador->nombre??'';?>" required>
                <label data-num="42" class="count-charts" for="">42</label>
            </div>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="apellido">Apellido</label>
            <div class="formulario__dato">
                <input class="formulario__input" type="text" placeholder="Apellido del empleado" id="apellido" name="apellido" value="<?php echo $trabajador->apellido??'';?>" requiered>
                <label data-num="42" class="count-charts" for="">42</label>
            </div>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="movil">Movil</label>
            <div class="formulario__dato">
                <input class="formulario__input" type="number" min="3000000000" max="3777777777" placeholder="Tu Movil" id="movil" name="movil" value="<?php echo $trabajador->movil??'';?>" requiered>
            </div>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <div class="formulario__dato">
                <input class="formulario__input" type="email" placeholder="Tu Email" id="email" name="email" value="<?php echo $trabajador->email??'';?>" requiered>
            </div>
        </div>
    </fieldset>
    <fieldset class="formulario__fieldset formulario__fieldset--servicios">
        <div class="formulario__campo">
            <label class="formulario__label" for="servicios">Servicios</label>
            <div class="formulario__tags">
                <!--<div class="formulario__tag">
                    <span>Servicio1</span>
                    <i class="fa-solid fa-rectangle-xmark"></i>
                </div>
                <div class="formulario__tag">
                    <span>Servicio1</span>
                    <i class="fa-solid fa-rectangle-xmark"></i>
                </div> -->
                <div class="formulario__campostags"><!-- aqui se agrega los skills con js en empleados.js --></div>
                <select class="formulario__tagselect" name="" id="selectservicios" >
                    <option value="" disabled selected> Seleccionar Servicio</option>
                    <?php foreach($servicios as $servicio): ?>
                        <option value="<?php echo $servicio->id??''; ?>"><?php echo $servicio->nombre??''; ?></option>
                    <?php endforeach; ?>
                </select>
                <input class="formulario__inputag" type="hidden" name="servicios" value="" requiered>
            </div>
        </div>
    </fieldset>
    <fieldset class="formulario__fieldset formulario__fieldset--lugar">
        <div class="formulario__campo">
            <label class="formulario__label" for="departamento">Departamento</label>
            <div class="formulario__dato">
                <input class="formulario__input" type="text" placeholder="departamento" id="departamento" name="departamento" value="<?php echo $trabajador->departamento??''; ?>">
                <label data-num="18" class="count-charts" for="">18</label>
            </div>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="ciudad">Ciudad</label>
            <div class="formulario__dato">
                <input class="formulario__input" type="text" placeholder="ciudad" id="ciudad" name="ciudad" value="<?php echo $trabajador->ciudad??'';?>">
                <label data-num="14" class="count-charts" for="">14</label>
            </div>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="direccion">Direccion</label>
            <div class="formulario__dato">
                <input class="formulario__input" type="text" placeholder="Direccion de  residencia" id="direccion" name="direccion" value="<?php echo $trabajador->direccion??'';?>">
                <label data-num="90" class="count-charts" for="">90</label>
            </div>
        </div>
    </fieldset>
                