<?php
//// Variables de configuracion del Super PauSpeculator Plus Megatrading!
// -----------------------------------------------
//-------------Info del Exchange a Tradear...
$idUser=1;
$mailUser='xxxxxxx@xxxxxx.xxx';
// Moneda. Posibles valores: btc, ltc
$ecoin='xxx';
// Exchange. Posibles valores: bitstamp, btce
$exchange='xxxx';
$key='xxxxxxxxxxxxxxxxxx';
$secret='xxxxxxxxxxxxxxxxxxxxxx';
//Id del user (utilitzado por bitstamp)
$id_exchange=''; 
//Id del mercado a comparar (utilizado por Cryptsy)
$idMarket=209; 
//---------Parametros de configuracion del script------
$marcoTiempo=60; //1 hora
// Media corta
$mediaSmall=$marcoTiempo*7;
// Media larga
$mediaLarge=$marcoTiempo*26;
//% Porcentaje bajo el precio sobre el que vende
$stopLossSellPercent=0.2;
//% Porcentaje sobre el precio sobre el que compra
$stopLossBuyPercent=0.2;
//1- Se envian mails. 0-No se envian
$enviarMail=1;
?>
