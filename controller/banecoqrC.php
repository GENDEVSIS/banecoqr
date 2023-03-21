<?php
    require_once("../conexion/conexion_mysql.php");
    require_once("../models/banecoqrM.php");
 

    //instanciamos con el usuario,password,key,cta de banco,url api
    $userbaneco='40224310';
    $password='1234';
    $key='DB6DA4629DA746E6B474C6EA0280DA29';
    $cta_encrypt='PRH+zrDOAJOjDvBcZ/G73W5SiJN1RBkh9vR26J4H3dE='; //encriptacion generada por Url de Baneco
    $url='https://apimktdesa.baneco.com.bo/ApiGateway/';
    
    $baneco = new banecoqr($userbaneco,$password,$key,$cta_encrypt,$url);

    $encryp=$baneco->cifrar_password();
    
    $token=$baneco->autenticar($encryp);

    // Datos para generar el QR
    
    // $transactionid=$_POST[''];
    // $moneda=$_POST[''];
    // $monto=$_POST[''];
    // $descripcion=$_POST[''];
    // $fvalides=$_POST[''];
    // $npagos=$_POST[''];
    // $modpagos=$_POST[''];

   // Data TESTS
    $transactionid="123456780";
    $moneda="BOB";
    $monto="1.2";
    $descripcion="Generacion de QR";
    $fvalides="2023-03-20";
    $npagos="true";
    $modpagos="false"; 

    $generaqr=$baneco->generarqr($token,$transactionid,$moneda,$monto,$descripcion,$fvalides,$npagos,$modpagos);
    
?>