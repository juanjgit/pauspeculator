<?php
// Variables de configuracion del Cogeprecios
// -----------------------------------------------

$rsExchange[]='bitstamp';
$rsExchange[]='btce';

$rsExchangeEcoin['bitstamp'][]='btc';
$rsExchangeEcoin['btce'][]='btc';
$rsExchangeEcoin['btce'][]='ltc';

//---------Parametros de configuracion del script------
$enviarMail=1;                  //1- Se envian mails. 0-No se envian

//-------------------------------------------------
//Empezamos

chdir(dirname(__FILE__)); //cambiamos al directorio donde se ejecuta el script

//Monto la clase de Trading. Este objeto llama a las Apis de los Exchanges

require("../classes/tradeClass.php");

//Monto la clase para enviar los correos
require ('../classes/mailClass.php');

//-----------Funcion de salida del script ---------------------

function adios($msg){
    echo $msg;
    die();
}

//---------Variables y objetos que utiliza el script-------

$fechaHora = date('Y-m-d H:i:s'); //La hora
$salida=$fechaHora."\r\n"; //string de salida del script

//Creo el objeto para hacer las operaciones con la Api

foreach ($rsExchange as $exchange){
    foreach ($rsExchangeEcoin[$exchange] as $ecoin){
        $oTrade = new Trade('', '', '', $exchange, $ecoin );

        //Recojo el precio
	try {
		$arrayTicker=$oTrade->getTicker();
	}
	catch (Exception $e) {
		$salida.= 'Error!'.$e->getMessage().'\n';
		adios($salida);
	}
        if ($arrayTicker){
            $ecoinPrecio=$arrayTicker['last'];
        } else {
            $salida.= 'Error! No se ha podido coger los precios publicos'."\r\n";
            adios($salida);
        }

        /*
        Variables recogidas:

        $ecoin
        $exchange
        $ecoinPrecio
         */

        //Por si aca compruebo todas las variables no sea que pase algo raro
        if ( !(isset($exchange)) or !(isset($ecoin)) or !($ecoinPrecio>0) ){
            $salida.= 'Error! Algun dato esta a 0 o peor. Ecoin:'.$ecoin." Exchange:".$exchange.' Precio:'.$ecoinPrecio."\r\n";
            adios($salida);
        }

        //Registro el precio en la base de datos
        $insertResult = $oTrade->insertEcoinPrecio($ecoinPrecio, $fechaHora);
        if (!$insertResult){
            $salida.='Se ha guardado el precio actual en la base de datos: '."\r\n";
            $salida.='Exchange: '.$exchange.' Ecoin: '.$ecoin.' Precio: '.$ecoinPrecio."\r\n";
        } else {
            $salida.= 'Error! No se ha podido guardar el precio actual en la base de datos'."\r\n";
            adios($salida);
        }
    } // foreach ($rsExchangeEcoin as $ecoin){
} // foreach ($rsExchange as $exchange){


echo $salida;
?>
