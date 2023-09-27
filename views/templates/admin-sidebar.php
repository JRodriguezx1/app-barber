<aside class="dashboard__sidebar">
    <nav class="dashboard__menu">
        <a href="/admin/dashboard" class="dashboard__enlace <?php echo validar_string_url('/dashboard')?'dashboard__enlace--actual':''; ?>">
            <i class="fa-solid fa-house"></i>
            <span class="dashboard__menu-texto">inicio</span>
        </a>

        <a href="/admin/servicios" class="dashboard__enlace <?php echo validar_string_url('/servicios')?'dashboard__enlace--actual':''; ?>" >
            <i class="fa-solid fa-list"></i>
            <span class="dashboard__menu-texto">servicios</span>
        </a>

        <a href="/admin/facturacion" class="dashboard__enlace <?php echo validar_string_url('/facturacion')?'dashboard__enlace--actual':''; ?>" >
            <i class="fa-solid fa-credit-card"></i>
            <span class="dashboard__menu-texto">facturacion</span>
        </a>

        <a href="/admin/reportes" class="dashboard__enlace <?php echo validar_string_url('/reportes')?'dashboard__enlace--actual':''; ?>" >
            <i class="fa-solid fa-coins"></i>
            <span class="dashboard__menu-texto">reportes</span>
        </a>

        <a href="/admin/citas" class="dashboard__enlace <?php echo validar_string_url('/citas')?'dashboard__enlace--actual':''; ?>" >
            <i class="fa-solid fa-calendar"></i>
            <span class="dashboard__menu-texto">citas</span>
        </a>

        <a href="/admin/clientes" class="dashboard__enlace <?php echo validar_string_url('/clientes')?'dashboard__enlace--actual':''; ?>" >
            <i class="fa-solid fa-users"></i>
            <span class="dashboard__menu-texto">clientes</span>
        </a>

        <a href="/admin/fidelizacion" class="dashboard__enlace <?php echo validar_string_url('/fidelizacion')?'dashboard__enlace--actual':''; ?>" >
            <i class="fa-solid fa-gift"></i>
            <span class="dashboard__menu-texto">fidelizacion</span>
        </a>
        <?php if($user['admin']>2): ?>
        <a href="/admin/adminconfig" class="dashboard__enlace <?php echo validar_string_url('/adminconfig')?'dashboard__enlace--actual':''; ?>" >
            <i class="fa-solid fa-gears"></i>
            <span class="dashboard__menu-texto">administrador</span>
        </a>
        <?php endif; ?>
    </nav>
</aside>