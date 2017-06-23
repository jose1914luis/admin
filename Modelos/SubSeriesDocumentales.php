<?php
/*
	Clase encargada de administrar la información relacionada con las subseries
	documentales en el CMQ, para el caso serían los formularios de los expedientes
*/

	class SubSeriesDocumentales {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase SubSeriesDocumentales.\n";
				return 0;
			}
		}
		
		function selectSubSerieByIdSerie($idSerie) {
			$queryStr =  '
				select id as id_subserie, nombre 
				from subseries_documentales 
					where id_serie_documental=$1 order by id			
			';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idSerie));
			if (!$result) {
			  echo "Error al consultar listado de SubSeries Documentales por Serie.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}	
		
		function selectIndicesByIdSubSerie($idSubSerie) {
			$queryStr =  '
				select
					sd.id as id_subserie,
					sd.nombre as nombre_subserie,
					sd.nro_indices,
					i.id as id_indice,
					i.posicion,
					i.nombre as nombre_indice,
					td.nombre as tipo_dato,
					li.lista_parametros,
					li.es_multiple_seleccion
				from 	subseries_documentales sd
					inner join indices i on (sd.id=i.id_subserie)
					inner join tipos_datos td on (i.id_tipo_dato=td.id)
					left join listas_indices li on (i.id=li.id_indice)
				where sd.id=$1
				order by i.posicion			
			';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idSubSerie));
			if (!$result) {
			  echo "Error al consultar listado de Indices.";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}	
		
		function existeSubSerie($subSerieDocumental) {
			$queryStr =  '
				select count(ssd.id) as result 
				from series_documentales sd 
					inner join subseries_documentales ssd on (sd.id=ssd.id_serie_documental)
				where ssd.id_serie_documental=$1 and ssd.nombre=$2  
			';			
			
			$result = pg_query_params($this->conn, $queryStr, array($subSerieDocumental["selSerie"], utf8_encode($subSerieDocumental["txtNombreSubserie"])));
			if (!$result) {
			  echo "Error al consultar SubSerie Documental.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista["0"]["result"];
		}
		
		function insertAll($subSerieDocumental) {
			$queryStr		= "select subseries_documentales_insert($1, $2, $3) as result";				
			$params 		= array($subSerieDocumental["selSerie"], utf8_encode($subSerieDocumental["txtNombreSubserie"]), utf8_encode($subSerieDocumental["indices"]));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista["0"]["result"];			
		}		
	
	}	
?>

