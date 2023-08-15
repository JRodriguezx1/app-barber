<header class="dashboard__header">
    <div class="dashboard__header-grid">
        <a href="/"><h2 class="dashboard__logo" style="color: <?php echo $negocio[0]->colorprincipal;?>;">App Salón</h2></a>
        <nav class="dashboard__nav">
            <form class="dashboard__form" method="POST" action="/logout">
                <input class="dashboard__submit--logout" type="submit" value="Cerrar Sesion">
            </form>
        </nav>
    </div>
</header>