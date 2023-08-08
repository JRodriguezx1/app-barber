<?php

namespace Controllers;

use Model\negocio;
use Model\fidelizacion;
use Model\servicios;
//use DateTime;
use MVC\Router;



class paginacontrolador{

    public static function index(Router $router) {
        //verificar la fecha de cada promocion para poner su estado en cero '0', inhabilitado. y para las promos pendientes colocar en '1' para activar
        date_default_timezone_set('America/Bogota');
        //$promos = fidelizacion::whereArray(['estado'=>1]);
        $promos = fidelizacion::inner_join("SELECT *FROM fidelizacion WHERE estado = 1 OR estado = 2;"); //me traigo las promos activas y pendientes
        $fechaactual = new \DateTime(date('Y-m-d'));

        foreach($promos as $key => $promo){
            $fechafinpromo = new \DateTime($promo->fecha_fin);
            $fechainipromo = new \DateTime($promo->fecha_ini);

            if(($fechaactual>=$fechainipromo)&&($fechaactual<=$fechafinpromo)&&($promo->estado==2)){
                $promo->estado = 1;
                $r = $promos[$key]->actualizar();
            } 
            if($fechaactual>$fechafinpromo){ //para finalizar la promo
                $promo->estado = 0;
                $r = $promos[$key]->actualizar();
                $servicio = servicios::find('id_fidelizacion', $promo->id);
                $servicio->id_fidelizacion = null;
                $s = $servicio->actualizar();
            }
        }

        $router->render('paginas/index', ['titulo'=>'app salon', 'logo'=>negocio::uncampo('id', 1, 'logo')]);
    }

    public static function promos(Router $router){ //vista de las promociones
        $promociones = fidelizacion::idregistros('estado', 1);
        foreach($promociones as $promo){
            $promo->nombreproducto = servicios::uncampo('id', $promo->product_serv, 'nombre');
            $promo->precioproducto = servicios::uncampo('id', $promo->product_serv, 'precio');
        }
        $router->render('paginas/promos', ['titulo'=>'app salon', 'promociones'=>$promociones, 'logo'=>negocio::uncampo('id', 1, 'logo')]);
    }
}

?>