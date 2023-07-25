<?php

namespace Controllers;

use Model\negocio;
use Model\fidelizacion;
use Model\servicios;
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

    public static function promos(Router $router){
        $promociones = fidelizacion::idregistros('estado', 1);
        foreach($promociones as $promo){
            $promo->nombreproducto = servicios::uncampo('id', $promo->product_serv, 'nombre');
            $promo->precioproducto = servicios::uncampo('id', $promo->product_serv, 'precio');
        }
        $router->render('paginas/promos', ['titulo'=>'app salon', 'promociones'=>$promociones, 'logo'=>negocio::uncampo('id', 1, 'logo')]);
    }
}

?>