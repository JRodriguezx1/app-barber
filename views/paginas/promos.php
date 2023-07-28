<h1>promociones</h1>

<div class="promociones">
    <?php foreach($promociones as $promocion):  ?>
    <div class="promocion">
        <p><?php echo $promocion->nombreproducto??'';?> con <?php echo $promocion->porcentaje??'';?>% de descuento</p>
        <p>Hasta <?php echo $promocion->fecha_fin??'';?></p>
    </div>
    <?php endforeach; ?>
</div>