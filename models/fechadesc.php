<?php

namespace Model;

class fechadesc extends ActiveRecord {
    protected static $tabla = 'fechadesc';
    protected static $columnasDB = ['id', 'empleado_id', 'fecha'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->empleado_id = $args['empleado_id'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
    }
}