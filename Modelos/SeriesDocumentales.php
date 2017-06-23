<?php
/*
	Clase encargada de administrar la información relacionada con las series
	documentales en el CMQ, para el caso serían los expedientes
*/

	class SeriesDocumentales {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase SeriesDocumentales.\n";
				return 0;
			}
		}
	
	
	/*	function selectSerieByIdEmpresa($idEmpresa) {
			$queryStr =  '
				select 
					sd.id,
					sd.nombre
				from series_documentales sd 
				where sd.id_empresa = $1 and sd.fecha_terminacion is null order by sd.nombre
			';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			if (!$result) {
			  echo "Error al generar lista de Series Documentales por Empresa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
	*/	
		function selectAll() {
			$queryStr =  '
				select 
					sd.id,
					sd.nombre
				from series_documentales sd 
				where sd.fecha_terminacion is null order by sd.nombre
			';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al generar lista de Series Documentales.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		

		function selectByEmpresa($idEmpresa) {
			$queryStr =  '
				select 
					e.nombre as empresa,
					sd.id,
					sd.nombre as serie,
					sd.nro_subseries,
					sd.fecha_creacion
				from empresas e inner join series_documentales sd on (e.id=sd.id_empresa)
				where e.id = $1 and sd.fecha_terminacion is null order by sd.fecha_creacion
			';			
			
			$result = pg_query_params($this->conn, $queryStr, array($idEmpresa));
			if (!$result) {
			  echo "Error al consultar Serie Documental.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function existeSerie($nombreSerie) {
			$queryStr =  '
				select count(sd.id) as result
				from series_documentales sd 
				where sd.nombre = $1  
			';			
			
			$result = pg_query_params($this->conn, $queryStr, array($nombreSerie));
			if (!$result) {
			  echo "Error al consultar Serie Documental.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista["0"]["result"];
		}
		
		function insertAll($serieDocumental) {
			$queryStr		= "select series_documentales_insert($1) as result";				
			$params 		= array(utf8_encode($serieDocumental["nombre"]));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista["0"]["result"];			
		}		
	
	}	
?>

