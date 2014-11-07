<?php 
//// Variables de configuracion del Super PauSpeculator Plus Megatrading!
// -----------------------------------------------
//-------------Info del Exchange a Tradear...

$idUser=3;
$mailUser='xxxxxxxx@gmail.com';
$ecoin='btc';
$exchange='btce';
$key='xxxxxxxxxxxxxx';
$secret='xxxxxxxxxxxxxxx';
$id_exchange='';

//---------Parametros de configuracion del script------

$marcoTiempo=60;		//1 hora
$mediaSmall=$marcoTiempo*7;
$mediaLarge=$marcoTiempo*26;
$stopLossSellPercent=0.2;       //% Porcentaje bajo el precio sobre el que vende
$stopLossBuyPercent=0.2;        //% Porcentaje sobre el precio sobre el que compra
$enviarMail=1;                  //1- Se envian mails. 0-No se envian
?>
