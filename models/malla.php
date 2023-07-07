<?php

namespace Model;

class malla extends ActiveRecord {
    protected static $tabla = 'malla';
    protected static $columnasDB = ['id', 'id_empleado', 'id_dia', 'inicioturno', 'inidescanso', 'findescanso', 'finturno', 'intervalo', 'dato1'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_empleado = $args['id_empleado'] ?? '';
        $this->id_dia = $args['id_dia'] ?? '';
        $this->inicioturno = $args['inicioturno'] ?? '';
        $this->inidescanso = $args['inidescanso'] ?? '';
        $this->findescanso = $args['findescanso'] ?? 'Pendiente';
        $this->finturno = $args['finturno'] ?? '';
        $this->intervalo = $args['intervalo'] ?? '';
        $this->dato1 = $args['dato1'] ?? '';
    }

    // Validar los servicios
    public function validarmalla() {
        if(!$this->inicioturno) {
            self::$alertas['error'][] = 'Inicio de turno no valido';  //['error] = ['string1', 'string2'...]
        }  //como el arreglo alertas es heredada de la clase padre activerecord self hace referencia a este arreglo de la clase padre
        if(!$this->inidescanso) {
            self::$alertas['error'][] = 'Inicio descanso no valido';
        }
        if(!$this->findescanso) {
            self::$alertas['error'][] = 'fin descanso no valido';
        }
        if(!$this->finturno) {
            self::$alertas['error'][] = 'fin turno no valido';
        }
        return self::$alertas;
    }

    public function actualizarmalla($empleado = [], $dias = [], $entrada = [], $inidescanso = [], $findescanso = [], $salida = []):bool {
        foreach($empleado as $dia)$diasdb[$dia->id] = $dia->id_dia;  //los ids de los dias q estan en la db
            $acts = array_intersect($diasdb, $dias);  //arreglo con los dias a solo actualizar
            foreach($acts as $key => $value) $ids[] = $key;   //los ids de la DB a actualizar

            //quitar la llave adicional para poder actualizar la malla
            $quitarkeys = array_diff($dias, $diasdb); 
            $quitarkeys = array_keys($quitarkeys); //obtengo las llaves a quitar
        
        $atributos = ['inicioturno'=>$entrada,  'inidescanso'=>$inidescanso, 'findescanso'=>$findescanso, 'finturno'=>$salida];
        
        foreach($atributos as $key => $values)
            foreach($values as $i => $value)
                foreach($quitarkeys as $quitarkey)
                    if($i == $quitarkey)unset($atributos[$key][$i]);
                      
        if($acts){
            $r = malla::updatemultiple($atributos, $ids);
        }else{
            $r = false;
        }
        return $r;
    }

    public function eliminardias($empleado = [], $dias = []):bool 
    {
        foreach($empleado as $dia)$diasdb[$dia->id] = $dia->id_dia;
        $eliminar = array_diff($diasdb, $dias);
        foreach($eliminar as $key => $value) $ids[] = $key;
        if($eliminar){
            $r = malla::eliminar_idregistros('id', $ids);
        }else{
            $r = false;
        }
        return $r; //$r = true/false
    }

    public function adicionardias($idempleado, $empleado = [], $dias = [], $entrada = [], $inidescanso = [], $findescanso = [], $salida = []):array 
    {
        foreach($empleado as $dia)$diasdb[$dia->id] = $dia->id_dia;
        $adicionar = array_diff($dias, $diasdb);
        
        $atributos = ['inicioturno'=>$entrada,  'inidescanso'=>$inidescanso, 'findescanso'=>$findescanso, 'finturno'=>$salida];

        foreach($atributos as $key => $values){
            foreach($values as $i => $value){
                $t = true;
                foreach($adicionar as $j => $dia){
                    if($i == $j){
                        $t = false;
                        break;
                    }
                }
                if($t)unset($atributos[$key][$i]); 
            }
        }
        
        foreach($adicionar as $key => $id_dia){
            $datos[] = ['id_empleado'=>$idempleado, 'id_dia'=>$id_dia, 'inicioturno'=>$atributos['inicioturno'][$key], 'inidescanso'=>$atributos['inidescanso'][$key], 'findescanso'=>$atributos['findescanso'][$key], 'finturno'=>$atributos['finturno'][$key], 'intervalo'=>'', 'dato1'=>''];
        }
        if($adicionar){
        $r = malla::crear_varios_reg($datos);
        }else{
            $r = [];
        }
        return $r; //$r = true/false
    }

}