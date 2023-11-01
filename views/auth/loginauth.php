<div class="lineaencabezado" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;">
    <h1><?php echo $negocio[0]->nombre??'';?></h1>
</div>

<section class="slider">
    <ul class="slider__contenido">
        <li class="slider__slide1 slider__slide"></li>
        <li class="slider__slide2 slider__slide"></li>
    </ul>
</section>

<main class="auth bloqueauth">
    <!-- <a class="auth__btnatras bloqueauth__btnregresar" href="/">Regresar</a> -->
    <a class="bloqueauth__logocliente" href="/">
        <img loading="lazy" src="/build/img/<?php echo $negocio[0]->logo??'';?>" alt="Logo Cliente">
    </a>
    
    <h2 class="auth__heading bloqueauth__iniciarsesion" style="color: <?php echo $negocio[0]->colorprincipal??'';?>;"><?php echo $titulo; ?></h2>
    <?php include __DIR__. "/../templates/alertas.php"; ?>
    <p class="auth__texto bloqueauth__subtitulo">Inicia sesión y reserva tu cita</p>
    <form class="formulario bloqueformulario" method="POST" action="/loginauth">
        <div class="formulario__campo bloqueformulario__campo">
            <label class="formulario__label bloqueformulario__label" for="">Correo Electrónico</label>
            <input class="formulario__input bloqueformulario__input" type="email" placeholder="Ingresa tu correo electrónico" id="email" name="email">
        </div>
        <div class="formulario__campo ">
            <label class="formulario__label" for="">Contraseña</label>
            <input class="formulario__input" type="password" placeholder="Ingresa tu contraseña" id="password" name="password">
        </div>
        <input class="formulario__submit--login bloqueformulario__submit--login" type="submit" value="Iniciar Sesión" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;">
    </form>

    <!--<div class="acciones">
        <a href="/registro" class="acciones__enlace">¿Aún no tienes cuenta? <br> Obtener una</a>
        <a href="/olvide" class="acciones__enlace">Olvidaste tu password</a>
    </div>-->
</main>