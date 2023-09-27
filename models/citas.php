<?php

namespace Model;

class citas extends ActiveRecord {
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'id_usuario', 'id_empserv', 'fecha_inicio', 'fecha_fin', 'hora', 'hora_fin', 'estado', 'duracion', 'valorcita', 'dcto', 'dctovalor', 'nameservicio', 'nameprofesional', 'nombrecliente'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->id_empserv = $args['id_empserv'] ?? '';
        $this->fecha_inicio = $args['fecha_inicio'] ?? date('Y-m-d');
        $this->fecha_fin = $args['fecha_fin'] ?? '';
        $this->hora = $args['hora'] ?? date('h:i:s');
        $this->hora_fin = $args['hora_fin'] ?? '';
        $this->estado = $args['estado'] ?? 'Pendiente';
        $this->duracion = $args['duracion'] ?? '';
        $this->valorcita = $args['valorcita'] ?? '';
        $this->dcto = $args['dcto'] ?? '0';
        $this->dctovalor = $args['dctovalor'] ?? '0';
        $this->nameservicio = $args['nameservicio'] ?? '';
        $this->nameprofesional = $args['nameprofesional'] ?? '';
        $this->nombrecliente = $args['nombrecliente']??'';
    }

    // Validar los servicios
    public function validarcitas() {
        if(!$this->fecha_fin) {
            self::$alertas['error'][] = 'No hay fecha especificada';  //['error] = ['string1', 'string2'...]
        }  //como el arreglo alertas es heredada de la clase padre activerecord self hace referencia a este arreglo de la clase padre
        if(!$this->hora_fin) {
            self::$alertas['error'][] = 'No hay hora especificada';
        }
        return self::$alertas;
    }
}