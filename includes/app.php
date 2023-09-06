<?php 

use Dotenv\Dotenv;   //variables de entorno para el deploy
use Model\ActiveRecord;  //importa el archivo de modelo para la gestion de bd mysql
require __DIR__ . '/../vendor/autoload.php';  //localizar las clases

// Añadir Dotenv
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require 'funciones.php';  //archivo de funciones debuguear y sanitizar html
require 'configDB.php';
require 'database.php';    //archivo de conexion de bd mysql con variables de entorno

// Conectarnos a la base de datos
ActiveRecord::setDB($db); //llama al modelo o clase ActiveRecord y a su metodo setDB y se le pasa la conexion $db definido dentro database.php
//setDB es publico estatic no requiere instanciarse para acceder a este metodo