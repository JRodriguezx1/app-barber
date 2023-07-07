<div class="clientes"></div>
<div class="citas">
    <div class="citas__contenedor">
        <?php include __DIR__. "/../../templates/alertas.php"; ?>
        <div class="citas__acciones">
            <div class="citas__crear">
                <span id="crearcliente" class="btnsmall"> + Crear Cliente</span>
            </div>
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

        <div class="clientes">
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
                            <td class="accionestd"> <div data-id="<?php echo $cliente->id;?>" class="acciones-iconos"> <i id="editar" class="finalizado fa-solid fa-pen-clip"></i><a href="/admin/clientes/detalle?id=<?php echo $cliente->id;?>"><i class="programar fa-solid fa-tablet-screen-button"></i></a><i id="eliminar" class="cancelado fa-solid fa-x"></i></div></td>    
                        </tr>
                    <?php endforeach; ?>
                    <!--
                <tr> 
                    <td class=""> id </td> 
                    <td class=""> lupe luli </td>         
                    <td class=""> lulu</td>
                    <td class=""> lulusito@cat.com </td> 
                    <td class=""> 3125571856 </td>         
                    <td class=""> <i class="fa-solid fa-xmark"></i><i class="fa-solid fa-user-check"></i><i class="fa-solid fa-check"></i></td>    
                </tr>
                <tr>
                    <td class=""> id </td> 
                    <td class=""> lupe luli </td>         
                    <td class=""> lulu</td>
                    <td class=""> lulusito@cat.com </td> 
                    <td class=""> 3125571856 </td>
                    <td class=""> <i class="fa-solid fa-x"></i><i class="fa-solid fa-file-pen"></i> <i class="fa-solid fa-pen-clip"></i> <i class="fa-solid fa-tablet-screen-button"></i></td>
                </tr>
                -->
                </tbody>
            </table>
        </div> <!-- fin clientes -->
    </div> <!-- fin citas contenedor -->
</div>