<?php
/*
	Clase encargada de la administración y gestión de las URL que abren popups
*/

	class ControlPopups {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase ControlPopups.\n";
				return 0;
			}
		}				
		
		function setControlPopup($placa, $clasificacion) {
			$queryStr =  "select control_popup_insert($1, $2) as result";						
			$params = array($placa, $clasificacion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}	

		function getControlPopup($codigo) {
			$queryStr =  "select * from control_popups where codigo_acceso = $1 limit 1";						
			$params = array($codigo);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);			
			return  $lista[0];
		}
	}	
?>

