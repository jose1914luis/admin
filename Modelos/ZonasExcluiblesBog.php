<?php
/*
	Clase encargada de administrar la información relacionada con las zonas excluibles de minería
	del CMQ
*/

	class ZonasExcluiblesBog {	
		var $conn;
		var $idZonaRestriccion;
		var $origenDefault	= 21897;  	// Origen default Gauss Bogotá
		var $wgs84 			= 4326; 	// Geográfico WGS84
		var $datumBogota 	= 4218; 	// Geográfico Datum Bogotá
		var $sistemasOrigen = array(
			"OESTE"  		=> 21896,		
			"BOGOTA" 		=> 21897,
			"ESTE"   		=> 21898,
			"ESTE-ESTE" 	=> 21899,
			""				=> 21897
		);	
		
		function __construct($coordenadas="") {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
			$this->Coordenadas = $coordenadas;	
			$this->origen = "BOGOTA";
		}
		
		function asignarSistemaOrigen($sOrigen) {
			return $this->sistemasOrigen[strtoupper($sOrigen)];
		}
		
		function procesarCoordenadas($coordenadas) {
			$coords = trim ($coordenadas);			
			$coords = str_ireplace(" ","",$coords);			
			$coords = str_ireplace(","," ",$coords);
			$coords = str_ireplace("\n",",",$coords);
			$coords = str_ireplace("\r","",$coords);
			$coords = str_ireplace(",E,","E",$coords);
			$coords = str_ireplace(",A,","A",$coords);
			$coords = str_ireplace(",,",",",$coords);

			$formatoAreaPoly = "MULTIPOLYGON(";
			$areasCoord = explode("A", $coords);
			$i = 0;
			$coma="";
			while($i<sizeof($areasCoord)) {
				$areas = explode("E",$areasCoord[$i]);
				$pnt1 = explode(" ",$areas[0],2);
				$pnt2 = explode(",",$pnt1[1],2);
				
				// procesamiento del area principal:
				$formatoAreaPoly .= "$coma((".$areas[0].",".$pnt1[0]." ".$pnt2[0].")";
				
				$j=1;
				// procesamiento de las exclusiones:
				while($j<sizeof($areas)) {
					$pnt1 = explode(" ",$areas[$j],2);
					$pnt2 = explode(",",$pnt1[1],2);				
					$formatoAreaPoly .= ",(".$areas[$j].",".$pnt1[0]." ".$pnt2[0].")";
					$j++;
				}
				
				$formatoAreaPoly .= ")";
				$coma=",";
				$i++;
			}
			$formatoAreaPoly .= ")";
			$formatoAreaPoly = str_ireplace(", )",")",$formatoAreaPoly);

			return $formatoAreaPoly;
		}
		
		function selectAll() {
			$queryStr =  'SELECT * FROM zonas_excluibles_bog ORDER BY fecha_ini_vigencia';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar zonas de restricci&0acute;n en origen Bogot&aacute;.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function existeZonaRestriccion($id) {
			$queryStr =  'SELECT count(1) as existe FROM zonas_excluibles_bog WHERE id=$1 limit 1';			
			$params = array($id);	
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error al consultar existencia de la zona de restriccion con id $id.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["existe"];
		}	
		
		function existeRestriccionDelete($idZE) {
			$queryStr =  '
				select count(1) as existe 
				from zonas_excluibles_bog zeb
					left join zonas_excluibles ze on (zeb.id=ze.id_zona_excluible_bog)
				where zeb.id = $1							
			';			
			$params = array($idZE);	
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error al consultar existencia de la zona excluible con Id $idZE.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["existe"];
		}	

		function getIdRestriccionByName($nombreRestriccion) {
			$queryStr =  '
				select id as id_restriccion 
				from zonas_excluibles_bog zeb
				where zeb.nombre = $1 order by id desc							
			';			
			
			$params = array(utf8_encode($nombreRestriccion));	
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error al verificar existencia de la restricci&oacute;n  $nombreRestriccion.";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			if(!empty($lista)) 
				return  $lista[0]["id_restriccion"];
			else
				return 0;
		}	

		
		function insertAll($vars) {
			$coords 	= $this->procesarCoordenadas($vars["coordenadas"]);
			$sOrigen 	= $this->asignarSistemaOrigen($vars["sistemaOrigen"]);
			
			// Validación de la geometría
			$hayErroresGeometricos = $this->revisarGeometria($coords, $sOrigen);
			
			if (!$hayErroresGeometricos) {		
				if($vars["sistemaOrigen"] == "BOGOTA") {
					$queryStr = "insert into ZONAS_EXCLUIBLES_BOG (nombre, id_tipo_zona_restriccion, sistema_origen, observacion, fecha_ini_vigencia, fecha_fin_vigencia, the_geom) 
						values ($1, $2, $3, $4, to_timestamp($5,'dd-mm-yyyy'), to_timestamp($6,'dd-mm-yyyy'), ST_GeomFromText($7, ".$this->origenDefault."))";	
						
					$queryStrWGS84 = "insert into ZONAS_EXCLUIBLES (id_zona_excluible_bog, id_tipo_zona_restriccion, the_geom) 
						values ($1, $2, ST_Transform(ST_Transform(ST_GeomFromText($3, ".$this->origenDefault."),".$this->datumBogota."),".$this->wgs84."))";	
				}	else	{									
					$queryStr = "insert into ZONAS_EXCLUIBLES_BOG (nombre, id_tipo_zona_restriccion, sistema_origen, observacion, fecha_ini_vigencia, fecha_fin_vigencia, the_geom) 
						values ($1, $2, $3, $4, to_timestamp($5,'dd-mm-yyyy'), to_timestamp($6,'dd-mm-yyyy'),  ST_Transform(ST_GeomFromText($7, $sOrigen), ".$this->origenDefault."))";	
						
					$queryStrWGS84 = "insert into ZONAS_EXCLUIBLES (id_zona_excluible_bog, id_tipo_zona_restriccion, the_geom) 
						values ($1, $2, ST_Transform(ST_Transform(ST_Transform(ST_GeomFromText($3, $sOrigen), ".$this->origenDefault."), ".$this->datumBogota."),".$this->wgs84."))";	
				}	
				
				if($vars["fechaInicial"]=="") 	$vars["fechaInicial"] = null;
				if($vars["fechaFinal"]=="")		$vars["fechaFinal"] = null;
						
				$paramsBog = array(utf8_encode($vars["nombreZona"]), utf8_encode($vars["tipoZonaRestriccion"]), utf8_encode($vars["sistemaOrigen"]), utf8_encode($vars["observacionZona"]), $vars["fechaInicial"], $vars["fechaFinal"], $coords);
				
				// Borrar
					//echo $queryStr."<hr>";
					//print_r($paramsBog);
				//  Fin borrar

				$result = pg_query_params($this->conn, $queryStr, $paramsBog);
				$ERROR = pg_last_error($this->conn);
				if($ERROR) 
					echo "<table bgcolor='red' border = 0><tr><td>Error al Insertar Restricci&oacute;n en Origen Bogot&aacute;: </td></tr><tr><td>$ERROR</td></tr></table>";	
				else  {
					$idRestriccion = $this->getIdRestriccionByName($vars["nombreZona"]);
					
					if($idRestriccion) {
						$this->updateAreaTo($idRestriccion);					
						$params = array($idRestriccion, $vars["tipoZonaRestriccion"], $coords);
						
						$result = pg_query_params($this->conn, $queryStrWGS84, $params);
						$ERROR = pg_last_error($this->conn);
						if($ERROR) {
							echo "<table bgcolor='red' border = 0><tr><td>Error al Insertar Coordenadas Geogr&aacute;ficas WGS84 de Restricci&oacute;n: $ERROR</td></tr></table>";	
							$this->deleteTo($idRestriccion);
						} else
							return 1;
					} else 
						echo "<table bgcolor='red' border = 0><tr><td>No se creo satisfactoriamente la restricci&oacute;n {$vars["nombreZona"]}</td></tr></table>";					
				}
			} else 
				echo "<table bgcolor='red' border = 0><tr><td>Errores en la geometr&iacute;a ingresada. $hayErroresGeometricos</tr></table>";
				
			return 0;
		}		

		function revisarGeometria($coords, $sOrigen) {			
			$polyCorrecto = "";
			$queryStr =  "select ST_IsValidReason(ST_GeomFromText($1, $2)) as es_correcto";			
			$params = array($coords, $sOrigen);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			
			$ERROR = pg_last_error($this->conn);
			if($ERROR) {
				echo "<table bgcolor='red' border = 0><tr><td>Error al validar coordenadas: Debe verificarse nuevamente la generación del polígono. $ERROR</td></tr></table>";
				$polyCorrecto = "Error de la operaci&oacute;n al validar coordenadas";
			}	else {
				$validacion = pg_fetch_all($result);	
				$polyCorrecto = $validacion[0]["es_correcto"];
				
				if($polyCorrecto=="Valid Geometry")
					$polyCorrecto = "";
			}

			return $polyCorrecto;
		}		
		
		function deleteTo($idRestriccion) {	
			$queryStr =  "delete from ZONAS_EXCLUIBLES_BOG where id=$1";			
			$params = array($idRestriccion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error en Eliminar Restriccion de Origen Bogot&aacute;:</td></tr><tr><td> $ERROR</td></tr></table>";			
		}		

		function deleteWGS84To($idRestriccion) {	
			$queryStr =  "delete from ZONAS_EXCLUIBLES where id_zona_excluible_bog=$1";			
			$params = array($idRestriccion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error en Eliminar Restricci&oacute;n WGS84: </td></tr><tr><td>$ERROR</td></tr></table>";			
		}
		
		function deleteRestriccion($idRestriccion) {
			$this->deleteTo($idRestriccion);
			$this->deleteWGS84To($idRestriccion);
			
			if($this->existeRestriccionDelete($idRestriccion))
				return 0; // Aún existe el polígono, no fue eliminado satisfactoriamente
			return 1;	
		}
		
		function updateAreaTo($idRestriccion) {	
			$queryStr =  "update ZONAS_EXCLUIBLES_BOG set area=ST_Area(the_geom), perimetro=ST_Perimeter(the_geom) where id=$1";			
			$params = array($idRestriccion);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error al actualizar area y perimetro de Restricci&oacute;n: </td></tr><tr><td>$ERROR</td></tr></table>";			
		}	

		function getCentroideWGS84($idRestriccion) {
			$queryStr =  "select ST_AsText(ST_centroid(the_geom)) as centroide from ZONAS_EXCLUIBLES where id_zona_excluible_bog=$1";			
			$params = array($idRestriccion);
			$result = pg_query_params($this->conn, $queryStr, $params);

			if (!$result) {
			  echo "Error al obtener centroide de la Restricci&oacute;n.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return explode(" ", substr($lista[0]["centroide"],6,-1)); 		
		}

		function getArea($idRestriccion) {
			$queryStr =  "select (area/10000) as area, perimetro from ZONAS_EXCLUIBLES_BOG where id=$1";			
			$params = array($idRestriccion);
			$result = pg_query_params($this->conn, $queryStr, $params);
			
			if (!$result) {
			  echo "Error al obtener centroide de la Restricci&oacute;n.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return $lista[0]; 		
		}		
	}	
?>

