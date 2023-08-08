
<div class="lineaencabezado">
    <h1><?php echo $negocio[0]->nombre??'';?></h1>
</div>

<main class="auth bloqueauth">
    <a class="bloqueauth__logocliente" href="/">
        <img loading="lazy" src="/build/img/<?php echo $negocio[0]->logo??'';?>" alt="Logo Cliente">
    </a>
    <?php
    require_once __DIR__ .'/../templates/alertas.php';
    ?>
    <h2 class="auth__heading bloqueauth__iniciarsesion"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <p class="auth__texto bloqueauth__subtitulo">Recupera tu contraseña</p>
    <form method="POST" action="/olvide" class="formulario bloqueformulario" action="">
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input class="formulario__input" type="email" placeholder="Tu Email" id="email" name="email">
        </div>
        
        <input class="formulario__submit bloqueformulario__submit--login" type="submit" value="Enviar Instrucciones">
    </form>

    <div class="acciones">
        <a href="/registro" class="acciones__enlace">¿Aún no tienes cuenta? <br> Obtener una</a>
        <a href="/olvide" class="acciones__enlace">Olvidaste tu password</a>
    </div>
</main>