<?php

namespace Model;

class negocio extends ActiveRecord {
    protected static $tabla = 'negocio';
    protected static $columnasDB = ['id', 'nombre', 'ciudad', 'direccion', 'telefono', 'movil', 'nit', 'ws', 'facebook', 'instagram', 'tiktok', 'youtube', 'twitter', 'logo'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->movil = $args['movil'] ?? '';
        $this->nit = $args['nit'] ?? '';
        $this->ws = $args['ws'] ?? '';
        $this->facebook = $args['facebook'] ?? '';
        $this->instagram = $args['instagram'] ?? '';
        $this->tiktok = $args['tiktok'] ?? '';
        $this->youtube = $args['youtube'] ?? '';
        $this->twitter = $args['twitter'] ?? '';
        $this->logo = $args['logo'] ?? '';
    }

    // Validar los servicios
    public function validarnegocio() {
        if(!$this->nombre || strlen($this->nombre)>42) {
            self::$alertas['error'][] = 'Nombre del negocio no valido';  //['error] = ['string1', 'string2'...]
        }  //como el arreglo alertas es heredada de la clase padre activerecord self hace referencia a este arreglo de la clase padre
        if(!$this->ciudad || strlen($this->ciudad)>40) {
            self::$alertas['error'][] = 'Ciudad no valida';
        }
        if(!$this->direccion || strlen($this->direccion)>55) {
            self::$alertas['error'][] = 'Direccion no valida';
        }
        if(strlen($this->telefono)>13) {
            self::$alertas['error'][] = 'Telefono muy extenso';
        }
        if(!$this->movil || strlen($this->movil)>14) {
            self::$alertas['error'][] = 'Movil no valido';
        }
        if(!$this->ws || strlen($this->ws)>15) {
            self::$alertas['error'][] = 'Whatsapp no valido';
        }
        if(strlen($this->facebook)>77) {
            self::$alertas['error'][] = 'Url de facebook muy extenso';
        }
        if(!$this->facebook){
            self::$alertas['error'][] = 'Url de facebook no valida';
        }
        if(strlen($this->instagram)>77) {
            self::$alertas['error'][] = 'URL de instagram muy extenso';
        }
        if(!$this->logo) {
            self::$alertas['error'][] = 'El logo es obligatorio';
        }
        return self::$alertas;
    }
}