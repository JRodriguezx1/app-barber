<?php

namespace Controllers;

use MVC\Router;  //namespace\clase
use Model\negocio;
use Model\empleados;
use Model\malla;
use Model\servicios;
use Model\empserv;
use Model\fechadesc;
use Model\ActiveRecord;
 
class configcontrolador{

    public static function index(Router $router){
        session_start();
        isadmin();
        $alertas = [];
        $negocio = negocio::find('id', 1);
        $servicios = servicios::all();
        $empleados = empleados::all();
        $router->render('admin/configuracion/index', ['titulo'=>'Configuracion', 'negocio'=>$negocio, 'empleados'=>$empleados, 'servicios'=>$servicios, 'alertas'=>$alertas]);
    }

    public static function actualizar(Router $router){ //metodo para el llenado y actualizacion de los datos del negocio
        $alertas = [];
        $servicios = servicios::all();
        $negocio = negocio::find('id', 1);

        if($negocio){ //actualizar
            if($_SERVER['REQUEST_METHOD'] === 'POST' ){
                $negocio->compara_objetobd_post($_POST);
                $alertas = $negocio->validarnegocio();
                if(!$alertas){
                    if($_FILES['logo']['name']){
                        $url_temp = $_FILES["logo"]["tmp_name"];
                        $nombreimg = explode(".", $_FILES['logo']['name']);
                        $nombreimg[0] = "logoapp";
                        unlink($_SERVER['DOCUMENT_ROOT']."/build/img/".$negocio->logo);
                        move_uploaded_file($url_temp, $_SERVER['DOCUMENT_ROOT']."/build/img/".$nombreimg[0].".".$nombreimg[1]);
                        $negocio->logo = "logoapp.".$nombreimg[1];
                    }
                    $r = $negocio->actualizar();
                    if($r)$alertas['exito'][] = "Datos de negocio actualizado";
                }
            }
        }else{  //crear
            if($_SERVER['REQUEST_METHOD'] === 'POST' ){
                $negocio = new negocio($_POST);
                $negocio->logo = $_FILES['logo']['name']; //solo se utiliza para validar el campo
                $alertas = $negocio->validarnegocio();
                if(!$alertas){
                    if($_FILES['logo']['name']){ //valida si se seleccion img en el form
                        $nombreimg = explode(".", $_FILES['logo']['name']);
                        $nombreimg[0] = "logoapp";
                        $existe_archivo1 = file_exists($_SERVER['DOCUMENT_ROOT']."/build/img/logoapp.webp");
                        $existe_archivo2 = file_exists($_SERVER['DOCUMENT_ROOT']."/build/img/logoapp.png");
                        $existe_archivo3 = file_exists($_SERVER['DOCUMENT_ROOT']."/build/img/logoapp.jpg");
                        if($existe_archivo1)unlink($_SERVER['DOCUMENT_ROOT']."/build/img/logoapp.webp");
                        if($existe_archivo2)unlink($_SERVER['DOCUMENT_ROOT']."/build/img/logoapp.png");
                        if($existe_archivo3)unlink($_SERVER['DOCUMENT_ROOT']."/build/img/logoapp.jpg");
                        
                        $url_temp = $_FILES["logo"]["tmp_name"];
                        move_uploaded_file($url_temp, $_SERVER['DOCUMENT_ROOT']."/build/img/".$nombreimg[0].".".$nombreimg[1]);
                    }  
                    $negocio->logo = "logoapp.".$nombreimg[1];
                    $r = $negocio->crear_guardar();
                    if($r)$alertas['exito'][] = "Datos de negocio actualizado";
                    //if($r)header('Location: /admin/dashboard/entrada');
                }
            }
        }

        $router->render('admin/configuracion/index', ['titulo'=>'Configuracion', 'negocio'=>$negocio, 'servicios'=>$servicios, 'alertas'=>$alertas]);
    }


    public static function crear_empleado(Router $router){ //metodo para crear empleado
        $alertas = []; $trabajador = '';
        $servicios = servicios::all();
        $negocio = negocio::find('id', 1);

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $empleado = new empleados($_POST);
            $emplserv = new empserv;

            $skills = explode(',', $_POST['servicios']);
            
            if(!$_POST['servicios'])empleados::setAlerta('error', 'No se ha seleccionado las habilidades');
            $alertas = $empleado->validarempleado();
            
            if(empty($alertas)){
                $r = $empleado->crear_guardar();
                if($r[0]){
                    foreach($skills as $key => $valor)
                        $array[$key] = ['idempleado'=>$r[1], 'idservicio'=>$valor];
                    $r1 = $emplserv->crear_varios_reg($array);
                    if($r1[0])$alertas['exito'][] = "Empleado creado correctamente";     
                }
            }else{
                $trabajador = $empleado;
            }
        }
        $empleados = empleados::all();
        $router->render('admin/configuracion/index', ['titulo'=>'Configuracion', 'negocio'=>$negocio, 'trabajador'=>$trabajador, 'servicios'=>$servicios, 'empleados'=>$empleados, 'alertas'=>$alertas]);
    }


    public static function update_employee(Router $router){

        $alertas = []; $trabajador = '';
        $servicios = servicios::all();
        $negocio = negocio::find('id', 1);

        $empleado = empleados::find('id', $_POST['idempleado']);
        $emplserv = empserv::idregistros('idempleado', $_POST['idempleado']);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $empleado->compara_objetobd_post($_POST);
            $newskills = new empserv;

            $skills = explode(',', $_POST['servicios']);  //$skills = ['1', '2', '3', '4']          
            if(!$_POST['servicios'])empleados::setAlerta('error', 'Debes elegir al menos una habilidad');
            
            $alertas = $empleado->validarempleado();
            if(empty($alertas)){
                $newarray1 = []; $newarray2 = [];
                
                $r = $empleado->actualizar();

                ////traerme los id de habilidades que se quitan del empleado para eliminarlos de la bd///
                foreach($emplserv as $key => $value){
                    $i = true;
                    foreach($skills as $index => $skill)
                        if($value->idservicio == $skill)$i = false;
                    if($i)$newarray1[] = $value->id; //me trae los id de la tabla emplserv que fueron quitados en el campo de habilidades
                }
                

                /////traerme los id de las habilidades agregadas para agregarlo como nuevo////
                foreach($skills as $index => $skill){
                    $x = true;
                    foreach($emplserv as $key => $value)
                        if($value->idservicio == $skill)$x = false;
                    if($x)$newarray2[$index] = ['idempleado' => $_POST['idempleado'], 'idservicio' => $skill];
                }
                 
                if($newarray1)$r1 = empserv::eliminar_idregistros('id', $newarray1);
                if($newarray2)$r2 = $newskills->crear_varios_reg($newarray2);

                if($r)$alertas['exito'][] = "Datos de empleado actualizados";
            }
        }
        $empleados = empleados::all();
        //*******cuando se de un error por falta de habilidades pasar una variable para leer con js para modificar clase css para resaltar la pagina 3
        //******* o validar la actualizacion de empleados por api rest para recargar pagina*/
        $router->render('admin/configuracion/index', ['titulo'=>'Configuracion', 'negocio'=>$negocio, 'trabajador'=>$trabajador, 'servicios'=>$servicios, 'empleados'=>$empleados, 'alertas'=>$alertas]);
    }

    public static function eliminaremployee(Router $router){
        session_start();
        isadmin();
        //$id = $_GET['id'];
        //$id = filter_var($id, FILTER_VALIDATE_INT);  //valida que se a numero entero
        $id = $_GET['id'];
        if(!is_numeric($id))return;
        
        $alertas = []; $trabajador = '';
        $servicios = servicios::all();
        $negocio = negocio::find('id', 1);

        $empleado = empleados::find('id', $id);
        //*****antes de eliminar registro se debe validar en tabla citas si no hay reservancio pendientes con el empleado a eliminar//

        $r = $empleado->eliminar_registro();
        if($r)$alertas['exito'][] = "Empleado eliminado y sus citas";

        $empleados = empleados::all();
        $router->render('admin/configuracion/index', ['titulo'=>'Configuracion', 'negocio'=>$negocio, 'trabajador'=>$trabajador, 'servicios'=>$servicios, 'empleados'=>$empleados, 'alertas'=>$alertas]);
    }

    public static function getAllemployee(){ //api llamada desde empleado.js entrega todos los empleados con sus skills
        $empleados = empleados::all();
        foreach($empleados as $empleado){
            $empleado->idservicios = empserv::idregistros('idempleado', $empleado->id);
            foreach($empleado->idservicios as $servicio)$empleado->servicios[] = servicios::find('id', $servicio->idservicio);
        }
        echo json_encode($empleados);
    }


    public static function actualizarmalla(Router $router){
        $alertas = []; $trabajador = '';
        $servicios = servicios::all();
        $negocio = negocio::find('id', 1);
        $empleados = empleados::all();
        $dias = $_POST['dia']??'';  // = ['lunes'=>'1', 'martes'=>'2',...] los dias a programar en la malla de turnos
        $idempleado = $_POST['idempleado']??'';
        $entrada = $_POST['entrada']??'';
        $inidescanso = $_POST['inidescanso']??'';
        $findescanso = $_POST['findescanso']??'';
        $salida = $_POST['salida']??'';
        
        if($dias)foreach($entrada as $key => $value)$entrada[$key] = str_replace(':', '', $entrada[$key]);

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $malla = new malla;
            $empleado = malla::idregistros('id_empleado', $idempleado);
            if($empleado){
                if($dias!= ''){ //algun dia seleccionado
                    $A = $malla->actualizarmalla($empleado, $dias, $entrada, $inidescanso, $findescanso, $salida);
                    $E = $malla->eliminardias($empleado, $dias);
                    $C = $malla->adicionardias($idempleado, $empleado, $dias, $entrada, $inidescanso, $findescanso, $salida);
                    if($A || $E || $C[0])$alertas['exito'][] = "La malla fue modificada";
                }else{ //cuando se quita o se deselecciona todos los dias
                    $et = malla::eliminar_idregistros('id_empleado', ['id_empleado'=>$idempleado]);
                    if($et)$alertas['error'][] = "El empleado no tiene malla";
                }
            }else{ // llenado por primera vez la malla
                foreach($dias as $key => $dia){
                    $datos[] = ['id_empleado'=>$idempleado, 'id_dia'=>$dia, 'inicioturno'=>$entrada[$key], 'inidescanso'=>$inidescanso[$key], 'findescanso'=>$findescanso[$key], 'finturno'=>$salida[$key], 'intervalo'=>'', 'dato1'=>''];
                }
                $r = $malla->crear_varios_reg($datos);
                if($r)$alertas['exito'][] = "La malla del empleado fue creada";
            }
        }
        $router->render('admin/configuracion/index', ['titulo'=>'Configuracion', 'negocio'=>$negocio, 'trabajador'=>$trabajador, 'servicios'=>$servicios, 'empleados'=>$empleados, 'alertas'=>$alertas]);
    }

    public static function getmalla(){ //api llamada desde malla.js entrega todos los turnos de todos los empleados
        $malla = malla::all();
        foreach($malla as $element){
            $datos['empleado_'.$element->id_empleado][] = $element;
        }
        echo json_encode($datos);
    }


    public static function fechadesc(Router $router){

        $alertas = []; $trabajador = '';
        $servicios = servicios::all();
        $negocio = negocio::find('id', 1);
        $empleados = empleados::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $fechadesc = new fechadesc($_POST);
            $r = $fechadesc->crear_guardar();
            if($r)$alertas['exito'][] = "Fecha de descanso ingresada correctamente";
        }
        $router->render('admin/configuracion/index', ['titulo'=>'Configuracion', 'negocio'=>$negocio, 'trabajador'=>$trabajador, 'servicios'=>$servicios, 'empleados'=>$empleados, 'alertas'=>$alertas]);
    }

    public static function getfechadesc(){ //api llamada desde fechadesc.js entrega todos las fechas de descanso ingresadas de manera manual o especial
        $fechadesc = fechadesc::all();
        if($fechadesc){
            echo json_encode($fechadesc);
        }else{
            echo json_encode(null);
        }
    }

    public static function deletefechadesc(){ //api llamada desde fechadesc.js para eliminar la fecha de descanso manual
        $id = $_GET['id'];
        if(!is_numeric($id))return;
        $fechadesc = fechadesc::find('id', $id);
        $r = $fechadesc->eliminar_registro();
        if($r){ echo json_encode(1);
        }else{ echo json_encode(0); }
    }
     
}