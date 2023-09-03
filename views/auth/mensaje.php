<div class="lineaencabezado" style="background-color: <?php echo $negocio[0]->colorprincipal;?>;">
    <h1><?php echo $negocio[0]->nombre??'';?></h1>
</div>

<main class="auth bloqueauth">
    <a class="bloqueauth__logocliente" href="/">
        <img loading="lazy" src="/build/img/<?php echo $negocio[0]->logo??'';?>" alt="Logo Cliente">
    </a>
    <h2 class="auth__heading mensajetitulo" style="color: <?php echo $negocio[0]->colorprincipal;?>;"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <div class="auth__mensaje">
        <h3 class="auth__texto">Su cuenta a sido creada con éxito, le hemos enviado un correo electrónico de confirmación.</h3>
        <p>Verifique su cuenta a través del enlace en el correo electrónico y confírmela.</p>
        <p>En caso de no recibir el correo en la banjeda de entrada, verifique su carpeta de correo no deseado o comuníquese con el servicio de atención al cliente</p>
    </div>
</main>