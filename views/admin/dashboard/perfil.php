<div class="perfil">
    <h4 class="dashboard__heading2"><i class="fa-solid fa-user-large"></i><?php echo $titulo; ?> </h4>
    <div class="perfil__contenedor">
        <div class="perfil__col1">
            <div class="perfil__img">
                <i class="fa-solid fa-user-large"></i>
                <p class="perfil__nombre"><?php echo $usuario->nombre??'';?> <?php echo $usuario->apellido??'';?></p>
                <p class="perfil__cuenta"><?php echo $usuario->admin?'Cuenta Admin':'';?></p>
            </div>
            <div class="perfil__contact">
                <label for="">Direccion email</label>
                <p class="perfil__email"><?php echo $usuario->email??'';?></p>
                <label for="">Contacto</label>
                <p class="perfil__phone"><?php echo $usuario->movil??'';?></p>
            </div>
        </div>
        <div class="perfil__col2">
        <?php require_once __DIR__ .'/../../templates/alertas.php'; ?>
        <a class="cambiarpassword" href="/admin/perfil/cambiarpassword">Cambiar Password</a>
        <div class="perfil__contenedorform">
            <form class="formulario" action="/admin/perfil" enctype="multipart/form-data" method="POST">
                <fieldset class="formulario__fieldset">
                    <div class="formulario__campo">
                        <label class="formulario__label" for="nombre">Nombre</label>
                        <div class="formulario__dato">
                            <input id="nombre" class="formulario__input" type="text" placeholder="Tu Nombre" id="nombre" name="nombre" value="<?php echo $usuario->nombre??''; ?>" required>
                            <label data-num="42" class="count-charts" for="">42</label>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="apellido">Apellido</label>
                        <div class="formulario__dato">
                            <input class="formulario__input" type="text" placeholder="Tu Apellido" id="apellido" name="apellido" value="<?php echo $usuario->apellido??'';?>" requiered>
                            <label data-num="42" class="count-charts" for="">42</label>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="email">Email</label>
                        <div class="formulario__dato">
                            <input class="formulario__input" type="email" placeholder="Tu Email" id="email" name="email" value="<?php echo $usuario->email??'';?>" requiered>
                            <label data-num="43" class="count-charts" for="">42</label>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="movil">Movil</label>
                        <input id="movil" class="formulario__input" type="number" min="3000000000" max="3777777777" placeholder="Movil de contacto" id="movil" name="movil" value="<?php echo $usuario->movil ?? '';?>" required>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="ciudad">Ciudad</label>
                        <div class="formulario__dato">
                            <input id="ciudad" class="formulario__input" type="text" placeholder="ciudad de residencia" id="ciudad" name="ciudad" value="<?php echo $usuario->ciudad ?? '';?>">
                            <label data-num="40" class="count-charts" for="">40</label>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="direccion">Direccion</label>
                        <div class="formulario__dato">
                            <input id="direccion" class="formulario__input" type="text" placeholder="Direccion del vivenda" id="direccion" name="direccion" value="<?php echo $usuario->direccion ?? '';?>">
                            <label data-num="56" class="count-charts" for="">56</label>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="formulario__fieldset">
                    <!--<legend class="formulario-legend">Redes Sociales</legend>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="logo">Logo</label>
                        <input class="formulario__input--file" type="file" id="logo" name="logo">
                    </div>-->
                    <input class="formulario__submit" type="submit" value="Actualizar Perfil">
                </fieldset>
            </form>
        </div>
        </div>
    </div>
</div>