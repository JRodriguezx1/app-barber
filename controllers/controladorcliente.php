<?php

namespace Controllers;

require __DIR__ . '/../classes/twilio-php-main/src/Twilio/autoload.php';

use Model\servicios;
use Model\citas;
use Model\empserv;
use Model\empleados;
use Model\usuarios;
use MVC\Router;  //namespace\clase
use Twilio\Rest\Client;
 
class controladorcliente{

    public static function index(Router $router){
        session_start();
        isauth();
        $alertas = [];
        $classjs = '';
        $usuario = usuarios::find('id', $_SESSION['id']);
        $servicios = servicios::all();
        date_default_timezone_set('America/Bogota');

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){ //cuando se actualize datos del cliente
            $classjs = 'perfil'; //se envia a la vista para q js lo lea y mantega la pagina en view
            $usuario->compara_objetobd_post($_POST);
            //validar pass para verificar que si es cliente
            $pass = $_POST['validarpassword'];
            if(!$usuario->comprobar_password($pass)){
                $alertas['error'][] = 'Password no es correcto';
            }else{
                $r = $usuario->actualizar();
                if($r)$alertas['exito'][] = 'Datos Actualizados';
            }  
        }

        $citas = citas::idregistros('id_usuario', $_SESSION['id']);
        foreach($citas as $cita){
           /* if($cita->id_empserv){
                $cita->idservicio = empserv::uncampo('id', $cita->id_empserv, 'idservicio');
                $cita->servicio = servicios::find('id', $cita->idservicio);
                $cita->idprofessional = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
                $cita->nameprofessional = empleados::uncampo('id', $cita->idprofessional, 'nombre');
                $cita->lastnameprofessional = empleados::uncampo('id', $cita->idprofessional, 'apellido');
            }*/
        }
        //debuguear($citas);

        $router->render('dash-cliente/index', ['titulo'=>'cliente registrado', 'classjs'=>$classjs, 'servicios'=>$servicios, 'citas'=>$citas, 'usuario'=>$usuario, 'alertas'=>$alertas]);
    }

    public static function enviarcita(){
        session_start();
        $alertas = [];
        $idusuario = $_SESSION['id'];
        $cita = new citas($_POST);
        $cita->id_usuario = $idusuario;
        $valorcita = servicios::uncampo('id', $_POST['nameservicio'], 'precio');
        $servicio = servicios::uncampo('id', $_POST['nameservicio'], 'nombre');
        $profesional = empleados::uncampo('id', $_POST['nameprofesional'], 'nombre').' '.empleados::uncampo('id', $_POST['nameprofesional'], 'apellido');
        $cita->valorcita = $valorcita;
        $cita->nameservicio = $servicio;
        $cita->nameprofesional = $profesional;
        $alertas = $cita->validarcitas();
        if(empty($alertas)){
            $r = $cita->crear_guardar();

            //////////////////enviar sms por whatsapp////////////
            /*
            $sid = "AC81feeb3abcf6563f2a8f9b32904f8ae0";
            $token = "64729da944065ae2872c8364f27bdd9e";
            $twilio = new Client($sid, $token);
            $message = $twilio->messages
                    ->create("whatsapp:+573022016786", // to
                            [
                                "from" => "whatsapp:+14155238886",
                                "body" => "mh aprenda lenguaje php..."
                            ]
                    );

            print($message->sid);*/
        }
        echo json_encode($r);
    }

    public static function getcitas(){
        $alertas = [];
        date_default_timezone_set('America/Bogota');
        //$citas = citas::inner_join('SELECT * FROM citas WHERE fecha_fin >= CURDATE();');
        $citas = citas::inner_join("SELECT id, id_usuario, id_empserv, fecha_inicio, fecha_fin, hora, TIME_FORMAT(hora_fin, '%H:%i') as hora_fin, estado, duracion, valorcita, dcto, dctovalor, nameservicio, nameprofesional FROM citas WHERE fecha_fin >= CURDATE();");
        foreach($citas as $cita){
            if($cita->id_empserv)$cita->idempleado = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
        }
        echo json_encode($citas);
    }

    public static function cancelarcita(){
        $id = $_GET['id'];
        if(!is_numeric($id))return;
        $cita = citas::find('id', $id);
        $cita->estado = "Cancelado";
        $r = $cita->actualizar();
        echo json_encode($cita);
    }
     
}