<div class="citas">
    <div class="citas__contenedor">
        <?php include __DIR__. "/../../templates/alertas.php"; ?>
        <div class="citas__acciones">
            <div class="text-center"><a class="btnsmall" href="/admin/fidelizacion"><i class="fa-solid fa-arrow-left"></i>Atras</a></div>
            <div class="citas__filtros">
                <div class="citas__busqueda">
                    <form action="/admin/clientes" method="POST">
                        <select class="formulario__select" name="filtro" id="selectprofesional" required>
                            <option value="" disabled selected>-- Seleccione --</option>
                            <!--<option value="all" > All </option>-->
                            <option value="nombre" > Nombre </option>
                            <option value="email" > Email </option>
                            <option value="movil" > Movil </option>
                        </select>
                        <div class="btn_busqueda">
                            <input class="formulario__input" type="text" name="buscar" placeholder="buscar" required value="<?php echo $info[0]->buscar ?? ''; ?>">
                            <label for="busqueda"><i class="lupa fa-solid fa-magnifying-glass"></i></label>
                            <input id="busqueda" type="submit" value="Buscar">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="dctoindividual">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Fecha Fin</th>
                        <th>Oferta</th>
                        <th>Dcto</th>
                        <th class="accionesth">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clientes as $cliente): ?>
                        <tr> 
                            <td class=""><?php echo $cliente->id; ?></td> 
                            <td class=""><?php echo $cliente->nombre.' '.$cliente->apellido; ?></td> 
                            <td class=""><?php echo $cliente->fecha??''; ?></td>         
                            <td class=""><?php echo $cliente->fechafin??''; ?></td> 
                            <td class=""><?php echo $cliente->email; ?></td> 
                            <td class=""><?php echo $cliente->dcto??''; ?></td>       
                            <td class="accionestd"> <div data-id="<?php echo $cliente->id;?>" class="acciones-iconos"> <i id="editar" class="finalizado fa-solid fa-pen-clip"></i><i id="eliminar" class="cancelado fa-solid fa-x"></i></div></td>    
                        </tr>
                    <?php endforeach; ?>
                    
                </tbody>
            </table>
        </div> <!-- fin clientes -->
    </div> <!-- fin citas contenedor -->
</div>