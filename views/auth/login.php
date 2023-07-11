
<main class="auth">
    <a class="auth__btnatras" href="/">Regresar</a>
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <?php include __DIR__. "/../templates/alertas.php"; ?>
    <p class="auth__texto">Iniciar Sesion en appbarber</p>
    <form class="formulario" method="POST" action="/login">
        <div class="formulario__campo">
            <label class="formulario__label" for="">Email</label>
            <input class="formulario__input" type="email" placeholder="Tu Email" id="email" name="email">
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="">Password</label>
            <input class="formulario__input" type="password" placeholder="Tu Password" id="password" name="password">
        </div>
        <input class="formulario__submit--login" type="submit" value="iniciar sesion">
    </form>

    <div class="acciones">
        <a href="/registro" class="acciones__enlace">¿aun no tienes cuenta? Obtener una</a>
        <a href="/olvide" class="acciones__enlace">Olvidaste tu password</a>
    </div>
</main>