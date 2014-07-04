<?php 
//// Variables de configuracion del Super PauSpeculator Plus Megatrading!
// -----------------------------------------------
//-------------Info del Exchange a Tradear...

$idUser=3;
$mailUser='pausoluciones@gmail.com';
$ecoin='btc';
$exchange='btce';
$key='P18BVAOR-KWZ2RC7D-2XP3BDLL-62MMZSO4-SHC4C9Y4';
$secret='27ca401611794fae7f0c9f646530941c546aba4df0bbc2262457e04fce831427';
$id_exchange='';

//---------Parametros de configuracion del script------

$marcoTiempo=60;		//1 hora
$mediaSmall=$marcoTiempo*7;
$mediaLarge=$marcoTiempo*26;
$stopLossSellPercent=0.2;       //% Porcentaje bajo el precio sobre el que vende
$stopLossBuyPercent=0.2;        //% Porcentaje sobre el precio sobre el que compra
$enviarMail=1;                  //1- Se envian mails. 0-No se envian
?>
