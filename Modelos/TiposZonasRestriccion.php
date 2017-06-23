<?php
/*
	Clase encargada de administrar la información relacionada a los tipos de zonas de restricción
	en el CMQ
*/

	class TiposZonasRestriccion {		
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);
		}
		
		function selectAll() {
			if (!$this->conn) {
			  echo "Error de Conexión con enlaces superiores.\n";
			  return 0;
			}

			$queryStr =  'SELECT * FROM TIPOS_ZONAS_RESTRICCION ORDER BY nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al seleccionar los tipos de zonas de restricci&oacute;n.\n";
			  return 0;
			}					
			
			$lista = pg_fetch_all($result);
			pg_free_result($result);
			
			return  $lista;
		}
	}	
?>

