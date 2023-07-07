<?php

namespace Model;

class pagosxdia extends ActiveRecord {
    protected static $tabla = 'pagosxdia';
    protected static $columnasDB = ['id', 'totalservices', 'fecha', 'horacierre', 'computarizado', 'totaldia', 'estado'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->totalservices = $args['totalservices'] ?? '';
        $this->fecha = $args['fecha']??date('Y-m-d'); //fecha actual
        $this->horacierre = $args['horacierre'] ?? '';
        $this->computarizado = $args['computarizado'] ?? '';
        $this->totaldia = $args['totaldia'] ?? '';  
        $this->estado = $args['estado'] ?? 'Abierto'; 
    }

    // Validar pagosxdia
    public function validarpagosxdia() {
        
        if(!is_numeric($this->totaldia)) {
            self::$alertas['error'][] = 'El dato no es numerico';
        }
        return self::$alertas;
    }

}