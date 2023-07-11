
<div class="servicios">
    <div class="servicios__titulos">
        <h2 class="dashboard__heading"><?php echo $titulo; ?> </h2>
        <p class="dashboard__descripcion">Agrega un servicio nuevo, editalo cambia su precio o descripcion.</p>
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
                        <label class="formulario__label" for="duracion">Duracion</label>
                        <div class="formulario__dato">
                            <input class="formulario__input" type="number" min="10" max="120" placeholder="Duracion en minutos del servicio" id="duracion" name="duracion" disabled>
                            <label for="">min</label>
                        </div>
                        
                    </div>
                    
                    <input class="formulario__submit" type="submit" value="Crear Servicio">
                </fieldset>
            </form>
        </div>
        <div class="servicios__lista">
            <?php foreach($servicios as $servicio): ?>
                <div data-id="<?php echo $servicio->id;?>" id="servicio" class="servicios__servicio">
                    <h4><?php echo $servicio->nombre; ?></h4>
                    <p>Precio: $<span id="precio"> <?php echo $servicio->precio; ?></span></p>
                    <p>Duracion: <span id="duracion"><?php echo $servicio->duracion; ?></span> min</p>
                    <a class="servicios__eliminar" href="#"><i class="fa-solid fa-trash-can"></i></a>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>



