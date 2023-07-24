<div class="lineaencabezado">
    <h1>Barería Latino Armenia</h1>
</div>
<main class="auth bloqueauth">
    <a class="bloqueauth__logocliente" href="#">
        <img src="/build/img/latino.png" alt="Logo Cliente">
    </a>
    <h2 class="auth__heading bloqueauth__iniciarsesion"><?php echo $titulo; ?></h2>  <!-- en archivo tipografia hay selector que selecciona todos los componentes con class = heading-->
    <p class="auth__texto bloqueauth__subtitulo">Tu cuenta appbarber</p>

    <?php
    require_once __DIR__ .'/../templates/alertas.php';
    ?>

    <?php if(isset($alertas['exito'])){ ?>
    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes cuenta, Inicia Sesion</a>
    </div>

    <?php } ?>
    
</main>