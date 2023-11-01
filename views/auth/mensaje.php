<div class="lineaencabezado" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;">
    <h1><?php echo $negocio[0]->nombre??'';?></h1>
</div>

<main class="auth bloqueauth">
    <a class="bloqueauth__logocliente bloqueauth__logosinslider" href="/">
        <img loading="lazy" src="/build/img/<?php echo $negocio[0]->logo??'';?>" alt="Logo Cliente">
    </a>
    <h2 class="auth__heading mensajetitulo" style="color: <?php echo $negocio[0]->colorprincipal??'';?>;"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <div class="auth__mensaje">
        <h3 class="auth__texto">Su cuenta a sido creada con éxito, le hemos enviado un correo electrónico con clave.</h3>
        <p>Ya pudes iniciar sesion y pedir tus citas y disfrutar de todos los beneficios.</p>
    </div>
    <div class="acciones">
        <a href="/login" class="acciones__enlace">Iniciar Sesion</a>
        <a href="/" class="acciones__enlace">Home</a>
    </div>
</main>