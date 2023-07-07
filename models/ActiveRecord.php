<?php
namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) { //este metodo se llama por primera vez en app.php
        self::$db = $database; //self hace referencia a atributos y metodos estatic de la clase padre o clase actual, es decir a la propiedad $db de la clase padre activerecord
    }

    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Validación
    public static function getAlertas() {
        return static::$alertas;
    }

    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    public function eliminar_registro(){
        $sqldelete = "DELETE FROM ".static::$tabla." WHERE id = ".self::$db->escape_string($this->id)." LIMIT 1";
        //debuguear($this);  $this tiene el objeto actual instanciado
        $resultado = self::$db->query($sqldelete);
        return $resultado;
    } 


    public static function eliminar_idregistros($id, $array = []){  //'id', [11, 8]
        $sqldelete = "DELETE FROM ".static::$tabla." WHERE ${id} IN (";
        foreach($array as $key => $value){
            if(array_key_last($array) == $key){ 
                $sqldelete.= self::$db->escape_string($value).");";
            }else{
                $sqldelete.= self::$db->escape_string($value).", ";
            }
        }
        //DELETE FROM empserv WHERE id IN (11, 12);
        $resultado = self::$db->query($sqldelete);
        return $resultado;  
    }


    public function crear_guardar(){
        $atributos = $this->sanitizar_datos();  //$atributos obtiene arreglo ['titulo'=>valor1, 'precio'=>valor2] 
        $string1 = join(', ', array_keys($atributos));  //array_keys  = ['llave1', 'llave2', 'llave3', ....]
        $string2 = join("', '", array_values($atributos)); // array_values = [valor1', 'valor2', 'valor3', '....]
        $sql = "INSERT INTO ".static::$tabla."(";
        $sql .= $string1;
        $sql .= ") VALUES('";
        $sql .= $string2;
        $sql .= "');";
        $resultado = self::$db->query($sql);
        return [$resultado, self::$db->insert_id];  //insert_id retorna el ultimo registro insertado en la bd
           //  [true/false, id=1,2,3...00] = [0,1] 
    }


    public function crear_varios_reg($arrays = []){ //guardar varios registros a la vez
                                  // $arrays = [['colum11', 'colum12',...],['colum21', 'colum22',..], ...] arrays contiene los valores de los registros a guardar
        $a = static::$columnasDB;
        $string2 = '';

        foreach($arrays as $key => $value){
            foreach(static::$columnasDB as $columdb){
                if($columdb == 'id')continue;
                $this->$columdb = $value[$columdb];
            }
            /////ya se lleno la primera vez el objeto////
            $atributos = $this->sanitizar_datos();
            if(array_key_last($arrays) == $key){
                $string2 .= "('".join("', '", array_values($atributos))."');";
            }else{
                $string2 .= "('".join("', '", array_values($atributos))."'), ";
            }
        }
        $string1 = join(', ', array_keys($atributos));
        $sql = "INSERT INTO ".static::$tabla."(".$string1.") VALUES".$string2;
        $resultado = self::$db->query($sql); 
        //INSERT INTO empserv(idempleado, idservicio) VALUES('3', '3'), ('3', '1');
        return [$resultado, self::$db->insert_id];
    }


    public function actualizar(){
        $atributos = $this->sanitizar_datos(); 
        $valores = [];
        foreach($atributos as $key => $value ){
            if($key === "fechacreacion")$value = date('y-m-d'); //
            $valores[] = "{$key}='{$value}'"; //$valores = [llave1='valor1', llave2='valor2',...]
        }
        $query = "UPDATE ".static::$tabla." SET ";
        $query .= join(", ", $valores); // = "llave1='valor1', llave2=>valor2,...."
        $query .= " WHERE id = '".self::$db->escape_string($this->id)."'";
        $query .= " LIMIT 1;";
        $resultado = self::$db->query($query);
        return $resultado;
    }


    //actualizar multiples registros, pero simpre y cuando las filas tengan los mismos valores en cada columna (valores, ids)
    public static function actualizar_ids($atributos=[], $ids=[]){
        $valores = [];
        foreach($atributos as $key => $value){
            $atributos[$key] = self::$db->escape_string($value);  
        }
        foreach($atributos as $key => $value ){
            $valores[] = "{$key}='{$value}'"; //$valores = [llave1='valor1', llave2='valor2',...]
        }
        $query = "UPDATE ".static::$tabla." SET ";
        $query .= join(", ", $valores); // = "llave1='valor1', llave2=>valor2,...."
        $query .= " WHERE id IN (";
        foreach($ids as $index => $element){
            if(array_key_last($ids) == $index){  //toma la ultima llave y la compara con la llave actual
                $query.= self::$db->escape_string($element).");";
            }else{
                $query.= self::$db->escape_string($element).", ";
            }
        } 
        //UPDATE estud_grupo SET idnivel='2', idgrupo='8' WHERE id IN (1, 3, 5);
        $resultado = self::$db->query($query);
        return $resultado;
    }


    //actualizar multiples filas donde cada fila tiene valores diferentes en cada columna
    public static function updatemultiple($array=[], $ids=[]){  //array = ['colum1'=>[fila1, fila2..], 'colum2'=>[fila1, fila2..]...]
        $valores = [];
        $query = "UPDATE ".static::$tabla." SET ";
        foreach($array as $keycolum => $colum){
            $query .= $keycolum." = CASE ";
            $j = 0;
            foreach($colum as $key => $value){
                $atributos[$keycolum][$key] = self::$db->escape_string($value);
                $query .= "WHEN id = {$ids[$j]} THEN '{$value}' ";
                $j++;
            } 
            if(array_key_last($array) === $keycolum){
                $query .= "ELSE {$keycolum} END";
            }else{
                $query .= "ELSE {$keycolum} END, ";
            } 
        }
        $query .= " WHERE id IN (";
        $query .= join(", ", array_values($ids));
        $query .= ");";
        //UPDATE nombretabla SET idnivel = CASE WHEN id = 1 THEN '2' WHEN id = 3 THEN '4' WHEN id = 5 THEN '6' ELSE idnivel END, idgrupo = CASE WHEN id = 1 THEN '8' WHEN id = 3 THEN '10' WHEN id = 5 THEN '12' ELSE idgrupo END WHERE id IN (1, 3, 5);
        $resultado = self::$db->query($query);
        return $resultado;
    }

    
    public function actualizar_referencia($copyreferencia){   //actualiza el id, referencia o llave primaria
        $atributos = $this->sanitizar_datos(); 
        $valores = [];
        foreach($atributos as $key => $value ){
            if($key === "fechacreacion")$value = date('y-m-d'); //
            $valores[] = "{$key}='{$value}'"; //$valores = [llave1='valor1', llave2='valor2',...]
        }
        $query = "UPDATE ".static::$tabla." SET ";
        $query .= join(", ", $valores);
        $query .= " WHERE Referencia = '".self::$db->escape_string($copyreferencia)."'";
        $query .= " LIMIT 1;";
        $resultado = self::$db->query($query);
        return $resultado;
    }


    public function sanitizar_datos(){
        $atributos = $this->atributos();  //devuelve arreglo asociativo ['titulo'=>valor_titulo, 'precio'=>valor_precio]
        $sanitizado = [];
        foreach($atributos as $key => $value){
            $sanitizado[$key] = self::$db->escape_string($value);  //arreglo sanitizado ['titulo'=>valor_titulo, 'precio'=>valor_precio] pero protegido pra inyecciones sql
        }
        return $sanitizado;  //se retorna arreglo[] ya sanitizado
    }


    public function atributos(){
        $atributos = [];
        foreach(static::$columnasDB as $columna){  //el arreglo $columnas_db toma valores de la clase hija
            if($columna == 'id') continue;  
            $atributos[$columna] = $this->$columna;    //columna va iterando y va tomando valores como 'titulo', 'precio'
        }      
        return $atributos;  //retorna arreglo asociativo ['titulo'=>$this->titulo, 'precio'=>$this->precio]
    }


    public static function all(){ //se trae todos los registros de la tabla de la bd
        $sql = "SELECT *FROM ".static::$tabla;  //accede al atributo protegido, static es un modificador de acceso, static busca el atributo tabla en la clase el cual se hereda
        $resultado = self::consultar_Sql($sql); 
        return $resultado;  //$resultado es un arreglo de objetos
    }


    public static function get($cantidad){  
        $sql = "SELECT *FROM ".static::$tabla." LIMIT ".$cantidad;
        $resultado = self::consultar_sql($sql);  //self hace referencia a la clase padre, self solo funciona dentro de metodos
        return $resultado;  //$resultado es arreglo de objetos
    }


    public static function filtro_nombre($columna, $nombre, $orden){   //metodo que trae los registros con el nombre especificado
        $sql = "SELECT *FROM ".static::$tabla." WHERE {$columna} LIKE "."'%{$nombre}%'"." ORDER BY {$orden} DESC;";
        $resultado = self::consultar_sql($sql);
        return $resultado;
    }


    public static function idregistros($colum, $id){ ////metodo que busca todos los registro qu pertenecen a un id
        $sql = "SELECT *FROM ".static::$tabla." WHERE $colum = '${id}';";
        $resultado = self::consultar_Sql($sql);
        return $resultado;
    }


    //busca un solo registro por su id, se puede usar para validar registros de login con email
    public static function find($colum, $id){
        $sql = "SELECT *FROM ".static::$tabla." WHERE $colum = '${id}' LIMIT 1;";
        $resultado = self::consultar_Sql($sql);
        return array_shift($resultado); //array_shift retorna el primer elemento del arreglo
    }

    //metodo de consulta libre utilizando lenguaje sql de inner_join
    public static function inner_join($consulta){
        $resultado = self::consultar_sql($consulta);
        return $resultado;
    }

    //metodo solo llamado desde clase hija que tenga la propiedad public email, y se debe instanciar clase hija para llamar este metodo
    public function validar_registro(){
        $sql = "SELECT * FROM ".static::$tabla." WHERE email = '".$this->email."' LIMIT 1;";  // propiedad email se defina en clase hija, y desde la clase hija se hereda este metodo de validar_registro(), por lo tanto este metodo solo debe ser llamado desde objeto con clase hija, clase hija q tenga la propiedad public email
        $resultado = self::$db->query($sql);
        return $resultado->num_rows;  //si hay registro num_rows = 1, si no hay registro num_rows = 0
    }


    //numero total de registros de una tabla
    public static function numregistros(){
        $sql = "SELECT COUNT(*) FROM ".static::$tabla.";";
        $resultado = self::$db->query($sql);
        $total = $resultado->fetch_assoc();
        return array_shift($total);
    }

    
    //numero total de registros de una tabla q cumplan una condicion
    public static function numreg_where($colum, $id){
        $sql = "SELECT COUNT(*) FROM ".static::$tabla." WHERE $colum = '${id}';";
        $resultado = self::$db->query($sql);
        $total = $resultado->fetch_assoc();
        return array_shift($total);
    }


    //suma numerica de una columna segun id
    public static function sumcolum($colum, $id, $colsum){
        $sql = "SELECT SUM($colsum) FROM ".static::$tabla." WHERE $colum = '${id}';";
        $resultado = self::$db->query($sql);
        $total = $resultado->fetch_assoc();
        return array_shift($total);
    }


    //paginar registros toma numero de registros por pagina y el offset
    public static function paginar($Nregistros_porpagina, $offset){
        $sql = "SELECT *FROM ".static::$tabla." ORDER BY id DESC LIMIT ".$Nregistros_porpagina." OFFSET ".$offset;
        $resultado = self::consultar_sql($sql);  
        return $resultado;  
    }

    
    //// busqueda con where con multiples opciones
    public static function whereArray($array = []){ ////
        $sql = "SELECT *FROM ".static::$tabla." WHERE ";
        foreach($array as $key => $value){
            if(array_key_last($array) == $key){
                $sql.= " ${key} = '${value}'";
            }else{
                $sql.= " ${key} = '${value}' AND ";
            }
        }
        $resultado = self::consultar_Sql($sql);
        return $resultado;
    }


    public static function ordenar($columna, $orden){
        $sql = "SELECT *FROM ".static::$tabla." ORDER BY ${columna} ${orden};";
        $resultado = self::consultar_sql($sql);
        return $resultado;
    }


    public static function ordenarlimite($columna, $orden, $limite){
        $sql = "SELECT *FROM ".static::$tabla." ORDER BY ${columna} ${orden} "."LIMIT ${limite}";
        $resultado = self::consultar_sql($sql);
        return $resultado;
    }
    

    public static function uncampo($colum, $id, $campo){ /// se trae un solo valor o campo segun el id que se le pase
        $sql = "SELECT ".static::$tabla.".".$campo." FROM ".static::$tabla." WHERE $colum = '${id}';";
        $resultado = self::$db->query($sql);
        $valorcampo = $resultado->fetch_assoc();
        return array_shift($valorcampo);
    }
    

    public static function consultar_sql($sql){
        $resultado = self::$db->query($sql);  // consulta la bd se trae toda la tabla 
        $array = []; 
        while($row = $resultado->fetch_assoc()){  //$row obtiene un registro a la vez desde el primero de la tabla de la bd
            $array[] = self::crear_objeto($row);   //Row = ['id'=>1, 'titulo'=>'xxxx'...] $row es un arreglo asociativo
        }
        $resultado->free();
        return $array;  //$array es un arreglo de objetos
    }


    public static function crear_objeto($row){
        $objeto = new static;  //crea un objeto con los atributos vacios y el tipo depende si se llamada del padre o del hijo
        foreach($row as $key => $value){  //Row es un arreglo asociativo donde las llaves son los atributos del objeto o clase
            if(property_exists($objeto, $key)){   // Esta función comprueba si la propiedad dada por $key existe en la clase o objeto especificada
                $objeto->$key = $value;
            }
        } 
        return $objeto;  //retorna el nuevo objeto creado dinamicamneto con los atributos llenos donde los atriburos son los campos de la bd..
    }


    //compara el objeto creado con los valores del $_POST, y remplaza solo los valores del $post
    public function compara_objetobd_post($args){
        foreach($args as $key => $value){      //$key = a la llave del arreglo asociativo y $value es el valor de esa llave del arreglo asociativo
            if(property_exists($this, $key) && !is_null($value)){   //property_exist revisa de un objeto que un atributo exista
               $this->$key = $value;                 //$this hace referencia al objeto actual, objeto va tomando los valores del $_POST                
            }
        }  
    }
  
}