<?php //['error'][] as ['error] => ['strin1', 'string2'...]
    foreach($alertas as $key => $alerta){ //$key => [error],   $alerta = ['strin1', 'string2'...]
        foreach($alerta as $mensaje){  //   $mensaje = 'string1'.. 'string2'   
            ?>
            <div class="alerta alerta__<?php echo $key ?>"> <!-- echo $key = clase deacuerdo al tipo de alerta si es un error etc -->
                <?php echo $mensaje;   ?>
            </div>

            <?php

        }
}

?>