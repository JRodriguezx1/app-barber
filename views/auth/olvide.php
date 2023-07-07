
    <?php
    require_once __DIR__ .'/../templates/alertas.php';
    ?>

<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <p class="auth__texto">Recuperar tu acceso a DevwebCamp</p>
    <form method="POST" action="/olvide" class="formulario" action="">
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input class="formulario__input" type="email" placeholder="Tu Email" id="email" name="email">
        </div>
        
        <input class="formulario__submit" type="submit" value="Eviar Instrucciones">
    </form>

    <div class="acciones">
        <a href="/registro" class="acciones__enlace">¿aun no tienes cuenta? Obtener una</a>
        <a href="/login" class="acciones__enlace">Ya tienes ceunta? Iniciar Sesion</a>
    </div>
</main>