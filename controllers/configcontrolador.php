<?php

namespace Controllers;

use MVC\Router;  //namespace\clase
use Model\usuarios;
use Model\empleados;
use Model\negocio;
use Model\servicios;
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

    public static function habilitarempleado(){ //apì llamada desde configuracion.js
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = usuarios::find('empleadoid', $_POST['idempleado']);
            $empleado = empleados::find('id', $_POST['idempleado']);
            if(!empty($usuario)){
                $usuario->password = 'Empleado';
                if($_POST['tiporol']==2)$usuario->password = 'Administrador';
                $usuario->admin = $_POST['tiporol'];
                $usuario->hashPassword();
                $usuario->confirmado = 1;
                $usuario->habilitar = 1;
                $r = $usuario->actualizar();
                $empleado->restriccion = $_POST['tiporol'];
                $re = $empleado->actualizar();
                if($r){ 
                    
                    echo json_encode(true);
                }else{
                    echo json_encode(false);
                }
            }else{
                echo json_encode(false);
            }   
        }
    }

    public static function crearuserprincipal(Router $router){ //metodo para crear usuario principal
        session_start(); 
        isadmin();
        $alertas = [];
        $empleado = new empleados($_POST);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $alertas = $empleado->validarempleado();
            $alertas = $empleado->validarEmail();
            if(empty($alertas)){
                $empleado->restriccion = 3;
                $r = $empleado->crear_guardar();
                if($r[0]){
                    $usuario = new usuarios($_POST);
                    $usuario->password2 = $_POST['password'];
                    $usuario->hashPassword();
                    $usuario->confirmado = 1;
                    $usuario->admin = 3;
                    $usuario->empleadoid = $r[1];
                    $usuario->habilitar = 1;
                    $r1 = $usuario->crear_guardar();
                    if($r1[0]){
                        $alertas['exito'][] = "Usuario principal dado de alta";
                    }else{ $alertas['error'][] = "Hubo un error fatal";}
                }else{ $alertas['error'][] = "Hubo un error fatal";}
            }
        }

        $negocio = negocio::find('id', 1);
        $servicios = servicios::all();
        $empleados = empleados::all();
        $mediospago = mediospago::all();
        $router->render('admin/adminconfig/index', ['titulo'=>'Administracion', 'negocio'=>$negocio, 'empleados'=>$empleados, 'servicios'=>$servicios, 'mediospago'=>$mediospago, 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }

    public static function setearpass(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = usuarios::find('empleadoid', $_POST['idempleado']);
            if($usuario->confirmado&&$usuario->habilitar){
                if($usuario->admin==1)$usuario->password = 'Empleado';
                if($usuario->admin>1)$usuario->password = 'Administrador';
                $usuario->hashPassword();
                $r = $usuario->actualizar();
                if($r){
                    echo json_encode(true);
                }else{echo json_encode(false);}
            }else{echo json_encode(false);}
        }
    }

    public static function coloresapp(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $negocio = negocio::find('id', 1);
            $negocio->compara_objetobd_post($_POST);
            $r = $negocio->actualizar();
            if($r){
                echo json_encode(true);
            }else{ echo json_encode(false); }
        }
    }


}