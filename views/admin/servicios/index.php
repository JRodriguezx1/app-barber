<div class="servicios">
    <div class="servicios__titulos">
        <h2 class="dashboard__heading"><?php echo $titulo; ?> </h2>
        <p class="dashboard__descripcion">Agrega un servicio nuevo, editalo cambia su precio o descripción.</p> 
    </div>    
    <div class="servicios__contenedor">
        <div class="servicios__campoformulario">
            <form class="formulario" action="/admin/servicios/crear" method="POST">
                <fieldset class="formulario__fieldset">
                    <?php include __DIR__. "/../../templates/alertas.php"; ?>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="nombre">Nombre</label>
                        <div class="formulario__dato">
                            <input class="formulario__input" type="text" placeholder="Nombre del servicio" id="nombre" name="nombre" value="<?php echo $usuario->nombre??''; ?>" required>
                            <label data-num="25" class="count-charts" for="">25</label>
                        </div>
                    </div>
                    
                    <div class="formulario__campo">
                        <label class="formulario__label" for="precio">Precio</label>
                        <div class="formulario__dato">
                            <input class="formulario__input" type="number" min="0" max="999999" placeholder="Precio del servicio" id="precio" name="precio" required>
                            <label for="">$</label>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="duracion">Duración</label>
                        <div class="formulario__dato">
                            <input class="formulario__input" type="number" min="10" max="120" placeholder="Duración en minutos del servicio" id="duracion" name="duracion" disabled>
                            <label for="">min</label>
                        </div>
                        
                    </div>
                    
                    <input class="formulario__submit servicios__botonCrearServicios" type="submit" value="Crear Servicio" <?php echo $user['admin']>2?'':'disabled'; ?>>
                </fieldset>
            </form>
        </div>
        <div class="servicios__lista">
            <?php foreach($servicios as $servicio): ?>
                <?php if($servicio->estado): ?>
                <div data-id="<?php echo $servicio->id;?>" data-user="<?php echo $user['admin'];?>" id="servicio" class="servicios__servicio">
                    <h4><?php echo $servicio->nombre; ?></h4>
                    <p>Precio: $<span id="precio"> <?php echo $servicio->precio; ?></span></p>
                    <p>Duracion: <span id="duracion"><?php echo $servicio->duracion; ?></span> min</p>
                    <?php if($user['admin']>2): ?>
                        <a class="servicios__eliminar" href="#"><i class="fa-solid fa-trash-can"></i></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>







