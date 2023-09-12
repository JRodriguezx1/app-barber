<div id="dash-cliente" class="cliente">
    <div class="cliente__contenedor">
        <div class="cliente__paginas">
            <nav class="cambiopaginas">
                <span id="pagina1">Asignar Cita</span>
                <span id="pagina2">Historial Citas</span>
                <span class="<?php echo $classjs??''; //para q js lo lea y mantega esta pagina view ?>" id="pagina3">Perfil</span>
            </nav>
        </div>
        <?php require_once __DIR__ .'/../templates/alertas.php'; ?>
        <div class="paginas pagina1 pagina1cita">
            <?php if($dctospromos):?>
                <a href="/promos">
                    <div class="cliente__linkpromos">
                        <p class="cliente__linkpromo" ><span>Ofertas y Promociones Vigentes</span> | Ingresa para ver los beneficio que tenemos para tí</p>
                    </div>
                </a>
            <?php endif; ?>
            <div class="cliente__informacion">
                <div class="cliente__heading" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;"><h4>Información Personal</h4></div>
                <div class="cliente__datos">
                    <div class="cliente__dato">
                        <p>Nombre: </p>
                        <span><?php echo $usuario->nombre.' '.$usuario->apellido; ?></span>
                    </div>
                    <div class="cliente__dato">
                        <p>Cédula: </p>
                        <span><?php echo $usuario->cedula??''; ?></span>
                    </div>
                    <div class="cliente__dato">
                        <p>Movil: </p>
                        <span id="telcliente"><?php echo $usuario->movil??''; ?></span>
                    </div>
                    <div class="cliente__dato">
                        <p>Email: </p>
                        <span><?php echo $usuario->email??''; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="cliente__cita">
                <div class="cliente__heading" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;"><h4>Reservar Cita</h4></div>
                
                <div class="cliente__disponibilidad">
                    <div class="cliente__select">
                        <div class="formulario__campo">
                            <label class="formulario__label" for="servicio">Seleccione Servicio: </label>
                            <select class="formulario__select" name="servicio" id="servicio" required>
                                <option value="" disabled selected> -Selecionar- </option>
                                <?php foreach($servicios as $service): ?>
                                    <?php if($service->estado): ?>
                                    <option value="<?php echo $service->id;?>"><?php echo $service->nombre;?> - $<?php echo $service->precio;?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="formulario__campo">
                            <label class="formulario__label" for="profesional">Seleccione Profesional: </label>
                            <select class="formulario__select" name="profesional" id="profesional" required>
                                <option value="" disabled selected> -Selecionar- </option>
                                
                            </select>
                        </div>
                        <div class="formulario__campo">
                            <label class="formulario__label" for="fecha">Seleccionar Fecha:</label>
                            <input class="formulario__input" id="date" type="date">
                        </div>
                    </div>
                    
                    <div class="cliente__horas">
                        <div class="headinghoras"><p class="text-center">Horario disponible Formato 24 Horas</p></div>
                        <div class="headinghoras">
                        <p class="text-center">Lugar: Centro de Armenia cr 14 # 18-5 esquina centro comercial</p>
                        </div>
                        <div class="cliente__campohoras" id="horas">

                        </div>
                    </div>
                </div> <!-- fin disponibilidad -->
            </div> <!-- fin cita -->
        </div> <!-- fin pagina1 -->
        <div class="paginas pagina2 pagina2historialcitas">
            <div class="cliente__heading" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;"><h4>Consulta y Cancelación de Citas</h4></div>
            <div class="cliente__nombrecliente"><p><?php echo $usuario->nombre.' '.$usuario->apellido; ?></p></div>
            <!-- poner mensaje de que las citas se pueden cancelar 2 horas antes o un dia antes -->
            <div class="cliente__tablahistorial">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Profesional</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Orden</th>
                            <th>Valor</th>
                            <th>Estado</th>
                            <th class="accionesth">Acciones</th>
                        </tr>
                    </thead>
                        
                    <tbody>
                        <?php foreach($citas as $cita): ?>
                        <tr class="<?php if($cita->id_empserv==null&&$cita->estado=='Pendiente')
                                        {
                                            echo 'reprogramar';
                                        }else{
                                            if($cita->estado == 'Cancelado')echo 'tr-resaltar';
                                        }
                                    ?>">               
                            <td class=""><?php echo $cita->nameservicio??''; ?></td>
                            <td class=""><?php echo $cita->nameprofesional??''; ?></td> 
                            <td class=""><?php echo $cita->fecha_fin; ?></td>         
                            <td class=""><?php echo substr($cita->hora_fin, 0, 5); ?></td>
                            <td class=""><?php echo $cita->id; ?></td> 
                            <td class="">$<?php echo $cita->valorcita; ?></td>
                            <td class=""><?php echo $cita->id_empserv==null&&$cita->estado=='Pendiente'?'Out':$cita->estado; ?></td>
                            <!--<td class="accionestd"> <div class="acciones-iconos" data-id="<?php //echo $cita->id; ?>"><i class="programar fa-solid fa-tablet-screen-button"></i><i class="cancelado fa-solid fa-x"></i></div></td>-->
                            <td class="accionestd"> <div class="acciones-iconos" data-id="<?php echo $cita->id; ?>"><span id="cancelado" class="btnmini">Cancelar</span></div></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div><!-- fin pagina2 -->
        <div class="paginas pagina3 pagina3perfil">
            <div class="pagina3perfil__heading"><h4>Tus Datos</h4></div>
            <form class="formulario" action="/Cliente/app" method="POST">
                <div class="formcampospagina3">
                    <div class="formulario__campo">
                        <label class="formulario__label" for="nombre">* Nombre</label>
                        <input class="formulario__input" type="text" placeholder="Tu Nombre" id="nombre" name="nombre" value="<?php echo $usuario->nombre??'';?>" requiered>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="apellido">* Apellido</label>
                        <input class="formulario__input" type="text" placeholder="Tu Apellido" id="apellido" name="apellido" value="<?php echo $usuario->apellido??'';?>" requiered>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="cedula">Cédula</label>
                        <input class="formulario__input" type="number" placeholder="Tu Cedula" id="cedula" name="cedula" value="<?php echo $usuario->cedula??'';?>" >
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="movil">* Movil</label>
                        <input class="formulario__input" type="number" placeholder="Tu Movil" id="movil" name="movil" value="<?php echo $usuario->movil??'';?>" requiered>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="email">* Email</label>
                        <input class="formulario__input" type="email" placeholder="Tu Email" id="email" name="email" value="<?php echo $usuario->email??'';?>" requiered>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="ciudad">* Ciudad</label>
                        <input class="formulario__input" type="text" placeholder="Ciudad de residencia" id="ciudad" name="ciudad" value="<?php echo $usuario->ciudad??'';?>" requiered>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="direccion">Dirección</label>
                        <input class="formulario__input" type="text" placeholder="Direccion en donde vivdes" id="direccion" name="direccion" value="<?php echo $usuario->direccion??'';?>" >
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="password">* Contraseña</label>
                        <input class="formulario__input" type="password" placeholder="Ingresa tu Contraseña" id="password" name="validarpassword" requiered>
                    </div>
                </div>
                <input class="formulario__submit--login" type="submit" value="Actualizar" style="background-color: <?php echo $negocio[0]->colorprincipal??'';?>;">
            </form>
        </div>
        

    </div> <!-- fin contenedor -->
</div>