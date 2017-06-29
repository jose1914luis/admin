<?php

	//header("Content-type:application/json"); 

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
		$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$cabeceras .= 'From: SIGMIN - Mining Properties<sigmin@sigmin.com.co>'."\r\n";
		$cabeceras .=  'Bcc: jmoreno084@gmail.com,contactenos@sigmin.com.co,colombiamineria@gmail.com,sebastian.velasquez@sigmin.com.co'. "\r\n"; 
		//$cabeceras .=  'Bcc: asesorservicios2005@gmail.com'. "\r\n"; 
		$cabeceras .= 'Return-Path: sigmin@sigmin.com.co' . "\r\n";
		$cabeceras .= 'Reply-To: sigmin@sigmin.com.co' . "\r\n";		

		// $enviado: Para comprobar que el mensaje haya sido enviado exitosamente.
		$enviado=mail($para, $asunto, $mensaje, $cabeceras);

		if($enviado) 
			return "OK";
		else return "Error de Env&iacute;o: <p> $mensaje";
	}
			
	// por definir
	if($_POST["tipo_servicio"]=="Actualizar")
		$mensaje = '	
			<html>
			<head>
			<title>SIGMIN :: Help Desk </title>
			</head>
			<body>
			<table border="0" align="center" width="600">
			<tr>
			<td>
			<center><h1>Bienvenido(a) al Sistema de Help Desk<h1></center>	
			<hr size="0">
			<p align="justify">
				La informaci&oacute;n a continuaci&oacute;n ser&aacute; enviada a nuestro equipo de soporte a fin de dar soluci&oacute;n lo m&aacute;s pronto posible. 
			</p>			
			<hr size="0">
			<table border="0" width="100%" align="center">
				<tr>
					<td bgcolor="#DEDEDE"><b>Departamento:</b></td>
					<td>'.@utf8_decode($_POST["departamento"]).'</td>
				</tr>
				<tr>
					<td bgcolor="#DEDEDE"><b>Municipio:</b></td>
					<td>'.@utf8_decode($_POST["municipio"]).'</td>
				</tr>
				<tr>
					<td bgcolor="#DEDEDE"><b>Placas afectadas:</b></td>
					<td>'.@utf8_decode($_POST["expedientes"]).'</td>
				</tr>
				<tr>
					<td bgcolor="#DEDEDE"><b>Observaci&oacute;n:</b></td>
					<td>'.@utf8_decode($_POST["observaciones"]).'</td>
				</tr>				
			</table>
			<hr size="0">
			<p align="justify">
				Una vez resuelto este inconveniente ser&aacute; informado por este medio. 
			</p>			
			
			<p>Para nosotros ser&aacute; un placer atenderle, pondremos todo nuestro empe&ntilde;o para garantizarle un buen servicio.</p>
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

			</td>
			</tr>
			</body>
			</html>	
		';	
	else  // únicamente para servicios asociados a mensaje de alerta de archivadas
		$mensaje = '	
			<html>
			<head>
			<title>SIGMIN :: Acceso a Servicios </title>
			</head>
			<body>
			<table border="0" align="center" width="600">
			<tr>
			<td>
			<center><h1>Bienvenido(a) al Sistema de B&uacute;squeda de Nuevos Prospectos Mineros - SIGMIN -<h1></center>	
			<hr size="0">

			<p>Apreciado(a) '.strtoupper($_POST["nombre"]).',
			<p>Muchas gracias por aceptar nuestra invitaci&oacute;n a conocer SIGMIN,</p>
			<p>Podr&aacute; acceder directamente al servicio <b>"'.$_POST["descripcion_servicio"].'"</b> a partir de ma&ntilde;ana revisando en su correo electr&oacute;nico <b>'.$_POST["email"].'</b>. Recibirá el listado de solicitudes y t&iacute;tulos que son archivados tanto por la Agencia Nacional de Miner&iacute;a como por la Secretar&iacute;a de Minas de la Gobernaci&oacute;n de Antioquia.</p>
			
			<p>Adem&aacute;s del listado, podr&aacute; verificar cada una de las &aacute;reas liberadas y los datos detallados asociados a las mismas.</p>
						
			<p>Para nosotros ser&aacute; un placer atenderle, pondremos todo nuestro empe&ntilde;o para garantizarle un buen servicio.</p>
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

			</td>
			</tr>
			</body>
			</html>	
		';		
	
								
	$listaEmails = $_POST["email"];
	$msg = enviar_email("www.sigmin.com.co", $listaEmails, "Sistema Help Desk :: SIGMIN", $mensaje);

	$msgJson["estado_envio"] 	= $msg;
	$msgJson["fecha_envio"] 	= date("j del n de Y");
				
	//$json = json_encode($msgJson);
	$json = print_r($msgJson, true);
	echo $json;	

?>