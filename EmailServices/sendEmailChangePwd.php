<?php

	header("Content-type:application/json"); 

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
		$cabeceras .= 'From: SIGMIN - Mining Properties<sigmin@sigmin.com.co>'."\r\n";
		$cabeceras .=  'Bcc: jmoreno084@gmail.com'. "\r\n";//'Bcc: contactenos@sigmin.com.co,jmoreno084@gmail.com,asesorservicios2005@gmail.com,colombiamineria@gmail.com'. "\r\n";	
		$cabeceras .= 'Return-Path: sigmin@sigmin.com.co' . "\r\n";
		$cabeceras .= 'Reply-To: sigmin@sigmin.com.co' . "\r\n";		

		// $enviado: Para comprobar que el mensaje haya sido enviado exitosamente.
		$enviado=mail($para, $asunto, $mensaje, $cabeceras);

		if($enviado) 
			return "OK";
		else return "Error de Env&iacute;o: <p> $mensaje";
	}
			


	$mensaje = '	
		<html>
		<head>
		<title>SIGMIN :: Cambio de Clave</title>
		</head>
		<body>
			<table border="0" align="center" width="600">
				<tr>
					<td>
						<center><h1>Usted ha solicitado cambio de clave en SIGMIN<h1></center>	
						<hr size="0">
						<p>A continuaci&oacute;n se encuentran las instrucciones para el cambio de clave de su cuenta </p>
						<ol>
							<li>Su usuario de acceso a cuenta sigmin es: '.$_POST["login_tmp"].'</li>
							<li>Su Clave temporal es : '.$_POST["passwd_tmp"].'</li>
							<li>Ingrese a <a href="http://www.sigmin.co/finder/">SIGMIN</a> y actualice su clave mediante la opci&oacute;n <i>ACCOUNT</i></li>
						</ol>
					</td>
				</tr>
				<tr>	
					<td>
						<p>Atentamente,</p>
						<p><b>Soporte T&eacute;cnico<br>
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
			</table>
		</body>
		</html>	
	';		
								
	$listaEmails = $_POST["email_pwd"];
	$msg = enviar_email("www.sigmin.com.co", $listaEmails, "Cambio de Clave :: SIGMIN", $mensaje);

	$msgJson["estado_envio"] 	= $msg;
	$msgJson["fecha_envio"] 	= date("j del n de Y");
				
	$json = json_encode($msgJson);
	echo $json;	

?>