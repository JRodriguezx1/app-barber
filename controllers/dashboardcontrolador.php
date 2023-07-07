<?php


namespace Controllers;
//require __DIR__ . '/../classes/dompdf/autoload.inc.php';
//require __DIR__ . '/../classes/twilio-php-main/src/Twilio/autoload.php';
use MVC\Router;
use Model\usuarios;
use Model\facturacion;
use Model\pagosxdia;
use Model\citas;
use Model\empserv;
use Model\servicios;
//use Dompdf\Dompdf;
//use Twilio\Rest\Client;


class dashboardcontrolador{

    public static function index(Router $router) {
        $usuario = [];
        session_start();
        isadmin();
        date_default_timezone_set('America/Bogota');
        $day = pagosxdia::find('fecha', date('Y-m-d'));
        
        /*
        $dompdf = new Dompdf();
        ...
        ...
        ...
        */
        //$client = new Twilio\Rest\Client($sid, $token);
        
        $router->render('admin/dashboard/index', ['titulo'=>'Dashboard - Innovatech', 'day'=>$day, 'usuario'=>$_SESSION]);
    }

    public static function alldays(){  //api
        $alldays = pagosxdia::ordenarlimite('id', 'DESC', 8);
        echo json_encode($alldays);
    }

    public static function totalcitas(){  //api
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $citasxdia = citas::idregistros('fecha_fin', $fecha);
        foreach($citasxdia as $value){
            $value->idservicio = empserv::uncampo('id', $value->id_empserv, 'idservicio');
            $value->facturacion = facturacion::find('idcita', $value->id);
        }
        echo json_encode($citasxdia);
    }
}

?>