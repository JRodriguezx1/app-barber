<?php
//$dias = facturacion::inner_join('SELECT COUNT(id) AS servicios, fecha_pago, SUM(total) AS totaldia FROM facturacion GROUP BY fecha_pago ORDER BY COUNT(id) DESC;');
namespace Controllers;

use Model\facturacion;
use Model\pagosxdia;
use Model\citas;
use Model\empserv;
use Model\servicios;
use Model\empleados;
use Model\mediospago;

use MVC\Router;  //namespace\clase
 
class factcontrolador{

    public static function index(Router $router){
        session_start();
        isadmin();
        $alertas = [];

        date_default_timezone_set('America/Bogota');

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){ //cuando se factura servicio ordinario
            ////buscamos el dia actual el cual se va a finalizar servicio
            $x = pagosxdia::find('fecha', date('Y-m-d'));
            if(!$x){ //si dia no existe lo creamos segun fecha en tabla pagosxdia, por lo general sucede con el primer servicio facturado
                $pagosxdia = new pagosxdia($array=['totalservices'=>1]); //en el modelo pagosxdias se establece la fecha y se pone el primer servicio facturado
                $r1 = $pagosxdia->crear_guardar(); //creamos el dia con la fecha de finalizacion de cita
            }
            $idpagosxdia = $x->id??$r1[1];  // me contiene el id de la tabla pagosxdia segun dia actial o fecha actual

            $facturacion = new facturacion($_POST);
            $facturacion->id_pagosxdia = $idpagosxdia;
            $facturacion->tipo = 0;  //tipo = 0 es para indicar que es una cita ordinaria o casual
            $r = $facturacion->crear_guardar(); //me guarda registro en tabla facturacion
            if($r[0]){
                //validar cuantos servicios van de este dia//
                $numservicios = facturacion::numreg_where('id_pagosxdia', $idpagosxdia);
                //validar la suma total de los servicios realizados de la columna total de la tabla facturacion
                $computarizado = facturacion::sumcolum('id_pagosxdia', $idpagosxdia, 'total');
                $actpagosxdia = pagosxdia::find('id', $idpagosxdia);
                $actpagosxdia->totalservices = $numservicios;
                $actpagosxdia->computarizado = $computarizado;
                $r1 = $actpagosxdia->actualizar();
                if($r1)$alertas['exito'][] = "Servicio facturado";
            }else{
                $alertas['error'][] = "Error de facturacion volver a facturar";
            }
        }
        $alldays = pagosxdia::ordenar('id', 'DESC');
        $router->render('admin/facturacion/index', ['titulo'=>'facturacion', 'alldays'=>$alldays, 'alertas'=>$alertas, 'user'=>$_SESSION]);
    }


    public static function buscarxfecha(Router $router){
        $fecha = $_GET['fecha'];
        $alertas = [];

        $day = pagosxdia::whereArray(['fecha'=>$fecha]);

        $router->render('admin/facturacion/index', ['titulo'=>'facturacion', 'alldays'=>$day, 'fecha'=>$fecha, 'alertas'=>$alertas, 'user'=>$_SESSION]);
    }


    public static function gestionar(Router $router){
        session_start();
        isadmin();
        $id = $_GET['id'];
        if(!is_numeric($id))return;

        $alertas = [];
        $valorservicios = 0;
        $numdctos = 0;  //cantidad de servicios q se le aplica el dcto
        $valordctos = 0;  // total dcto aplicado del dia
        $recibidos = 0;
        $devoluciones = 0;
        
        $pagosxdia = pagosxdia::find('id', $id);
        $fecha = $pagosxdia->fecha;
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){ //cunado se cierra caja
            $pagosxdia->compara_objetobd_post($_POST);
            $r = $pagosxdia->actualizar();
            if($r)header('Location: /admin/facturacion');
        }

        $gestionardia = facturacion::idregistros('id_pagosxdia', $id);
        $numservicios = count($gestionardia); //servicios realizados
        foreach($gestionardia as $value){
            if($value->idcita!=1){
                $value->idempserv = citas::uncampo('id', $value->idcita, 'id_empserv');
                if($value->idempserv){
                    $value->idservicio = empserv::uncampo('id', $value->idempserv, 'idservicio');
                    $value->idempleado = empserv::uncampo('id', $value->idempserv, 'idempleado');
                    $value->servicio = servicios::uncampo('id', $value->idservicio, 'nombre');
                    $value->empleado = empleados::uncampo('id', $value->idempleado, 'nombre').' '.empleados::uncampo('id', $value->idempleado, 'apellido');
                }
            }
            if($value->idmediospago){
                $value->idmediospago = mediospago::uncampo('id', $value->idmediospago, 'mediopago');
            }
            $valorservicios+= $value->total; //facturacion de todos los servicios con sus precios originales
            if($value->dcto != 0){
                $numdctos++;
                $valordctos+= $value->valordcto;
            }
            $recibidos+= $value->recibido;
            $devoluciones+= $value->devolucion;
        }
        $total = pagosxdia::uncampo('id', $id, 'computarizado');
        ///*********************** *////
        ///mostrar los servicios y el dinero total de cada empleado
        ///***********************// */
        $router->render('admin/facturacion/gestionar', ['titulo'=>'facturacion', 'fecha'=>$fecha, 'gestionardia'=>$gestionardia, 'numservicios'=>$numservicios, 'valorservicios'=>$valorservicios, 'numdctos'=>$numdctos, 'valordctos'=>$valordctos, 'recibidos'=>$recibidos, 'devoluciones'=>$devoluciones, 'total'=>$total, 'alertas'=>$alertas, 'user'=>$_SESSION]);
    }

     
    public static function detallepagoxcita(){
        $id = $_GET['id'];
        if(!is_numeric($id))return;
        $factura = facturacion::find('idcita', $id);
        echo json_encode($factura);
    }

    public static function anularpagoxcita(){
        $id = $_GET['id'];
        if(!is_numeric($id))return;
        date_default_timezone_set('America/Bogota');
        $fechaactual = new \DateTime(date('Y-m-d'));
        $factura = facturacion::find('id', $id);
        $fechadepago = new \DateTime($factura->fecha_pago);
        if($fechadepago==$fechaactual){
            $cita = citas::find('id', $factura->idcita);
            $cita->estado = "Pendiente";
            $a = $cita->actualizar();
            $r = $factura->eliminar_registro();
        }else{
            $r = false;
        }
        echo json_encode($r);
    }
}