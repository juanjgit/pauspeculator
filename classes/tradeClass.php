<?php

/**
 * Description of tradeClass
 * La caña!
 * 
 * @author PauSoluciones
 */
require("bitstampClass.php");
require("btceClass.php");
require("config.php");

class trade {
        private $key;
        private $secret;
        private $client_id;
        private $exchange;
        private $ecoin;
        private $oBitstamp;


        
        public function __construct($key, $secret, $client_id, $exchange, $ecoin)
        {
                if (isset($secret) && isset($key) && isset($client_id) && isset($exchange) && isset($ecoin) )
                {
                        $this->key = $key;
                        $this->secret = $secret;
                        $this->client_id = $client_id;
                        $this->exchange = $exchange;
                        $this->ecoin = $ecoin;
                        if ( $exchange == 'bitstamp' ) {
                            $this->oBitstamp=new Bitstamp($key, $secret, $client_id);
                            
                        } else if ( $exchange == 'btce' ) {
                            $this->oBtce=new Btce($key, $secret);
                            
                        } else {
                            die ("El Exchange debe ser bitstamp o btce. Pero es ".$exchange."\r\n");
                        }
                        
                } else
                        die("NO KEY/ SECRET/ CLIENT ID/ EXCHANGE/ ECOIN"."\r\n");
        }
        
        public function getTicker() {
            
            $exchange = $this->exchange;
            $ecoin=$this->ecoin;
            
            if ($exchange=='bitstamp') {

                $ticker =  $this->oBitstamp->bitstamp_query_public('https://www.bitstamp.net/api/ticker/');
                $return_ticker['last'] = $ticker['last'];
                return $return_ticker;

            } else if ($exchange=='btce') {

                $ticker =  $this->oBtce->btce_query_public('https://btc-e.com/api/2/'.$ecoin.'_usd/ticker');
                $return_ticker['last'] = $ticker['ticker']['last'];
                return $return_ticker;

            } else {
                die ("El Exchange debe ser Bitstamp o Btce"."\r\n");
            }   

            
        }
        
        public function getBalance() {

            $exchange = $this->exchange;
            $ecoin=$this->ecoin;
            
            if ($exchange=='bitstamp') {
		try {
			$balance = $this->oBitstamp->bitstamp_query('balance');
		}
		catch (exception $e) {
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			return null;
		}
                $return_balance['usd_available'] = $balance['usd_available'];
                $return_balance[$ecoin.'_available'] = $balance[$ecoin.'_available'];
                $return_balance['fee'] = $balance['fee'];
                return $return_balance; 

            } else if ($exchange=='btce') {
		try {
			$balance = $this->oBtce->btce_query('getInfo');
		}
		catch (Exception $e) {
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
			return null;
			
		}
                $return_balance['usd_available'] = $balance['return']['funds']['usd'];
                $return_balance[$ecoin.'_available'] = $balance['return']['funds'][$ecoin];
                $return_balance['fee'] = 0.02;
                return $return_balance; 
                
            } else {
                die ("El Exchange debe ser Bitstamp o Btce"."\r\n");
            }              
        }
        
        public function buyEcoins($ecoinNum, $ecoinPrecioOperacion) {

            $exchange = $this->exchange;
            $ecoin=$this->ecoin;
            
            if ($exchange=='bitstamp') {
                
                $salidaBuy=$this->oBitstamp->bitstamp_query('buy', array('amount' => $ecoinNum, 'price' => $ecoinPrecioOperacion));
                
            } else if ($exchange=='btce') {

                $salidaBuy=$this->oBtce->btce_query('Trade', array('pair' => $ecoin.'_usd', 'type' => 'buy', 'amount' => $ecoinNum, 'rate' => $ecoinPrecioOperacion));
                //$salidaSell=$ecoin.'_usd numero:'.$ecoinNum.' precio:'.$ecoinPrecioOperacion;
                
                return $salidaBuy['success'];
            } else {
                die ("El Exchange debe ser Bitstamp o Btce"."\r\n");
            }              
        }
        
        public function sellEcoins($ecoinNum, $ecoinPrecioOperacion) {

            $exchange = $this->exchange;
            $ecoin=$this->ecoin;
            
            if ($exchange=='bitstamp') {
                
                $salidaSell=$this->oBitstamp->bitstamp_query('sell', array('amount' => $ecoinNum, 'price' => $ecoinPrecioOperacion));
                return $salidaSell;
                
            } else if ($exchange=='btce') {

                $salidaSell=$this->oBtce->btce_query('Trade', array('pair' => $ecoin.'_usd', 'type' => 'sell', 'amount' => $ecoinNum, 'rate' => $ecoinPrecioOperacion));
                
                return $salidaSell['success'];

            } else {
                die ("El Exchange debe ser Bitstamp o Btce"."\r\n");
            }   
            
        }        

//---------------Funciones de Consultas en Base de datos---------------------
//---------------------------------------------------------------------------
        
        public function getModoTrade($idUser) {
            
            $exchange = $this->exchange;
            $ecoin=$this->ecoin;
            
            $consulta = 'SELECT user_modo_trade.modo AS modo
                                    FROM  user_modo_trade
                                        WHERE user_modo_trade.id_user="'.$idUser.'" AND
                                              user_modo_trade.exchange="'.$exchange.'" AND
                                              user_modo_trade.ecoin="'.$ecoin.'"';
            $oConectar = new conectorDB; //instanciamos conector
            $this->precioMax= $oConectar->consultarBD($consulta);
            
            return $this->precioMax;

        } 
        
        public function getPrecioMedia($minutosMedia, $fechaHora) {
            
            $exchange = $this->exchange;
            $ecoin=$this->ecoin;
            
           $consulta = 'SELECT AVG(coin_exchange.precio) AS precio_media FROM coin_exchange
                                    WHERE coin_exchange.exchange="'.$exchange.'" AND
                                          coin_exchange.ecoin="'.$ecoin.'" AND
                                            coin_exchange.fecha_hora  
                                                BETWEEN DATE_ADD("' . $fechaHora . '", INTERVAL -' . $minutosMedia . ' MINUTE) AND "' . $fechaHora . '"';
            $oConectar = new conectorDB; //instanciamos conector
            $this->precioMax= $oConectar->consultarBD($consulta);
            
            return $this->precioMax;

        } 
        
        public function insertEcoinPrecio($ecoinPrecio, $fechaHora) {
            
            $exchange = $this->exchange;
            $ecoin=$this->ecoin;
            
            $consulta = 'INSERT INTO coin_exchange ( coin_exchange.fecha_hora,
                                                     coin_exchange.exchange,
                                                     coin_exchange.ecoin,
                                                     coin_exchange.precio) 
                                                        VALUES ("'.$fechaHora.'",
                                                                "'.$exchange.'",
                                                                "'.$ecoin.'",
                                                                '.$ecoinPrecio.')';
            $oConectar = new conectorDB; //instanciamos conector
            $this->salida= $oConectar->consultarBD($consulta);

            return $this->salida;

        }
        
        public function getEcoinPrecio($fechaHora) {
            
            $exchange = $this->exchange;
            $ecoin=$this->ecoin;
            
            $consulta = 'SELECT AVG(coin_exchange.precio) AS precio
                                FROM coin_exchange
                                    WHERE  coin_exchange.exchange="'.$exchange.'" AND
                                           coin_exchange.ecoin="'.$ecoin.'" AND
                                           coin_exchange.fecha_hora 
                                            BETWEEN DATE_ADD("'.$fechaHora.'", INTERVAL -1 MINUTE) AND "'.$fechaHora.'"';
            $oConectar = new conectorDB; //instanciamos conector
            $this->salida= $oConectar->consultarBD($consulta);

            return $this->salida;

        }
	
	// Devuelve un array de valores dado:
	// 	fechainicial: fecha del ultimo valor
	//	$vela: tamano de la vela en minutos
	//	$numero: numero de valores
	public function getArrayPrecios($fecha,$vela,$numero)
	{
		$exchange = $this->exchange;
		$ecoin = $this->ecoin;
		$precioAnterior=null;
		
		$oConectar = new conectorDB; 
		$fechaSQLfinal = '"'.$fecha.'"';
		
		for ( $i=1; $i <= $numero; $i++) {
			$fechaSQLinicial = 'DATE_ADD("'.$fecha.'", INTERVAL -'.($vela*$i).' MINUTE)';

			$consulta = 'SELECT AVG(coin_exchange.precio) AS precio
                                FROM coin_exchange
                                WHERE  coin_exchange.exchange="'.$exchange.'" AND
                                       coin_exchange.ecoin="'.$ecoin.'" AND
                                       coin_exchange.fecha_hora
                                BETWEEN '.$fechaSQLinicial.' AND '.$fechaSQLfinal;
			$resultado[$i-1]=$oConectar->consultarBD($consulta)[0]['precio'];
			if ($resultado[$i-1] == null) {
				// En el caso de que el valor mas reciente sea nulo, abortamos
				if ( $i == 1 ) {
					return false;
				}
				$resultado[$i-1] = $precioAnterior;
			}
			$precioAnterior = $resultado[$i-1]; 
			$fechaSQLfinal = $fechaSQLinicial;
		}
		return $resultado;
	}  
        
        
        public function updateModoTrade($idUser, $ecoinModo) {
            
            $exchange = $this->exchange;
            $ecoin=$this->ecoin;
            
            $consulta = 'UPDATE user_modo_trade SET user_modo_trade.modo = "'.$ecoinModo.'"
                                                            WHERE user_modo_trade.id_user = "'.$idUser.'" AND 
                                                                  user_modo_trade.exchange = "'.$exchange.'" AND 
                                                                  user_modo_trade.ecoin = "'.$ecoin.'"';               
                                                                
            $oConectar = new conectorDB; //instanciamos conector
            $this->salida= $oConectar->consultarBD($consulta);

            return $this->salida;

        } 

public	function LInterpolation($arr,$step=1,$returnval) {
    global $smarty;
     
    if(!is_array($arr)){
        $smarty->assign('error', array('str' => '<b>LInterpolation:</b> expects an array'));
        return false;
    }
     
    $narr = array();
    foreach($arr AS $key => $value) {
        $nval = $value;
        $nkey = $key;
        $narr[(string) $nkey] = (string) $nval;
    }
    $arr = $narr;
     
    $array_keys = array_keys($arr);
    $first_key  = current($array_keys);
    $last_key   = end($array_keys);
     
    $arr_calc = array();
    foreach($arr AS $key => $value){
        if(is_numeric($key) == false || is_numeric($value) == false){
            $smarty->assign('error', array('str' => '<b>LInterpolation:</b> array keys & values must be numeric'));
            return false;
        }
        if($key !== $first_key){
            array_push($arr_calc,((($value-$arr["$before_key"]) / ($key-$before_key)) * $step));
        }
        $before_key = $key;
    }
     
   $x = -1;
   $arr_new = array();
   for($i = $first_key; (string) $i <= (string) $last_key; $i=$i+$step){
      if(!isset($arr["$i"])){
         $arr_new["$i"] = ($arr_new["$before_key"] + $arr_calc[$x]);
      } else {
         $arr_new["$i"] = $arr["$i"];
         $x++;
      }
      if (is_numeric($returnval) && $arr_new["$i"] >= $returnval){
        if($arr_new["$before_key"] == '' && (float) $returnval !== (float) $arr_new["$i"]){
            $smarty->assign('error', array('str' => 'outofbounds', 'vars' => array($returnval, $i, $arr_new["$i"])));
            return false;
        }
        $diff_key = $i - $before_key;
        $diff_val = $arr_new["$i"] - $arr_new["$before_key"];
        $growth = $returnval - $arr_new["$before_key"];
        $growthperunit = $diff_key / $diff_val;
        $ttlgrowth = $growth * $growthperunit;
        $est = $before_key + $ttlgrowth;
        return $est;
      }
      $before_key = $i;
   }
    
   if(is_numeric($returnval)){
        return $before_key;
   }
   return $arr_new;
}
}

//------------Funcion conectora de Base de Datos----------------------------------
//

class conectorDB extends configuracion //clase principal de conexion y consultas
{
	private $conexion;
		
	public function __construct(){
		$this->conexion = parent::conectar(); //creo una variable con la conexión
		return $this->conexion;										
	}
	
	public function consultarBD($consulta){  //funcion principal, ejecuta todas las consultas
		$resultado = false;
		
		if($statement = $this->conexion->prepare($consulta)){  //prepara la consulta
			try {
				if (!$statement->execute()) { //si no se ejecuta la consulta...
					print_r($statement->errorInfo()); //imprimir errores
				}
				$resultado = $statement->fetchAll(PDO::FETCH_ASSOC); //si es una consulta que devuelve valores los guarda en un arreglo.
				$statement->closeCursor();
			}
			catch(PDOException $e){
				echo "Error de ejecución: \n";
				print_r($e->getMessage());
			}	
		}
		return $resultado;
		$this->conexion = null; //cerramos la conexión
	} /// Termina funcion consultarBD
}/// Termina clase conectorDB

?>
