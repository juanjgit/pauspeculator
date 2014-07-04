<?php
/**
 * @author elporfirio.com
 * @copyright 2013
 *
 * #############################
 * Archivo de configuración
 * - Se requiere el archivo "configuracion.ini" en la misma carpeta
 */
 
 
abstract class configuracion {
	
	protected $datahost;
	protected function conectar($archivo = '../cfg/basedatos.cfg'){
		
		if (!$ajustes = parse_ini_file($archivo, true)) throw new exception ('No se puede abrir el archivo ' . $archivo . '.');
		$controlador = $ajustes["database"]["driver"]; //controlador (MySQL la mayoría de las veces)
		$servidor = $ajustes["database"]["host"]; //servidor como localhost o 127.0.0.1 usar este ultimo cuando el puerto sea diferente
		$puerto = $ajustes["database"]["port"]; //Puerto de la BD
		$basedatos = $ajustes["database"]["schema"]; //nombre de la base de datos

		try{
			return $this->datahost = new PDO (
                                    "mysql:host=$servidor;port=$puerto;dbname=$basedatos",
                                    $ajustes['database']['username'], //usuario
                                    $ajustes['database']['password'], //constrasena
                                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                                    );
			}
		catch(PDOException $e){
				echo "Error en la conexión: ".$e->getMessage();
			}
	}
}
?>
