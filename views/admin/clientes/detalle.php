<div class="detalle">
    <div class="detalle__atras"><a class="btnsmall" href="/admin/clientes"><i class="fa-solid fa-arrow-left"></i> Atras</a></div>
    <div class="detalle__contenedor">
        <div class="detalle__datospersonal">
            <h4 class="detalle__heading">Datos Personales del Cliente</h4>
            <div class="detalle__dato detalle__nombre">
                <div class="detalle__icon"><i class="fa-regular fa-user"></i></div>
                <?php echo $cliente->nombre.' '.$cliente->apellido; ?>
            </div>
            <div class="detalle__dato detalle__cc">
                <div class="detalle__icon"><i class="fa-regular fa-address-card"></i></div>
                <?php echo $cliente->cedula; ?>
            </div>
            <div class="detalle__dato detalle__email">
                <div class="detalle__icon"><i class="fa-regular fa-envelope"></i></div> 
                <?php echo $cliente->email; ?>
            </div>
            <div class="detalle__dato detalle__movil">
                <div class="detalle__icon"><i class="fa-solid fa-mobile-screen"></i></div>
                <?php echo $cliente->movil; ?>
            </div>
            <div class="detalle__dato detalle__ciudad">
                <div class="detalle__icon"><i class="fa-regular fa-building"></i></div>
                <?php echo $cliente->ciudad; ?>
            </div>
            <div class="detalle__dato detalle__direccion">
                <div class="detalle__icon"><i class="fa-solid fa-location-dot"></i></div>
                <?php echo $cliente->direccion; ?>
            </div>       
        </div>  <!-- fin datospersonal-->
        
        <div class="detalle__historial">
            <h4 title="Historial de las citas del cliente" class="detalle__heading">Historial</h4>
            <div class="detalle__registros">
                <?php foreach($citas as $cita): ?>
                    <div class="detalle__contenido <?php
                                                        if($cita->estado=='Cancelado')echo 'citacancel';
                                                        if($cita->estado=='Pendiente')echo 'citapendiente';
                                                    ?>">
                        <div class="detalle__registro">
                            <div class="detalle__servicio">
                                <p class="fecha"><?php echo $cita->fecha_fin; ?></p>
                                <p class="servicio"><?php echo $cita->servicio->nombre??''; ?></p>
                                <p class="profesional"><?php echo $cita->empleado??''; ?></p>
                            </div>
                            <div class="detalle__pago">
                                <p>$<?php echo $cita->servicio->precio??''; ?></p>
                            </div>
                        </div>
                        <div class="detalle__deduccion">
                            <p>Deduccion:</p>
                            <p> $<?php echo $cita->facturacion->valordcto??''; ?></p>
                        </div>
                        <div class="detalle__total">
                            <p>total: <span> $<?php echo $cita->facturacion->total??''; ?></span></p>
                        </div>
                    </div>   
                <?php endforeach; ?>
                
            </div>
        </div> <!-- fin detalle historial -->
    </div> <!-- fin detalle contenedor -->
</div>