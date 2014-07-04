<?
//------------------------------------------------------------

$salida=' ';
chdir(dirname(__FILE__)); //cambiamos al directorio donde se ejecuta el script
                          // esto se hace para que no de problemas con los requires relativos
//La hora
$fechaHora = date('Y-m-d H:i:s');


//Monto la clase de Operaciones de Bitstamp
require ('../classes/bitstampClass.php');

//Creo el objeto para hacer las operaciones con la Api
$oBitstamp = new Bitstamp('ofR1pEOs7ppo4ZUklbdFrpYVnK37BN83', 'LKOtAV97cTmHnDLuHtbQtH4UNKxPzrwv', '89331');


//---------Parametros pasados als script------
if (count($argv)===3){
	$btcCompra=intval($argv[1]);
	$btcNum=intval($argv[2]);
} else {
	$salida.= 'Error! faltan o sobran parametros. Uso: compra btcCompra btcNum'."\r\n";	
	echo $salida;
	die();
}

//Recojo el precio
$arrayTicker=$oBitstamp->ticker();
if ($arrayTicker){
    $btcPrecio=$arrayTicker['last'];
} else {
    $salida.= 'Error! No se ha podido coger los precios publicos'."\r\n";
    echo $salida;
    die();
}

//saco el Balance, los dolares disponibles, los bitcoins disponibles y la tasa que se embolsan estos cabrones
$arrayBalance=$oBitstamp->balance();

if ($btcNum=='all'){
    if ($arrayBalance){
    	$pastaDisp=$arrayBalance['usd_available'];
    	$btcDisp=$arrayBalance['btc_available'];
    	$fee=$arrayBalance['fee'];
	$btcNum=($pastaDisp/$btcCompra)-($pastaDisp/$btcCompra)*($fee/100);
	$btcNum=intval($btcNum*100)/100;
    } else {
	$salida.= 'Error! No se ha podido coger los datos privados'."\r\n";
	echo $salida;
	die();
    }
}


   if ( !(isset($btcNum)) or !(isset($btcCompra)) or !(isset($fee)) or $btcCompra>$btcPrecio*1.15 ) {

        $salida.='Alguna variable esta mal! O estas comprando un 15% mas caro del precio actual'."\r\n";
	$salida.='dolares: '.$btcNum.' precio bitcoin: '.$btcCompra."\r\n";
        echo $salida;
        die();

   } else { 

	echo 'Hay '.$pastaDisp.' disponibles. La comision es: '.$fee.'. vas a comprar '.$btcNum.' Bitcoins. Comprando a '.$btcCompra.'$ el Bitcoin'."\r\n";
	echo "Vas a continuar? (si): ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        if(trim($line) == 'si'){
		$arraySellBTC=$oBitstamp->buyBTC($btcNum, $btcCompra);
		if ($arraySellBTC['amount']){
			$salida.='Se ha metido una orden de compra de '.$arraySellBTC['amount'].' bitcoins a '.$arraySellBTC['price']. ' dolares'."\r\n";
		} else {
			$salida.='Algo raro ha pasado al comprar los bitcoins'."\r\n";
			print_r($arraySellBTC);
		}	 
  	} else {
                echo "Operacion cancelada!\n";
        }

  } 
$salida.='Total dolares: '.$pastaDisp."\r\n";
$salida.='Total bitcoins: '.$btcDisp."\r\n";
$salida.='Precio Bitcoin: '.$btcPrecio."\r\n";
echo $salida;
?>
