<?php

namespace Controllers;

require __DIR__ . '/../classes/twilio-php-main/src/Twilio/autoload.php';

use Model\servicios;
use Model\citas;
use Model\empserv;
use Model\empleados;
use Model\usuarios;
use Model\fidelizacion;
use Model\negocio;
use MVC\Router;  //namespace\clase
//use Classes\ws_cloud_api;
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

        //$citas = citas::idregistros('id_usuario', $_SESSION['id']);
        $citas = citas::inner_join("SELECT *FROM citas WHERE id_usuario = {$_SESSION['id']} AND estado = 'Pendiente' ORDER BY fecha_fin ASC;");
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

        $dctospromos = fidelizacion::whereArray(['estado'=>1]);
        $router->render('dash-cliente/index', ['titulo'=>'cliente registrado', 'classjs'=>$classjs, 'servicios'=>$servicios, 'citas'=>$citas, 'usuario'=>$usuario, 'dctospromos'=>$dctospromos, 'alertas'=>$alertas, 'negocio'=>negocio::get(1)]);
    }

    public static function enviarcita(){ //api llamada desde dash_client_assign.js para reservar cita desde el dashboard del cliente
        session_start();
        $alertas = [];
        $idusuario = $_SESSION['id'];
        $cita = new citas($_POST);
        $cita->id_usuario = $idusuario;
        $Ncitaspendient = citas::numreg_multicolum(['id_usuario'=>$cita->id_usuario, 'estado'=>'Pendiente']);
        if($Ncitaspendient > 2){ $r[0] = false;
        }else{
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
            $cita->nombrecliente = usuarios::uncampo('id', $cita->id_usuario, 'nombre').' '.usuarios::uncampo('id', $cita->id_usuario, 'apellido');
            $alertas = $cita->validarcitas();
            if(empty($alertas)){
                $ws = negocio::uncampo('id', 1, 'ws'); //ws del negocio o encargado para recibir los msj de ws
                //validar que no se repita el mismo servicio con el mismo empleado con la misma fecha y hora
                $citaunica = citas::whereArray(['id_empserv'=>$cita->id_empserv, 'fecha_fin'=>$cita->fecha_fin, 'hora_fin'=>$cita->hora_fin, 'estado'=>'Pendiente']);
                if(empty($citaunica)){
                    $r = $cita->crear_guardar();
                    if($r[0]){
                        //////////////////enviar msj por whatsapp cloud api////////////
                        //$wstext = new ws_cloud_api($ws, $r[1], $_SESSION['nombre'], $_POST['telcliente'], $profesional, $servicio, $_POST['fecha_fin'], $_POST['hora_fin']);
                        //$wstext->send1textws();

                        ////////////// enviar sms con twilio////////////
                        /*$sid = 'AC81feeb3abcf6563f2a8f9b32904f8ae0';
                        $token = '591ed3e8000d266c4aa9d2dbd0584336';
                        $twilio = new Client($sid, $token);

                        $message = $twilio->messages->create("+573022016786", // to
                            array(
                            "from" => "+16183684812",
                            "body" => "Cita Nº:".$r[1]." reservada por: ".$_SESSION['nombre'].", Telefono cliente: ".$_POST['telcliente'].", Profesional: ".$profesional.", Servicio: ".$servicio.", Fecha de la cita: ".$_POST['fecha_fin']." Hora de la cita: ".$_POST['hora_fin']
                            )
                        );*/
                        //print($message->sid);
                        

                    }else{ $r[0] = false; }
                }else{ $r[0] = false; }
            }
        }
        echo json_encode($r[0]);
    }

    public static function getcitas(){  //api llamado desde dash_cliente_assign.js
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
        session_start();
        $id = $_GET['id'];
        if(!is_numeric($id))return;
        $ws = negocio::uncampo('id', 1, 'ws'); //ws del negocio o encargado para recibir los msj de ws
        $cita = citas::find('id', $id);
        $cita->estado = "Cancelado";
        $r = $cita->actualizar();
        if($r){
            //$wstext = new ws_cloud_api($ws, $id, $_SESSION['nombre'], 0, '', $cita->nameservicio, $cita->fecha_fin, $cita->hora_fin);
            //$wstext->send2textws();

            //enviar sms por twilio
            $sid = 'AC81feeb3abcf6563f2a8f9b32904f8ae0';
            $token = '591ed3e8000d266c4aa9d2dbd0584336';
            $twilio = new Client($sid, $token);

            $message = $twilio->messages->create("+573022016786", // to
                array(
                    "from" => "+16183684812",
                    "body" => "Cita Nº:".$id." Cancelada por: ".$_SESSION['nombre'].", Servicio: ".$cita->nameservicio.", Fecha de la cita: ".$cita->fecha_fin." Hora de la cita: ".$cita->hora_fin
                    )
            );

            echo json_encode($cita);
        }else{echo json_encode(false); }
    }
     
}