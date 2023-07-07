<?php

namespace Model;

class fidelizacion extends ActiveRecord {
    protected static $tabla = 'fidelizacion';
    protected static $columnasDB = ['id', 'descripcion', 'dcto', 'fecha_ini', 'fecha_fin', 'tipo', 'idservicio'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->descripcion = $args['descripcion'] ?? '';
        $this->dcto = $args['dcto'] ?? '';
        $this->fecha_ini = $args['fecha_ini'] ?? date('Y-m-d');
        $this->fecha_fin = $args['fecha_fin'] ?? '';
        $this->tipo = $args['tipo'] ?? '';
        $this->idservicio = $args['idservicio'] ?? '';
    }

    // Validar fidelizacion
    public function validarfidelizacion() {
        if(!$this->descripcion || strlen($this->descripcion)<4) {
            self::$alertas['error'][] = 'La descripcion es obligatoria o muy corto';  //['error] = ['string1', 'string2'...]
        }  //como el arreglo alertas es heredada de la clase padre activerecord self hace referencia a este arreglo de la clase padre
        if(!is_numeric($this->dcto)) {
            self::$alertas['error'][] = 'El precio no es válido';
        }
        if(!$this->fecha_fin) {
            self::$alertas['error'][] = 'Debe seleccionar Fecha de caducidad de la oferta';
        }
        return self::$alertas;
    }

}