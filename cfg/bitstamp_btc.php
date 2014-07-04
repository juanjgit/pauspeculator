<?php

$ecoin='btc';                               //Moneda con la que operar (ltc, btc, nmc, nvc, ppc)
$exchange='bitstamp';                       //Exchange en el que opera
$key='ofR1pEOs7ppo4ZUklbdFrpYVnK37BN83';    //La clave de la api del Exchange
$secret='LKOtAV97cTmHnDLuHtbQtH4UNKxPzrwv'; //y su  secreto iberico
$id_exchange='89331';                       //El id de usuario (solo para Bitstamp)

//---------Parametros de configuracion del script------

$ecoinAvisoPercent=4;                       //% Porcentaje sobre el que vende o compra
$stopLossSell=1;                            //1- Vende automaticamente al dejar de subir el precio. 0- No lo hace
$stopLossBuy=1;                             //1- Compra automaticamente al dejar de caer el precio. 0- No lo hace
$stopLossSellPercent=0.2;       //% Porcentaje bajo el precio sobre el que vende
$stopLossBuyPercent=0.2;         //% Porcentaje sobre el precio sobre el que compra
$enviarMail=1;                              //1- Se envian mails. 0-No se envian
?>
