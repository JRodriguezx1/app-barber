<main class="auth">
    <a class="btnclose" href="/"><i class="fa-solid fa-circle-xmark"></i></a>
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <p class="auth__texto">Registrate en appbarber</p>

    <?php require_once __DIR__ .'/../templates/alertas.php'; ?>


    <form class="formulario" action="/registro" method="POST">
        <div class="formulario__campo">
            <label class="formulario__label" for="nombre">Nombre</label>
            <input class="formulario__input" type="text" placeholder="Tu Nombre" id="nombre" name="nombre" value="<?php echo $usuario->nombre??'';?>" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="apellido">Apellido</label>
            <input class="formulario__input" type="text" placeholder="Tu Apellido" id="apellido" name="apellido" value="<?php echo $usuario->apellido??'';?>" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="movil">Movil</label>
            <input class="formulario__input" type="number" placeholder="Tu Movil" id="movil" name="movil" value="<?php echo $usuario->movil??'';?>" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input class="formulario__input" type="email" placeholder="Tu Email" id="email" name="email" value="<?php echo $usuario->email??'';?>" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="password">Password</label>
            <input class="formulario__input" type="password" placeholder="Tu Password" id="password" name="password" requiered>
        </div>
        <div class="formulario__campo">
            <label class="formulario__label" for="password2">Repetir Password</label>
            <input class="formulario__input" type="password" placeholder="Repetir Password" id="password2" name="password2" requiered>
        </div>
        <input class="formulario__submit--login" type="submit" value="Crear Cuenta">
    </form>

    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes cuenta? Iniciar Sesion</a>
        <a href="/olvide" class="acciones__enlace">Olvidaste tu password</a>
    </div>
</main>