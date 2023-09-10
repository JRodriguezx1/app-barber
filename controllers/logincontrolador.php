<?php

namespace Controllers;

use Classes\Email;
use Model\usuarios; //namespace\clase hija
use Model\negocio;
use MVC\Router;  //namespace\clase
 
class logincontrolador{

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            //$_POST = ['email'=>'correo@correo.com', 'password'=>123456]
            $auth = new usuarios($_POST);  //$auth es objeto de la clase usuarios de los datos que el usuario escribio
            $alertas = $auth->validarLogin();  //valida que los campos esten escritos
            if(empty($alertas)){

                //$usuario = $auth->validar_registro();  //valida si email existe? retorna 1 o 0 
                $usuario = $auth->find('email', $auth->email); //busca en la columna 'email' el correo electronico: $auth->email y retorna el registro de la bd en un objeto
                if($usuario&&$usuario->habilitar){ //existe usuario o confirmado     //$usuario es objeto de la clase usuarios pero con los datos de la bd
                    //$usuario->password = $auth->password
                    $pass = $usuario->comprobar_password($auth->password);  //comprueba password y verifica si esta confirmado
                    $confirmado = $usuario->confirmado;
                    if($pass&&$confirmado){         //$auth->password = es lo que se escribe en el form
                        //autenticar usuario         
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre." ".$usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        $_SESSION['admin'] = $usuario->admin ?? null;  //si no es admin la llave $_SESSION['admin'] = null

                        //redireccion al dashboard del superior-admin-empleado o cliente
                        if($usuario->admin>=1){
                            header('Location: /admin/dashboard');
                        }else{
                            header('Location: /Cliente/app');
                        }

                    }else{ $alertas = usuarios::setAlerta('error', 'Password incorrecto o cliente no confirmado, verfica tu email');  }
                }else{
                    $alertas = usuarios::setAlerta('error', 'usuario no encontrado o no existe');
                }
            } //cierre del empty de alertas
        } //cierre de $_SERVER['REQUEST_METHOD'] === 'POST'

        $alertas = usuarios::getAlertas();
       $router->render('auth/login', ['alertas'=>$alertas, 'titulo'=>'iniciar sesión', 'negocio'=>negocio::get(1)]);   //  'autenticacion/login' = carpeta/archivo
    }



    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }



    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new usuarios($_POST);
            $alertas = $auth->validarEmail();
            if(empty($alertas)){
                $auth = usuarios::find('email', $auth->email);
                if($auth && $auth->confirmado === '1'){ //si existe registro y si el campo confirmado es uno '1'
                    //generar token
                    $auth->creartoken();
                    $auth->actualizar();

                    // Enviar el email
                    $email = new Email( $auth->email, $auth->nombre, $auth->token );
                    $email->enviarInstrucciones();

                    usuarios::setAlerta('exito', 'revisa tu email'); //q pasa si no se envia email
                }else{
                    usuarios::setAlerta('error', 'el usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = usuarios::getAlertas();
        $router->render('auth/olvide', ['alertas'=>$alertas, 'titulo'=>'recuperar', 'negocio'=>negocio::get(1)]);
    }



    public static function recuperarpass(Router $router){

        $alertas = [];
        $error = true;

        $token = s($_GET['token']); //token de la url ?token=xxxxx
        //buscar usuario por su token

        if(!$token) header('Location: /');

        $resultado = usuarios::find('token', $token);  //instancia con los datos de la bd
        if(empty($resultado)){
            usuarios::setAlerta('error', 'token no valido');
            $error = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //leer el nuevo password y guardarlo
            $new_password = new usuarios($_POST); //instancia solo con los datos del formulario
            $alertas = $new_password->validarPassword();
            if(empty($alertas)){
                $resultado->password = null;
                $resultado->password = $new_password->password;  //la propiedad password de la instancia u objeto resultado toma el valor de la propiedad password del objeto o instancia new_password.
                $resultado->hashPassword();
                $resultado->token = null;
                
                if($resultado->actualizar()){
                    header('Location: /');  }  
                
            }
        }
        $alertas = usuarios::getAlertas();
        $router->render('auth/recuperarpass', ['alertas'=>$alertas, 'error'=>$error, 'titulo'=>'recuperar', 'negocio'=>negocio::get(1)]);
    }



    public static function registro(Router $router){
        //debuguear('registrando');
        $usuario = new usuarios; //instancia el objeto vacio
        $alertas = [];  //alertas vacias
        if($_SERVER['REQUEST_METHOD'] === 'POST' ){
            
            $usuario->compara_objetobd_post($_POST); //objeto instaciado toma los valores del $_POST
            $alertas = $usuario->validar_nueva_cuenta(); //metodo de mensajes de validacion para la creacion de la cuenta, nombre, apellido etc..
            $alertas = $usuario->validarEmail();
            //revisar que las alertas esten vacios
            if(empty($alertas)){ //si el arreglo alertas esta vacio es pq paso las validacion del formulario crear cuenta
                
                $usuarioexiste = $usuario->validar_registro();//retorn 1 si existe usuario(email), 0 si no existe
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
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                //guardar cliente recien creado en bd  
                $resultado = $usuario->crear_guardar();  
                if($resultado){
                    header('Location: /mensaje');
                }     

                }
            }
        } //cierre del if $_SERVER[REQUEST_METHOD]
       
        $router->render('auth/registro', ['usuario'=>$usuario, 'alertas'=>$alertas, 'titulo'=>'crear cuenta', 'negocio'=>negocio::get(1)]);
    } //cierre del metodo



    public static function mensaje(Router $router){
        $router->render('auth/mensaje', ['titulo'=>'mensaje', 'negocio'=>negocio::get(1)]);
    } 



    public static function confirmar_cuenta(Router $router){ //este metodo se llama cuando el usuario confirma la url enviada al correo electroico
        $alertas = [];
        $token  = s($_GET['token']);  //con el metodo get se lee los datos de url superior
        
        $resultado = usuarios::find('token', $token); //encuentra usuario por token
        if(empty($resultado)){
            usuarios::setAlerta('error', 'token no valido'); 

        }else{  //usuario encontrado, se debe cambiar token para que el token no sea mas valido, lo que se hace es validar que el correo si exista y no sea inventado
             $resultado->confirmado = '1';
             $resultado->token = null;
             //actualizar bd con el objeto en memoria ya modificado
             $resultado->actualizar();
             usuarios::setAlerta('exito', 'cuenta comprobada correctamente');  //cuenta confirmado y correo validado 
        }

        $alertas = usuarios::getAlertas();
        $router->render('auth/confirmar_cuenta', ['alertas'=>$alertas, 'titulo'=>'confirmacion de cuenta', 'negocio'=>negocio::get(1)]);
    }

     
} //cierre de la clase
