<?php 

namespace Classes;

class ws_cloud_api{

    public function __construct($ws, $ncita, $nombrecliente, $telcliente, $profesional, $servicio, $fecha, $hora){
        $this->ws = $ws;
        $this->ncita = $ncita;
        $this->nombrecliente = $nombrecliente;
        $this->telcliente = $telcliente;
        $this->profesional = $profesional;
        $this->servicio = $servicio;
        $this->fecha = $fecha;
        $this->hora = $hora;
    }

    public function send1textws(){
        //token de fb
        $token="EAAEmL8UtZA0UBOwZCYjCTKDQgJJFhXSB3Y3nlWFJeDV3bH1p5rxFLmyf9LnpOmtJQ2EDJabZCU2dhzW71Kn29sPDdg95Toe6ySPUwJ854HXQGGZCEiX4SJ0LsK6yjM80JfOWHWCPxVoxYN1ItrkQlF8L0Ljp91u6glCEafNRcjsKruezWb4tTzLd7ZAXYGMtt";
        //telefono al q se va emviar msj
        $telefono="57{$this->ws}";
        //url a donde se enviará el mensaje
        $url="https://graph.facebook.com/v17.0/115280074990733/messages";
        //configuración del mensaje
        /*
        $mensaje= '{'
                .'"messaging_product": "whatsapp", '
                .'"recipient_type": "individual", '
                .'"to": "'.$telefono.'", '
                .'"type": "template", '
                .'"template": '
                    .'{ '
                    .'"name": "hello_world", '
                    .'"language": { "code":"en_US" } '
                    .'} '
                .'}'
        ;
        */
        
        $mensaje= '{'
            .'"messaging_product": "whatsapp", '
            .'"recipient_type": "individual", '
            .'"to": "'.$telefono.'", '
            .'"type": "text", '
            .'"text": '
                .'{ '
                .'"preview_url": false, '
                .'"body": "Cita Nº:'.$this->ncita.' reservada por: '.$this->nombrecliente.', Telefono cliente: '.$this->telcliente.', Profesional: '.$this->profesional.', Servicio: '.$this->servicio.', Fecha de la cita: '.$this->fecha.' Hora de la cita: '.$this->hora.'"'
                .'} '
            .'}'
        ;
        //debuguear($mensaje);
        //declaramos las cabeceras
        $header=array("Authorization: Bearer " . $token, "Content-Type:application/json",);
        //iniciamos la curl
        $curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); // Establecer la URL a la que se va a realizar la solicitud
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //obtenemos la respuesta del envío de la información
        $response=json_decode(curl_exec($curl), true); // Ejecutar la solicitud y obtener la respuesta
        //imprimimos la respuesta
        //print_r($response);
        //obtenemos el código de la respuesta
        $status_code=curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //cerramos la curl
        curl_close($curl);
    }


    public function send2textws(){  //enviar msj ws cuando cliente cancela la cita
        //token de fb
        $token="EAAEmL8UtZA0UBOwZCYjCTKDQgJJFhXSB3Y3nlWFJeDV3bH1p5rxFLmyf9LnpOmtJQ2EDJabZCU2dhzW71Kn29sPDdg95Toe6ySPUwJ854HXQGGZCEiX4SJ0LsK6yjM80JfOWHWCPxVoxYN1ItrkQlF8L0Ljp91u6glCEafNRcjsKruezWb4tTzLd7ZAXYGMtt";
        //telefono al q se va emviar msj
        $telefono="57{$this->ws}";
        //url a donde se enviará el mensaje
        $url="https://graph.facebook.com/v17.0/115280074990733/messages";
        //configuración del mensaje
        
        $mensaje= '{'
            .'"messaging_product": "whatsapp", '
            .'"recipient_type": "individual", '
            .'"to": "'.$telefono.'", '
            .'"type": "text", '
            .'"text": '
                .'{ '
                .'"preview_url": false, '
                .'"body": "Cita Nº:'.$this->ncita.' Cancelada. Por: '.$this->nombrecliente.', Servicio: '.$this->servicio.', Fecha de la cita: '.$this->fecha.' Hora de la cita: '.$this->hora.'. Cancelada."'
                .'} '
            .'}'
        ;
        //declaramos las cabeceras
        $header=array("Authorization: Bearer " . $token, "Content-Type:application/json",);
        //iniciamos la curl
        $curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); // Establecer la URL a la que se va a realizar la solicitud
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //obtenemos la respuesta del envío de la información
        $response=json_decode(curl_exec($curl), true); // Ejecutar la solicitud y obtener la respuesta
        //imprimimos la respuesta
        //print_r($response);
        //obtenemos el código de la respuesta
        $status_code=curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //cerramos la curl
        curl_close($curl);
    }

}