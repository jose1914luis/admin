<?php
/*
	Clase encargada de administrar la información relacionada a titulos
	en el CMQ
*/

	class Titulos {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'SELECT * FROM TITULOS ORDER BY ID';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Titulos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function insertAll($sol) {
			$queryStr =  " insert into TITULOS (	
					PLACA,			
					CODIGO_RMN,		
					CODIGO_ANTERIOR,		
					MODALIDAD,		
					ESTADO_JURIDICO,		
					GRUPO_TRABAJO,		
					FECHA_INSCRIPCION,	
					FECHA_CONTRATO,		
					FECHA_TERMINACION,	
					AREA_OTORGADA,		
					AREA_DEFINITIVA	,	
					DIRECCION_CORRESPONDENCIA,
					TELEFONO_CONTACTO	
				) values (
					$1, $2, $3, $4, $5, $6, to_timestamp($7,'yyyy-mm-dd hh24:mi:ss'), to_timestamp($8,'yyyy-mm-dd hh24:mi:ss'), to_timestamp($9,'mm-dd-yyyy hh12:mi:ss AM'), $10, $11, $12, $13
				)
			";			
			
			$params = array($sol["codigoExpediente"], $sol["codigoRMN"], $sol["codigoAnterior"], $sol["modalidad"], $sol["estadoJuridico"], $sol["grupoTrabajoDetalle"], $sol["fechaInscripcionRMN"], $sol["fechaContrato"], $sol["FECHA_TERMINACION"], $sol["areaSolicitada"], $sol["areaDefinitiva"], utf8_encode($sol["DIRECCION_CORRESPONDENCIA"]), utf8_encode($sol["TELEFONO_CONTACTO"]));


			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				echo "<table bgcolor='yellow' border = 0><tr><td>Error al crear titulo minero</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
			}	
		}

		function updateAll($sol) {
			if($sol["fechaInscripcionRMN"]=="") $sol["fechaInscripcionRMN"] = null;
			if($sol["fechaContrato"]=="")		$sol["fechaContrato"] = null; 
			if($sol["FECHA_TERMINACION"]=="")	$sol["FECHA_TERMINACION"] = null;			
			
			
			$queryStr =  " 
				UPDATE TITULOS SET 	
					-- PLACA,			
					CODIGO_RMN = $2,		
					CODIGO_ANTERIOR = $3,		
					MODALIDAD = $4,		
					ESTADO_JURIDICO = $5,		
					GRUPO_TRABAJO = $6,		
					FECHA_INSCRIPCION = $7,	
					FECHA_CONTRATO = $8,		
					FECHA_TERMINACION = $9,	
					AREA_OTORGADA = $10,		
					AREA_DEFINITIVA = $11,	
					DIRECCION_CORRESPONDENCIA = $12,
					TELEFONO_CONTACTO = $13
				WHERE placa = $1	
			";			
			
			$params = array($sol["codigoExpediente"], $sol["codigoRMN"], $sol["codigoAnterior"], $sol["modalidad"], $sol["estadoJuridico"], $sol["grupoTrabajoDetalle"], $sol["fechaInscripcionRMN"], $sol["fechaContrato"], $sol["FECHA_TERMINACION"], $sol["areaSolicitada"], $sol["areaDefinitiva"], utf8_encode($sol["DIRECCION_CORRESPONDENCIA"]), utf8_encode($sol["TELEFONO_CONTACTO"]));
			
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				echo "<table bgcolor='yellow' border = 0><tr><td>Error al actualizar titulo minero</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
			}	
		}
		
		function getIdPlaca($placa) {
			$queryStr =  'SELECT id FROM TITULOS WHERE placa=$1';			
			
			$result = pg_query_params($this->conn, $queryStr,array($placa));
			if (!$result) {
				echo "Error al consultar Titulos.\n";
				return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["id"];
		}	

		function getEstadoPlaca($placa) {
			$queryStr =  'SELECT estado_juridico FROM TITULOS WHERE placa=$1';			
			
			$result = pg_query_params($this->conn, $queryStr,array($placa));
			if (!$result) {
				echo "Error al consultar estado juridico de Titulo.\n";
				return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["estado_juridico"];
		}
		

		function updateEstado($placa, $estado, $fechaTermina, $observacion="") {
			$queryStr = "UPDATE TITULOS SET ESTADO_JURIDICO=$1, FECHA_TERMINACION=to_timestamp($2,'dd-mm-yyyy'), OBSERVACION = COALESCE(OBSERVACION || $4, $4)  WHERE placa=$3";
			$params = array($estado, $fechaTermina, $placa, utf8_encode($observacion));	

			$result = pg_query_params($this->conn, $queryStr, $params);
			
			if (pg_last_error($this->conn)) {
				echo "<table bgcolor='yellow' border = 0><tr><td>Error al actualizar estado de titulo minero</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";
				return 0;				
			}							
			return 1;
		}		
		
	}	
?>

