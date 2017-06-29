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
		$cabeceras .= 'Bcc: jmoreno084@gmail.com,contactenos@sigmin.com.co,colombiamineria@gmail.com'. "\r\n"; 
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
		<title>SIGMIN :: Creación de Cuenta </title>
		</head>
		<body>
		<table border="0" align="center" width="600">
		<tr>
		<td>
		<center><h1>Bienvenido(a) al Catastro Minero Online, SIGMIN -<h1></center>	
		<hr size="0">

		<p>Apreciado(a) '.strtoupper(utf8_decode($_POST["nombre"])).',
		<p>Con una cuenta en SIGMIN usted tendr&aacute; acceso al Catastro Minero Online, donde encontrar&aacute; la informaci&oacute;n actualizada de solicitudes y t&iacute;tulos mineros de Colombia, con la posibilidad de visualizarlos f&aacute;cilmente sobre los mapas de Google.</p>
		<p>Para activar su <i><b>CUENTA GRATUITA</b></i> haga <b><a href="http://www.sigmin.co/finder/registrese_en_sigmin.php?'.$_POST["urlActivaUsuario"].'" style="text-decoration:none;" target="_top">CLICK AQU&Iacute;</a></b></p>
		<p> RECUERDE: Su usuario (username) de acceso al sistema es: <i><u>'.$_POST["email"].'</u></i></p>
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
	$msg = enviar_email("www.sigmin.com.co", $listaEmails, "Creación de Cuenta :: SIGMIN", $mensaje);

	$msgJson["estado_envio"] 	= $msg;
	$msgJson["fecha_envio"] 	= date("j del n de Y");
				
	$json = json_encode($msgJson);
	echo $json;	

?>