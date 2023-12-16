<?php


namespace Controllers;
//require __DIR__ . '/../classes/dompdf/autoload.inc.php';
require __DIR__ . '/../classes/twilio-php-main/src/Twilio/autoload.php';
//require __DIR__ . '/../classes/aws/aws-autoloader.php';
use MVC\Router;
use Model\usuarios;
use Model\facturacion;
use Model\pagosxdia;
use Model\citas;
use Model\empserv;
use Model\servicios;
use Model\empleados;
//use Dompdf\Dompdf;
use Twilio\Rest\Client;
//use Aws\Sns\SnsClient;


class dashboardcontrolador{

    public static function index(Router $router) {
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

        //// enviar sms con twilio
        /*$sid = 'AC81feeb3abcf6563f2a8f9b32904f8ae0';
        $token = '591ed3e8000d266c4aa9d2dbd0584336';
        $twilio = new Client($sid, $token);

        $message = $twilio->messages->create("+573042029683", // to
            array(
            "from" => "+16183684812",
            "body" => "hola lupelulu desde twilio"
            )
        );

        print($message->sid);*/

        
        /* //enviar sms con aws
        $snsClient = new SnsClient([
            'credentials' => [
                'key' => '',
                'secret' => ''
            ],
            'region' => 'us-east-1',
            'version' => 'latest' 
        ]);

        $args = array(
            'MessageAttributes'=>['AWS.SNS.SMS.SMSType'=>['DataType'=>'String', 'StringValue'=>'Transactional']
            ],
            'Message'=>'hola lupe lulu',
            'PhoneNumber'=>'+573042029683'
        );

        $result = $snsClient->publish($args);
        */

        


        $totalempleados = empleados::numregistros();
        $totalclientes = usuarios::numreg_multicolum(['confirmado'=>1, 'admin'=>0]);
        
        $router->render('admin/dashboard/index', ['titulo'=>'Dashboard - Innovatech', 'day'=>$day, 'totalclientes'=>$totalclientes, 'totalempleados'=>$totalempleados, 'user'=>$_SESSION]);
    }

    public static function perfil(Router $router) {
        $alertas = [];
        session_start();
        isadmin();
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $usuario = usuarios::find('id', $_SESSION['id']);
            $usuario->compara_objetobd_post($_POST);
            $alertas = $usuario->validarEmail();
            if(empty($alertas)){
                $r = $usuario->actualizar();
                if($r){
                    $alertas['exito'][] = "Datos actualizados";
                }else{ $alertas['error'][] = "Hubo un error"; }
            }
        }
        $usuario = usuarios::find('id', $_SESSION['id']);
        $router->render('admin/dashboard/perfil', ['titulo'=>'Dashboard - mi perfil', 'usuario'=>$usuario, 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }

    public static function cambiarpassword(Router $router){
        $alertas = [];
        session_start();
        isadmin();
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $usuario = usuarios::find('id', $_SESSION['id']);
            $x = $usuario->comprobar_password($_POST['passwordactual']);
            if($x){
                $usuario->compara_objetobd_post($_POST);
                $alertas = $usuario->validarPassword();
                if(empty($alertas)){
                    if($usuario->password == $usuario->password2){
                        $usuario->hashPassword();
                        $a = $usuario->actualizar();
                        $alertas['exito'][] = "Password Actualizado";
                    }else{
                        $alertas['error'][] = "El password nuevo no coincide cuando se repite";
                    }
                }
            }else{
                $alertas['error'][] = "Password actual no es correcto";
            }
        }
        $router->render('admin/dashboard/cambiarpassword', ['titulo'=>'Cambio de password', 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }

    public static function alldays(){  //api
        $alldays = pagosxdia::ordenarlimite('id', 'DESC', 8);
        echo json_encode($alldays);
    }

    public static function totalcitas(){  //api
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d'); //dia actual hoy
        $citasxdia = citas::idregistros('start', $fecha);
        //$citasxdia = citas::whereArray(['start'=>$fecha]);
        foreach($citasxdia as $value){
           // $value->idservicio = empserv::uncampo('id', $value->id_empserv, 'idservicio');
            $value->facturacion = facturacion::find('idcita', $value->id);
        }
        echo json_encode($citasxdia);
    }
}

?>