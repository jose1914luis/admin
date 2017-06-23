<?php
/*
	Clase encargada de administrar la información relacionada a solicitudes y sus correspondientes minerales asociados
	en el CMQ
*/

	class SolicitudesPersonasTMP {		
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);
		}
		
		function selectAll() {
			if (!$this->conn) {
			  echo "Error de Conexión con enlaces superiores.\n";
			  return 0;
			}

			$queryStr =  'SELECT * FROM SOL_PERSONAS_TMP ORDER BY id_solicitud';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al seleccionar las personas de la placa.\n";
			  return 0;
			}					
			
			$lista = pg_fetch_all($result);
			pg_free_result($result);
			
			return  $lista;
		}

		function insertAll($sol) {
			if (!$this->conn) {
			  echo "Error de Conexión con enlaces superiores.\n";
			  return 0;
			}
			$personas = explode("\n", $sol["persona"]);
			
			$nroPersonas = sizeof($personas);
			$i =0;
			
			$queryStr =  " insert into SOL_PERSONAS_TMP (
				ID_SOLICITUD,		
				NOMBRE_PERSONA		
				) values (
					$1, $2
				)
			";			
			
			while($i<$nroPersonas) {
				if(!empty($personas[$i]) && trim($personas[$i])!="") {
					$params = array($sol["idPlaca"], utf8_encode(trim($personas[$i])));
					$result = pg_query_params($this->conn, $queryStr, $params);
					if(pg_last_error($this->conn))
						echo "<table bgcolor='yellow' border = 0><tr><td>Error al almacenar personas de solicitudes</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";
				}
				$i++;
			}
		}
		
		function deletePersonas($idSolicitud) {
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
			
			$queryStr =  "delete from SOL_PERSONAS_TMP where id_solicitud=$1";			
			$params = array($idSolicitud);
			$result = pg_query_params($this->conn, $queryStr, $params);
			if(pg_last_error($this->conn))
			echo "<table bgcolor='yellow' border = 0><tr><td>Error al eliminar personas existentes de solicitud</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}		
		
	}	
?>

