<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function validar_string_url($path):bool{ //retorna bolean
    return strpos($_SERVER['PATH_INFO']??'/', $path)?true:false;
}

function isauth():void  //valida si el usuario esta registrao
{
  if(!isset($_SESSION['login'])||$_SESSION['admin']!=0){ //si no esta registrado y si no es cliente
      header('Location: /');       //lo redirecciona a la pagina web
  }
}

function isadmin():void
{
    if($_SESSION['admin']!=1){
        header('Location: /');
    }
}