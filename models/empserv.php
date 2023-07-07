<?php

namespace Model;

class empserv extends ActiveRecord{
    protected static $tabla = "empserv";
    protected static $columnasDB = ['id', 'idempleado', 'idservicio']; 

    public $id;
    public $idempleado;
    public $idservicio;

    public function __construct($args = [])
    {
        $this->id = $args['id']??null;
        $this->idempleado = $args['idempleado']??'';
        $this->idservicio = $args['idservicio']??'';
        
    }
}