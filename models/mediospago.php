<?php

namespace Model;

class mediospago extends ActiveRecord{
    protected static $tabla = 'mediospago';
    protected static $columnasDB = ['id', 'mediopago', 'tipo', 'longitud', 'estado'];
    
    public function __construct($args = []){
        $this->id = $args['id']??'';
        $this->mediopago = $args['mediopago']??'';
        $this->tipo = $args['tipo']??'';
        $this->longitud = $args['longitud']??'';
        $this->estado = $args['estado']??'';
    }


    public function validar(){
        if(!$this->mediopago)$alertas['error'][] = "Medio de pago no especificado";
        return $alertas;
    }
}

?>