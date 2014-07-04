<?php 
//// Variables de configuracion del Super PauSpeculator Plus Megatrading!
// -----------------------------------------------
//-------------Info del Exchange a Tradear...

$idUser=3;
$mailUser='pausoluciones@gmail.com';
$ecoin='btc';
$exchange='btce';
$key='ZJRN5UKR-PRA1IU8Q-DE7E4ES1-XDCUNJVS-GVR1LUDO';
$secret='483980f8f6e47a244ee16d1483e438d1dd063f97eb90947c41b4ac41aa9d18af';
$id_exchange='';

//---------Parametros de configuracion del script------

$marcoTiempo=60;		//1 hora
$mediaSmall=$marcoTiempo*7;
$mediaLarge=$marcoTiempo*26;
$stopLossSellPercent=0.2;       //% Porcentaje bajo el precio sobre el que vende
$stopLossBuyPercent=0.2;        //% Porcentaje sobre el precio sobre el que compra
$enviarMail=1;                  //1- Se envian mails. 0-No se envian
?>
