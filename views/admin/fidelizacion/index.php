<div class="fidelizacion">
    <h4 class="dashboard__heading2"><i class="fa-solid fa-pen-to-square"></i><?php echo $titulo; ?> </h4>

    <div class="fidelizacion__contenedor">
        <?php require_once __DIR__ .'/../../templates/alertas.php'; ?>
        <div class="fidelizacion__creardescuento">
            <a class="btnsmall" href="<?php echo $user['admin']>2?'/admin/fidelizacion/creardctoxproduct':''?>"><i class="fa-solid fa-plus"></i> Crear Descuento</a>
        </div>
        <div class="fidelizacion__tabla">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Categoria</th>
                        <th>Producto</th>
                        <th>Valor</th>
                        <th>Dcto (%)</th>
                        <th>Valor Final</th>
                        <th>Inicio</th>
                        <th>Fecha Fin</th>
                        <th>estado</th>
                        <th class="accionesth">Acciones</th>
                    </tr>
                </thead>
                    
                <tbody>
                    <?php foreach($descuentos as $dcto): ?>
                        <tr class="<?php echo $dcto->estado?'':'reprogramar';?>">
                            <td class=""><?php echo $dcto->id; ?></td> 
                            <td class=""><?php echo $dcto->categoria; ?></td> 
                            <td class=""><?php echo $dcto->nombreservicio; ?></td>
                            <td class="">$<?php echo $dcto->precioservicio; ?></td>
                            <td class=""><?php echo $dcto->porcentaje.'%'.' - $'.$dcto->valor;?></td>
                            <td class="">$<?php echo $dcto->precioservicio-$dcto->valor;?></td>        
                            <td class=""><?php echo $dcto->fecha_ini; ?></td>
                            <td class=""><?php echo $dcto->fecha_fin; ?></td>
                            <td class=""><?php if($dcto->estado==0){echo 'No-activo';}if($dcto->estado==1){echo 'Activo';}if($dcto->estado==2){echo 'Pendiente';}?></td>
                            <?php if($user['admin']>2): ?>
                            <td class="accionestd"> <div class="acciones-iconos" data-id="<?php echo $dcto->id;?>"><?php if($dcto->estado): if($dcto->estado==1){?><i class="sendmsj fa-solid fa-envelope-circle-check"></i><?php } ?><i class="programar fa-solid fa-tablet-screen-button"></i><?php endif; ?><i class="cancelado fa-solid fa-x"></i></div></td>
                            <?php endif; ?>
                        </tr>   
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div> <!-- fin fidelizacion__tabla -->

    </div>
</div>