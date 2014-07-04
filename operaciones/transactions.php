<?
//------------------------------------------------------------

$salida='';
chdir(dirname(__FILE__)); //cambiamos al directorio donde se ejecuta el script
                          // esto se hace para que no de problemas con los requires relativos
//La hora
$fechaHora = date('Y-m-d H:i:s');

//Monto la clase para realizar las consultas de Bitstamp 
require('../classes/monedasClass.php');

//Monto la clase de Operaciones de Bitstamp
require ('../classes/bitstampClass.php');

//Creo el objeto para hacer las operaciones con la Api
$oBitstamp = new Bitstamp('ofR1pEOs7ppo4ZUklbdFrpYVnK37BN83', 'LKOtAV97cTmHnDLuHtbQtH4UNKxPzrwv', '89331');

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

    if ($arrayBalance){
    	$pastaDisp=$arrayBalance['usd_available'];
    	$btcDisp=$arrayBalance['btc_available'];
    	$fee=$arrayBalance['fee'];
    } else {
	$salida.= 'Error! No se ha podido coger los datos privados'."\r\n";
	echo $salida;
	die();
    }

$transactions=$oBitstamp->userTransactions();

print_r($transactions);

$salida.='Total dolares: '.$pastaDisp."\r\n";
$salida.='Total bitcoins: '.$btcDisp."\r\n";
$salida.='Precio Bitcoin: '.$btcPrecio."\r\n";

echo $salida;
?>
