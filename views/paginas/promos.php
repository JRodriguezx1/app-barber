<h1>promociones</h1>

<div class="cliente__promociones">
    <?php foreach($promociones as $promocion):  ?>
    <div class="cliente__promocion">
        <p><?php echo $promocion->nombreproducto??'';?> con <?php echo $promocion->porcentaje??'';?>% de descuento</p>
        <p>Hasta <?php echo $promocion->fecha_fin??'';?></p>
    </div>
    <?php endforeach; ?>
</div>