<div id="dashboardcitas" class="citas">
    <div class="citas__contenedor">
        <?php require_once __DIR__ .'/../../templates/alertas.php'; ?>
        <div class="citas__acciones">
            <div class="citas__crear">
                <a id="crearcita" class="btnsmall" href=""> + Crear</a>
            </div>
            <div class="citas__filtros">
                <div class="citas__contenedorprofesional">
                    <p>Citas por profesional y fecha</p>
                    <div class="citas__profesional">
                        <select class="formulario__select" name="filtro" id="selectprofesional" required>
                            <option value="" disabled selected>-- Seleccione --</option>
                            <?php foreach($profesionales as $profesional):  ?>
                            <option value="<?php echo $profesional->id??''; ?>" > <?php echo $profesional->nombre.' '.$profesional->apellido; ?> </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="citas__consultar">
                            <button class="btnsmall" id="filtros-personalizado">Consultar</button>
                        </div>
                    </div>
                </div>
                <div class="citas__busqueda">
                    <!--<select class="formulario__select" name="filtro" id="selectprofesional" required>
                            <option value="" disabled selected>-- Seleccione --</option>
                            <option value="0" > Cedula </option>
                            <option value="1" > Nombre </option>
                            <option value="2" > Estado </option>
                        </select>-->
                    <form action="/admin/citas/consultaxestadoxname" method="POST">
                        <div class="btn_busqueda">
                            <input class="formulario__input" type="text" name="consulta" placeholder="buscar por estado" required value="<?php echo $estado ?? ''; ?>">
                            <label for="estado"><i class="lupa fa-solid fa-magnifying-glass"></i></label>
                            <input id="estado" type="submit" name="columna" value="estado">
                        </div>
                    </form>
                    <form action="/admin/citas/consultaxestadoxname" method="POST">
                        <div class="btn_busqueda">
                            <input class="formulario__input" type="text" name="consulta" placeholder="buscar por nombre" required value="<?php echo $nombre ?? ''; ?>">
                            <label for="nombre"><i class="lupa fa-solid fa-magnifying-glass"></i></label>
                            <input id="nombre" type="submit" name="columna" value="nombre">
                        </div>
                    </form>
                </div>
            </div>
            <div class="citas__fecha">
                <div class="">
                    <label class="formulario__label" for="fecha">FECHA:</label>
                    <input class="formulario__input" id="fecha" type="date" name="fecha" value="<?php echo $fecha??'';?>">
                </div> 
            </div>
        </div>

        <div class="citas__tabla">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Cedula</th>
                        <th>Servicio</th>
                        <th>Profesional</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>estado</th>
                        <th class="accionesth">Acciones</th>
                    </tr>
                </thead>
                    
                <tbody>
                    <?php foreach($citas as $cita): ?>
                    <tr class="<?php if($cita->id_empserv==null&&$cita->estado=='Pendiente') echo 'reprogramar';
                                    if($cita->estado=='Finalizada') echo 'tr-resaltar'
                                ?>">
                        <td class=""><?php echo $cita->id; ?></td> 
                        <td class=""><?php echo $cita->usuario->nombre.' '.$cita->usuario->apellido; ?></td>         
                        <td class=""><?php echo $cita->usuario->cedula; ?></td>
                        <!--<td class="" data-precio="<?php //echo $cita->servicio->precio??''; ?>"><?php //echo $cita->servicio->nombre??''; ?></td>
                        <td class=""><?php //echo $cita->empleado->nombre??'';?> <?php //echo $cita->empleado->apellido??''?></td> -->
                        <td class="" data-precio="<?php echo $cita->valorcita??''; ?>"><?php echo $cita->nameservicio??''; ?></td>
                        <td class=""><?php echo $cita->nameprofesional??'';?></td>
                        <td class=""><?php echo $cita->fecha_fin; ?></td>         
                        <td class=""><?php echo $cita->hora_fin; ?></td>
                        <td class=""><?php echo $cita->id_empserv==null&&$cita->estado=='Pendiente'?'Out':$cita->estado; //echo $cita->estado; ?></td>
                        <td class="accionestd"> <div class="acciones-iconos" data-promodcto="<?php echo $cita->dcto??'';?>" data-promodctovalor="<?php echo $cita->dctovalor??'';?>"> <i class="finalizado fa-solid fa-check"></i><i class="programar fa-solid fa-tablet-screen-button"></i><i class="cancelado fa-solid fa-x"></i><i class="factura fa-regular fa-note-sticky"></i></div></td>
                    </tr>   
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php echo $paginacion; ?>
    </div> <!-- fin contenedor -->
</div>