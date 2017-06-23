<?php
	require_once("../Acceso/Config.php"); // Definición de las variables globales	
	require_once("../Modelos/Alertas.php");
	require_once("../Modelos/ControlPopups.php");
	require_once("/home/sigmin/public_html_services/Modelos/ServiciosSigmin.php");
	
	$notificar 		= new Alertas();	
	$generaURL		= new ControlPopups();
	$emails			= new ServiciosSigmin();
	$listaNotificar	= $notificar->listaAlertasExpedientesArchivados();
	// Actualiza el estado de los mensajes ya enviados
	@$notificar->notificacionesDeArchivadas();
	
	
	$server_email	= "www.sigmin.co";
	
	$para			= $notificar->getEmailArchivadas();	
	//$para			= "asesorservicios2005@gmail.com";

	$asuntoMsg 		= ':: Alertas SIGMIN ::';
	$listaPlacas	= "";

	//echo "<h2>::: SIGMIN ::: Notificaci&oacute;n por Liberaci&oacute;n de &Aacute;rea</h2>";
	
	// mensaje	
	$mensaje 		= "	
	<table border='0' width='95%'>
		<tr>
			<td colspan='2'><h2>::: RELEASE ::: Notificaci&oacute;n por Liberaci&oacute;n de &Aacute;rea</h2></td>
		</tr>
	";
	
	// Se genera la lista de todas las placas pendientes de archivo
	if(!empty($listaNotificar))
		foreach($listaNotificar as $cadaNotificacion) {
			$codAcceso = $generaURL->setControlPopup($cadaNotificacion["placa"], $cadaNotificacion["tipo_expediente"]);
			$URL_Acceso = "http://www.sigmin.co/services/reporteAreas.php?cod_acceso=$codAcceso";

			$listaPlacas	.= $cadaNotificacion["placa"].", ";
			$mensaje 		.= "	
					<tr>
						<td colspan='2'><hr size='1'></td>
					</tr>
					<tr>
						<td width='35%'><b>Placa Liberada:</b></td>
						<td>{$cadaNotificacion["placa"]}</td>
					</tr>
					<tr>
						<td><b>Tipo de Expediente:</b></td>
						<td>{$cadaNotificacion["tipo_expediente"]}</td>
					</tr>	
					<tr>
						<td><b>Modalidad del Expediente:</b></td>
						<td>{$cadaNotificacion["modalidad"]}</td>
					</tr>				
					<tr>
						<td><b>Fecha de Ejecutoria:</b></td>
						<td>{$cadaNotificacion["fecha_notificacion"]}</td>
					</tr>				
					<tr>
						<td><b>Visualizaci&oacute;n del &Aacute;rea hasta:</b></td>
						<td>{$cadaNotificacion["fecha_vencimiento"]}</td>
					</tr>
					<tr>
						<td><b>Minerales:</b></td>
						<td>".utf8_decode($cadaNotificacion["minerales"])."</td>
					</tr>				
					<tr>
						<td><b>Titulares:</b></td>
						<td>".utf8_decode($cadaNotificacion["personas"])."</td>
					</tr>				
					<tr>
						<td><b>Municipios:</b></td>
						<td>".utf8_decode($cadaNotificacion["municipios"])."</td>
					</tr>
					<tr><td colspan='2'><hr size='1'></td></tr>
					<tr>
						<td colspan='2'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b><a href='$URL_Acceso' title='Ver poligono asociado al expediente {$cadaNotificacion["placa"]}' target='_blank'>Poligono Asociado</a></b>
						</td>
					</tr>
					<tr>
						<td colspan='2'>&nbsp;</td>
					</tr>
			";
		}	
	else
		$mensaje .= "<tr>
						<td colspan='2' align='center'><h2>No hay expedientes archivados para reportar</h2></td>
					</tr>
				</table>";

		
	$mensaje .= "<tr>
					<td colspan='2'><hr size='1'></td>
				</tr>
			</table>
	";
	
	$mensaje .= '
		<p>Atentamente,</p>
		<p><b>Jos&eacute; Ernesto C&aacute;rdenas Santamar&iacute;a<br>
		Director General<br>
		SIGMIN S.A.S</b><br>		
		<div style="font-family: Verdana, Arial, Helvetica, sans-serif;	font-size: 11px; background-color: #555555;	color: #ffffff;	text-align: center;">
			<div>&nbsp;</div>
			<div>
				<div>Calle 7 Sur # 42-70 Of 1101, Edificio Forum. Medell&iacute;n-Colombia</div>
				<div>Tel&eacute;fono: (574) 322 70 04 - M&oacute;vil: 314 716 0680</div>
				<div><a href="mailto:contactenos@sigmin.com.co" style="text-decoration:none; color:#ffffff">contactenos@sigmin.com.co</a></div>
				<div>:: SIGMIN S.A.S. 2012 - TODOS LOS DERECHOS RESERVADOS &reg; ::</div>
			</div>
			<div style="padding-bottom:10px;">&nbsp;</div>
		</div>
	';
	
	// funcion de envío de email
	//$res = $notificar->enviar_email($server_email, $para, $asuntoMsg, $mensaje);
	
	//echo $mensaje;
	
	$msgJson["mensaje"] 		= utf8_decode($mensaje);
	$msgJson["emails_alertas"]  = $emails->getListaEmailsAlertas();
	
	$json = json_encode($msgJson);
	//$json = print_r($msgJson, true);

	header("Content-type:application/json"); 	
	echo $json;	
	
	/*
	if($res!="ERROR")
		echo $mensaje;
	else 
		echo $res;
	*/
	//echo "<hr>Placas reportadas: $listaPlacas - Respuesta Proceso es: ".$res."</hr>";	

	
?>