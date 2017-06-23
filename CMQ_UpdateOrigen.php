<?php
	session_start();

	require("Acceso/Config.php");
	require("Modelos/Solicitudes.php");
	require("Modelos/SolicitudesArcifinios.php");
	require("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");
	
	// require_once('recaptcha/recaptchalib.php');

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	// Definición de variables generales:
	$placa 			= "";
	$msgProceso 	= "";
	$clasificacion 	= "PROSPECTO";
	

	// Variable de Captcha	
	// Get a key from https://www.google.com/recaptcha/admin/create
	$publickey = "6Lc7c9ESAAAAADp2w51MWnzDLstVbm-w6aFGwpOu";
	$privatekey = "6Lc7c9ESAAAAAOl8hX99-0CGxUZ6xqbOgOckk6wU";
	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;
	# was there a reCAPTCHA response?
/*	if (@$_POST["recaptcha_response_field"]) {
		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);
*/										
		if (1==1) {  //$resp->is_valid) {	
			if(@$_POST["guardarPoly"]=="YES"&&@trim($_POST["codigoExpediente"])!="") {		
			
				$placa  		=  $_POST["codigoExpediente"]; 
				$origen			=  $_POST["sistemaOrigen"]; 
				
				$solicitud		= new Solicitudes;
				$solOrigen 		= new SolicitudesArcifiniosTMP;
				$resultado 		= $solOrigen->setSistemaOrigen($placa, $origen);
				

				
				if($resultado=="O.K") {
					$msgProceso 	= "<script>alert('Se ha cambiado satisfactoriamente el Origen del expediente $placa')</script>";
					$idSolicitud 	= $solicitud->getIdPlaca($placa);
					$clasificacion 	= !empty($idSolicitud) ? "SOLICITUD" : "TITULO";
					
					$accionPage 	= new SeguimientosUsuarios;
					$accionPage->generarAccion("Cambio de Origen del expediente '$placa'");
				} else 
					$msgProceso = "<script>alert('::ERROR:: El Expediente $placa no actualizó origen satisfactoriamente. Mensaje Error: $resultado')</script>";
			}
		} else {
				# set the error code so that we can display it
				//$error = $resp->error;
				$msgProceso =  "<script>alert('Código de verificación incorrecto')</script>";
		}
	//}			
			

?>

<html>
<head>
<script>

	function saveOrigen() {
		if(document.forms[0].codigoExpediente.value!="") {
			document.forms[0].guardarPoly.value='YES'			
			document.forms[0].submit();
		}
		else
			alert("No ha ingresado aún la placa");			
	}	
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<link rel="stylesheet" type="text/css" href="../css/layouts.css"/>
<link rel="stylesheet" type="text/css" href="../css/general.css"/>
<script type="text/javascript" src="../js/general.js"></script>
<title>:: CMQ :: Actualizacion del Sistema de Origen</title>
<style type="text/css">
<!--
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 14px;
}
.Estilo4 {
	font-size: 16px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #FFFFFF;
}
.Estilo5 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
}
.Estilo6 {font-family: Verdana, Arial, Helvetica, sans-serif}
-->
</style>
</head>

<body>
	<form method="post" action="CMQ_UpdateOrigen.php">		
	  <table width="1000" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="3">
			  <tr>
				<td colspan="3"><div align="right"><img src="imgs/logoQuimbaya.jpg" width="294" height="68" /></div></td>
			  </tr>  
			  <tr>
				<td width="9%"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="195" height="51" border="0" /></a></div></td>
				<td colspan="2" bgcolor="#2872A3"><div align="center"><img src="imgs/textoCMQ.jpg" width="562" height="51" /></div></td>
			  </tr>
		  </table></td>
		</tr>
		<tr>
		  <td bgcolor="#D60B0A"><div align="left"><span class="Estilo4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACTUALIZAR SISTEMA DE ORIGEN </span></div></td>
		</tr>
		<tr>
		  <td><hr size="1"></td>
		</tr>
		<tr>
		  <td><span class="Estilo5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C&oacute;digo del Expediente:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo6">&nbsp;</span>        
				<input type="text" name="codigoExpediente" value="<?php echo $placa ?>" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Sistema de Origen:</span>
			<select name="sistemaOrigen">
			  <option value="OESTE" selected="selected">OESTE</option>
			  <option value="BOGOTA">CENTRO</option>
			  <option value="ESTE-ESTE">ESTE-ESTE</option>
			  <option value="ESTE">ESTE</option>
			  <?php
				if(isset($_POST["sistemaOrigen"]))
					echo '<option value="'.$_POST["sistemaOrigen"].'" selected>'.$_POST["sistemaOrigen"].'</option>';
			  ?>	
		  </select></td>
		</tr>
		<tr>
		  <td><hr size="1" /></td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="button" type="button" onClick="saveOrigen()" value="Cambiar Origen" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="CMQ_UpdateOrigen.php" class="Estilo1">[NUEVA ACTUALIZACI&Oacute;N DE ORIGEN]</a>
			</td>
		</tr>
		<tr>
		  <td><hr size="1" /></td>
		</tr>	
	  </table>
	  <table width="1000" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="100%" valign="top">
				  <?php 						
						$url = "visorCapturas/visualizaPoligono.php?codExpediente=$placa&clasificacion=$clasificacion";
				  ?>
				  <iframe border=1 src="<?php echo $url ?>" width="550" height="350"></iframe>
			</td>
		</tr>
	<!--
		<tr>
		  <td colspan="3" valign="top">
		  <hr size=1>
		  <?php 
				// echo recaptcha_get_html($publickey, $error); 
		  ?>	  	  	  
		  <hr size=1></td>
		</tr>
	-->
		<tr>
		  <td colspan="3" valign="top"><hr size=1></td>
		</tr>
	  </table>
	  <input type="hidden" name="guardarPoly" value="NO">
	</form>
<?php
	if($msgProceso!="") 
		echo $msgProceso;
?>
</body>
</html>
