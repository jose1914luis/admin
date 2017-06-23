<?php
/*
	Clase encargada de desplegar los posibles tipos de formularios que se puedan presentar
*/

	class TiposFormularios {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase TiposFormularios.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, nombre from tipos_formularios order by id';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Tipos de Formularios.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
	}	
?>

