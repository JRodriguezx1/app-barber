<?php

namespace Model;

class empleados extends ActiveRecord {
    protected static $tabla = 'empleados';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'cedula', 'movil', 'email', 'fecha_nacimiento', 'genero', 'departamento', 'ciudad', 'direccion', 'dato1', 'dato2'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->cedula = $args['cedula'] ?? '';
        $this->movil = $args['movil'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->fecha_nacimiento = $args['fecha_nacimiento'] ?? date('Y-m-d');
        $this->genero = $args['genero'] ?? '';
        $this->departamento = $args['departamento'] ?? '';
        $this->ciudad = $args['ciudad'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->dato1 = $args['dato1'] ?? '';
        $this->dato2 = $args['dato2'] ?? '';
    }

    // Validar los servicios
    public function validarempleado() {
        if(!$this->nombre || strlen($this->nombre)<3) {
            self::$alertas['error'][] = 'Nombre no valido';  //['error] = ['string1', 'string2'...]
        }  //como el arreglo alertas es heredada de la clase padre activerecord self hace referencia a este arreglo de la clase padre
        if(!$this->apellido || strlen($this->apellido)<3) {
            self::$alertas['error'][] = 'apellido no valido';  
        }
        /*if(!$this->cedula || strlen($this->cedula)<6 || strlen($this->cedula)<10) {
            self::$alertas['error'][] = 'cedula no valido';  
        }*/
        if(!$this->movil || strlen($this->movil)<9) {
            self::$alertas['error'][] = 'movil no valido';  
        }
        return self::$alertas;
    }
    
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        return self::$alertas;
    }
}