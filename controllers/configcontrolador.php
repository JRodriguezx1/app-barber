<?php

namespace Controllers;

use MVC\Router;  //namespace\clase
use Model\negocio;
use Model\empleados;
use Model\malla;
use Model\servicios;
use Model\empserv;
use Model\fechadesc;
use Model\ActiveRecord;
use Model\mediospago;
 
class configcontrolador{

    public static function getmediospago(){  //api que retorna los medios pago, llamado desde configuracion.js
        $mediospago = mediospago::all();
        echo json_encode($mediospago);
    }

    public static function setmediospago(){  //api que retorna los medios pago, llamado desde configuracion.js
        $mediospago = mediospago::all();
        $length = count($mediospago);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $idsok = $_POST['ids']; // = string = "1,5,4"
            $arrayidsok = explode(',', $idsok); // = ['1', '5', '4']

            for($i = 1; $i<=$length; $i++){
                $ids[] = $i;
                $existe = in_array("{$i}", $arrayidsok);
                if($existe){
                    $campo['estado'][] = 1;
                }else{
                    $campo['estado'][] = 0;
                }
            }
            $r = mediospago::updatemultiple($campo, $ids);
        }
        echo json_encode($r);
    }
}