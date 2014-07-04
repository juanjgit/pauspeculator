<?php 
//// Variables de configuracion del Super PauSpeculator Plus Megatrading!
// -----------------------------------------------
//-------------Info del Exchange a Tradear...

$idUser=1;
$mailUser='pausoluciones@gmail.com';
$ecoin='vrc';
$exchange='cryptsy';
$key='fbb4a877214781d7df39c17a50dfd6741fd9a1f1';
$secret='38bdf5b68e46dee04162181a0b3725c45b37d2f7c14c46ccbc8df95817bd70a3b0a2fb0a656996e1';
$id_exchange=''; //Id del user (utilitzado por bitstamp=
$idMarket=209; //Id del mercado a comparar (utilizado por Cryptsy)

//---------Parametros de configuracion del script------

$marcoTiempo=60;		//1 hora
$mediaSmall=$marcoTiempo*7;
$mediaLarge=$marcoTiempo*26;
$stopLossSellPercent=0.2;       //% Porcentaje bajo el precio sobre el que vende
$stopLossBuyPercent=0.2;        //% Porcentaje sobre el precio sobre el que compra
$enviarMail=1;                  //1- Se envian mails. 0-No se envian
?>
