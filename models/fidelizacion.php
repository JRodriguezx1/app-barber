<?php

namespace Model;

class fidelizacion extends ActiveRecord {
    protected static $tabla = 'fidelizacion';
    protected static $columnasDB = ['id', 'categoria', 'product_serv', 'nombreproducto', 'precioproducto', 'tipo', 'porcentaje', 'valor', 'descripcion', 'fecha_ini', 'fecha_fin', 'dctorecurrente', 'estado', 'sendmsj'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->categoria = $args['categoria'] ?? '';
        $this->product_serv = $args['product_serv'] ?? '';
        $this->nombreproducto = $args['nombreproducto'] ?? '';
        $this->precioproducto = $args['precioproducto'] ?? '';
        $this->tipo = $args['tipo'] ?? '';
        $this->porcentaje = $args['porcentaje'] ?? '';
        $this->valor = $args['valor'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->fecha_ini = $args['fecha_ini'] ?? date('Y-m-d');
        $this->fecha_fin = $args['fecha_fin'] ?? '';
        $this->dctorecurrente = $args['dctorecurrente'] ?? '';
        $this->estado = $args['estado'] ?? '1';
        $this->sendmsj = $args['sendmsj'] ?? '0';
    }

    // Validar fidelizacion
    public function validarfidelizacion() {
        if(!$this->descripcion || strlen($this->descripcion)<4) {
            self::$alertas['error'][] = 'La descripcion es obligatoria o muy corto';  //['error] = ['string1', 'string2'...]
        }  //como el arreglo alertas es heredada de la clase padre activerecord self hace referencia a este arreglo de la clase padre
        if(!is_numeric($this->valor)) {
            self::$alertas['error'][] = 'Valor de dcto no es válido';
        }
        if(!$this->fecha_fin) {
            self::$alertas['error'][] = 'Debe seleccionar Fecha de caducidad de la oferta';
        }
        return self::$alertas;
    }

}