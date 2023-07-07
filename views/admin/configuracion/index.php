<div class="informacion configuración">
    <p>Complete la informacion</p>
    <div class="barraprogreso">
        <div class="barraprogreso__barra">
            <div class="barraprogreso__label">30%</div>
        </div>
    </div>
</div>

<div class="negocio__info">
    <?php include __DIR__. "/../../templates/alertas.php"; ?>
    <div class="negocio__grid">
        <h4>Informacion Del Negocio</h4>
        <div class="negocio__form">
            <form class="formulario" action="/admin/configuracion/actualizar" enctype="multipart/form-data" method="POST">
                <fieldset class="formulario__fieldset">
                    <div class="formulario__campo">
                        <label class="formulario__label" for="nombre">Nombre</label>
                        <div class="formulario__dato">
                            <input id="negocio" class="formulario__input" type="text" placeholder="Nombre del negocio" id="nombre" name="nombre" value="<?php echo $negocio->nombre??''; ?>" required>
                            <label data-num="42" class="count-charts" for="">42</label>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="ciudad">Ciudad</label>
                        <div class="formulario__dato">
                            <input id="negocio" class="formulario__input" type="text" placeholder="ciudad del negocio" id="ciudad" name="ciudad" value="<?php echo $negocio->ciudad ?? '';?>" required>
                            <label data-num="40" class="count-charts" for="">40</label>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="direccion">Direccion</label>
                        <div class="formulario__dato">
                            <input id="negocio" class="formulario__input" type="text" placeholder="Direccion del negocio" id="direccion" name="direccion" value="<?php echo $negocio->direccion ?? '';?>" required>
                            <label data-num="56" class="count-charts" for="">56</label>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="telefono">Telefono</label>
                        <div class="formulario__dato">
                            <input id="negocio" class="formulario__input" type="number" placeholder="telefono fijo de contacto" id="telefono" name="telefono" value="<?php echo $negocio->telefono ?? '';?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="movil">Movil</label>
                        <div class="formulario__dato">
                            <input id="negocio" class="formulario__input" type="number" min="3000000000" max="3777777777" placeholder="Movil de contacto" id="movil" name="movil" value="<?php echo $negocio->movil ?? '';?>" required>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="nit">NIT</label>
                        <div class="formulario__dato">
                            <input id="negocio" class="formulario__input" type="text" placeholder="Nit del negocio" id="nit" name="nit" value="<?php echo $negocio->nit ?? '';?>" required>
                            <label data-num="12" class="count-charts" for="">12</label>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="formulario__fieldset">
                    <legend class="formulario-legend">Redes Sociales</legend>
                    <div class="formulario__campo">
                        <div class="formulario__contenedor-icono">
                            <div class="formulario__icono"><i class="fa-brands fa-whatsapp"></i></div>
                            <input id="negocio" class="formulario__input--sociales" type="number"  min="3000000000" max="3777777777" name="ws" placeholder="whatsapp" value="<?php echo $negocio->ws ?? '';?>" required>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <div class="formulario__contenedor-icono">
                            <div class="formulario__icono"><i class="fa-brands fa-facebook"></i></div>
                            <input id="negocio" class="formulario__input--sociales" type="text" name="facebook" placeholder="Facebook" value="<?php echo $negocio->facebook ?? ''; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <div class="formulario__contenedor-icono">
                            <div class="formulario__icono"><i class="fa-brands fa-instagram"></i></div>
                            <input id="negocio" class="formulario__input--sociales" type="text" name="instagram" placeholder="Instagram" value="<?php echo $negocio->instagram ?? ''; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <div class="formulario__contenedor-icono">
                            <div class="formulario__icono"><i class="fa-brands fa-tiktok"></i></div>
                            <input id="negocio" class="formulario__input--sociales" type="text" name="tiktok" placeholder="tiktok" value="<?php echo $negocio->tiktok ?? ''; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <div class="formulario__contenedor-icono">
                            <div class="formulario__icono"><i class="fa-brands fa-youtube"></i></div>
                            <input id="negocio" class="formulario__input--sociales" type="text" name="youtube" placeholder="Youtube" value="<?php echo $negocio->youtube ?? ''; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <div class="formulario__contenedor-icono">
                            <div class="formulario__icono"><i class="fa-brands fa-twitter"></i></div>
                            <input id="negocio" class="formulario__input--sociales" type="text" name="twitter" placeholder="twitter" value="<?php echo $negocio->twitter ?? ''; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="logo">Logo</label>
                        <input class="formulario__input--file" type="file" id="logo" name="logo">
                    </div>
                    <input class="formulario__submit" type="submit" value="Actualizar">
                </fieldset>
            </form>
        </div>
    </div>
</div>

<div class="configuracion">
    <div class="configuracion__grid">
        <nav class="cambiopaginas">
            <span id="pagina1">Empleados</span>
            <span id="pagina2">Malla</span>
            <span id="pagina3">Fecha desc..</span>
            <span id="pagina4">Update Data</span>
        </nav>
        <!-- crear empleado-->
        <div class="paginas pagina1">
            <h4 class="configuracion__heading">datos del empleado</h4>
            <form class="formulario" action="/admin/configuracion/crear_empleado" method="POST">
                <?php include __DIR__.'/empleado.php'; ?>
                <input class="formulario__submit" type="submit" value="Crear Empleado">
            </form>
        </div>  <!--fin crear empleado-->

        <!--malla-->
        <div class="paginas pagina2 pagina2malla">
            <h4 class="configuracion__heading">malla</h4>
            <form class="formulario" action="/admin/configuracion/actualizarmalla" method="POST">

                <div class="formulario__elegirempleado">
                    <label class="formulario__label" for="categorias">Elegir Empleado: </label>
                    <select class="formulario__select" name="idempleado" id="selectempleadomalla" required>
                        <option value="" disabled selected> Seleccionar Servicio</option>
                        <?php foreach($empleados as $empleado): ?>
                            <option value="<?php echo $empleado->id??''; ?>"><?php echo $empleado->nombre.' '.$empleado->apellido; ?></option>
                        <?php endforeach; ?>
                    </select> 
                </div>

                <div class="malla">
                    <div class="dia">
                        <div class="nombre-dia">
                            <input type="checkbox" data-id="1" id="lunes" name="dia[lunes]" value="1">
                            <label for="lunes">Lunes</label>
                        </div>
                        <div class="horario">
                            <input class="formulario__input" data-id="1" list="listatiempo1" id="entrada" name="entrada[lunes]" disabled placeholder="Hora de entrada" required>
                            <!--<input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Hora de salida" requiered>
                            <input class="formulario__input" list="listatiempo1" id="entrada" name="entrada" placeholder="Inicio de descanso" requiered>
                            <input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Fin de descanso" requiered>-->
                            <select class="formulario__select" data-id="1" id="inidescanso"  name="inidescanso[lunes]" disabled required>
                                <option value="" disabled selected> Inicio de descanso </option>
                            </select> 
                            <select class="formulario__select" data-id="1" id="findescanso" name="findescanso[lunes]" disabled required>
                                <option value="" disabled selected> Fin de descanso </option>
                            </select>
                            <select class="formulario__select" data-id="1" id="salida" name="salida[lunes]" disabled required>
                                <option value="" disabled selected> Hora de salida </option>
                            </select> 
                        </div>
                    </div>
                    <div class="dia">
                        <div class="nombre-dia">
                            <input type="checkbox" data-id="2" id="martes" name="dia[martes]" value="2">
                            <label for="martes">Martes</label>
                        </div>
                        <div class="horario">
                            <input class="formulario__input" data-id="2" list="listatiempo1" id="entrada" name="entrada[martes]" disabled placeholder="Hora de entrada" value="<?php echo $usuario->entrada??''; ?>" required>
                            <!--<input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Hora de salida" requiered>
                            <input class="formulario__input" list="listatiempo1" id="entrada" name="entrada" placeholder="Inicio de descanso" requiered>
                            <input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Fin de descanso" requiered>-->
                            <select class="formulario__select" data-id="2" id="inidescanso"  name="inidescanso[martes]" disabled required>
                                <option value="" disabled selected> Inicio de descanso </option>
                            </select> 
                            <select class="formulario__select" data-id="2"  id="findescanso" name="findescanso[martes]" disabled required>
                                <option value="" disabled selected> Fin de descanso </option>
                            </select>
                            <select class="formulario__select" data-id="2" id="salida" name="salida[martes]" disabled required>
                                <option value="" disabled selected> Hora de salida </option>
                            </select> 
                        </div>
                    </div>
                    <div class="dia">
                        <div class="nombre-dia">
                            <input type="checkbox" data-id="3" id="miercoles" name="dia[miercoles]" value="3">
                            <label for="miercoles">Miercoles</label>
                        </div>
                        <div class="horario">
                            <input class="formulario__input" data-id="3" list="listatiempo1" id="entrada" name="entrada[miercoles]" disabled placeholder="Hora de entrada" value="<?php echo $usuario->entrada??''; ?>" required>
                            <!--<input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Hora de salida" requiered>
                            <input class="formulario__input" list="listatiempo1" id="entrada" name="entrada" placeholder="Inicio de descanso" requiered>
                            <input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Fin de descanso" requiered>-->
                            <select class="formulario__select" data-id="3" id="inidescanso"  name="inidescanso[miercoles]" disabled required>
                                <option value="" disabled selected> Inicio de descanso </option>
                            </select> 
                            <select class="formulario__select" data-id="3" id="findescanso" name="findescanso[miercoles]" disabled required>
                                <option value="" disabled selected> Fin de descanso </option>
                            </select>
                            <select class="formulario__select" data-id="3" id="salida" name="salida[miercoles]" disabled required>
                                <option value="" disabled selected> Hora de salida </option>
                            </select> 
                        </div>
                    </div>
                    <div class="dia">
                        <div class="nombre-dia">
                            <input type="checkbox" data-id="4" id="jueves" name="dia[jueves]" value="4">
                            <label for="jueves">Jueves</label>
                        </div>
                        <div class="horario">
                            <input class="formulario__input" data-id="4" list="listatiempo1" id="entrada" name="entrada[jueves]" disabled placeholder="Hora de entrada" value="<?php echo $usuario->entrada??''; ?>" required>
                            <!--<input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Hora de salida" requiered>
                            <input class="formulario__input" list="listatiempo1" id="entrada" name="entrada" placeholder="Inicio de descanso" requiered>
                            <input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Fin de descanso" requiered>-->
                            <select class="formulario__select" data-id="4" id="inidescanso"  name="inidescanso[jueves]" disabled required>
                                <option value="" disabled selected> Inicio de descanso </option>
                            </select> 
                            <select class="formulario__select" data-id="4" id="findescanso" name="findescanso[jueves]" disabled required>
                                <option value="" disabled selected> Fin de descanso </option>
                            </select>
                            <select class="formulario__select" data-id="4" id="salida" name="salida[jueves]" disabled required>
                                <option value="" disabled selected> Hora de salida </option>
                            </select> 
                        </div>
                    </div>
                    <div class="dia">
                        <div class="nombre-dia">
                            <input type="checkbox" data-id="5" id="viernes" name="dia[viernes]" value="5">
                            <label for="viernes">Viernes</label>
                        </div>
                        <div class="horario">
                            <input class="formulario__input" data-id="5" list="listatiempo1" id="entrada" name="entrada[viernes]" disabled placeholder="Hora de entrada" value="<?php echo $usuario->entrada??''; ?>" required>
                            <!--<input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Hora de salida" requiered>
                            <input class="formulario__input" list="listatiempo1" id="entrada" name="entrada" placeholder="Inicio de descanso" requiered>
                            <input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Fin de descanso" requiered>-->
                            <select class="formulario__select" data-id="5" id="inidescanso"  name="inidescanso[viernes]" disabled required>
                                <option value="" disabled selected> Inicio de descanso </option>
                            </select> 
                            <select class="formulario__select" data-id="5" id="findescanso" name="findescanso[viernes]" disabled required>
                                <option value="" disabled selected> Fin de descanso </option>
                            </select>
                            <select class="formulario__select" data-id="5" id="salida" name="salida[viernes]" disabled required>
                                <option value="" disabled selected> Hora de salida </option>
                            </select> 
                        </div>
                    </div>
                    <div class="dia">
                        <div class="nombre-dia">
                            <input type="checkbox" data-id="6" id="sabado" name="dia[sabado]" value="6">
                            <label for="sabado">Sabado</label>
                        </div>
                        <div class="horario">
                            <input class="formulario__input" data-id="6" list="listatiempo1" id="entrada" name="entrada[sabado]" disabled placeholder="Hora de entrada" value="<?php echo $usuario->entrada??''; ?>" required>
                            <!--<input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Hora de salida" requiered>
                            <input class="formulario__input" list="listatiempo1" id="entrada" name="entrada" placeholder="Inicio de descanso" requiered>
                            <input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Fin de descanso" requiered>-->
                            <select class="formulario__select" data-id="6" id="inidescanso"  name="inidescanso[sabado]" disabled required>
                                <option value="" disabled selected> Inicio de descanso </option>
                            </select> 
                            <select class="formulario__select" data-id="6" id="findescanso" name="findescanso[sabado]" disabled required>
                                <option value="" disabled selected> Fin de descanso </option>
                            </select>
                            <select class="formulario__select" data-id="6" id="salida" name="salida[sabado]" disabled required>
                                <option value="" disabled selected> Hora de salida </option>
                            </select> 
                        </div>
                    </div>
                    <div class="dia">
                        <div class="nombre-dia">
                            <input type="checkbox" data-id="7" id="domingo" name="dia[domingo]" value="7">
                            <label for="domingo">Domingo</label>
                        </div>
                        <div class="horario">
                            <input class="formulario__input" data-id="7" list="listatiempo1" id="entrada" name="entrada[domingo]" disabled placeholder="Hora de entrada" value="" required>
                            <!--<input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Hora de salida" requiered>
                            <input class="formulario__input" list="listatiempo1" id="entrada" name="entrada" placeholder="Inicio de descanso" requiered>
                            <input class="formulario__input" list="listatiempo2" id="salida" name="salida" placeholder="Fin de descanso" requiered>-->
                            <select class="formulario__select" data-id="7" id="inidescanso" name="inidescanso[domingo]" disabled required>
                                <option value="" disabled selected> Inicio de descanso </option>
                            </select> 
                            <select class="formulario__select" data-id="7" id="findescanso" name="findescanso[domingo]" disabled required>
                                <option value="" disabled selected> Fin de descanso </option>
                            </select> 
                            <select class="formulario__select" data-id="7" id="salida" name="salida[domingo]" disabled required>
                                <option value="" disabled selected> Hora de salida </option>
                            </select> 
                        </div>
                    </div>

                </div>
                

                
                <datalist id="listatiempo1">
                    <option value="06:00">
                    <option value="06:30">
                    <option value="07:00">
                    <option value="07:30">
                    <option value="08:00">
                    <option value="08:30">
                    <option value="09:00">
                    <option value="09:30">
                    <option value="10:00">
                    <option value="10:30">
                    <option value="11:00">
                    <option value="11:30">
                    <option value="12:00">
                    <option value="12:30">
                    <option value="13:00">
                    <option value="13:30">
                    <option value="14:00">
                    <option value="14:30">
                    <option value="15:00">
                </datalist>
                
                <div><input class="formulario__submit" type="submit" value="Actualizar Malla"></div>
            </form>

        </div> <!-- fin pagina 2 = malla -->

        <div class="paginas pagina3 descpagina3">
            <h4 class="configuracion__heading">Fechas manuales de descanso</h4>
            <form class="formulario" action="/admin/configuracion/fechadesc" method="POST">
                <div class="registrasfechas">
                    <div class="formulario__campo">
                        <label class="formulario__label" for="categorias">Elegir Empleado: </label>
                        <select class="formulario__select" name="empleado_id" id="selectemployeedate" required>
                            <option value="" disabled selected> Seleccionar Servicio</option>
                            <?php foreach($empleados as $empleado): ?>
                                <option value="<?php echo $empleado->id??'';?>"><?php echo $empleado->nombre.' '.$empleado->apellido; ?></option>
                            <?php endforeach; ?>
                        </select> 
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__label" for="fecha">FECHA:</label>
                        <input class="formulario__input" id="fecha" type="date" name="fecha" required>
                    </div>
                    <div><input class="formulario__submit" type="submit" value="Ingresar fecha"></div>
                </div>
            </form>
            <div class="viewdates">
                
            </div>
        </div>

        <!--update empleado-->
        <div class="paginas pagina4">
            <h4 class="configuracion__heading">Actualizar datos del empleado</h4>
            <form class="formulario" action="/admin/configuracion/update_employee" method="POST">
                
                <div class="formulario__elegirempleado">
                    <label class="formulario__label" for="categorias">Elegir Empleado: </label>
                    <select class="formulario__select" name="idempleado" id="selectemployee" required>
                        <option value="" disabled selected> Seleccionar Servicio</option>
                        <?php foreach($empleados as $empleado): ?>
                            <option value="<?php echo $empleado->id??'';?>"><?php echo $empleado->nombre.' '.$empleado->apellido; ?></option>
                        <?php endforeach; ?>
                    </select> 
                </div>

                <?php include __DIR__.'/empleado.php'; ?>
                <div>
                    <input class="formulario__submit" type="submit" value="Actualizar datos">
                    <a id="eliminaremployee" class="formulario__submit--eliminar" href="">Eliminar</a>
                </div>
                
            </form>
        </div>  <!--fin update empleado-->
    </div> <!--fin configuracion__grid-->
</div>