<?php


namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];
    

    public function get($url, $fn){ $this->getRoutes[$url] = $fn; }

    public function post($url, $fn){ $this->postRoutes[$url] = $fn; }

    public function comprobarRutas()
    {
        //$url_actual = $_SERVER['PATH_INFO'] ?? '/';
        $url_actual = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        //$url_actual = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$url_actual] ?? null;
        } else {
            $fn = $this->postRoutes[$url_actual] ?? null;
        }

        if ( $fn ) {
            call_user_func($fn, $this);
        } else {
            echo "Página No Encontrada o Ruta no válida"; //header('Location: /404');
        }
    }

    public function render($view, $datos = [])  //este metodo se llama desde el controlador carpeta controllers
    {
        foreach ($datos as $key => $value) {
            $$key = $value;  //$key = variable variale, cada llave del arreglo asociativo es variable
            //$$key genera variables con los nombres de los keys del arreglo asociativo
        }

        ob_start(); 

        include_once __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean(); // Limpia el Buffer // limpia la memoria y en variable $contenido se almacena el include de arriba, y la variable $contenido se muestra en el include de abajo

        //utilizar el layout o la vista del dashboard deacuerdo a la url
        //$url_actual = $_SERVER['PATH_INFO'] ?? '/';
        //$url_actual = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
        $url_actual = $_SERVER['REQUEST_URI'] ?? '/';
        //debuguear($_SERVER['REQUEST_URI']);
        if(str_contains($url_actual, '/Cliente')){
            include_once __DIR__ . '/views/cliente-layout.php'; //pagina maestra para el dashboard admin-cliente
        }else{
            if(str_contains($url_actual, '/admin')){  //busca /admin en la cadena o variable $url_admin
                include_once __DIR__ . '/views/admin-layout.php';  //pagina maestra para el dashboard admin
            }else{
                include_once __DIR__ . '/views/layout.php';  //pagina maestra para las paginas externas publicas
            }
        }
        
    }
}
