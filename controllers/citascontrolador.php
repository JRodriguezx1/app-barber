<?php

namespace Controllers;

use MVC\Router;  //namespace\clase
use Model\empserv;
use Model\empleados;
use Model\servicios;
use Model\citas;
use Model\usuarios;
use Model\fidelizacion;
use Model\facturacion;
use Model\pagosxdia;
use Classes\paginacion;
 
class citascontrolador{

    public static function index(Router $router){
        session_start();
        isadmin();
        $alertas = [];

        //$citas = citas::all();

        $profesionales = empleados::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){ //cuando se actualiza/reprograma cita
            $id = $_POST['id'];
            $citas = citas::find('id', $id);
            $citas->compara_objetobd_post($_POST);  //validar el campo hora
            
            $valorcita = servicios::uncampo('id', $_POST['nameservicio'], 'precio');
            $servicio = servicios::uncampo('id', $_POST['nameservicio'], 'nombre');
            $profesional = empleados::uncampo('id', $_POST['nameprofesional'], 'nombre').' '.empleados::uncampo('id', $_POST['nameprofesional'], 'apellido');
            $citas->valorcita = $valorcita;
            $citas->nameservicio = $servicio;
            $citas->nameprofesional = $profesional;

            $alertas = $citas->validarcitas();
            if(empty($alertas)){
                $r = $citas->actualizar();
                if($r)$alertas['exito'][] = 'Cita Actualizada';
            }
            
        }
        $pagina_actual = $_GET['pagina'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if(!$pagina_actual || $pagina_actual<1)header('Location: /admin/citas?pagina=1');
       
        $registros_por_pagina = 10;
        $registros_total = citas::numregistros();
        $paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total, '/admin/citas');
        if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');
        $citas = citas::paginar($registros_por_pagina, $paginacion->offset()); //metodo paginar es de activerecord

        foreach($citas as $cita){
            $cita->usuario = usuarios::find('id', $cita->id_usuario);
            $cita->usuario->dctogeneral = fidelizacion::find('id', $cita->usuario->idfidelizacion);
            if($cita->id_empserv){
                $cita->idservicio = empserv::uncampo('id', $cita->id_empserv, 'idservicio');
                $cita->idempleado = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
                $cita->servicio = servicios::find('id', $cita->idservicio);
                $cita->empleado = empleados::find('id', $cita->idempleado);
            }
        }

        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>$paginacion->paginacion(), 'alertas'=>$alertas]);
    }


    public static function consultaxprofesxfecha(Router $router){
        $alertas = []; $ids = "";
        
        /*$pagina_actual = $_GET['pagina'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if(!$pagina_actual || $pagina_actual<1)header('Location: /admin/citas?pagina=1');*/
        
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $fecha = $_POST['fecha'];
            $idempserv = empserv::idregistros('idempleado', $_POST['profesional']);
            foreach($idempserv as $key => $value){
                if(array_key_last($idempserv) == $key){
                    $ids.= $value->id;
                }else{
                    $ids.= $value->id.', '; 
                }
            }
        
            /*$citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($ids) AND fecha_fin = '$fecha';");
            $registros_por_pagina = 10;
            $registros_total = count($citas);
            $paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total);
            if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');*/

            //$citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($ids) AND fecha_fin = '$fecha'/* ORDER BY id ASC LIMIT 10 OFFSET {$paginacion->offset()}*/;");
            $citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($ids) AND fecha_fin = '$fecha';");
            foreach($citas as $cita){
                $cita->usuario = usuarios::find('id', $cita->id_usuario);
                $cita->usuario->dctogeneral = fidelizacion::find('id', $cita->usuario->idfidelizacion);
                if($cita->id_empserv){
                    $cita->idservicio = empserv::uncampo('id', $cita->id_empserv, 'idservicio');
                    $cita->idempleado = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
                    $cita->servicio = servicios::find('id', $cita->idservicio);
                    $cita->empleado = empleados::find('id', $cita->idempleado);
                }
            }
        }
        
        $profesionales = empleados::all();
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>'', 'alertas'=>$alertas]);
    }


    public static function filtroxfecha(Router $router){
        session_start();
        isadmin();
        $alertas = [];

        $profesionales = empleados::all();

        /*$pagina_actual = $_GET['pagina'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if(!$pagina_actual || $pagina_actual<1)header('Location: /admin/citas?pagina=1');*/

        $fecha = $_GET['fecha'];
        $citas = citas::idregistros('fecha_fin', $fecha);

        /*$registros_por_pagina = 10;
        $registros_total = count($citas);
        $paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total);
        if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');*/

        //$citas = citas::inner_join("SELECT *FROM citas WHERE fecha_fin = '$fecha' ORDER BY id ASC LIMIT 10 OFFSET {$paginacion->offset()};");
        foreach($citas as $cita){
            $cita->usuario = usuarios::find('id', $cita->id_usuario);
            $cita->usuario->dctogeneral = fidelizacion::find('id', $cita->usuario->idfidelizacion);
            if($cita->id_empserv){
                $cita->idservicio = empserv::uncampo('id', $cita->id_empserv, 'idservicio');
                $cita->idempleado = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
                $cita->servicio = servicios::find('id', $cita->idservicio);
                $cita->empleado = empleados::find('id', $cita->idempleado);
            }
        }

        
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>'', 'fecha'=>$fecha, 'alertas'=>$alertas]);
        //echo json_encode(123);
    }

    public static function crear(Router $router){
        $alertas = [];

        $profesionales = empleados::all();
        date_default_timezone_set('America/Bogota');
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $cita = new citas($_POST); //validar el campo hora
            $valorcita = servicios::uncampo('id', $_POST['nameservicio'], 'precio');
            $servicio = servicios::uncampo('id', $_POST['nameservicio'], 'nombre');
            $profesional = empleados::uncampo('id', $_POST['nameprofesional'], 'nombre').' '.empleados::uncampo('id', $_POST['nameprofesional'], 'apellido');
            $cita->valorcita = $valorcita;
            $cita->nameservicio = $servicio;
            $cita->nameprofesional = $profesional;
            $alertas = $cita->validarcitas();
            if(empty($alertas)){
                $r = $cita->crear_guardar();
                if($r[0])$alertas['exito'][] = "Cita Creada";
            }
        }

        $pagina_actual = $_GET['pagina'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if(!$pagina_actual || $pagina_actual<1)header('Location: /admin/citas?pagina=1');
       
        $registros_por_pagina = 10;
        $registros_total = citas::numregistros();
        $paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total, '/admin/citas');
        if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');
        $citas = citas::paginar($registros_por_pagina, $paginacion->offset()); //metodo paginar es de activerecord

        foreach($citas as $cita){
            $cita->usuario = usuarios::find('id', $cita->id_usuario);
            $cita->usuario->dctogeneral = fidelizacion::find('id', $cita->usuario->idfidelizacion);
            if($cita->id_empserv){
                $cita->idservicio = empserv::uncampo('id', $cita->id_empserv, 'idservicio');
                $cita->idempleado = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
                $cita->servicio = servicios::find('id', $cita->idservicio);
                $cita->empleado = empleados::find('id', $cita->idempleado);
            }
        }
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>$paginacion->paginacion(), 'alertas'=>$alertas]);
    }


    public static function finalizar(Router $router){ //llamado desde finalizcitas.js
        $alertas = [];
        date_default_timezone_set('America/Bogota');
        
        ////buscamos el dia actual el cual se va a finalizar servicio
        $x = pagosxdia::find('fecha', date('Y-m-d'));
        if(!$x){ //si dia no existe lo creamos segun fecha en tabla pagosxdia, por lo general sucede con el primer servicio finalizado
            $pagosxdia = new pagosxdia($array=['totalservices'=>1]); //en el modelo pagosxdias se establece la fecha
            $r1 = $pagosxdia->crear_guardar(); //creamos el dia con la fecha de finalizacion de cita
        }
        $idpagosxdia = $x->id??$r1[1];  // me contiene el id de la tabla pagosxdia segun dia actial o fecha actual

        $profesionales = empleados::all();
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $cita = citas::find('id', $_POST['id']);
            $cita->estado = 'Finalizada';
            $cita->fecha_fin = date('Y-m-d');  //si la cita fue programada para un dia determinado y se realiza antes, la fecha de la cita se corre para el dia en que se paga o realiza
            $r2 = $cita->actualizar();
            if($r2){
                $facturacion = new facturacion($_POST);
                $facturacion->idcita = $_POST['id'];
                $facturacion->id_pagosxdia = $idpagosxdia;
                $facturacion->tipo = 1;  //tipo = 1 es para indicar que es una cita programada
                $r3 = $facturacion->crear_guardar(); //me guarda registro en tabla facturacion
                if($r3[0]){
                    //validar cuantos servicios van de este dia//
                    $numservicios = facturacion::numreg_where('id_pagosxdia', $idpagosxdia);
                    //validar la suma total de los servicios realizados de la columna total de la tabla facturacion
                    $computarizado = facturacion::sumcolum('id_pagosxdia', $idpagosxdia, 'total');
                    $actpagosxdia = pagosxdia::find('id', $idpagosxdia);
                    $actpagosxdia->totalservices = $numservicios;
                    $actpagosxdia->computarizado = $computarizado;
                    $r4 = $actpagosxdia->actualizar();
                    if($r4)$alertas['exito'][] = "Cita Terminada";
                }
            }
        }

        $pagina_actual = $_GET['pagina'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if(!$pagina_actual || $pagina_actual<1)header('Location: /admin/citas?pagina=1');
       
        $registros_por_pagina = 10;
        $registros_total = citas::numregistros();
        $paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total, '/admin/citas');
        if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');
        $citas = citas::paginar($registros_por_pagina, $paginacion->offset());

        foreach($citas as $cita){
            $cita->usuario = usuarios::find('id', $cita->id_usuario);
            $cita->usuario->dctogeneral = fidelizacion::find('id', $cita->usuario->idfidelizacion);
            if($cita->id_empserv){
                $cita->idservicio = empserv::uncampo('id', $cita->id_empserv, 'idservicio');
                $cita->idempleado = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
                $cita->servicio = servicios::find('id', $cita->idservicio);
                $cita->empleado = empleados::find('id', $cita->idempleado);
            }
        }
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>$paginacion->paginacion(), 'alertas'=>$alertas]);
    }

    
    public static function getemployee_services(){  //api rest llamada desde dash_cliente.js
        $empserv = empserv::all();
        foreach($empserv as $value){
            $value->nombre = empleados::uncampo('id', $value->idempleado, 'nombre').' '.empleados::uncampo('id', $value->idempleado, 'apellido');
            $value->servicio = servicios::find('id', $value->idservicio);
        }
        if($empserv){
            echo json_encode($empserv);
        }else{
            echo json_encode(NULL);
        }
    }


     
}