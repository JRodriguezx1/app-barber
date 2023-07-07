
<div class="facturacion">
    <h2 class="dashboard__heading">Historial de pagos</h2>
    <div class="facturacion__contenedor">
    <?php require_once __DIR__ .'/../../templates/alertas.php'; ?>
        <div class="facturacion__filtros">
            <form class="formulario" action="">
                <div class="formulario__campo">
                    <label class="formulario__label" for="fecha">FECHA:</label>
                    <input class="formulario__input" id="fecha" type="date" name="fecha" value="<?php echo $fecha??'';?>">
                </div>
            </form>
            <div class="facturacion__pagar">
                <span id="pagar" class="btnsmall">Registrar Pago</span>
            </div>
        </div>
        <div class="facturacion__tabla">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Fecha</th>
                        <th>Hora de cierre</th>
                        <th>Computarizado</th>
                        <th>Total Dia</th>
                        <th>Estado</th>
                        <th class="accionesth">Acciones</th>
                    </tr>
                </thead>
                    
                <tbody>
                    <?php foreach($alldays as $allday): ?>
                        <tr>
                        <td class=""><?php echo $allday->id; ?></td> 
                        <td class=""><?php echo $allday->fecha??''; ?></td>         
                        <td class=""><?php echo $allday->horacierre??''; ?></td>
                        <td class="">$<?php echo $allday->computarizado??''; ?></td>
                        <td class="">$<?php echo $allday->totaldia??''; ?></td>
                        <td class=""><?php echo $allday->estado??''; ?></td>  
                        <td class="accionestd"> <div class="acciones-iconos"><a class="btnmini" href="/admin/facturacion/gestionar?id=<?php echo $allday->id; ?>">Gestionar</a></div></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>