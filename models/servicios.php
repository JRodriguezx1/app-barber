<?php

namespace Model;

class servicios extends ActiveRecord {
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'id_fidelizacion', 'nombre', 'precio', 'duracion', 'dcto', 'dctovalor', 'dctodescp', 'estado'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_fidelizacion = $args['id_fidelizacion'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->duracion = $args['duracion'] ?? '';
        $this->dcto = $args['dcto'] ?? '0';
        $this->dctovalor = $args['dctovalor'] ?? '0';
        $this->dctodescp = $args['dctodescp'] ?? '';
        $this->estado = $args['estado'] ?? 1;
    }

    // Validar los servicios
    public function validarservicios() {
        if(!$this->nombre || strlen($this->nombre)<4) {
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio o muy corto';  //['error] = ['string1', 'string2'...]
        }  //como el arreglo alertas es heredada de la clase padre activerecord self hace referencia a este arreglo de la clase padre
        if($this->precio){
            if(!is_numeric($this->precio))self::$alertas['error'][] = 'El precio no es válido';
        }
        if(!$this->duracion){
            $this->duracion = 0;
        }
        return self::$alertas;
    }

}