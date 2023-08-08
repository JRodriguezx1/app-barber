<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use Model\negocio;

class Email {

    public $email;
    public $nombre;
    public $token;
    
    public function __construct($email, $nombre, $token, $password='')
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
        $this->password = $password;
    }

    public function enviarConfirmacion() { //cunado se registra por primera vez
         // create a new object
         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = $_ENV['EMAIL_HOST'];//'smtp.gmail.com'; 
         $mail->SMTPAuth = true;
         $mail->SMTPSecure = 'tls'; //'ssl'; //ENCRYPTION_STARTTLS - PHPMailer::ENCRYPTION_SMTPS; //'ssl' = si la url tiene el candado, si no =  'tls'
         $mail->Port = $_ENV['EMAIL_PORT']; //465; para ssl y 587 para tls
         $mail->Username = $_ENV['EMAIL_USER']; //'julianithox1@gmail.com';
         $mail->Password = $_ENV['EMAIL_PASS']; //'ddvcysabiytmkwca'; 
     
         $negocio = negocio::get(1);
         $mail->setFrom($negocio[0]->email);
         $mail->addAddress($this->email, $this->nombre);
         $mail->Subject = 'Confirma tu Cuenta';

         // Set HTML
         $mail->isHTML(TRUE);
         $mail->CharSet = 'UTF-8';

         $contenido = '<html>';
         $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has Registrado Correctamente tu cuenta en {$negocio[0]->nombre}; pero es necesario confirmarla</p>";
         $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a>";       
         if($this->password)$contenido .= "<p>Tu password de ingreso es: ".$this->password."</p>";
         $contenido .= "<p>Si tu no creaste esta cuenta; puedes ignorar el mensaje</p>";
         $contenido .= '</html>';
         $mail->Body = $contenido;

         //Enviar el mail
         if(!$mail->send()){
             debuguear($mail->ErrorInfo);
         }

    }

    public function enviarInstrucciones() {
        // create a new object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST']; // 'smtp.gmail.com'; ;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls'; // 'ssl';
        $mail->Port = $_ENV['EMAIL_PORT']; //465; 
        $mail->Username = $_ENV['EMAIL_USER']; // 'julianithox1@gmail.com'; 
        $mail->Password = $_ENV['EMAIL_PASS']; // 'ddvcysabiytmkwca'; 
    
        $negocio = negocio::get(1);
        $mail->setFrom($negocio[0]->email);
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Reestablece tu password';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/recuperar?token=" . $this->token . "'>Reestablecer Password</a>";        
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //Enviar el mail
        $mail->send();
    }
}