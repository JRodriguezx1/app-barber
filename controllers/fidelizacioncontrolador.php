<?php

namespace Controllers;

use Model\usuarios;
use Model\servicios;
use Model\fidelizacion;
use MVC\Router;  //namespace\clase
 
class fidelizacioncontrolador{

    public static function index(Router $router){
        session_start();
        isadmin();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET' ){
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                if(!is_numeric($id))return;
                $fidelizacion = fidelizacion::find('id', $id);
                $r = $fidelizacion->eliminar_registro();
                if($r){
                    $alertas['exito'][] = "Oferta Eliminada";
                }else{
                    $alertas['error'][] = "Error, intentalo de nuevo";
                }
            }
        }
        $descuentos = fidelizacion::all();
        foreach($descuentos as $dcto){
            $dcto->nombreservicio = servicios::uncampo('id', $dcto->product_serv, 'nombre');
            $dcto->precioservicio = servicios::uncampo('id', $dcto->product_serv, 'precio');
        }
        $router->render('admin/fidelizacion/index', ['titulo'=>'fidelizacion', 'descuentos'=>$descuentos, 'alertas'=>$alertas]);
    }


    public static function creardctoxproduct(Router $router){
        session_start();
        isadmin();
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $existe = fidelizacion::whereArray(['categoria'=>$_POST['categoria'], 'product_serv'=>$_POST['product_serv'], 'estado'=>1]);
            if(empty($existe)){
                $fidelizacion = new fidelizacion($_POST);
                if($_POST['tipo']=='valor'){
                    $fidelizacion->valor = $_POST['dcto1'];
                    $fidelizacion->porcentaje = $_POST['dcto2'];
                }
                if($_POST['tipo']=='porcentaje'){
                    $fidelizacion->valor = $_POST['dcto2'];
                    $fidelizacion->porcentaje = $_POST['dcto1'];
                }
                $r = $fidelizacion->crear_guardar();
                if($r[0]){
                    $servicio = servicios::find('id', $fidelizacion->product_serv);
                    $servicio->id_fidelizacion = $r[1];
                    $a = $servicio->actualizar();
                    if($a){
                        $alertas['exito'][] = "Descuento ingresado exitosamente";
                    }
                }
            }else{
                $alertas['error'][] = "Ya existe oferta vigente para el mismo producto";
            }
        }
        $router->render('admin/fidelizacion/dctoindividual', ['titulo'=>'fidelizacion', 'alertas'=>$alertas]);
    }


    public static function editar_dctoxproduct(Router $router){
        session_start();
        isadmin();
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $fidelizacion = fidelizacion::find('id', $_POST['id']);
            if($_POST['tipo']=='valor'){
                $fidelizacion->valor = $_POST['dcto1'];
                $fidelizacion->porcentaje = $_POST['dcto2'];
            }
            if($_POST['tipo']=='porcentaje'){
                $fidelizacion->valor = $_POST['dcto2'];
                $fidelizacion->porcentaje = $_POST['dcto1'];
            }
            $r = $fidelizacion->Actualizar();
            if($r){$alertas['exito'][] = "Descuento ingresado exitosamente";
            }else{ $alertas['error'][] = "Error Intentalo de nuevo";}
        }
        $descuentos = fidelizacion::all();
        foreach($descuentos as $dcto){
            $dcto->nombreservicio = servicios::uncampo('id', $dcto->product_serv, 'nombre');
            $dcto->precioservicio = servicios::uncampo('id', $dcto->product_serv, 'precio');
        }
        $router->render('admin/fidelizacion/index', ['titulo'=>'fidelizacion', 'descuentos'=>$descuentos, 'alertas'=>$alertas]);  
    }
     
}