<?php
/*
	Clase encargada de administrar la información relacionada a solicitudes y sus correspondientes minerales asociados
	en el CMQ
*/

	class SolicitudesMineralesTMP {		
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);
		}
		
		function selectAll() {
			if (!$this->conn) {
			  echo "Error de Conexión con enlaces superiores.\n";
			  return 0;
			}

			$queryStr =  'SELECT * FROM SOL_MINERALES_TMP ORDER BY id_solicitud';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al seleccionar los minerales de la placa.\n";
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
			
			$nroMinerals = sizeof($sol["mineral"]);
			$i =0;
			
			$queryStr =  " insert into SOL_MINERALES_TMP (
				ID_SOLICITUD,		
				MINERAL		
				) values (
					$1, $2
				)
			";			
			
			while($i<$nroMinerals) {
				if($sol["mineral"][$i]!="") {
					$params = array($sol["idPlaca"], utf8_encode($sol["mineral"][$i]));
					$result = pg_query_params($this->conn, $queryStr, $params);
					if(pg_last_error($this->conn))
						echo "<table bgcolor='yellow' border = 0><tr><td>Error al ingresar mineral de solicitud</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";
				}
				$i++;
			}
		}
		
		function deleteMinerals($idSolicitud) {
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
			
			$queryStr =  "delete from SOL_MINERALES_TMP where id_solicitud=$1";			
			$params = array($idSolicitud);
			$result = pg_query_params($this->conn, $queryStr, $params);
			if(pg_last_error($this->conn))
			echo "<table bgcolor='yellow' border = 0><tr><td>Error al eliminar minerales existentes de solicitud</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}		
	}	
?>

