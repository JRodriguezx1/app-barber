
    
<main class="auth">
    
    <h2 class="auth__heading" style="color: <?php echo $negocio[0]->colorprincipal??'';?>;"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <p class="auth__texto">Coloca tu nuevo password</p>
    <?php require_once __DIR__ .'/../templates/alertas.php'; ?>
    <?php if($error){ ?>
    <form method="POST" class="formulario">
        <div class="formulario__campo">
            <label class="formulario__label" for="password">Nuevo password</label>
            <input class="formulario__input" type="password" placeholder="Tu nuevo password" id="password" name="password">
        </div>
        
        <input class="formulario__submit" type="submit" value="Guardar Password">
    </form>
    <?php } ?>

    <div class="acciones">
        <a href="/registro" class="acciones__enlace">¿aun no tienes cuenta? Obtener una</a>
        <a href="/login" class="acciones__enlace">Ya tienes ceunta? Iniciar Sesion</a>
    </div>
</main>