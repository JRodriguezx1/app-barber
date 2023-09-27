<?php
//$dias = facturacion::inner_join('SELECT COUNT(id) AS servicios, fecha_pago, SUM(total) AS totaldia FROM facturacion GROUP BY fecha_pago ORDER BY COUNT(id) DESC;');
namespace Controllers;

use MVC\Router;  //namespace\clase
 
class reportescontrolador{

    public static function index(Router $router){
        session_start();
        isadmin();
        $alertas = [];

        $router->render('admin/reportes/index', ['titulo'=>'Reportes', 'user'=>$_SESSION, 'alertas'=>$alertas]);
    }
}