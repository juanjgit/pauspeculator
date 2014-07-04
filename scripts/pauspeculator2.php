<?
//------------------------Super PauSpeculator Plus Megatrading!----------------------
// -----------------------------------------------
// Se debe pasar el archivo de configuracion con las variables del Exchange
// Como parametro. Tipo:
        /*
        $idUser=1;
        $ecoin='ltc';
        $exchange='btce';
        $key='dsfsdfsdfsd';
        $secret='sdfsdfsdf';
        $id_exchange='';

        //---------Parametros de configuracion del script------

        $mediaSmall=105;
        $mediaLarge=450;
        $stopLossSellPercent=0.2;       //% Porcentaje bajo el precio sobre el que vende
        $stopLossBuyPercent=0.2;        //% Porcentaje sobre el precio sobre el que compra
        $enviarMail=1;  
		$metodo='macd'; 				// valores posibles: macd,media
        */ 
//---------------Empezamos-------------------------------
$metodo='macd';
$slowPeriod=26;
$fastPeriod=12;
$signalPeriod=9;
$margenMacd=1;

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

// Parametro con el archivo de configuracion
if (count($argv)===2){
	$fileConfig=$argv[1];
} else {
    $salida.= 'Error! no se ha pasado el archivo de configuracion del exchange'."\r\n";
    adios($salida);
}

require($fileConfig);                   //Archivo con las configuraciones (tipo exchange_moneda.php)

//-------Comprobamos que el archivo de configuracion este bien montado

if ( !(isset($idUser)) or !($stopLossSellPercent > 0) or !($stopLossBuyPercent > 0) or !(isset($enviarMail)) or !($mediaSmall>0) or !($mediaLarge)>0){
            $salida.= 'Error! no se han configurado bien las medias en el archivo de configuracion'."\r\n";
            adios($salida);   
}

//---------Variables y objetos que utiliza el script-------

$mail= 0;                 // Solo al realizar compras y ventas envia el correo. 
$fechaHora = date('Y-m-d H:i:s'); //La hora
$salida='---'.$mailUser.'---'.$exchange.'---'.$ecoin.'---'."\r\n"; //string de salida del script
$salida.=$fechaHora."\r\n"; //string de salida del script
$decisionCompra = false;
$precioMediaSmall = null;
$precioMediaLarge = null;

//----------Cogemos todos los datos necesarios------------------------------
//--------------------------------------------------------------------------
//
//Creo el objeto para hacer las operaciones con la Api

$oTrade = new Trade($key, $secret, $id_exchange, $exchange, $ecoin );


//Recojo el precio
/*CAMBIAR!! EL PRECIO SE COGERA DE LA BASE DE DATOS (omprobar hora)
$arrayTicker=$oTrade->getTicker();
if ($arrayTicker){
    $ecoinPrecio=$arrayTicker['last'];
} else {
    $salida.= 'Error! No se ha podido coger los precios publicos'."\r\n";
    adios($salida);
}*/

$rsPrecio=$oTrade->getEcoinPrecio($fechaHora);
if ($rsPrecio) {
    //print_r($rsPrecio);
    foreach ($rsPrecio as $rowPrecio){ // CAMBIAR: Esto de hacer un foreach cuando se que solo hay un resultado no me gusta
        $ecoinPrecio=$rowPrecio['precio'];
    }
} else {
       $salida.= 'Error! No se ha podido coger el ultimo precio guardado: '."\r\n"; 
       adios($salida);
}

//saco el Balance, los dolares disponibles, los ecoins disponibles y la tasa que se embolsan estos cabrones
$arrayBalance=$oTrade->getBalance();

if ($arrayBalance){
    //print_r($arrayBalance);
    $usdDisp=intval($arrayBalance['usd_available']*100)/100;
    $ecoinDisp=$arrayBalance[$ecoin.'_available'];
    $fee=$arrayBalance['fee'];
} else {
    $salida.= 'Error! No se ha podido coger los datos privados'."\r\n";
    adios($salida);
}

//Recojo el precio maximo anterior y si esta en modo compra o venta
$rsModoTrade=$oTrade->getModoTrade($idUser);
if ($rsModoTrade) {
    //print_r($rsModoTrade);
    foreach ($rsModoTrade as $rowModoTrade){ // CAMBIAR: Esto de hacer un foreach cuando se que solo hay un resultado no me gusta
        $ecoinModo=$rowModoTrade['modo'];
    }
} else {
       $salida.= 'Error! No se ha podido coger el modo en el que trabajamos: '."\r\n"; 
       adios($salida);
}

// Recogida de datos históricos
if ($metodo == "media") {
	$rsPrecioMedia=$oTrade->getPrecioMedia($mediaSmall, $fechaHora);
	if ($rsPrecioMedia) {
		//print_r($rsPrecioMedia);
		foreach ($rsPrecioMedia as $rowPrecioMedia){ // CAMBIAR: Esto de hacer un foreach cuando se que solo hay un resultado no me gusta
			$precioMediaSmall=$rowPrecioMedia['precio_media'];
		}
	} else {
		   $salida.= 'Error! No se ha podido coger la media corta'."\r\n"; 
		   adios($salida);
	}

	$rsPrecioMedia=$oTrade->getPrecioMedia($mediaLarge, $fechaHora);
	if ($rsPrecioMedia) {
		//print_r($rsPrecioMedia);
		foreach ($rsPrecioMedia as $rowPrecioMedia){ // CAMBIAR: Esto de hacer un foreach cuando se que solo hay un resultado no me gusta
			$precioMediaLarge=$rowPrecioMedia['precio_media'];
		}
	} else {
		   $salida.= 'Error! No se ha podido coger la media larga'."\r\n"; 
		   adios($salida);
	}
	
		/*

	Variables recogidas:

	$ecoin
	$exchange
	$key
	$secret
	$id_exchange
	$stopLossSellPercent
	$stopLossBuyPercent
	$mail

	$ecoinPrecio
	$ecoinDisp
	$usdDisp
	$fee
	$ecoinModo=$arrayModoTrade['modo'];
	$precioMediaSmall=$rowPrecioMedia['precio_media']
	$precioMediaLarge=$rowPrecioMedia['precio_media']
	 */

	//Por si aca compruebo todas las variables no sea que pase algo raro
	if (!($precioMediaSmall>0) or !($precioMediaLarge>0) or !($ecoinPrecio>0) or !(isset($usdDisp)) or !(isset($ecoinDisp)) or !($fee>0) or ($ecoinModo!='compra' and $ecoinModo!='venta')){
		$salida.= 'Error! Algun dato esta a 0 o peor. Precio Ecoin: '. $ecoinPrecio.' Dolares disponibles: '.$usdDisp.' Ecoin disponibles: '. $ecoinDisp.' Comision: '.$fee.' Modo: '.$ecoinModo.' Media corta:'.$precioMediaSmall.' Media larga:'.$precioMediaLarge."\r\n";
		adios($salida);
	}
}
else if ($metodo == "macd") {
	$valores = $oTrade->getArrayPrecios($fechaHora,60,34);
	$res=trader_macd($valores,$fastPeriod,$slowPeriod,$signalPeriod);
	$histograma = array_shift($res[2]);
	
	//Por si aca compruebo todas las variables no sea que pase algo raro
	if (($histograma == null) or !($ecoinPrecio>0) or !(isset($usdDisp)) or !(isset($ecoinDisp)) or !($fee>0) or ($ecoinModo!='compra' and $ecoinModo!='venta')){
		$salida.= 'Error! Algun dato esta a 0 o peor. Precio Ecoin: '. $ecoinPrecio.' Dolares disponibles: '.$usdDisp.' Ecoin disponibles: '. $ecoinDisp.' Comision: '.$fee.' Modo: '.$ecoinModo.' Media corta:'.$precioMediaSmall.' Media larga:'.$precioMediaLarge."\r\n";
	}
}
else {
	salida("Método desconocido");
}

//---Realizamos las compra o Venta si ha dejado de subir o bajar por debajo del porcentaje configura
//-------------------------------------------------------------------------

//---Modo venta-------------------


switch ($metodo) {
	case "media":
		$decisionComprar = $precioMediaSmall>=$precioMediaLarge;
		break;
	case "macd":
		$decisionComprar = $histograma >= $margenMacd;
		break;
	default:
		adios("método desconocido");
}


if ($ecoinModo=='venta') { 
    
    if ($decisionCompra) {
        $salida.='El metodo '.$metodo.' no aconseja vender (Precio Actual='.$ecoinPrecio.' Modo: '.$ecoinModo.' Media corta: '.$precioMediaSmall.' Media larga: '.$precioMediaLarge.' Histograma: '.$histograma.')'."\r\n";
        
    } else { //el precioactual es inferior al precio maximo anterior
        $salida.='El metodo '.$medodo.' aconseja VENDER!! (Precio Actual='.$ecoinPrecio.' Modo: '.$ecoinModo.' Media corta: '.$precioMediaSmall.' Media larga: '.$precioMediaLarge.' Histograma: '.$histograma.' )'."\r\n";
        $mail=1; //Marcamos para notificar por mail
        $mailEcoinModo=$ecoinModo; //Marcamos variable para enviar por Email-Venta

            if ($ecoinDisp > 0) { //Y si tenemos algo de pasta.. 
                $ecoinPrecioOperacion=$ecoinPrecio-$ecoinPrecio*$stopLossSellPercent/100;
                $ecoinPrecioOperacion= intval($ecoinPrecioOperacion*100)/100; //truncamos los decimales a dos
                $ecoinNum=$ecoinDisp;
                $salida.='Se van a vender '.$ecoinNum.' '.$ecoin.' a '.$ecoinPrecioOperacion.' dolares '."\r\n";
                // /*
                $arraySellEcoins=$oTrade->sellEcoins($ecoinNum, $ecoinPrecioOperacion);//Vende 1% mas barato para que entre la venta 
                print_r($arraySellEcoins);
                
                if ($arraySellEcoins){
                    $salida.='Se puesto una orden de venta de '.$ecoinNum.' '.$ecoin.' a '.$ecoinPrecioOperacion. ' dolares'."\r\n";
                    $ecoinModo='compra'; //si se ha hecho la venta cambiamos el modo  a compra
                    $insertResult = $oTrade->updateModoTrade($idUser, $ecoinModo); //Metemos el modo compra en la base de datos
                    if (!$insertResult){
                        $salida.='Se ha cambiado el modo a compra en la base de datos'."\r\n";
                    } else {
                        $salida.= 'Error! Se ha cambiado el modo a compra en la base de datos'."\r\n";
                        adios($salida);
                    }
                } else {
                    $salida.='Algo raro ha pasado al vender los ecoins'."\r\n";
                    $salida.= print_r($arraySellEcoins);
                    adios($salida);
                } 
                //   */
            } else {
                    $salida.='No hay ecoins para vender'."\r\n";
            } 
    }
}
//--Fin del modo venta--------------------------
//
//---Modo compra--------------------------------=
else { 
     
    if (!$decisionCompra){
        $salida.='El metodo '.$metodo.' no aconseja comprar (Precio Actual='.$ecoinPrecio.' Modo: '.$ecoinModo.' Media corta: '.$precioMediaSmall.' Media larga: '.$precioMediaLarge.' Histograma: '.$histograma.')'."\r\n";
    } else { //el precioactual es superior al precio maximo anterior
        $salida.='El metodo '.$metodo.' aconseja COMPRAR!! (Precio Actual='.$ecoinPrecio.' Modo: '.$ecoinModo.' Media corta: '.$precioMediaSmall.' Media larga: '.$precioMediaLarge.' Histograma: '.$histograma.' )'."\r\n";
        // no metemos el precio en la base de datos
            $mail=1; //Marcamos para notificar por mail
            $mailEcoinModo=$ecoinModo; //Marcamos variable para enviar por Email-compra
        
            if ($usdDisp > 0) { //Y si tenemos algunos ecoins para vender.. 

                $ecoinPrecioOperacion= $ecoinPrecio+$ecoinPrecio*$stopLossBuyPercent/100;
                $ecoinPrecioOperacion= intval($ecoinPrecioOperacion*100)/100; //truncamos los decimales a dos
                $ecoinNum=($usdDisp/$ecoinPrecioOperacion)-($usdDisp/$ecoinPrecioOperacion)*($fee/100);
                $ecoinNum=intval($ecoinNum*100)/100; // para que sean solo dos decimales

                $salida.='Se van a comprar '.$ecoinNum.' '.$ecoin.' a '.$ecoinPrecioOperacion.' dolares '."\r\n";
                // /*
                $arrayBuyEcoins=$oTrade->buyEcoins($ecoinNum,$ecoinPrecioOperacion);//Vende 1% mas caro para que entre la venta 

                print_r($arrayBuyEcoins);

                if ($arrayBuyEcoins){
                    $salida.='Se puesto una orden de compra de '.$ecoinNum.' '.$ecoin.' a '.$ecoinPrecioOperacion. ' dolares'."\r\n";
                    $ecoinModo='venta';
                    $insertResult = $oTrade->updateModoTrade($idUser, $ecoinModo); //metemos el modo compra en la base de datos
                    if (!$insertResult){
                        $salida.='Se ha cambiado el modo a compra en la base de datos'."\r\n";
                    } else {
                        $salida.= 'Error! No se ha cambiado el modo a compra en la base de datos'."\r\n";
                        adios($salida);
                    }
                } else {
                    $salida.='Algo raro ha pasado al comprar los ecoins'."\r\n";
                    $salida.= print_r($arrayBuyEcoins);
                    adios($salida);
                } 
                //     */
            } else {
                    $salida.='No hay ecoins para comprar'."\r\n";
            } 
    }   
} 
//--Fin del modo compra---------------  

//---Tema correos----------------

if ($enviarMail==1 and $mail===1){
    //Envia un correo para decir que ha pasado

    $monitorAddress='llenamedecaca@gmail.com'; //direccion de copia
    $mailFrom = 'support@ecoining.com';
    $mailTo = $mailUser;
    $mailSubject = $mailEcoinModo. ' de '.$ecoin.' a '.$ecoinPrecio.' dolares';
    $mailBody =$salida;

    $oMail = new Mail($mailFrom, $mailTo, $monitorAddress, $mailSubject, $mailBody);
    $resultMail = $oMail->enviaMail();


    if ($resultMail) {
        $salida.='Se ha enviado un correo de informacion'."\r\n";
    } else {
        $salida.='No se ha podido enviar un correo de información al respecto'."\r\n";
    } 
}
echo $salida;
?>
