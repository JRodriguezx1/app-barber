<?php

namespace Controllers;

use Model\negocio;
use Model\fidelizacion;
//use DateTime;
use MVC\Router;



class paginacontrolador{

    public static function index(Router $router) {
        //verificar la fecha de cada promocion para poner su estado en cero '0', inhabilitado.
        date_default_timezone_set('America/Bogota');
        $promos = fidelizacion::whereArray(['estado'=>1]);
        $fechaactual = new \DateTime(date('Y-m-d'));
        foreach($promos as $key => $promo){
            $fechapromo = new \DateTime($promo->fecha_fin); 
            if($fechaactual>$fechapromo){
                $promo->estado = 0;
                $r = $promo[$key]->actualizar();
            }
        }
        $router->render('paginas/index', ['titulo'=>'app salon', 'logo'=>negocio::uncampo('id', 1, 'logo')]);
    }
}

?>