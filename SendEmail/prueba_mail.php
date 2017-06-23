<?php

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
			$cabeceras .= 'From: SIGMIN - Mining Properties'."\r\n";

			// $enviado: Para comprobar que el mensaje haya sido enviado exitosamente.
			$enviado=mail($para, $asunto, $mensaje, $cabeceras);

			if($enviado) 
				return "Mensaje OK";
			else return "Error de Envío: <p> $mensaje";
		}
		
		echo enviar_email("www.sigmin.co", "jmoreno084@gmail.com, jaime.moreno@anm.gov.co, cmqpru@sigmin.co", "Prueba envio", "Esto es una prueba de envio");

?>