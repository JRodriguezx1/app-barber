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

        if($_SESSION['admin']==1){ // cuando es cuenta empleado
            $empleadoid = usuarios::uncampo('id', $_SESSION['id'], 'empleadoid'); //id del empleado
            $ids_empserv = empserv::multicampos('idempleado', $empleadoid, 'id'); //[24, 25, ..]ids del empleado en relacion con los servicios
            $stridsempserv = implode(', ', $ids_empserv); // = "24, 25, 37, ..."
        }
        $profesionales = empleados::all();

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){ //cuando se actualiza/reprograma cita
            $id = $_POST['id'];
            $citas = citas::find('id', $id);
            $citas->compara_objetobd_post($_POST);  //validar el campo hora
            
            $valorcita = servicios::uncampo('id', $_POST['idservicio'], 'precio');
            $servicio = servicios::uncampo('id', $_POST['idservicio'], 'nombre');
            $profesional = empleados::uncampo('id', $_POST['nameprofesional'], 'nombre').' '.empleados::uncampo('id', $_POST['nameprofesional'], 'apellido');
            $fidelizacion = fidelizacion::whereArray(['categoria'=>'servicios', 'product_serv'=>$_POST['idservicio'], 'estado'=>1]);
            
            $citas->valorcita = $_POST['valorpersonalizado'];
            if($citas->valorcita === '')$citas->valorcita = $valorcita;
            $citas->dcto = 0;
            $citas->dctovalor = 0;
            if($fidelizacion){
                $citas->dcto = $fidelizacion[0]->porcentaje;  //porcentaje del dcto
                $citas->dctovalor = $fidelizacion[0]->valor;   //valor del dcto
            }
            $citas->nameservicio = $servicio;
            $citas->nameprofesional = $profesional;

            $alertas = $citas->validarcitas();
            if(empty($alertas)){
                $r = $citas->actualizar();
                if($r)$alertas['exito'][] = 'Cita Actualizada';
            } 
        }
        
        /*$pagina_actual = $_GET['pagina'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if(!$pagina_actual || $pagina_actual<1)header('Location: /admin/citas?pagina=1');*/
       
        //$registros_por_pagina = 10;
        if($_SESSION['admin']>1){
            $registros_total = citas::numregistros(); //para usuario admin
        }else{ $registros_total = citas::numreg_multiwhere('id_empserv', $ids_empserv); } //para usuario empleado
        
        //$paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total, '/admin/citas');
        //if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');
        
        if($_SESSION['admin']>1){
            //$citas = citas::paginar($registros_por_pagina, $paginacion->offset()); //metodo paginar es de activerecord
        }else{ //para cuenta empleado
            //$citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($stridsempserv) ORDER BY id DESC LIMIT $registros_por_pagina OFFSET {$paginacion->offset()};"); //metodo = ->multipaginar()
        }

        $citas = citas::inner_join("SELECT *FROM citas WHERE estado = 'Pendiente' AND `start` = CURDATE() ORDER BY id ASC;");

        foreach($citas as $cita){
            if($cita->id_empserv){
                $cita->idservicio = empserv::uncampo('id', $cita->id_empserv, 'idservicio');
                $cita->idempleado = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
            }
        }

        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, /*'paginacion'=>$paginacion->paginacion(),*/ 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }


    public static function consultaxprofesxfecha(Router $router){
        session_start();
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
        
            /*$citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($ids) AND start = '$fecha';");
            $registros_por_pagina = 10;
            $registros_total = count($citas);
            $paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total);
            if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');*/

            //$citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($ids) AND start = '$fecha'/* ORDER BY id ASC LIMIT 10 OFFSET {$paginacion->offset()}*/;");
            $citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($ids) AND 'start' = '$fecha';");
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
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>'', 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }


    public static function filtroxfecha(Router $router){
        session_start();
        isadmin();
        $alertas = [];

        if($_SESSION['admin']==1){
            $empleadoid = usuarios::uncampo('id', $_SESSION['id'], 'empleadoid'); //id del empleado
            $ids_empserv = empserv::multicampos('idempleado', $empleadoid, 'id'); //[24, 25, ..]ids del empleado en relacion con los servicios
            $stridsempserv = implode(', ', $ids_empserv);
        }
        $profesionales = empleados::all();

        /*$pagina_actual = $_GET['pagina'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if(!$pagina_actual || $pagina_actual<1)header('Location: /admin/citas?pagina=1');*/

        $fecha = $_GET['fecha'];
        if($_SESSION['admin']>1){
            $citas = citas::idregistros('start', $fecha);
        }else{
            $citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($stridsempserv) AND 'start' = '$fecha';");
        }

        /*$registros_por_pagina = 10;
        $registros_total = count($citas);
        $paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total);
        if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');*/

        //$citas = citas::inner_join("SELECT *FROM citas WHERE start = '$fecha' ORDER BY id ASC LIMIT 10 OFFSET {$paginacion->offset()};");
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

        
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>'', 'fecha'=>$fecha, 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }

    public static function crear(Router $router){
        session_start();
        if($_SESSION['admin']==1){
            $empleadoid = usuarios::uncampo('id', $_SESSION['id'], 'empleadoid'); //id del empleado
            $ids_empserv = empserv::multicampos('idempleado', $empleadoid, 'id'); //[24, 25, ..]ids del empleado en relacion con los servicios
            $stridsempserv = implode(', ', $ids_empserv);  //para filtrar todas las citas y servicios de un empleado
        }
        $alertas = [];

        $profesionales = empleados::all();
        date_default_timezone_set('America/Bogota');
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $cita = new citas($_POST); //validar el campo hora

            $valorcita = servicios::uncampo('id', $_POST['idservicio'], 'precio');
            $servicio = servicios::uncampo('id', $_POST['idservicio'], 'nombre');
            $profesional = empleados::uncampo('id', $_POST['nameprofesional'], 'nombre').' '.empleados::uncampo('id', $_POST['nameprofesional'], 'apellido');
            $fidelizacion = fidelizacion::whereArray(['categoria'=>'servicios', 'product_serv'=>$_POST['idservicio'], 'estado'=>1]);
            
            $cita->valorcita = $_POST['valorpersonalizado'];  //valida si es valor del servicio fijo o personalizado
            if($cita->valorcita==='')$cita->valorcita = $valorcita; //el valor es fijo ya que campo valorpersonalizado no se envia

            if($fidelizacion){
                $cita->dcto = $fidelizacion[0]->porcentaje;  //porcentaje del dcto
                $cita->dctovalor = $fidelizacion[0]->valor;   //valor del dcto
            }
            $cita->nameservicio = $servicio;
            $cita->nameprofesional = $profesional;
            $cita->id_empserv = empserv::whereArray(['idempleado'=>$_POST['nameprofesional'], 'idservicio'=>$_POST['idservicio']])[0]->id;
            if($cita->id_usuario == 2){  //para cliente no registrado
                $cita->nombrecliente = $_POST['nombrecliente'];
            }else{ 
                $cita->nombrecliente = usuarios::uncampo('id', $cita->id_usuario, 'nombre').' '.usuarios::uncampo('id', $cita->id_usuario, 'apellido');
            }
            
            $alertas = $cita->validarcitas();
            if(empty($alertas)){
                //validar que no se repita el mismo servicio con el mismo empleado con la misma fecha y hora
                $citaunica = citas::whereArray(['id_empserv'=>$cita->id_empserv, 'start'=>$cita->start, 'hora_fin'=>$cita->hora_fin, 'estado'=>'Pendiente']);
                if(empty($citaunica)){
                    $r = $cita->crear_guardar();
                    if($r[0])$alertas['exito'][] = "Cita Creada";
                }else{ $alertas['error'][] = "Error actualize la pagina"; }
            }
        }

        $citas = citas::inner_join("SELECT *FROM citas WHERE estado = 'Pendiente' AND `start` = CURDATE() ORDER BY id ASC;");

        foreach($citas as $cita){
            if($cita->id_empserv){
                $cita->idservicio = empserv::uncampo('id', $cita->id_empserv, 'idservicio');
                $cita->idempleado = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
            }
        }
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, /*'paginacion'=>$paginacion->paginacion(),*/ 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }

/*
    public static function crearnoregistrado(Router $router){
        session_start();
        if($_SESSION['admin']==1){
            $empleadoid = usuarios::uncampo('id', $_SESSION['id'], 'empleadoid'); //id del empleado
            $ids_empserv = empserv::multicampos('idempleado', $empleadoid, 'id'); //[24, 25, ..]ids del empleado en relacion con los servicios
            $stridsempserv = implode(', ', $ids_empserv);  //para filtrar todas las citas y servicios de un empleado
        }
        $alertas = [];

        $profesionales = empleados::all();
        date_default_timezone_set('America/Bogota');
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $cita = new citas($_POST); //validar el campo hora

            $valorcita = servicios::uncampo('id', $_POST['idservicio'], 'precio');
            $servicio = servicios::uncampo('id', $_POST['idservicio'], 'nombre');
            $profesional = empleados::uncampo('id', $_POST['nameprofesional'], 'nombre').' '.empleados::uncampo('id', $_POST['nameprofesional'], 'apellido');
            $fidelizacion = fidelizacion::whereArray(['categoria'=>'servicios', 'product_serv'=>$_POST['idservicio'], 'estado'=>1]);
            $cita->valorcita = $valorcita;
            if($fidelizacion){
                $cita->dcto = $fidelizacion[0]->porcentaje;  //porcentaje del dcto
                $cita->dctovalor = $fidelizacion[0]->valor;   //valor del dcto
            }
            $cita->nameservicio = $servicio;
            $cita->nameprofesional = $profesional;
            $cita->nombrecliente = $_POST['nombrecliente'];
            $alertas = $cita->validarcitas();
            if(empty($alertas)){
                //validar que no se repita el mismo servicio con el mismo empleado con la misma fecha y hora
                $citaunica = citas::whereArray(['id_empserv'=>$cita->id_empserv, 'start'=>$cita->start, 'hora_fin'=>$cita->hora_fin, 'estado'=>'Pendiente']);
                if(empty($citaunica)){
                    $r = $cita->crear_guardar();
                    if($r[0])$alertas['exito'][] = "Cita Creada";
                }else{ $alertas['error'][] = "Error actualize la pagina"; }
            }
        }

        $pagina_actual = $_GET['pagina'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if(!$pagina_actual || $pagina_actual<1)header('Location: /admin/citas?pagina=1');
       
        $registros_por_pagina = 10;
        if( $_SESSION['admin']>1){
            $registros_total = citas::numregistros(); //para usuario admin
        }else{ $registros_total = citas::numreg_multiwhere('id_empserv', $ids_empserv); }
        
        $paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total, '/admin/citas');
        if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');
        
        if($_SESSION['admin']>1){
            $citas = citas::paginar($registros_por_pagina, $paginacion->offset()); //metodo paginar es de activerecord
        }else{
            $citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($stridsempserv) ORDER BY id DESC LIMIT $registros_por_pagina OFFSET {$paginacion->offset()};");
        }

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
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>$paginacion->paginacion(), 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }
*/

    public static function finalizar(Router $router){ //llamado desde finalizcita.js
        session_start();
        if($_SESSION['admin']==1){
            $empleadoid = usuarios::uncampo('id', $_SESSION['id'], 'empleadoid'); //id del empleado
            $ids_empserv = empserv::multicampos('idempleado', $empleadoid, 'id'); //[24, 25, ..]ids del empleado en relacion con los servicios
            $stridsempserv = implode(', ', $ids_empserv);
        }
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
            $cita = citas::find('id', $_POST['id']);  // $_POST['id'] viene de finalizcita.js
            if(!$cita->valorcita)$cita->valorcita =$_POST['valorapagar'];
            
            $cita->estado = 'Finalizada';
            $cita->start = date('Y-m-d');  //si la cita fue programada para un dia determinado y se realiza antes, la fecha de la cita se corre para el dia en que se paga o realiza
            
            $r2 = $cita->actualizar();
            if($r2){
                $facturacion = new facturacion($_POST);
                $facturacion->idcita = $_POST['id'];
                $facturacion->id_pagosxdia = $idpagosxdia;
                $facturacion->tipo = 1;  //tipo = 1 es para indicar que es una cita programada
                if(!$facturacion->valordcto)$facturacion->valordcto = 0;
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
        if( $_SESSION['admin']>1){
            $registros_total = citas::numregistros(); //para usuario admin
        }else{ $registros_total = citas::numreg_multiwhere('id_empserv', $ids_empserv); }
        $paginacion = new paginacion($pagina_actual, $registros_por_pagina, $registros_total, '/admin/citas');
        if($pagina_actual>$paginacion->total_paginas()&&$paginacion->total_paginas()!=0)header('Location: /admin/citas?pagina=1');
        
        if($_SESSION['admin']>1){
            $citas = citas::paginar($registros_por_pagina, $paginacion->offset());
        }else{
            $citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($stridsempserv) ORDER BY id DESC LIMIT $registros_por_pagina OFFSET {$paginacion->offset()};");
        }

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
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>$paginacion->paginacion(), 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }


    public static function consultaxestadoxname(Router $router){
        session_start();
        if($_SESSION['admin']==1){
            $empleadoid = usuarios::uncampo('id', $_SESSION['id'], 'empleadoid'); //id del empleado
            $ids_empserv = empserv::multicampos('idempleado', $empleadoid, 'id'); //[24, 25, ..]ids del empleado en relacion con los servicios
            $stridsempserv = implode(', ', $ids_empserv);
        }
        $alertas = []; $ids = ""; $estado=''; $nombre='';

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            //$citas = citas::inner_join("SELECT *FROM citas WHERE estado LIKE "."'%{$_POST['estado']}%'"." ORDER BY id DESC;"/* ORDER BY id ASC LIMIT 10 OFFSET {$paginacion->offset()}*/);
            if($_POST['columna'] == 'estado'){
                $estado = $_POST['consulta'];
                if($_SESSION['admin']>1){
                    $citas = citas::filtro_nombre($_POST['columna'], $_POST['consulta'], 'id');
                }else{
                    $citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($stridsempserv) AND estado LIKE '%{$_POST['consulta']}%' ORDER BY id DESC;");
                }
            }else{  //consulta por nombre de cliente usuario
                $nombre = $_POST['consulta'];
                $usuarios = usuarios::filtro_nombre($_POST['columna'], $_POST['consulta'], 'id');
                foreach($usuarios as $key => $value){
                    if(array_key_last($usuarios) == $key){
                        $ids.= $value->id;
                    }else{
                        $ids.= $value->id.', '; 
                    }
                }
                $citas = [];
                if($_SESSION['admin']>1){
                    if($ids)$citas = citas::inner_join("SELECT *FROM citas WHERE id_usuario IN($ids) ORDER BY id DESC;");
                    //if(!$ids)$citas = citas::inner_join("SELECT *FROM citas WHERE id_usuario IN('') ORDER BY id DESC;");
                }else{
                    if($ids)$citas = citas::inner_join("SELECT *FROM citas WHERE id_empserv IN($stridsempserv) AND id_usuario IN($ids) ORDER BY id DESC;");
                    //if(!$ids)$citas = citas::inner_join("SELECT *FROM citas WHERE id_usuario IN('') ORDER BY id DESC;");
                }
            }
            foreach($citas as $cita){
                $cita->usuario = usuarios::find('id', $cita->id_usuario);
                $cita->usuario->dctogeneral = fidelizacion::find('id', $cita->usuario->idfidelizacion);
            }
        }
        $profesionales = empleados::all();
        $router->render('admin/citas/index', ['titulo'=>'Citas', 'citas'=>$citas, 'profesionales'=>$profesionales, 'paginacion'=>'', 'alertas'=>$alertas, 'estado'=>$estado, 'user'=>$_SESSION, 'nombre'=>$nombre]);
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

    public static function diasdelascitas(){
        $dias = citas::inner_join("SELECT DISTINCT `start` FROM citas ORDER BY `start`;"); //solo me trae las fechas donde hay citas sin repetir fecha
        echo json_encode($dias);
    }


     
}