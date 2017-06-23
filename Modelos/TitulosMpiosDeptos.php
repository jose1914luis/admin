<?php
/*
	Clase encargada de administrar la información relacionada a titulos
	en el CMQ
*/

	class TitulosMpiosDeptos {		
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);
		}
		
		function selectAll() {
			if (!$this->conn) {
			  echo "Error de Conexión con enlaces superiores.\n";
			  return 0;
			}

			$queryStr =  'SELECT * FROM TIT_MPIOS_DEPTOS_TMP ORDER BY PLACA';			
			
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
			
			$queryStr =  " insert into TIT_MPIOS_DEPTOS_TMP (
				ID_TITULO,		
				DEPARTAMENTO,		
				MUNICIPIO,		
				PORCENTAJE_PARTICIPA
				) values (
					$1, $2, $3, $4
				)
			";			
			
			while($i<$nroMpios) {
				$params = array($sol["idPlaca"], $sol["departamento"][$i], $sol["municipio"][$i], $sol["porcentaje"][$i]);
				$result = pg_query_params($this->conn, $queryStr, $params);
				if(pg_last_error($this->conn))
					echo "<table bgcolor='yellow' border = 0><tr><td>Error al insertar municipios en titulo minero</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";
				$i++;
			}
		}
		
		function deleteMpiosDeptos($idTitulo) {
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
			
			$queryStr =  "delete from TIT_MPIOS_DEPTOS_TMP where id_titulo=$1";			
			$params = array($idTitulo);
			$result = pg_query_params($this->conn, $queryStr, $params);
			if(pg_last_error($this->conn))
			echo "<table bgcolor='yellow' border = 0><tr><td>Error al eliminar municipios y departamentos del titulo</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}
		
	}	
?>

