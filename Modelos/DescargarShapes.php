<?php
/*
	Función que realiza las operaciones de descargar archivos de 
*/

	class DescargarShapes {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase DescargarShapes.\n";
				return 0;
			}
		}
		
		function getShapeListaExpedientesBog($lista, $session) {
			$queryStr 	=  "select getShapeListaExpedientesBog($1, $2) as result";			
			$params 	= array($lista, $session);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);	

			return $lista[0]["result"];				
		}
		
		function borrarCaracteres($string) {
		   $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
		   return preg_replace('/[^A-Za-z0-9\-\,]/', '', $string); // Removes special chars.
		}		
		
	}	
?>

