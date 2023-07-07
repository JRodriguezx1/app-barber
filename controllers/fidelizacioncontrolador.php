<?php

namespace Controllers;

use Model\usuarios;
use Model\servicios;
use MVC\Router;  //namespace\clase
 
class fidelizacioncontrolador{

    public static function index(Router $router){
        session_start();
        isadmin();
        $alertas = [];

        $servicios = servicios::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
        }

        $router->render('admin/fidelizacion/index', ['titulo'=>'fidelizacion', 'servicios'=>$servicios, 'alertas'=>$alertas]);
    }

    public static function crear(Router $router){ //metodo para crear dcto global
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            debuguear($_POST);
        }

        $router->render('admin/fidelizacion/index', ['titulo'=>'fidelizacion', 'alertas'=>$alertas]);
    }

    public static function dctoindividual(Router $router){
        session_start();
        isadmin();
        $alertas = [];
        $clientes = usuarios::all();
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
        }

        $router->render('admin/fidelizacion/dctoindividual', ['titulo'=>'fidelizacion', 'clientes'=>$clientes, 'alertas'=>$alertas]);
    }

     
}