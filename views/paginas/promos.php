<div class="lineaencabezado" style="background-color: <?php echo $negocio[0]->colorprincipal;?>;">
    <h1><?php echo $negocio[0]->nombre??'';?></h1>
</div>
<div class="bloquetitulopromo">
    <div class="promotitulo">
        <h2><span>Ofertas y Promociones</span></h2>
        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M480 32c0-12.9-7.8-24.6-19.8-29.6s-25.7-2.2-34.9 6.9L381.7 53c-48 48-113.1 75-181 75H192 160 64c-35.3 0-64 28.7-64 64v96c0 35.3 28.7 64 64 64l0 128c0 17.7 14.3 32 32 32h64c17.7 0 32-14.3 32-32V352l8.7 0c67.9 0 133 27 181 75l43.6 43.6c9.2 9.2 22.9 11.9 34.9 6.9s19.8-16.6 19.8-29.6V300.4c18.6-8.8 32-32.5 32-60.4s-13.4-51.6-32-60.4V32zm-64 76.7V240 371.3C357.2 317.8 280.5 288 200.7 288H192V192h8.7c79.8 0 156.5-29.8 215.3-83.3z"/></svg>
    </div>
    <div class="promodescripcion">
        <p>En esta sesión encontrarás las ofertas y promociones, te invitamos a echar un vistazo y sacar el máximo provecho.</p>
    </div>
</div>


<div class="promociones auth bloqueauth">
    <?php foreach($promociones as $promocion):  ?>
        <div class="bloquepromocion">
            <div class="promocion" style="background-color: <?php echo $negocio[0]->colorprincipal;?>;">
                <div class="bloquepromo">
                    <div class="decripciontituloimagen">
                        <img loading="lazy" src="/build/img/servicio.jpg" alt="Imagen Servicio">
                        
                        <div class="tituloservicio" style="background-color: <?php echo $negocio[0]->colorprincipal;?>;">
                            <p><?php echo $promocion->nombreproducto??'';?></p>
                        </div>
                    </div>
                    <div class="promoinfo">
                        <div class="promoprecio">
                            <p>Antes:$<?php echo $promocion->precioproducto??'';?></p>
                            <h3><span>$<?php echo $promocion->precioproducto-$promocion->valor;?></span></h3>
                        </div>
                        <div class="fechavencimiento">
                            <p>Válido hasta: <?php echo $promocion->fecha_fin??'';?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sessiondescuento">
                <a href="/Cliente/app?id=<?php echo $promocion->product_serv.'&valordcto='.$promocion->precioproducto-$promocion->valor;?>">
                    <div class="bloquedescuento">
                        <div class="iconodescuento">
                            <img loading="lazy" src="/build/img/iconodescuento.svg" alt="Icono Descuento Servicio">
                        </div>
                        <div class="descuentoservicio">
                            <p><?php echo $promocion->porcentaje??'';?>% <span>Descuento</span></p>
                        </div>
                        <a class="llamadoaccion" href="/Cliente/app?id=<?php echo $promocion->product_serv.'&valordcto='.$promocion->precioproducto-$promocion->valor;?>">
                            <div class="llamadoaccion1">
                                <p>Aprovecha Ya</p> 
                            </div>
                        </a>
                    </div>
                </a>
            </div>
            
        </div>
    <?php endforeach; ?>
</div>

