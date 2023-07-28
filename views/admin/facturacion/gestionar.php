<div class="fact_gestionar">
    <h2 class="dashboard__heading">Reportes Del Dia</h2>
    <div class="fact_gestionar__contenedor">
        <div><a class="btnsmall" href="/admin/facturacion"><i class="fa-solid fa-arrow-left"></i>Atras</a></div>
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
                </div> <!-- fact_gestionar__infogeneral -->

                <div class="fact_gestionar__tabla">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>Orden:</th>
                                <th>Tipo:</th>
                                <th>Hora Pago:</th>
                                <th>Medio Pago:</th>
                                <th>Costo Servicio:</th>
                                <th>Dcto:</th>
                                <th>Promo:</th>
                                <th>Pago:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($gestionardia as $value): ?>
                                <tr>
                                    <td><?php echo $value->idcita??'';?></td>
                                    <td><?php echo $value->tipo==1?'Cita':'Ordinaria';?></td>
                                    <td><?php echo $value->hora_pago??'';?></td>
                                    <td><?php echo $value->idmediospago??'';?></td>
                                    <td>$<?php echo $value->valor_servicio??'';?></td>
                                    <td>$<?php echo $value->valordcto??'';?></td>
                                    <td>$<?php echo $value->valorpromo??'';?></td>
                                    <td>$<?php echo $value->total??'';?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div> <!-- fin reporte -->
        </div> <!-- fin fact_gestionar__contenido -->
    </div>

</div>