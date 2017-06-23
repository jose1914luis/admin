<?php
/*
	Clase encargada de administrar la información relacionada con los documentos
	en el instante en que son indexados, es decir, el almacenamiento de los datos asociados
	a los indices y a las imagenes cargadas para los mismos.
*/

	class DocumentosSubseries {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase DocumentosSubseries.\n";
				return 0;
			}
		}
		
		function getIdDocumentoSubserie() {
			$queryStr =  "select nextval('doc_subseries_seq') as result";
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al generar consecutivo de documentos_subseries.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["result"];
		}	
		
		function insertAll($documentoSubserie) {

			$idDocSubserie 	= $this->getIdDocumentoSubserie();
			$queryStr		= "select docsubseries_insert($1, $2, $3, $4, $5, $6) as result";				
			$params 		= array($idDocSubserie, $documentoSubserie["idSubSerie"], utf8_encode($documentoSubserie["codigoExpediente"]),utf8_encode($documentoSubserie["folderImg"].$documentoSubserie["nameImgFile"]), $documentoSubserie["tipoFormulario"], $documentoSubserie["idEmpresa"]);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);			
		
			if($lista[0]["result"]=="OK") {
				// inserción de los indices digitalizados			
				foreach($documentoSubserie as $indice=>$valor) {
					$campos = explode("_", $indice);
					if($campos[0]=="indice") {
						$queryStr = "select docsubseries_indices_insert($1, $2, $3) as result";
						if(!is_array($valor)) $valorCampo = $valor;
						else $valorCampo = implode(",",$valor);
						$params = array($idDocSubserie, $campos[1], utf8_encode($valorCampo));
						$result = pg_query_params($this->conn, $queryStr, $params);
						$lista = pg_fetch_all($result);			
						if($lista[0]["result"]!="OK")
							return $lista[0]["result"];						
					}
				}
				
				if($documentoSubserie["tipoFormulario"]==2) {
					// Si el tipo de formulario es un documento de requerimiento
					$queryStr = "select documentos_requieren_insert($1) as result";
					$result = pg_query_params($this->conn, $queryStr, array($idDocSubserie));
					$lista = pg_fetch_all($result);			
					if($lista[0]["result"]!="OK")
						return $lista[0]["result"];											
				}
			
				// inserción de documentos asociados que resuelven uno o varios requerimientos
				if(!empty($documentoSubserie["docQueRequiere"])) {
					foreach($documentoSubserie["docQueRequiere"] as $indice=>$idDocRequiere) { 
							$queryStr = "select documentos_resuelven_insert($1, $2) as result";
							$params = array($idDocRequiere, $idDocSubserie);
							$result = pg_query_params($this->conn, $queryStr, $params);
							$lista = pg_fetch_all($result);			
							if($lista[0]["result"]!="OK")
								return $lista[0]["result"];						
					}
				}				
			}

			return $lista[0]["result"];						
		}

		function insertDocRequieren($idRequiere) {
			$queryStr 	=  "select documentos_requieren_insert($1)";			
			$params 	= array($idRequiere);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);	

			return $lista[0]["result"];				
		}
		
		function selectDocumentosRequieren($placa) {
			$queryStr =  "
				select 
					ds.id id_documento, 
					ds.expediente, 
					ds.id_tipo_formulario, 
					sd.nombre as formulario,
					docsubseries_indices_select_data(ds.id, id.id,tc.nombre) as fecha_inicia_termino
				from documentos_subseries ds inner join subseries_documentales sd on (ds.id_subserie=sd.id)
					inner join indices id on (sd.id=id.id_subserie)
					inner join tipos_datos tc on (id.id_tipo_dato=tc.id)
					left join documentos_resuelven dr on (ds.id=id_doc_requiere)
				where 	ds.expediente=$1 and
					ds.id_tipo_formulario=2 and
					tc.nombre='FECHA INICIA TERMINO'  and 	
					dr.id_doc_resuelve is null
			";
			
			$result = pg_query_params($this->conn, $queryStr, array($placa));
			if (!$result) {
			  echo "Error al generar consecutivo de documentos_subseries.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);	
			
			pg_free_result($result);

			return  $lista;
		}			
	}	
?>

