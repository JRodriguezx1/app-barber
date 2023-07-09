<?php

namespace Controllers;

use Model\servicios;
use MVC\Router;  //namespace\clase
 
class servicioscontrolador{

    public static function index(Router $router){
        session_start();
        isadmin();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $servicios = new servicios($_POST);
            $alertas = $servicios->validarservicios();
            if(empty($alertas)){
                $r = $servicios->crear_guardar();
                if($r){
                    $alertas['exito'][] = "Servicio agregado";
                }else{
                    $alertas['error'][] = "Hubo un error";
                }
            }
        }

        $servicios = servicios::all();
        $router->render('admin/servicios/index', ['titulo'=>'Servicios', 'servicios'=>$servicios, 'alertas'=>$alertas]);
    }


    public static function editar(Router $router){
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $servicios = new servicios($_POST);
            $alertas = $servicios->validarservicios();
            if(empty($alertas)){
                $r = $servicios->actualizar();
                if($r){
                    $alertas['exito'][] = "Servicio actualizado";
                }else{
                    $alertas['error'][] = "Hubo un error";
                }
            }
        }
        $servicios = servicios::all();
        $router->render('admin/servicios/index', ['titulo'=>'Servicios', 'servicios'=>$servicios, 'alertas'=>$alertas]);
    }

    public static function eliminar(){  //api llamada en servicios.js para eliminar servicio
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $servicio = servicios::find('id', $_POST['id']);
            $r = $servicio->eliminar_registro();
            echo json_encode($r);
        }
    }

    public static function getservices(){  //api Rest llamada en facturacion.js para elegir el servicio que se pagara
        $servicios = servicios::all();
        echo json_encode($servicios);
    }
     
} //cierre de la clase