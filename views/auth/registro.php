<!-- <div class="bloqueauth__bloqueregresar">
    
</div> -->

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

    <h2 class="auth__heading bloqueauth__iniciarsesion" style="color: <?php echo $negocio[0]->colorprincipal??'';?>;"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <p class="auth__texto bloqueauth__subtitulo">Registrate en appbarber</p>
 
    <?php require_once __DIR__ .'/../templates/alertas.php'; ?>


    <form class="formulario bloqueformulario" action="/registro" method="POST">
        <div class="formulario__campo">
            <label class="formulario__label" for="nombre">Nombre</label>
            <input class="formulario__input" type="text" placeholder="Ingresa tu nombre" id="nombre" name="nombre" value="<?php echo $usuario->nombre??'';?>" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="apellido">Apellido</label>
            <input class="formulario__input" type="text" placeholder="Ingresa tu apellido" id="apellido" name="apellido" value="<?php echo $usuario->apellido??'';?>" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="movil">Número de Teléfono</label>
            <input class="formulario__input" type="number" placeholder="Ingresa tu número de telefónico" id="movil" name="movil" value="<?php echo $usuario->movil??'';?>" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Correo Electrónico</label>
            <input class="formulario__input" type="email" placeholder="Ingresa tu correo electrónico" id="email" name="email" value="<?php echo $usuario->email??'';?>" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="password">Contraseña</label>
            <input class="formulario__input" type="password" placeholder="Contraseña de 4 digitos" id="password" name="password" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '');" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="password2">Repetir Contraseña</label>
            <input class="formulario__input" type="password" placeholder="Confirmar contraseña" id="password2" name="password2" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '');" requiered>
        </div>
        <input class="formulario__submit--login bloqueformulario__submit--login" type="submit" value="Crear Cuenta" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;">
    </form>

    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes cuenta? <br> Iniciar Sesion</a>
        <!--<a href="/olvide" class="acciones__enlace">Olvidaste tu password</a> -->
        <a href="/" class="acciones__enlace">Home</a>
    </div>
</main>