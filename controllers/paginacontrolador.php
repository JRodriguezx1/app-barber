<?php

namespace Controllers;

use Model\negocio;
use MVC\Router;



class paginacontrolador{

    public static function index(Router $router) {

        $router->render('paginas/index', ['titulo'=>'app salon', 'logo'=>negocio::uncampo('id', 1, 'logo')]);
    }
}

?>