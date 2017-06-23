<?php
/*
	Clase encargada de la administración y gestión de alertas del SIGMIN
*/

	class Alertas {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Alertas.\n";
				return 0;
			}
		}
		
		function listaAlertasExpedientesArchivados() {
			$queryStr =  "
				select	distinct
					al.placa,
					al.id_tipo_alerta,
					to_char(al.fecha_notificacion,'DD/MM/YYYY') as fecha_notificacion,
					to_char(al.fecha_vencimiento - '1 second'::interval,'DD/MM/YYYY HH24:MI') as fecha_vencimiento,
					case
						when t.id is not null then 'TITULO'
						when s.id is not null then 'SOLICITUD'
						else null
					end as tipo_expediente,					
					case
						when t.id is not null then getlinedatabyidtitulo(t.id, 'TIT_MUNICIPIOS')
						when s.id is not null then getlinedatabyidsolicitud(s.id, 'SOL_MUNICIPIOS')
						else null
					end as municipios,
					case
						when t.id is not null then getlinedatabyidtitulo(t.id, 'TIT_PERSONAS')
						when s.id is not null then getlinedatabyidsolicitud(s.id, 'SOL_PERSONAS')
						else null
					end as personas,
					case
						when t.id is not null then getlinedatabyidtitulo(t.id, 'TIT_MINERALES')
						when s.id is not null then getlinedatabyidsolicitud(s.id, 'SOL_MINERALES')
						else null
					end as minerales,
					case
						when t.id is not null then t.modalidad
						when s.id is not null then s.modalidad
						else null
					end as modalidad,					
					now() as fecha_envio_email,			
					emails_notificaciones_archivadas(1) as lista_emails			
				from alertas al 
					inner join notificaciones_alertas na on (al.id=na.id_alerta)
					left join (select tt.* from titulos tt inner join titulos_cg tg on (tt.id=tg.id_titulo))t on (al.placa=t.placa)
					left join (select ss.* from solicitudes ss inner join solicitudes_cg sg on (sg.id_solicitud=ss.id)) s on (al.placa=s.placa)		 
				where na.estado_alarma='ACTIVO' and na.fecha_alarma::date - 1 <= now()::date
					and al.fecha_vencimiento::date-1 > now()::date 
					and (s.area_definitiva > 0 or s.area_definitiva is null)
				order by 4
			";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Alertas.listaAlertasExpedientesArchivados.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function listaAlertasAreasLibresProspectos() {
			$queryStr =  "
				select	distinct
					p.placa as prospecto,
					case
						when t.id is not null then t.placa
						when s.id is not null then s.placa
						else null
					end as placa,					
					2 as id_tipo_alerta, -- area libre sobre prospecto
					null as fecha_notificacion,
					to_char(al.fecha_vigencia_poly - '1 second'::interval,'DD/MM/YYYY HH24:MI') as fecha_vigencia_poly,
					case
						when t.id is not null then 'TITULO'
						when s.id is not null then 'SOLICITUD'
						else null
					end as tipo_expediente,					
					case
						when t.id is not null then getlinedatabyidtitulo(t.id, 'TIT_MUNICIPIOS')
						when s.id is not null then getlinedatabyidsolicitud(s.id, 'SOL_MUNICIPIOS')
						else null
					end as municipios,
					case
						when t.id is not null then getlinedatabyidtitulo(t.id, 'TIT_PERSONAS')
						when s.id is not null then getlinedatabyidsolicitud(s.id, 'SOL_PERSONAS')
						else null
					end as personas,
					case
						when t.id is not null then getlinedatabyidtitulo(t.id, 'TIT_MINERALES')
						when s.id is not null then getlinedatabyidsolicitud(s.id, 'SOL_MINERALES')
						else null
					end as minerales,
					case
						when t.id is not null then t.modalidad
						when s.id is not null then s.modalidad
						else null
					end as modalidad,					
					now() as fecha_envio_email,			
							al.emails_notificar as lista_emails			
					from 	areas_libres_prospectos al 
						inner join prospectos_sgm p on (p.gid=al.id_prospecto_sgm)
						left join titulos t on (al.id_titulo=t.id)
						left join solicitudes s on (al.id_solicitud=s.id) 
					where al.fecha_vigencia_poly::date - 1 = now()::date
							or al.fecha_proceso::date = now()::date			
			";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
				echo "Error al consultar Alertas.listaAlertasAreasLibresProspectos.\n";
				return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return  $lista;
		}
		

		function enviar_email($server_email, $emails, $asuntoMsg, $body) {
			// establecimiento de la variable de correo remoto para envío de emails
			ini_set("SMTP" , $server_email);

			// envio del email con el n&uacute;mero de PIN
			//------------------------------------------------------------------------------
			// multiples recipientes
			$para  = $emails;

			// asunto
			$asunto = $asuntoMsg;

			// mensaje
			$mensaje = "
			<html>
			<head>
			  <title> $asunto </title>
			</head>
			<body>
				$body
			</body>
			</html>
			";

			// Para enviar correo HTML, la cabecera Content-type debe definirse
			$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
			$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$cabeceras .= 'From: SIGMIN - Minning Properties<sigmin@sigmin.com.co>'."\r\n";
			$cabeceras .= 'Bcc: SIGMIN - administration<jaime.moreno@sigmin.com.co>';

			// $enviado: Para comprobar que el mensaje haya sido enviado exitosamente.
			$enviado=mail($para, $asunto, $mensaje, $cabeceras);

			if($enviado) 
				return $this->notificacionesDeArchivadas();
			else return "ERROR";
		}		
		
		
		function notificacionesDeArchivadas() {
			$queryStr =  "select hist_notif_enviadas_archivo() as result";			
				
			$result = pg_query($this->conn, $queryStr);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}
		
		function getEmailArchivadas() {
			$queryStr =  "
				select emails_notificaciones_archivadas(1) as lista_notificar
			";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Alertas.listaAlertasExpedientesArchivados.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["lista_notificar"];		
		}

		function montoreoServicioArchivoExpedientes($prospecto) {
			$queryStr =  "select servicios.monitoreo_alert_areas_libres($1) as result";			
	
			$params = array($prospecto);
			$result = pg_query_params($this->conn, $queryStr, $params);				
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}

		function montoreoArchivoExpedientes($prospecto) {
			$queryStr =  "select monitoreo_alert_areas_libres($1) as result";			
	
			$params = array($prospecto);
			$result = pg_query_params($this->conn, $queryStr, $params);				
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];
		}		
		
		function vencimientoAlertasRelease() {
			$queryStr =  "
				select distinct correo_electronico as email, upper(nombre || ' ' || apellido) as nombre 
				from servicios.consignaciones where estado_transaccion=1 
					and (fecha_vence::date - 3 = now()::date) and id_tipo_servicio between 1 and 3
				union
				select distinct email, upper(nombre) as nombre from servicios.promociones_notifica_usrs where fecha_vence::date-3 = now()::date
			";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Alertas.vencimientoAlertasRelease.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
		
			return  $lista;
		}		
	}	
?>

