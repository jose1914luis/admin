<?php
	require_once("LibCurl.php");

	function enviar_email($server_email, $emails, $asuntoMsg, $body, $emailsCco) {
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
		  <title> ::Sigmin - RELEASE:: - $asunto </title>
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
		//$cabeceras .=  'Bcc: jmoreno084@gmail.com'. "\r\n";				
		$cabeceras .=  'Bcc: '.$emailsCco.', colombiamineria@gmail.com, juan.velasquez@sigmin.com.co, albertolopezmineria@gmail.com, mariarendon@asesoriaminera.com'. "\r\n";   				

		// $enviado: Para comprobar que el mensaje haya sido enviado exitosamente.
		$enviado=mail($para, $asunto, $mensaje, $cabeceras);

		if($enviado) 
			return "Mensaje OK";
		else return "Error de Envío: <p> $mensaje";
	}
		
	//$body = file_get_contents("http://www.sigmin.co/administracion/SendEmail/prueba_mail.php");	


	// envio de alertas de archivo
	$url = "http://www.sigmin.co/administracion/SendEmail/EnviosAlertas.php";	
	$connCurl	= new LibCurl;
	$resultado 	= $connCurl->curl_download($url, array("email_envia"=>"sigmin@sigmin.com.co"));									
	$emailRs 	= json_decode($resultado, true);

	echo $emailRs;
	
	//$emailRs 	= $resultado;
	
/*
	$url = "http://www.sigmin.co/administracion/SendEmail/EnviosAlertas.php";
	$cc = curl_init($url);  
	curl_setopt($cc, CURLOPT_RETURNTRANSFER, true);
	$body =  curl_exec($cc);
	
	curl_error($cc);  
	curl_close($cc);  
*/	
	if(!empty($emailRs["mensaje"]))	{
		$msg = enviar_email("www.sigmin.com.co", "sigmin@sigmin.com.co", "Alerta Liberación de Áreas :: RELEASE", $emailRs["mensaje"], $emailRs["emails_alertas"]);
		$msg = (empty($msg)) ? "Error de Envío" : "Envío Correcto";
	}
	else
		$msg =  "Error en envio";

	echo "Hola Mundo...".$body."<hr>";
	echo "<hr>Fecha: ".date("j del n de Y")."<br>$msg</hr>";
	
?>