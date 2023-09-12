
<div class="lineaencabezado" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;">
    <h1><?php echo $negocio[0]->nombre??'';?></h1>
</div>

<main class="auth bloqueauth">
    <a class="bloqueauth__logocliente bloqueauth__logosinslider" href="/">
        <img loading="lazy" src="/build/img/<?php echo $negocio[0]->logo??'';?>" alt="Logo Cliente">
    </a>
    <?php require_once __DIR__ .'/../templates/alertas.php'; ?>
    <h2 class="auth__heading bloqueauth__iniciarsesion" style="color: <?php echo $negocio[0]->colorprincipal??'';?>;"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <p class="auth__texto bloqueauth__subtitulo">Recupera tu contraseña</p>
    <form method="POST" action="/olvide" class="formulario bloqueformulario" action="">
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input class="formulario__input" type="email" placeholder="Tu Email" id="email" name="email">
        </div>
        
        <input class="formulario__submit bloqueformulario__submit--login" type="submit" value="Enviar Instrucciones" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;">
    </form>

    <div class="acciones">
        <a href="/registro" class="acciones__enlace">¿Aún no tienes cuenta? <br> Obtener una</a>
        <a href="/login" class="acciones__enlace">¿Ya tienes cuenta? <br> Iniciar Sesion</a>
    </div>
</main>