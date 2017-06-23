<?php
/*
	Clase encargada de administrar la información relacionada a solicitudes
	en el CMQ
*/

	class SolicitudesMpiosDeptos {		
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);
		}
		
		function selectAll() {
			if (!$this->conn) {
			  echo "Error de Conexión con enlaces superiores.\n";
			  return 0;
			}

			$queryStr =  'SELECT * FROM SOL_MPIOS_DEPTOS_TMP ORDER BY PLACA';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al seleccionar Municipios y Departamentos de la Placa.\n";
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
			
			$nroMpios = sizeof($sol["municipio"]);
			$i =0;
			
			$queryStr =  " insert into SOL_MPIOS_DEPTOS_TMP (
				ID_SOLICITUD,		
				DEPARTAMENTO,		
				MUNICIPIO,		
				PORCENTAJE_PARTICIPA
				) values (
					$1, $2, $3, $4
				)
			";			
			
			while($i<$nroMpios) {
				$params = array($sol["idPlaca"], utf8_encode($sol["departamento"][$i]), utf8_encode($sol["municipio"][$i]), utf8_encode($sol["porcentaje"][$i]));
				$result = pg_query_params($this->conn, $queryStr, $params);
				if(pg_last_error($this->conn))
					echo "<table bgcolor='yellow' border = 0><tr><td>$result</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";
				$i++;
			}
		}
		
		function deleteMpiosDeptos($idSolicitud) {
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
			
			$queryStr =  "delete from SOL_MPIOS_DEPTOS_TMP where id_solicitud=$1";			
			$params = array($idSolicitud);
			$result = pg_query_params($this->conn, $queryStr, $params);
			if(pg_last_error($this->conn))
			echo "<table bgcolor='yellow' border = 0><tr><td>Error al eliminar municipios y departamentos de solicitud</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}		
		
	}	
?>

