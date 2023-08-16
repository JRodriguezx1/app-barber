<?php

namespace Model;

class facturacion extends ActiveRecord {
    protected static $tabla = 'facturacion';
    protected static $columnasDB = ['id', 'idcita', 'idempleado', 'id_pagosxdia', 'idmediospago', 'tipo', 'nota', 'hora_pago', 'fecha_pago', 'valor_servicio', 'dcto', 'valordcto', 'promodcto', 'valorpromo', 'recibido', 'devolucion', 'total'];
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->idcita = $args['idcita'] ?? '';
        $this->idempleado = $args['idempleado'] ?? null;
        $this->id_pagosxdia = $args['id_pagosxdia']??'';
        $this->idmediospago = $args['idmediospago']??1;
        $this->tipo = $args['tipo'] ?? '';
        $this->nota = $args['nota'] ?? '';
        $this->hora_pago = $args['hora_pago'] ?? date('h:i:s');  // hora actual
        $this->fecha_pago = $args['fecha_pago'] ?? date('Y-m-d'); //fecha actual
        $this->valor_servicio = $args['valor_servicio'] ?? '';
        $this->dcto = $args['dcto'] ?? 0;
        $this->valordcto = $args['valordcto'] ?? 0;
        $this->promodcto = $args['promodcto'] ?? 0;
        $this->valorpromo = $args['valorpromo'] ?? 0;
        $this->recibido = $args['recibido'] ?? '';
        $this->devolucion = $args['devolucion'] ?? '';
        $this->total = $args['total'] ?? '';
    }

    // Validar facturacion
    public function validarfacturacion() {
        if(!$this->hora_pago) {
            self::$alertas['error'][] = 'Hora no permitida';
        }
        if(!$this->fecha_pago) {
            self::$alertas['error'][] = 'Fecha no permitida';
        }
        if(!is_numeric($this->valor)) {
            self::$alertas['error'][] = 'El dato no es numerico';
        }
        return self::$alertas;
    }

}