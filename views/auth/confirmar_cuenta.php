<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <p class="auth__texto">Tu cuenta appbarber</p>

    <?php
    require_once __DIR__ .'/../templates/alertas.php';
    ?>

    <?php if(isset($alertas['exito'])){ ?>
    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes cuenta, Inicia Sesion</a>
    </div>

    <?php } ?>
    
</main>