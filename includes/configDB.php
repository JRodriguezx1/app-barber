<?php

$host = $_SERVER['HTTP_HOST'];  //app_barber.test, cliente1.app_barber.test, cliente2.app_barber.test
$cliente = explode('.', $host); // $cliente['cliente1', 'app_barber', 'test']

$configDB = [
    //'app_barber'=>['namedb'=>'intermix_limpio'],
    'cliente'=>['namedb'=>'app_barber'],
    'cliente1'=>['namedb'=>'app_barber1'],
    'cliente2'=>['namedb'=>'app_barber2']
];

$selectDB = $configDB[$cliente[0]]??'';

if($selectDB == null){ ?>
    <meta http-equiv="refresh" content="0; url=https://innovatech-production.up.railway.app/">
<?php
    exit;
}
?>




