<div class="cambiarpassword">
    <h4 class="dashboard__heading2"><i class="fa-solid fa-user-large"></i><?php echo $titulo; ?> </h4>
    <div class="cambiarpassword__contenedor">
        <?php require_once __DIR__ .'/../../templates/alertas.php'; ?>

        <div class="cambiarpassword__atras">
            <a class="btnsmall" href="/admin/perfil"><i class="fa-solid fa-arrow-left"></i>Atras</a>
        </div>
        <div class="cambiarpassword__contenido">
            <form class="formulario" action="/admin/perfil/cambiarpassword" method="POST">
                <div class="formulario__campo">
                    <label class="formulario__label" for="passwordactual">Password Actual</label>
                    <input class="formulario__input" type="password" placeholder="Tu Password Actual" id="passwordactual" name="passwordactual" required>
                </div>
                <div class="formulario__campo">
                    <label class="formulario__label" for="password">Nuevo Password</label>
                    <input class="formulario__input" type="password" placeholder="Ingrese El Nuevo Password" id="password" name="password" required>
                </div>
                <div class="formulario__campo">
                    <label class="formulario__label" for="password2">Repetir Nuevo Password</label>
                    <input class="formulario__input" type="password" placeholder="Repite El Nuevo Password" id="password2" name="password2" required>
                </div>
                <input class="formulario__submit" type="submit" value="Guardar Cambios">
            </form>
        </div>
    </div>
</div>