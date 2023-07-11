<div class="fact_gestionar">
    <h2 class="dashboard__heading">Reportes Del Dia</h2>
    <div class="fact_gestionar__contenedor">
    <div ><a class="btnsmall" href="/admin/facturacion"><i class="fa-solid fa-arrow-left"></i>Atras</a></div>
        <div class="fact_gestionar__contenido">
            <div class="fact_gestionar__cierre">
                <form class="formulario" action="" method="POST">
                    <H4 class="fact_gestionar__heading">Denominaciones</H4>
                        <div class="formulario__campo">
                            <label class="formulario__label" for="descripcion">$ 100.000</label>
                            <input id="negocio" class="formulario__input" type="number" min="0" placeholder="Numero de billetes" id="descripcion" name="d100" value="<?php echo $caja->d100??''; ?>">
                        </div>
                        <div class="formulario__campo">
                            <label class="formulario__label" for="descuento">$ 50.000</label>
                            <input id="negocio" class="formulario__input" type="number" min="0" placeholder="Numero de billetes" id="descuento" name="d50" value="<?php echo $caja->d50 ?? '';?>">
                        </div>
                        <div class="formulario__campo">
                            <label class="formulario__label" for="descuento">$ 20.000</label>
                            <input id="negocio" class="formulario__input" type="number" min="0" placeholder="Numero de billetes" id="descuento" name="d20" value="<?php echo $caja->d20 ?? '';?>">
                        </div>
                        <div class="formulario__campo">
                            <label class="formulario__label" for="descuento">$ 10.000</label>
                            <input id="negocio" class="formulario__input" type="number" min="0" placeholder="Numero de billetes" id="descuento" name="d10" value="<?php echo $caja->d10 ?? '';?>">
                        </div>
                        <div class="formulario__campo">
                            <label class="formulario__label" for="descuento">$ 5.000</label>
                            <input id="negocio" class="formulario__input" type="number" min="0" placeholder="Numero de billetes" id="descuento" name="d5" value="<?php echo $caja->d5 ?? '';?>">
                        </div>
                        <div class="formulario__campo">
                            <label class="formulario__label" for="descuento">Total Del Dia:</label>
                            <input id="negocio" class="formulario__input" type="number" min="0" placeholder="Total Efectivo" id="descuento" name="totaldia" value="<?php echo $caja->totaldia ?? '';?>" required>
                        </div>
                        <?php date_default_timezone_set('America/Bogota'); ?>
                        <input type="hidden" name="horacierre" value="<?php echo date('h:i:s'); ?>">
                        <input type="hidden" name="estado" value="Cerrado">
                        <input class="formulario__submit" type="submit" value="Cierre del dia">
                </form>
            </div>
            <div class="fact_gestionar__reporte">
                <!--<H4 class="fact_gestionar__heading">Servicios Realizados(Citas - Ordinarios)</H4>-->
                <h4 class="fact_gestionar__heading">Informacion General <span class="fact_gestionar__fecha"><?php echo $fecha; ?></span></h4>
                <div class="fact_gestionar__infogeneral">
                    <div class="fact_gestionar__datos">
                        <div class="fact_gestionar__dato">
                            <p>servicos Realizados:</p>
                            <span><?php echo $numservicios??''; ?></span>
                        </div>
                        <div class="fact_gestionar__dato">
                            <p>servicos Faturados:</p>
                            <span>$<?php echo $valorservicios??''; ?></span>
                        </div>
                    </div>
                    <div class="fact_gestionar__datos">
                        <div class="fact_gestionar__dato">
                            <p>Descuentos Aplicados:</p>
                            <span><?php echo $numdctos??''; ?></span>
                        </div>
                        <div class="fact_gestionar__dato">
                            <p>Total Descuento:</p>
                            <span>$<?php echo $valordctos??''; ?></span>
                        </div>
                    </div>
                    <div class="fact_gestionar__datos">
                        <div class="fact_gestionar__dato">
                            <p>Recibido:</p>
                            <span>$<?php echo $recibidos??''; ?></span>
                        </div>
                        <div class="fact_gestionar__dato">
                            <p>Devolucion:</p>
                            <span>$<?php echo $devoluciones??''; ?></span>
                        </div>
                    </div>
                    <div class="fact_gestionar__datos">
                        <div class="fact_gestionar__dato">
                            <p>Total:</p>
                            <span>$<?php echo $total??''; ?></span>
                        </div>
                    </div>
                </div>
                <ul  class="reporte-listas">
                    <?php foreach($gestionardia as $value): ?>
                        <li  class="reporte-lista">
                            <div class="lista">
                                <p>Orden: <span><?php echo $value->idcita??''; ?></span></p>
                                <p>Tipo: <span><?php echo $value->tipo==1?'Cita':'Casual'; ?></span></p>
                                <p>Hora Pago: <span><?php echo $value->hora_pago??''; ?></span></p>
                                <p>Costo Servicio: <span>$<?php echo $value->valor_servicio??''; ?></span></p>
                                <p>Dcto: <span><?php echo $value->dcto??''; ?></span></p>
                                <p>Pago: <span>$<?php echo $value->total??''; ?></span></p>
                            </div>
                            <div>

                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

</div>