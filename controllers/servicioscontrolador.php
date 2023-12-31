<?php

namespace Controllers;

use Model\servicios;
use Model\fidelizacion;
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
                if($r[0]){
                    $alertas['exito'][] = "Servicio agregado";
                }else{
                    $alertas['error'][] = "Hubo un error";
                }
            }
        }

        $servicios = servicios::all();
        $router->render('admin/servicios/index', ['titulo'=>'Servicios', 'servicios'=>$servicios, 'alertas'=>$alertas, 'user'=>$_SESSION]);
    }


    public static function editar(Router $router){
        session_start();
        isadmin();
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $servicios = new servicios($_POST);
            $alertas = $servicios->validarservicios();
            if(empty($alertas)){
                $r = $servicios->actualizar();
                if($r){
                    $alertas['exito'][] = "Servicio actualizado";
                    /////modificar el valor de dcto de la tabla fidelizacion/////
                    //$dcto = fidelizacion::whereArray(['categoria'=>'servicios', 'product_serv'=>$_POST['id'], 'estado'=>1]);
                    $dcto = fidelizacion::inner_join("SELECT *FROM fidelizacion WHERE categoria = 'servicios' AND product_serv = {$_POST['id']} AND estado IN(1, 2)");
                    if($dcto){
                        $dcto[0]->valor = round(($dcto[0]->porcentaje*($servicios->precio))/100);
                        $r1 = $dcto[0]->actualizar();
                        if($r1){
                            $alertas['exito'][] = "Descuento en la promocion tambien actualizada";
                        }else{ $alertas['error'][] = 'Volver actualizar datos de servicio, ya no se actualizo en la promocion';}
                    }
                }else{
                    $alertas['error'][] = "Hubo un error";
                }
            }
        }
        $servicios = servicios::all();
        $router->render('admin/servicios/index', ['titulo'=>'Servicios', 'servicios'=>$servicios, 'alertas'=>$alertas, 'user'=>$_SESSION]);
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