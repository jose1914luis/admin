<?php
/*
	Clase encargada de la visualización de los tipos de datos en Document Management
*/

	class TiposDatos {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase TiposDocumentos.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, nombre from tipos_datos order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar el tipo de dato.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
					
	}	
?>

