<div id="dashboardcitas" class="citas">
    <div class="citas__contenedor">
        <?php require_once __DIR__ .'/../../templates/alertas.php'; ?>
        <!--
        <div class="citas__acciones">
            <div class="citas__crear">
                <a id="crearcita" class="btnsmall" href=""> + Crear</a>
                <a id="crearcitanoreg" class="btnsmallred" href=""> No registrado</a>
            </div>
            <div class="citas__filtros">
                <?php if($user['admin']>1): ?>
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
                <?php endif; ?>
                <div class="citas__busqueda">
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
        </div>-->

        <div class="citas__tabla">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        
                        <th>Servicio</th>
                        <th>Profesional</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>estado</th>
                        <th class="accionesth">Acciones</th>
                    </tr>
                </thead>
                    
                <tbody>
                    <?php foreach($citas as $cita):  ?>
                        <tr class="<?php if($cita->id_empserv==null&&$cita->estado=='Pendiente') echo 'reprogramar';
                                        if($cita->estado=='Finalizada') echo 'tr-resaltar'
                                    ?>">
                            <td class="" data-descripcion="<?php echo $cita->descripcion??''; ?>" > <?php echo $cita->id; ?> </td> 
                            <!--<td class=""><?php //echo $cita->usuario->nombre.' '.$cita->usuario->apellido; ?></td>  -->
                            <td class=""><?php echo $cita->nombrecliente??'';?></td>     
                            
                            <!--<td class="" data-precio="<?php //echo $cita->servicio->precio??''; ?>"><?php //echo $cita->servicio->nombre??''; ?></td>
                            <td class=""><?php //echo $cita->empleado->nombre??'';?> <?php //echo $cita->empleado->apellido??''?></td> -->
                            <td class="" data-precio="<?php echo $cita->valorcita??''; ?>"><?php echo $cita->nameservicio??''; ?></td>
                            <td class="" data-idempleado="<?php echo $cita->idempleado??'';?>"><?php echo $cita->nameprofesional??'';?></td>
                            <td class=""><?php echo $cita->fecha_fin; ?></td>         
                            <td class=""><?php echo date("h:i A", strtotime($cita->hora_fin)); ?></td>
                            <td class="" data-tipocita="<?php echo $cita->tipocita; ?>"><?php echo $cita->id_empserv==null&&$cita->estado=='Pendiente'?'Out':$cita->estado; ?></td>
                            <td class="accionestd"> <div class="acciones-iconos" data-promodcto="<?php echo $cita->dcto??'';?>" data-promodctovalor="<?php echo $cita->dctovalor??'';?>"> <i class="finalizado fa-solid fa-check"></i><i class="programar fa-solid fa-tablet-screen-button"></i><i class="cancelado fa-solid fa-x"></i><i class="detallepagocita fa-regular fa-note-sticky"></i></div></td>
                        </tr>  
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php //echo $paginacion; ?>
        <input type="hidden" id="loginuser" value="<?php echo $idempleado; ?>">
        <input type="hidden" id="roluser" value="<?php echo $user['admin']; ?>">
        <div class="citas__controls">
            <div class="citas__btnscrearcitas">
                <a id="crearcita" class="btncrearcita btnsmallazul" href=""> + Crear</a>
                <a id="crearcitanoreg" class="btncrearcita btnsmallazulturquia" href=""> No registrado</a>
            </div>
            <div class="citas__ctrlsfiltros">
                <div class="campos">
                    <label for="todas">Todas</label>
                    <input type="radio" id="todas" name="filtro" value="Todas">
                </div>
                <div class="campos">
                    <label for="finalizadas">Finalizadas</label>
                    <input type="radio" id="finalizadas" name="filtro" value="Finalizada">
                </div>
                <div class="campos">
                    <label for="pendientes">Pendientes</label>
                    <input type="radio" id="pendientes" name="filtro" value="Pendiente" checked>
                </div>
            </div>
        </div>

        <div class="citas__contenido">
            <div class="calendario"><div class="calendar"  id="calendar"></div></div>
            <div class="citas__contenidoul">

                <?php include __DIR__.'/modaldialog.php'; ?>

                <?php
                    date_default_timezone_set('America/Bogota');
                    $fechaactual = new DateTime(date('Y-m-d'));
                    $mes = new IntlDateFormatter('es_ES', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMMM');
                    $diasemana = new IntlDateFormatter('es_ES', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'EEEE');
                 ?>
                <div class="fechaformateada" data-fecha="<?php echo date('Y-m-d');?>">
                    <p class="diasemana"><?php echo $diasemana->format($fechaactual); ?></p>
                    <p class="dia"><?php echo $fechaactual->format('d');?></p>
                    <p class="mesaño"><?php echo $mes->format($fechaactual).' '.$fechaactual->format('Y');?></p>
                </div>
                <ul class="citas__lista">
                    <?php foreach($citas as $cita):  ?>
                    <li class="listacita">
                        <div class="listacita-content">
                            <div class="col1">
                                <p class="nombrecliente"><?php echo $cita->nombrecliente??'';?></p>
                                <p class="fecha"><?php echo $cita->start; ?></p>
                                <p class="servicio"><?php echo $cita->nameservicio??'';?></p>
                            </div>
                            <div class="col2">
                                <p data-id="<?php echo $cita->id;?>" class="estadocita citapendiente"><?php echo $cita->estado??''; ?></p>
                                <p class="hora"><?php echo date("h:i A", strtotime($cita->hora_fin)); ?></p>
                                <i data-id="<?php echo $cita->id;?>" id="reprogramar" class="fa-solid fa-clock"></i>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?> 
                </ul>
            </div>
            
        </div>
    </div> <!-- fin contenedor -->
</div>