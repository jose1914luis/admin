<?php
/*
	Clase encargada de administrar la información relacionada a las anotaciones del Registro Minero Nacional
	en el CMQ
*/

	class AnotacionesRMN {	
		var $conn;
		var $criterios;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'SELECT * FROM ANOTACIONES_RMN ORDER BY ID';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar las Anotaciones de RMN.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function selectByPlaca($placa) {
			$queryStr =  'SELECT * FROM ANOTACIONES_RMN WHERE PLACA=$1 ORDER BY fecha_anotacion, fecha_ejecutoria, id';			
			
			$result = pg_query_params($this->conn, $queryStr, array($placa));
			if (!$result) {
			  echo "Error al consultar las Anotaciones de RMN por la placa $placa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		

		function selectByFilter($variables) {
			$codExpediente 	= utf8_encode(strtoupper($variables["codExpediente"])); 
			$anotacionDesde	= $variables["fechaAnotacionDesde"]; 
			$anotacionHasta	= $variables["fechaAnotacionHasta"]; 
			$tipoAnotacion 	= utf8_encode(strtoupper($variables["tipoAnotacion"])); 
			
			$where = "";
			$posVar =1;

			$criteriosConsulta = "";
		
			if(!empty($codExpediente)) {
				$where .= "placa like $".($posVar++)." and ";
				$parametros[0] = "%".strtoupper($codExpediente)."%";
				$criteriosConsulta .= "Placa contiene '".$codExpediente."' & ";
			}


			if(!empty($anotacionDesde)) {
				$where .= "fecha_anotacion > to_timestamp($".($posVar++).",'dd-mm-yyyy') and ";
				$parametros[1] = strtoupper($anotacionDesde);
				$criteriosConsulta .= " fecha de anotacion desde contiene '".$anotacionDesde."' & ";
			}

			
			if(!empty($anotacionHasta)) {
				$where .= "fecha_anotacion < to_timestamp($".($posVar++).",'dd-mm-yyyy') and ";
				$parametros[2] = strtoupper($anotacionHasta);
				$criteriosConsulta .= " fecha de anotacion hasta contiene '".$anotacionHasta."' & ";
			}

			
			if(!empty($tipoAnotacion)) {
				$where .= "tipo_anotacion like $".($posVar++)." and ";
				$parametros[3] = "%".strtoupper($tipoAnotacion)."%";
				$criteriosConsulta .= " tipo de anotacion contiene '".$tipoAnotacion."' & ";
			}

			if($criteriosConsulta=="")
				$criteriosConsulta = " NINGUNO ";

			if($where != "") 
				$where = " where ".$where." 1=1";			
				
			$queryStr =  "
				select 
					placa,
					fecha_anotacion,
					fecha_ejecutoria,
					tipo_anotacion,
					observacion
				from anotaciones_rmn $where		
				order by placa, fecha_anotacion,  fecha_ejecutoria, id				
			";	
					
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar las anotaciones por filtro. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			$this->setCriterios($criteriosConsulta);
			return  $lista;
		}
		
		function setCriterios($criterios) {
			$this->criterios = $criterios;
		}
		
		function getCriterios() {
			return $this->criterios;
		}
		
		function insertAll($anota) {
			$queryStr =  " insert into ANOTACIONES_RMN (	
					PLACA,			
					FECHA_ANOTACION,		
					FECHA_EJECUTORIA,		
					TIPO_ANOTACION,		
					OBSERVACION
				) values (
					$1, to_timestamp($2,'dd-mm-yyyy'), to_timestamp($3,'dd-mm-yyyy'), $4, $5
				)
			";			
			
			$params = array(trim(strtoupper($anota["codExpediente"])), trim($anota["fechaAnotacion"]), trim($anota["fechaEjecutoria"]), trim(utf8_encode($anota["tipoAnotacion"])), trim(utf8_encode($anota["observacionAnota"])));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				echo "<table bgcolor='yellow' border = 0><tr><td>Error al crear anotaci&oacute;n al titulo {$anota["codExpediente"]}</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";
				return 0;	// proceso con errores de almacenamiento
			}	
			return 1; 		// proceso almacenado correctamente
		}		
	}	
?>

