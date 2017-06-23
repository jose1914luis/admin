<?php
/*
	Clase encargada de administrar la información relacionada a consultas sobre solicitudes y titulos
	en el CMQ
*/

	class ConsultasCMQ {	
		var $conn;
		var $idSolicitud;
		var $criterios;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
		}
			
		function selectSolicitudesConsultas($codExpediente, $mineral, $municipio, $departamento, $titular) {
			$codExpediente 	= utf8_encode(strtoupper($codExpediente)); 
			$mineral 		= utf8_encode(strtoupper($mineral)); 
			$municipio 		= utf8_encode(strtoupper($municipio)); 
			$departamento 	= utf8_encode(strtoupper($departamento)); 
			$titular 		= utf8_encode(strtoupper($titular));
			
			$where = "";
			$posVar =1;

			$criteriosConsulta = "";
		
			if(!empty($codExpediente)) {
				$where .= "placa like $".($posVar++)." and ";
				$parametros[0] = "%".strtoupper($codExpediente)."%";
				$criteriosConsulta .= "Placa contiene '".$codExpediente."' & ";
			}


			if(!empty($mineral)) {
				$where .= "minerales like $".($posVar++)." and ";
				$parametros[1] = "%".strtoupper($mineral)."%";
				$criteriosConsulta .= " minerales contiene '".$mineral."' & ";
			}

			if(!empty($municipio)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[2] = "%".strtoupper($municipio)."%";
				$criteriosConsulta .= " municipio contiene '".$municipio."' & ";
			}

			if(!empty($departamento)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[3] = "%".strtoupper($departamento)."%";
				$criteriosConsulta .= " departamento contiene '".$departamento."' & ";
			}

			if(!empty($titular)) {
				$where .= "solicitantes like $".($posVar++)." and ";
				$parametros[4] = "%".strtoupper($titular)."%";
				$criteriosConsulta .= " titular contiene '".$titular."' & ";
			}
			
			if($criteriosConsulta=="")
				$this->criterios = " NINGUNO ";
			else
				$this->criterios = $criteriosConsulta;

			if($where != "") 
				$where = " where ".$where." 1=1";
	
			$queryStr =  "
				select 
					placa,
					modalidad,
					estado_juridico,
					grupo_trabajo,
					formulario,
					fecha_radicacion,
					fecha_terminacion,
					fecha_otorgamiento,
					fecha_creacion,
					area_solicitada_ha,
					area_definitiva_ha,
					direccion_correspondencia,
					telefono_contacto,
					observacion,
					justificacion_extemporanea,
					municipios,
					solicitantes,
					minerales,
					plancha_igac,
					sistema_origen,
					centroide,
					descripcion_pa
				from v_solicitudes $where					
			";		
			
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la vista de Solicitudes. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectTitulosConsultas($codExpediente, $mineral, $municipio, $departamento, $titular) {
			$codExpediente 	= utf8_encode(strtoupper($codExpediente)); 
			$mineral 		= utf8_encode(strtoupper($mineral)); 
			$municipio 		= utf8_encode(strtoupper($municipio)); 
			$departamento 	= utf8_encode(strtoupper($departamento)); 
			$titular 		= utf8_encode(strtoupper($titular));
	
			$where = "";
			$posVar =1;

		
			if(!empty($codExpediente)) {
				$where .= "(placa like $".($posVar)." or codigo_rmn like $".($posVar)." or codigo_anterior like $".($posVar).") and ";
				$posVar++;
				$parametros[0] = "%".strtoupper($codExpediente)."%";
			}


			if(!empty($mineral)) {
				$where .= "minerales like $".($posVar++)." and ";
				$parametros[1] = "%".strtoupper($mineral)."%";
			}

			if(!empty($municipio)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[2] = "%".strtoupper($municipio)."%";
			}

			if(!empty($departamento)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[3] = "%".strtoupper($departamento)."%";
			}

			if(!empty($titular)) {
				$where .= "titulares like $".($posVar++)." and ";
				$parametros[4] = "%".strtoupper($titular)."%";
			}

			if($where != "") 
				$where = " where ".$where." 1=1";
	
			$queryStr =  "
				select 
					placa, 
					codigo_rmn, 
					codigo_anterior, 
					modalidad, 
					estado_juridico, 
					grupo_trabajo, 
					fecha_inscripcion, 
					fecha_contrato, 
					fecha_terminacion, 
					fecha_creacion, 
					area_otorgada_ha, 
					area_definitiva_ha, 
					direccion_correspondencia, 
					telefono_contacto, 
					municipios, 
					titulares, 
					minerales, 
					plancha_igac, 
					sistema_origen, 
					descripcion_pa, 
					centroide
				from v_titulos $where					
			";		
			
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la vista de Titulos. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectProspectosConsultas($codExpediente, $municipio, $departamento) {
			$codExpediente = utf8_encode(strtoupper($codExpediente));
			$municipio = utf8_encode(strtoupper($municipio));
			$departamento = utf8_encode(strtoupper($departamento));
		
			$where = "";
			$posVar =1;
		
			if(!empty($codExpediente)) {
				$where .= "placa like $".($posVar++)." and ";
				$parametros[0] = "%".strtoupper($codExpediente)."%";
			}

			if(!empty($municipio)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[1] = "%".strtoupper($municipio)."%";
			}

			if(!empty($departamento)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[2] = "%".strtoupper($departamento)."%";
			}

			if($where != "") 
				$where = " where ".$where." 1=1";
	
			$queryStr =  "
				select 
					placa, 
					fecha_creacion, 
					area_has as area_definitiva_ha, 
					perimetro, 
					sistema_origen,
					municipios,
					centroide
					--coordenadas_bog
				from v_prospectos $where					
			";		
			
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la vista de Prospectos. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function selectEstudiosTecnicosConsultas($codExpediente, $mineral, $municipio, $departamento, $titular) {
			$codExpediente 	= utf8_encode(strtoupper($codExpediente)); 
			$mineral 		= utf8_encode(strtoupper($mineral)); 
			$municipio 		= utf8_encode(strtoupper($municipio)); 
			$departamento 	= utf8_encode(strtoupper($departamento)); 
			$titular 		= utf8_encode(strtoupper($titular));
			
			$where = "";
			$posVar =1;

			$criteriosConsulta = "";
		
			if(!empty($codExpediente)) {
				$where .= "placa = $".($posVar++)." and ";
				$parametros[0] = strtoupper(trim($codExpediente));//"%".strtoupper($codExpediente)."%";
				$criteriosConsulta .= "Placa contiene '".$codExpediente."' & ";
			}


			if(!empty($mineral)) {
				$where .= "minerales like $".($posVar++)." and ";
				$parametros[1] = "%".strtoupper($mineral)."%";
				$criteriosConsulta .= " minerales contiene '".$mineral."' & ";
			}

			if(!empty($municipio)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[2] = "%".strtoupper($municipio)."%";
				$criteriosConsulta .= " municipio contiene '".$municipio."' & ";
			}

			if(!empty($departamento)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[3] = "%".strtoupper($departamento)."%";
				$criteriosConsulta .= " departamento contiene '".$departamento."' & ";
			}

			if(!empty($titular)) {
				$where .= "solicitantes like $".($posVar++)." and ";
				$parametros[4] = "%".strtoupper($titular)."%";
				$criteriosConsulta .= " titular contiene '".$titular."' & ";
			}
			
			if($criteriosConsulta=="")
				$this->criterios = " NINGUNO ";
			else
				$this->criterios = $criteriosConsulta;

			if($where != "") 
				$where = " where ".$where." 1=1";
				
			$queryStr =  "select get_area_def(placa) from v_solicitudes ".$where;
			
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al ejecutar el estudio t&eacute;cnico de superposiciones. Error: ".pg_last_error($this->conn);
			  return 0;
			}	

			// subconsulta para obtener las placas que responden a la busqueda realizada por el usuario
			$queryStr =  "select placa from v_solicitudes ".$where;
			
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al ejecutar la subconsulta de placas que cumplen con la busqueda del usuario. Error: ".pg_last_error($this->conn);
			  return 0;
			}	

			// transformación del resultado de la subconsulta en una nueva consulta
			$lista = pg_fetch_all($result);
			if(isset($lista[0])) {
				$subConsulta = "where";
				foreach($lista as $campo) 
					$subConsulta .= " vas.area_estudio='".$campo["placa"]."' or ";
				$subConsulta .= " 1=2";
			} else {
				$subConsulta = "where 1=2";
			}
					
			// fin del procesamiento de la subconsulta
		
			$queryStr =  "
				select distinct
					vas.area_estudio, 					
					vas.fecha_radicacion, 
					vas.modalidad_area_estudio, 
					vas.minerales_area_estudio, 
					vas.estado_juridico, 
					vas.sistema_origen, 
					vas.expediente_superpone, 
					vas.fecha_radica_inscribe, 
					vas.minerales_area_superpone, 
					vas.tipo_superposicion, 
					case when vas.recortar=1 then 'SI' else 'NO' end as recortar, 
					vas.modalidad_area_superpone, 
					vas.area_superposicion, 
					vas.porcentaje_superpone || '%' as porcentaje_superpone, 					
					get_num(ab.area_ini/10000) as area_inicial_has,
					get_num(ab.area_fin/10000) as area_final_has,
					get_num((abs(ab.area_ini-ab.area_fin)/ab.area_ini)*100) || '%' as porcentaje_recortado,
					getLineDataByIdSolicitud(s.id,'SOL_PERSONAS') as proponentes,
					getLineDataByIdSolicitud(s.id,'SOL_MUNICIPIOS') as municipios,
					ST_AsText(ab.the_geom) as coordenadas_resultantes
				from 	v_analisis_superposiciones vas 
					inner join solicitudes s on (s.placa=vas.area_estudio)
					inner join areas_superposiciones_bog ab on (s.id=ab.id_solicitud)
				$subConsulta 	
				order by 2, 8		
			";	
			
			
			$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al realizar la consulta de superposiciones. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectEstudiosTecnicosProspectos($codExpediente, $municipio, $departamento) {
			$codExpediente 	= utf8_encode(strtoupper($codExpediente)); 
			$municipio 		= utf8_encode(strtoupper($municipio)); 
			$departamento 	= utf8_encode(strtoupper($departamento)); 
			
			$where = "";
			$posVar =1;

			$criteriosConsulta = "";
		
			if(!empty($codExpediente)) {
				$where .= "placa = $".($posVar++)." and ";
				$parametros[0] = strtoupper(trim($codExpediente));
				$criteriosConsulta .= "Placa igual a '".$codExpediente."' & ";
			}

			if(!empty($municipio)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[2] = "%".strtoupper($municipio)."%";
				$criteriosConsulta .= " municipio contiene '".$municipio."' & ";
			}

			if(!empty($departamento)) {
				$where .= "municipios like $".($posVar++)." and ";
				$parametros[3] = "%".strtoupper($departamento)."%";
				$criteriosConsulta .= " departamento contiene '".$departamento."' & ";
			}

			if($criteriosConsulta=="")
				$this->criterios = " NINGUNO ";
			else
				$this->criterios = $criteriosConsulta;

			if($where != "") 
				$where = " where ".$where." 1=1";
				
			$queryStr =  "select get_area_def_qmy(placa) from v_prospectos ".$where;

			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al ejecutar el estudio t&eacute;cnico de superposiciones para prospectos mineros. Error: ".pg_last_error($this->conn);
			  return 0;
			}							


			// subconsulta para obtener las placas que responden a la busqueda realizada por el usuario
			$queryStr =  "select placa from v_prospectos ".$where;
			
			if($where != "") 
				$result = pg_query_params($this->conn, $queryStr, $parametros);
			else	
				$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al ejecutar la subconsulta de placas prospectos que cumplen con la busqueda del usuario. Error: ".pg_last_error($this->conn);
			  return 0;
			}	

			// transformación del resultado de la subconsulta en una nueva consulta
			$lista = pg_fetch_all($result);
			if(isset($lista[0])) {
				$subConsulta = "where";
				foreach($lista as $campo) 
					$subConsulta .= " vas.area_estudio='".$campo["placa"]."' or ";
				$subConsulta .= " 1=2";
			} else {
				$subConsulta = "where 1=2";
			}
					
			// fin del procesamiento de la subconsulta


			
			$queryStr =  "
				select distinct
					vas.area_estudio, 					
					vas.fecha_creacion, 
					vas.sistema_origen, 
					vas.expediente_superpone, 
					vas.fecha_radica_inscribe, 
					vas.minerales_area_superpone, 
					vas.tipo_superposicion, 
					case when vas.recortar=1 then 'SI' else 'NO' end as recortar, 
					vas.modalidad_area_superpone, 
					vas.area_superposicion, 
					vas.porcentaje_superpone || '%' as porcentaje_superpone, 					
					get_num(abg.area_ini/10000) as area_inicial_has,
					get_num(abg.area_fin/10000) as area_final_has,
					get_num((abs(abg.area_ini-abg.area_fin)/abg.area_ini)*100) || '%' as porcentaje_recortado,
					personasByExpediente(vas.expediente_superpone,vas.tipo_superposicion) as titulares,
					getLineDataByProspecto(vas.area_estudio, 'PROSP_MUNICIPIOS') as municipios,
					ST_AsText(abg.the_geom) as coordenadas_resultantes
				from v_prospectos_superposiciones vas 
					inner join prospectos_superposiciones_bog abg on (vas.area_estudio=abg.placa)
				$subConsulta
				order by 2, 5			
			";	
					
			$result = pg_query($this->conn, $queryStr);

			if (pg_last_error($this->conn)) {
			  echo "Error al realizar la consulta de superposiciones para prospectos mineros. Error: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function criteriosConsulta() {
			return $this->criterios;
		}
		
		function generaUrlViewMap($placa, $centroide, $areaPoly, $cobertura="solicitudes_cg", $tituloCobertura="Solicitudes") {
			$centroidesLonLat = explode(" ", substr($centroide,6,-1)); 		
			$centroideLon = $centroidesLonLat[0];
			$centroideLat = $centroidesLonLat[1];			
			
			$enlace = "codigoExpediente=$placa&centroideLon=$centroideLon&centroideLat=$centroideLat&cobertura=$cobertura&tituloCobertura=$tituloCobertura&areaPoly=$areaPoly";			
			return $enlace;		
		}
		
		function generarViewMap($placa, $clasificacion="SOLICITUD") { 
			$queryStr = "";
			
			if($clasificacion=='SOLICITUD') {
				$queryStr = 'select * from viewCoordSolicitud ($1) as ("centroide" text, "coordenadas" text, "area_has" varchar)';
			} else if ($clasificacion=='TITULO') {
				$queryStr = 'select * from viewCoordTitulo ($1) as ("centroide" text, "coordenadas" text, "area_has" varchar)';							
			} else if ($clasificacion=='PROSPECTO') {
				$queryStr =	"select 	ST_AsText(ST_Centroid(sg.the_geom)) as centroide,  
								ST_AsText(sg.the_geom) as coordenadas,  
								get_num(ST_Area(ST_Transform(sg.the_geom, get_sistema_origen(sb.sistema_origen)))/10000) as area_has
							from 	prospectos sg inner join prospectos_bog sb on (sg.placa=sb.placa)
							where 	sg.placa=$1";
			} else if ($clasificacion=='PROSPECTO_SGM') {
				$queryStr =	"select 	ST_AsText(ST_Centroid(sg.the_geom)) as centroide,  
								ST_AsText(sg.the_geom) as coordenadas,  
								get_num(ST_Area(ST_Transform(sg.the_geom, get_sistema_origen(sb.sistema_origen)))/10000) as area_has
							from 	prospectos_sgm sg inner join prospectos_bog_sgm sb on (sg.placa=sb.placa)
							where 	sg.placa=$1";
			} else if ($clasificacion=='ESTUDIO_TECNICO') {
				$queryStr =	"select 	ST_AsText(ST_Centroid(sg.the_geom)) as centroide,  
								ST_AsText(sg.the_geom) as coordenadas,  
								get_num(ST_Area(ST_Transform(sb.the_geom, get_sistema_origen(sar.sistema_origen)))/10000) as area_has
							from 	areas_superposiciones sg inner join solicitudes s on (s.id=sg.id_solicitud)
								inner join areas_superposiciones_bog sb on (s.id=sb.id_solicitud)
								left join sol_arcifinios_tmp sar on (s.id = sar.id_solicitud)	 
							where 	s.placa=$1";
			} else if ($clasificacion=='ESTUDIO_TECNICO_PROSPECTO') {
				$queryStr =	"select 	ST_AsText(ST_Centroid(sg.the_geom)) as centroide,  
								ST_AsText(sg.the_geom) as coordenadas,  
								get_num(ST_Area(ST_Transform(sb.the_geom, get_sistema_origen(pb.sistema_origen)))/10000) as area_has
							from 	prospectos_superposiciones sg 
								inner join prospectos_bog pb on (sg.placa=pb.placa)
								inner join prospectos_superposiciones_bog sb on (sg.placa=sb.placa)	
							where 	sg.placa=$1";
			} else if ($clasificacion=='RESTRICCION') {
				$queryStr =	"
					select 	ST_AsText(ST_Centroid(sg.the_geom)) as centroide,  
						ST_AsText(sg.the_geom) as coordenadas,  
						get_num(ST_Area(ST_Transform(sb.the_geom, get_sistema_origen(sb.sistema_origen)))/10000) as area_has
					from 	zonas_excluibles sg 
						inner join zonas_excluibles_bog sb on (sg.id_zona_excluible_bog=sb.id)
					where 	sb.id=$1								
				";
			}			
		
			$result = pg_query_params($this->conn, $queryStr, array($placa));

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la vista de areas en $clasificacion: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);		
			pg_free_result($result);
			
			if(!isset($lista[0])) return null;	

			return  $lista[0];
		}		
		
		function generarReporte($placa, $clasificacion="SOLICITUD") { 				
			$queryStr = "";
			
			if($clasificacion=='SOLICITUD') {
				$queryStr = "
					select distinct vs.*,
						ST_AsText(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen))) as coordenadas,
						get_num(ST_Area(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen)))/10000) as Area_Def_has
					from 	v_solicitudes vs 
						inner join solicitudes s on (vs.placa=s.placa)
						left join solicitudes_cg_bog sg on (s.id=sg.id_solicitud)
					where s.placa=$1
				";
			} else if ($clasificacion=='TITULO') {
				$queryStr =	"
					select  distinct s.id as id_titulo,
						vs.*,
						ST_AsText(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen))) as coordenadas,
						get_num(ST_Area(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen)))/10000) as Area_Def_has
					from 	v_titulos vs 
						inner join titulos s on (vs.placa=s.placa)
						left join titulos_cg_bog sg on (s.id=sg.id_titulo)
					where s.placa=$1
				";
			} else if ($clasificacion=='PROSPECTO') {
				$queryStr =	"
					select 	vp.placa,
						vp.fecha_creacion,
						vp.area_has,
						vp.municipios,
						vp.sistema_origen,
						ST_AsText(ST_Transform(pb.the_geom, get_sistema_origen(pb.sistema_origen))) as coordenadas
					from v_prospectos vp
						inner join prospectos_bog pb on (vp.placa=pb.placa)				
					where vp.placa=$1
				";
			} else if ($clasificacion=='ESTUDIO_SOLICITUD') {
				$queryStr =	"
					select  s.id as id_solicitud,
						vs.*,
						ST_AsText(ST_Transform(sg.the_geom, get_sistema_origen(vs.sistema_origen))) as coordenadas,
						ST_AsText(ST_Transform(ss.the_geom, get_sistema_origen(vs.sistema_origen))) as coordenadas_estudio,
						get_num(ss.area_ini/10000) as Area_Def_has,
						get_num(ss.area_fin/10000) as Area_Def_Estudio	
					from 	v_solicitudes vs 
						inner join solicitudes s on (vs.placa=s.placa)
						left join solicitudes_cg_bog sg on (s.id=sg.id_solicitud)
						left join areas_superposiciones_bog ss on (s.id=ss.id_solicitud)
					where vs.placa=$1
				";
			} else if ($clasificacion=='ESTUDIO_PROSPECTO') {
				$queryStr =	"
					select 	vp.placa,
						vp.fecha_creacion,
						vp.area_has,
						vp.municipios,
						vp.sistema_origen,
						ST_AsText(ST_Transform(pb.the_geom, get_sistema_origen(pb.sistema_origen))) as coordenadas,
						ST_AsText(ST_Transform(psb.the_geom, get_sistema_origen(pb.sistema_origen))) as coordenadas_estudio,
						get_num(psb.area_ini/10000) as area_def_has,
						get_num(psb.area_fin/10000) as area_def_estudio	
					from v_prospectos vp
						inner join prospectos_bog pb on (vp.placa=pb.placa)
						inner join prospectos_superposiciones_bog psb on (vp.placa=psb.placa)				
					where vp.placa=$1			
				";
			}			
			
			
			$result = pg_query_params($this->conn, $queryStr, array($placa));

			if (pg_last_error($this->conn)) {
			  echo "Error al consultar la generación de reportes en $clasificacion: ".pg_last_error($this->conn);
			  return 0;
			}						
			$lista = pg_fetch_all($result);		
			pg_free_result($result);
			
			if(!isset($lista[0])) return null;	

			return  $lista[0];
		}		
	}	
?>

