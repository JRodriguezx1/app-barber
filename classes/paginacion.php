<?php

namespace Classes;

class paginacion{
    public $pagina_actual;
    public $registros_por_pagina;
    public $registros_total;
    public $rutaenlace; //paginacion se hace en llamada get ruta en donde se hace la paginacion

    public function __construct($pagina_actual = 1, $registros_por_pagina = 10, $registros_total = 0, $rutaenlace = '/')
    {
        $this->pagina_actual = (int)$pagina_actual;
        $this->registros_por_pagina = (int)$registros_por_pagina;
        $this->registros_total = (int)$registros_total;
        $this->rutaenlace = $rutaenlace;
    }



    public function offset(){
        return $this->registros_por_pagina*($this->pagina_actual-1); //registros por pagina = 10
    }
    public function total_paginas(){
        return ceil($this->registros_total/$this->registros_por_pagina);
    }
    public function pagina_anterior(){
        $anterior = $this->pagina_actual-1;
        return ($anterior>0)?$anterior:false;
    }
    public function pagina_siguiente(){
        $siguiente = $this->pagina_actual+1;
        return ($siguiente <= $this->total_paginas())?$siguiente:false;
    }
    public function enlace_anterior(){
        $html = "";
        if($this->pagina_anterior()){
            $html.= "<a class=\"paginacion__enlace paginacion__enlace--texto\" href=\"{$this->rutaenlace}?pagina={$this->pagina_anterior()}\">&laquo; Anterior </a>";
        }
        return $html;
    }
    public function enlace_siguiente(){
        $html = "";
        if($this->pagina_siguiente()){
            $html.= "<a class=\"paginacion__enlace paginacion__enlace--texto\" href=\"{$this->rutaenlace}?pagina={$this->pagina_siguiente()}\">Siguiente &raquo; </a>";
        }
        return $html;
    }
    public function paginacion(){
        $html = "";
        if($this->registros_total>1){
            $html.= '<div class="paginacion">';
            $html.= $this->enlace_anterior();
            $html.= $this->nums_paginas();
            $html.= $this->enlace_siguiente();
            $html.= '</div>';
        }
        return $html;    
    }
    public function nums_paginas(){
        $html = "";
        for($i = 1; $i<=$this->total_paginas(); $i++){
            if($i == $this->pagina_actual){
                $html.= "<span class='paginacion__enlace paginacion__enlace--actual'>{$i}</span>";
            }else{
                $html.= "<a class='paginacion__enlace paginacion__enlace--numero' href='{$this->rutaenlace}?pagina={$i}'>{$i}</a>";
            }
        }
        return $html;

    }


}