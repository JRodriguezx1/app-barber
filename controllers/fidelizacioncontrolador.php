<?php

namespace Controllers;

use Model\usuarios;
use Model\servicios;
use Model\fidelizacion;
use DateTime;
use MVC\Router;  //namespace\clase
 
class fidelizacioncontrolador{

    public static function index(Router $router){
        session_start();
        isadmin();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'GET' ){ //cuando se elimina un dcto promo
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
        $router->render('admin/fidelizacion/index', ['titulo'=>'fidelizacion', 'descuentos'=>$descuentos, 'alertas'=>$alertas, 'user'=>$_SESSION]);
    }


    public static function creardctoxproduct(Router $router){
        session_start();
        isadmin();
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            //$existe = fidelizacion::whereArray(['categoria'=>$_POST['categoria'], 'product_serv'=>$_POST['product_serv'], 'estado'=>1]);
            //validar que el dcto a ingresar ya no esta activo o pendiente por activar
            $existe = fidelizacion::inner_join("SELECT *FROM fidelizacion WHERE categoria = '{$_POST['categoria']}' AND product_serv = {$_POST['product_serv']} AND estado IN(1, 2)");
            //validar que la fecha ini no se >= a fecha fin
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
                
                $alertas = $fidelizacion->validarfidelizacion();
                if(empty($alertas)){
                    //validar la fecha antual con la fecha de inicio del dcto para activar o colocar como pendiente
                    $fechaactual = new DateTime(date('Y-m-d'));
                    $fechainicio = new DateTime($_POST['fecha_ini']);
                    if($fechainicio>$fechaactual)$fidelizacion->estado = 2;  //pendiente

                    $r = $fidelizacion->crear_guardar();
                    if($r[0]){
                        $servicio = servicios::find('id', $fidelizacion->product_serv);
                        $servicio->id_fidelizacion = $r[1];
                        $a = $servicio->actualizar();
                        if($a){
                            $alertas['exito'][] = "Descuento ingresado exitosamente";
                        }
                    }
                }
            }else{
                $alertas['error'][] = "Ya existe oferta vigente o pendiente para el mismo producto";
            }
        }
        $router->render('admin/fidelizacion/dctoindividual', ['titulo'=>'fidelizacion', 'alertas'=>$alertas, 'user'=>$_SESSION]);
    }


    public static function editar_dctoxproduct(Router $router){
        session_start();
        isadmin();
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $fidelizacion = fidelizacion::find('id', $_POST['id']);
            $fidelizacion->fecha_fin = $_POST['fecha_fin'];
            if($_POST['tipo']=='valor'){
                $fidelizacion->valor = $_POST['dcto1'];
                $fidelizacion->porcentaje = $_POST['dcto2'];
            }
            if($_POST['tipo']=='porcentaje'){
                $fidelizacion->valor = $_POST['dcto2'];
                $fidelizacion->porcentaje = $_POST['dcto1'];
            }
            
            $r = $fidelizacion->Actualizar();
            if($r){$alertas['exito'][] = "Promocion actualizado exitosamente";
            }else{ $alertas['error'][] = "Error Intentalo de nuevo";}
        }
        $descuentos = fidelizacion::all();
        foreach($descuentos as $dcto){
            $dcto->nombreservicio = servicios::uncampo('id', $dcto->product_serv, 'nombre');
            $dcto->precioservicio = servicios::uncampo('id', $dcto->product_serv, 'precio');
        }
        $router->render('admin/fidelizacion/index', ['titulo'=>'fidelizacion', 'descuentos'=>$descuentos, 'alertas'=>$alertas, 'user'=>$_SESSION]);  
    }
     
}