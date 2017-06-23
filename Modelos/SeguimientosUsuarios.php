<?php
/*
	Clase encargada de administrar la información relacionada a solicitudes
	en el CMQ
*/

	class SeguimientosUsuarios {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase Usuarios.\n";
				return 0;
			}
		}
	
		function selectAll() {
			$queryStr =  'select * from seguimientos_usuarios order by fecha_accion';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Usuarios.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function generarAccion($accion) {		
			$accion 		=   utf8_encode($accion);
			$queryStr		=   "insert into seguimientos_usuarios (login, accion, ruta_pagina, ip_equipo) values ($1, $2, $3, $4)";				
			$params 		= 	array($_SESSION['usuario_cmq'], $accion, $_SERVER['PHP_SELF'], $_SERVER['REMOTE_ADDR']);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR) 
				echo "<table bgcolor='red' border = 0><tr><td>Error al insertar accion del usuario '$accion': $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";						
		}	

		function getEstadisticaConsultas($fechaIni="",$fechaFin="") {	
			if($fechaIni=="") $fechaIni = "1/1/2014";
			if($fechaFin=="") $fechaFin = date("d/m/Y");
			
			$queryStr =  "
				select distinct
					cu.id,
					to_char(cu.fecha_consulta,'DD/MM/YYYY HH24:MI:SS') as  fecha_consulta,
					string_consulta,
					ip_acceso,
					browser_cliente,
					pais_acceso,
					login,
					numero_documento,
					nombre,
					correo_electronico,
					estado as estado_sigmin			
				from consultas_usuarios cu
					inner join logeo_usuarios lu on cu.id_logeo_usuario=lu.id
					inner join usuarios_sgm u on lu.id_usuario=u.id
				where cu.fecha_consulta between to_date($1,'dd/mm/yyyy') and to_date($2,'dd/mm/yyyy')+1
					and u.login <> 'jmoreno'
				order by 1 desc
			";
			
			$result = pg_query_params($this->conn, $queryStr, array($fechaIni, $fechaFin));
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de consulta de pagos
			} else {
				$lista = pg_fetch_all($result);	
				return  $lista;
			}
		}
		
		function getTareasFuncionarios($fechaInicial, $fechaFinal, $tipo_analisis) {
			if($fechaInicial=="")	$fechaInicial = "1/1/2010";
			if($fechaFinal=="")		$fechaFinal = date("d/m/Y");	
			if($tipo_analisis=="")	$tipo_analisis = "YYYY-MM";
			
			$queryStr =  "	
			/*	select  
					fecha,
					tipo_operacion,
					login,	
					count(distinct placa) as total
				from (	
					select distinct
						login,
						to_char(fecha_accion, $3) as fecha,
						accion,
						case 
							when accion like 'Almacenamiento%Datos%Solicitudes%Textual%' then 'Ingreso Datos Basicos Solicitud'
							when accion like 'Almacenamiento%Datos%Titulos%Textual%' then 'Ingreso Datos Basicos Titulo' 
							when accion like 'Almacenamiento%Datos%Títulos%Poligono%' then 'Ingreso Poligono Titulo' 
							when accion like 'Almacenamiento%Datos%Solicitudes%Poligono%' then 'Ingreso Poligono Solicitudes' 
							when accion like '%Ingreso%Anotaci%n%' then 'Ingreso de Anotacion'
							else 'N/A'
						end as tipo_operacion,
						case 
							when accion like 'Almacenamiento%Datos%Solicitudes%Textual%' then 
								trim(replace(replace(accion, 'Almacenamiento de Datos Basicos de Solicitudes (Textual), Placa ',''),'''',''))
							when accion like 'Almacenamiento%Datos%Titulos%Textual%' then 
								trim(replace(replace(accion, 'Almacenamiento de Datos Basicos de Titulos (Textual), Placa ',''),'''',''))
							when accion like 'Almacenamiento%Datos%Títulos%Poligono%' then 
								trim(replace(replace(accion, 'Almacenamiento de Datos Básicos de Títulos (Poligono), Placa ',''),'''',''))
							when accion like 'Almacenamiento%Datos%Solicitudes%Poligono%' then 
								trim(replace(replace(accion, 'Almacenamiento de Datos Basicos de Solicitudes (Poligono), Placa ',''),'''',''))
							when accion like '%Ingreso%Anotaci%n%' then 
								trim(replace(accion, 'Ingreso de Anotación a la placa ',''))
							else 'N/A'
						end as placa		
					from seguimientos_usuarios su
					where (accion like 'Almacenamiento%Datos%' or accion like '%Ingreso%Anotaci%n%')
						and fecha_accion  between to_date($1,'DD/MM/YYYY') and to_date($2,'DD/MM/YYYY') + 1
				) tf group by tf.fecha, tipo_operacion, tf.login
				order by 1 desc				
			*/
				select  
					fecha,
					tipo_operacion,
					login,	
					count(distinct placa) as total
				from (	
					select distinct
						login,
						to_char(fecha_accion, $3) as fecha,
						accion,
						case 
							when accion like 'Almacenamiento%Datos%Solicitudes%Textual%' then 'Ingreso Datos Basicos Solicitud'
							when accion like 'Almacenamiento%Datos%Titulos%Textual%' then 'Ingreso Datos Basicos Titulo' 
							when accion like 'Almacenamiento%Datos%Títulos%Poligono%' then 'Ingreso Poligono Titulo' 
							when accion like 'Almacenamiento%Datos%Solicitudes%Poligono%' then 'Ingreso Poligono Solicitudes' 
							when accion like '%Ingreso%Anotaci%n%' then 'Ingreso de Anotacion'
							when accion like 'Actualizaci%n satisfactoria del estado%' then 'Archivo de Expediente'
							else 'N/A'
						end as tipo_operacion,
						case 
							when accion like 'Almacenamiento%Datos%Solicitudes%Textual%' then 
								trim(replace(replace(accion, 'Almacenamiento de Datos Basicos de Solicitudes (Textual), Placa ',''),'''',''))
							when accion like 'Almacenamiento%Datos%Titulos%Textual%' then 
								trim(replace(replace(accion, 'Almacenamiento de Datos Basicos de Titulos (Textual), Placa ',''),'''',''))
							when accion like 'Almacenamiento%Datos%Títulos%Poligono%' then 
								trim(replace(replace(accion, 'Almacenamiento de Datos Básicos de Títulos (Poligono), Placa ',''),'''',''))
							when accion like 'Almacenamiento%Datos%Solicitudes%Poligono%' then 
								trim(replace(replace(accion, 'Almacenamiento de Datos Basicos de Solicitudes (Poligono), Placa ',''),'''',''))
							when accion like '%Ingreso%Anotaci%n%' then 
								trim(replace(accion, 'Ingreso de Anotación a la placa ',''))
							when accion like 'Actualizaci%n satisfactoria del estado%' then	
								trim(
									regexp_replace(
										regexp_replace(
											accion, '(Actualizaci)(o|ó)(n satisfactoria del estado de)(l titulo| la solicitud )', '', 'g'
										), '( a TERMINADO| a VIGENTE| a OTORGADA| a ARCHIVADA)', '', 'g'
									)
								)
							else 'N/A'
						end as placa		
					from seguimientos_usuarios su
					where (accion like 'Almacenamiento%Datos%' or accion like '%Ingreso%Anotaci%n%' or accion like 'Actualizaci%n satisfactoria del estado%')
						and fecha_accion  between to_date($1,'DD/MM/YYYY') and to_date($2,'DD/MM/YYYY') + 1
				) tf group by tf.fecha, tipo_operacion, tf.login
				order by 1 desc			
			";

			$queryStr =  utf8_encode($queryStr);			
			
			$result = pg_query_params($this->conn, $queryStr, array($fechaInicial, $fechaFinal, $tipo_analisis));
			if (pg_last_error($this->conn)) {
				echo "Error : ".pg_last_error($this->conn);	// proceso con errores de consulta de pagos
				return 0;
			} 			
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return $lista;
		}		

	}	
?>

