<?php
	require_once("LibCurl.php");

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
		  <title> ::SIGMIN - Release:: - $asunto </title>
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
		$cabeceras .=  'Bcc: jmoreno084@gmail.com'. "\r\n";				
		//$cabeceras .=  'Bcc: colombiamineria@gmail.com, juan.velasquez@sigmin.com.co, jmoreno084@gmail.com'. "\r\n";   				

		// $enviado: Para comprobar que el mensaje haya sido enviado exitosamente.
		$enviado=mail($para, $asunto, $mensaje, $cabeceras);

		if($enviado) 
			return "Mensaje OK";
		else return "Error de Envío: <p> $mensaje";
	}
		
	//$body = file_get_contents("http://www.sigmin.co/administracion/SendEmail/prueba_mail.php");	


	// envio de alertas de archivo
	$url = "http://www.sigmin.co/administracion/SendEmail/EnviosVencimientoAlertas.php";	
	$connCurl	= new LibCurl;
	$resultado 	= $connCurl->curl_download($url, array("servicio_vence"=>"release"));									
	$emailRs 	= json_decode($resultado, true);				
	//$emailRs 	= $resultado;
	
/*
	$url = "http://www.sigmin.co/administracion/SendEmail/EnviosAlertas.php";
	$cc = curl_init($url);  
	curl_setopt($cc, CURLOPT_RETURNTRANSFER, true);
	$body =  curl_exec($cc);
	
	curl_error($cc);  
	curl_close($cc);  
*/	

	if(!empty($emailRs)) {
		foreach($emailRs as $cadaEmail) {
			$mensaje = utf8_decode('	
				<html>
				<head>
				<title>SIGMIN :: Vencimiento servicio liberaci&oacute;nes de &aacute;reas </title>
				</head>
				<body>
				<table border="0" align="center" width="600">
				<tr>
				<td>
				<center><h1>Bienvenido(a) a Sigmin RELEASE, sistema de alerta de liberaci&oacute;n de &aacute;reas mineras</h1></center>	
				<hr size="0">

				<p>Apreciado(a) '.strtoupper($cadaEmail["nombre"]).',
				<p>Le informamos que su servicio de alertas de "Liberaciones de &Aacute;reas" vencer&aacute; en tres d&iacute;as.</p>
				<p>Puede conocer nuestros planes y renovar su servicio en <b><a href="http://www.sigmin.com/#!release-total/cee5" style="text-decoration:none;" target="_top">SIGMIN - RELEASE</a></b>.</p>		
				<p><b>Recuerde:</b><br>Para nosotros ser&aacute; un placer atenderle, pondremos todo nuestro empe&ntilde;o para garantizarle un buen servicio.</p>
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
			');	
			
			$msg = enviar_email("www.sigmin.com.co", $cadaEmail["email"], "Vencimiento Servicio Release :: SIGMIN", $mensaje);			
			$msg = (empty($msg)) ? "Error de Envío" : "Envío Correcto";
		}
	} else
		$msg =  "Error en envio";

	echo "<hr>Fecha: ".date("j del n de Y")."<br>$msg</hr>";
	
?>