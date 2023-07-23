<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;  //namespace\clase
use Model\usuarios;
use Model\fidelizacion;
use Model\citas;
use Model\empserv;
use Model\servicios;
use Model\empleados;
use Model\facturacion;
 
class clientescontrolador{

    public static function index(Router $router){
        session_start();
        isadmin();
        $alertas = [];
        $buscar = '';
        $clientes = usuarios::whereArray(['confirmado'=>1, 'admin'=>0]); //me trae los usuario que esten confirmados y no admin

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            if($_POST['filtro']!='all')
                $clientes = usuarios::filtro_nombre($_POST['filtro'], $_POST['buscar'], 'id');
                $buscar = $_POST['buscar'];
/*
            if($_POST['filtro'] == "cedula"){
                $registros_total = estudiantes::inner_join("SELECT COUNT(*) FROM estudiantes WHERE cedula LIKE '{$_POST['buscar']}%';");
                }else{
                    $registros_total = estudiantes::inner_join("SELECT COUNT(*) FROM estudiantes WHERE nombre LIKE '{$_POST['buscar']}%';");
                }*/
        }

        $router->render('admin/clientes/index', ['titulo'=>'clientes', 'clientes'=>$clientes, 'alertas'=>$alertas, 'buscar'=>$buscar]);
    }

    public static function crear(Router $router){
        $usuario = new usuarios; //instancia el objeto vacio
        $alertas = [];  
        $clientes = usuarios::whereArray(['confirmado'=>1, 'admin'=>0]);

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            
            $usuario->compara_objetobd_post($_POST); //objeto instaciado toma los valores del $_POST
            $alertas = $usuario->validar_nueva_cuenta(); 
            
            if(empty($alertas)){ 
                
                $usuarioexiste = $usuario->validar_registro();//retorna 1 si existe usuario(email), 0 si no existe
                if($usuarioexiste){ //si usuario ya existe
                    $usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = $usuario::getAlertas();
                }else{
                    //hashear pass
                    $usuario->hashPassword();
                    //eliminar pass2
                    unset($usuario->password2);
                    //generar token
                    $usuario->creartoken();

                    //enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token, $_POST['password']);
                    $email->enviarConfirmacion();

                //guardar cliente recien creado en bd  
                    $resultado = $usuario->crear_guardar();  
                    if($resultado){
                        $alertas['exito'][] = 'Cliente debe confirma su cuenta en su email';
                    }     
                }
            }
        } 
        $router->render('admin/clientes/index', ['titulo'=>'clientes', 'clientes'=>$clientes, 'alertas'=>$alertas]);
    }


    public static function actualizar(Router $router){
        $alertas = [];  
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            $cliente = usuarios::find('id', $_POST['id']);
            $cliente->compara_objetobd_post($_POST);
            $cliente->password2 = $cliente->password;
            $alertas = $cliente->validar_nueva_cuenta();
            $alertas = $cliente->validarEmail();
            if(empty($alertas)){
                $r = $cliente->actualizar();
                if($r)$alertas['exito'][] = 'Datos de cliente actualizados';
            }
        }
        $clientes = usuarios::whereArray(['confirmado'=>1, 'admin'=>0]);
        $router->render('admin/clientes/index', ['titulo'=>'clientes', 'clientes'=>$clientes, 'alertas'=>$alertas]);
    }


    public static function hab_desh(Router $router){
        session_start();
        isadmin();
        $alertas = [];  
        $id = $_GET['id'];
        if(!is_numeric($id))return;

        $cliente = usuarios::find('id', $id);
        if($cliente){
            if($cliente->habilitar){
                $cliente->habilitar = 0;
                $alertas['exito'][] = 'Cliente bloqueado de la base de datos';
            }else{
                $cliente->habilitar = 1;
                $alertas['exito'][] = 'Cliente habilitado de la base de datos';
            }
            $r = $cliente->actualizar();
        }else{
            header('Location: /admin/clientes');
        }

        if(!$r)$alertas['exito'][] = 'hubo un error';
        
        $clientes = usuarios::whereArray(['confirmado'=>1, 'admin'=>0]);
        $router->render('admin/clientes/index', ['titulo'=>'clientes', 'clientes'=>$clientes, 'alertas'=>$alertas]);
    }


    public static function detalle(Router $router){
        session_start();
        isadmin(); 
        $id = $_GET['id'];
        if(!is_numeric($id))return;

        $alertas = []; 
 
        $cliente = usuarios::find('id', $id);
        $citas = citas::idregistros('id_usuario', $id);
        foreach($citas as $cita){
            if($cita->id_empserv){
                $cita->idservicio = empserv::uncampo('id', $cita->id_empserv, 'idservicio');
                $cita->idempleado = empserv::uncampo('id', $cita->id_empserv, 'idempleado');
                $cita->servicio = servicios::find('id', $cita->idservicio);
                $cita->empleado = empleados::uncampo('id', $cita->idempleado, 'nombre').' '.empleados::uncampo('id', $cita->idempleado, 'apellido');
                $cita->facturacion = facturacion::find('idcita', $cita->id);
            }
        }
        $router->render('admin/clientes/detalle', ['titulo'=>'clientes', 'cliente'=>$cliente, 'citas'=>$citas, 'alertas'=>$alertas]);
    }
     

    public static function allclientes(){  //api llamado desde citas.js
        $clientes = usuarios::all();
        foreach($clientes as $cliente){
            $cliente->dctogeneral = fidelizacion::find('id', $cliente->idfidelizacion);
        }
        echo json_encode($clientes);
    }
}