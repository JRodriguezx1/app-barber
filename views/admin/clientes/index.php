
<div class="clientes">
    <div class="clientes__contenedor">
        <?php include __DIR__. "/../../templates/alertas.php"; ?>
        <div class="clientes__acciones">
            <div class="clientes__crear">
                <span id="crearcliente" class="btnsmall"> + Crear Cliente</span>
            </div>
            
                <div class="clientes__busqueda">
                    <form action="/admin/clientes" method="POST">
                        <select class="formulario__select" name="filtro" id="selectprofesional" required>
                            <option value="" disabled selected>-- Seleccione --</option>
                            <!--<option value="all" > All </option>-->
                            <option value="nombre" > Nombre </option>
                            <option value="email" > Email </option>
                            <option value="movil" > Movil </option>
                        </select>
                        <div class="btn_busqueda">
                            <input class="formulario__input" type="text" name="buscar" placeholder="buscar" required value="<?php echo $buscar ?? ''; ?>">
                            <label for="busqueda"><i class="lupa fa-solid fa-magnifying-glass"></i></label>
                            <input id="busqueda" type="submit" value="Buscar">
                        </div>
                    </form>
                </div>
            
        </div>

        <div class="clientes__tabla">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Movil</th>
                        <th>Email</th>
                        <th class="accionesth">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clientes as $cliente): ?>
                        <tr> 
                            <td class=""><?php echo $cliente->id; ?></td> 
                            <td class=""><?php echo $cliente->nombre; ?></td>         
                            <td class=""><?php echo $cliente->apellido; ?></td> 
                            <td class=""><?php echo $cliente->movil; ?></td> 
                            <td class=""><?php echo $cliente->email; ?></td>        
                            <td class="accionestd"> <div data-id="<?php echo $cliente->id;?>" class="acciones-iconos"> <i id="editar" class="finalizado fa-solid fa-pen-clip"></i><a href="/admin/clientes/detalle?id=<?php echo $cliente->id;?>"><i class="programar fa-solid fa-tablet-screen-button"></i></a><i id="hab_desh" class="<?php echo $cliente->habilitar==1?'cancelado fa-solid fa-x':'habilitar fa-solid fa-check' ?>"></i></div></td>    
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div> <!-- fin clientes__tabla -->
    </div> <!-- fin citas contenedor -->
</div>