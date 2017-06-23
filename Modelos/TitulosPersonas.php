<?php
/*
	Clase encargada de administrar la información relacionada a titulos y sus correspondientes personas asociadas
	en el CMQ
*/

	class TitulosPersonasTMP {		
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);
		}
		
		function selectAll() {
			if (!$this->conn) {
			  echo "Error de Conexión con enlaces superiores.\n";
			  return 0;
			}

			$queryStr =  'SELECT * FROM TIT_PERSONAS_TMP ORDER BY id_titulo';			
			
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
			
			$nroPersonas = sizeof($sol["persona"]);
			$i =0;
			
			$queryStr =  " insert into TIT_PERSONAS_TMP (
				ID_TITULO,		
				NOMBRE_PERSONA,
				NUMERO_IDENTIFICACION,
				TIPO_IDENTIFICACION
				) values (
					$1, $2, $3, $4
				)
			";				
			
			while($i<$nroPersonas) {
				if(trim($sol["persona"][$i])!="") {
					$params = array($sol["idPlaca"], $sol["persona"][$i], $sol["numeroIdentificacion"][$i], $sol["tipoIdentificacion"][$i]);
					$result = pg_query_params($this->conn, $queryStr, $params);
					if(pg_last_error($this->conn))
						echo "<table bgcolor='yellow' border = 0><tr><td>Error al almacenar personas de titulos</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";
				}
				$i++;
			}
		}

		function deletePersonas($idTitulo) {
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
			
			$queryStr =  "delete from TIT_PERSONAS_TMP where id_titulo=$1";			
			$params = array($idTitulo);
			$result = pg_query_params($this->conn, $queryStr, $params);
			if(pg_last_error($this->conn))
			echo "<table bgcolor='yellow' border = 0><tr><td>Error al eliminar personas existentes de titulo</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}		
	}	
?>

