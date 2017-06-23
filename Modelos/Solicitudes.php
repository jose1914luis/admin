<?php
/*
	Clase encargada de administrar la información relacionada a solicitudes
	en el CMQ
*/

	class Solicitudes {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'SELECT * FROM SOLICITUDES ORDER BY ID';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Solicitudes.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function insertAll($sol) {
			$queryStr =  " insert into SOLICITUDES (
					PLACA,
					MODALIDAD,
					ESTADO_JURIDICO,
					GRUPO_TRABAJO,
					FORMULARIO,
					FECHA_RADICACION,
					AREA_SOLICITADA,
					AREA_DEFINITIVA,
					DIRECCION_CORRESPONDENCIA,
					TELEFONO_CONTACTO,
					OBSERVACION,
					JUSTIFICACION_EXTEMPORANEA,
					DOCUMENTO_SOPORTE 
				) values (
					$1, $2, $3, $4, $5, to_timestamp($6,'mm-dd-yyyy hh12:mi:ss AM'), $7, $8, $9, $10, $11, $12, $13
				)
			";	

			if($sol["areaSolicitada"]=="") $sol["areaSolicitada"] = null;	
			if($sol["areaDefinitiva"]=="") $sol["areaDefinitiva"] = null;
			
			$params = array($sol["codigoExpediente"], $sol["modalidad"], $sol["estadoJuridico"], $sol["grupoTrabajo"], $sol["numeroFormulario"], $sol["FECHA_RADICACION"], $sol["areaSolicitada"], $sol["areaDefinitiva"], utf8_encode($sol["DIRECCION_CORRESPONDENCIA"]), utf8_encode($sol["TELEFONO_CONTACTO"]), utf8_encode(substr($sol["OBSERVACION"],0,797))."...", utf8_encode($sol["JUSTIFICACION_EXTEMPORANEA"]), utf8_encode($sol["DOCUMENTO_SOPORTE"]));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			
			if (pg_last_error($this->conn)) {
				echo "<table bgcolor='yellow' border = 0><tr><td>Error al crear solicitud minera</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
			}							
		}

		function updateAll($sol) {
			$queryStr =  " 
				update SOLICITUDES set
					-- PLACA=$1,
					MODALIDAD=$2,
					ESTADO_JURIDICO=$3,
					GRUPO_TRABAJO=$4,
					FORMULARIO=$5,
					--FECHA_RADICACION=$6,
					AREA_SOLICITADA=$7,
					AREA_DEFINITIVA=$8,
					DIRECCION_CORRESPONDENCIA=$9,
					TELEFONO_CONTACTO=$10,
					OBSERVACION=$11,
					JUSTIFICACION_EXTEMPORANEA=$12,
					DOCUMENTO_SOPORTE=$13 
				where placa=$1	and to_timestamp($6,'mm-dd-yyyy hh12:mi:ss AM') <= now()		
			";	
			
			if($sol["areaSolicitada"]=="") $sol["areaSolicitada"] = null;	
			if($sol["areaDefinitiva"]=="") $sol["areaDefinitiva"] = null;
			
			$params = array($sol["codigoExpediente"], $sol["modalidad"], $sol["estadoJuridico"], $sol["grupoTrabajo"], $sol["numeroFormulario"], $sol["FECHA_RADICACION"], $sol["areaSolicitada"], $sol["areaDefinitiva"], utf8_encode($sol["DIRECCION_CORRESPONDENCIA"]), utf8_encode($sol["TELEFONO_CONTACTO"]), utf8_encode($sol["OBSERVACION"]), utf8_encode($sol["JUSTIFICACION_EXTEMPORANEA"]), utf8_encode($sol["DOCUMENTO_SOPORTE"]));
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			
			if (pg_last_error($this->conn)) {
				echo "<table bgcolor='yellow' border = 0><tr><td>Error al actualizar solicitud minera</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
			}							
		}
		
		
		function getIdPlaca($placa) {
			$queryStr =  'SELECT id FROM SOLICITUDES WHERE placa=$1';			
			
			$result = pg_query_params($this->conn, $queryStr,array($placa));
			if (!$result) {
				echo "Error al consultar Solicitudes.\n";
				return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["id"];
		}

		function getEstadoPlaca($placa) {
			$queryStr =  'SELECT estado_juridico FROM SOLICITUDES WHERE placa=$1';			
			
			$result = pg_query_params($this->conn, $queryStr,array($placa));
			if (!$result) {
				echo "Error al consultar estado juridico de solicitud.\n";
				return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["estado_juridico"];
		}
		
		function updateEstado($placa, $estado, $fechaOtorga, $fechaTermina, $observacion="") {
			$fechaOtorga = str_replace("\/","-",$fechaOtorga);
			$queryStr = "UPDATE SOLICITUDES SET ESTADO_JURIDICO=$1, FECHA_OTORGAMIENTO=to_timestamp($2,'dd-mm-yyyy'), FECHA_TERMINACION=to_timestamp($3,'dd-mm-yyyy'), OBSERVACION = COALESCE(OBSERVACION || $5, $5) WHERE placa=$4";
			$params = array($estado, $fechaOtorga, $fechaTermina, $placa, utf8_encode($observacion));						
			$result = pg_query_params($this->conn, $queryStr, $params);
			
			if (pg_last_error($this->conn)) {
				echo "<table bgcolor='yellow' border = 0><tr><td>Error al actualizar estado de solicitud</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";
				return 0;				
			}							
			return 1;
		}		

	}	
?>

