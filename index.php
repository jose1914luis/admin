<?php 
	session_start(); 
	require_once("Acceso/Config.php"); 
	
	//require_once('recaptcha/recaptchalib.php');

	// Definición de las variables globales 
	$msgAcceso = ""; 

/*
	// Get a key from https://www.google.com/recaptcha/admin/create
	$publickey = "6Lc7c9ESAAAAADp2w51MWnzDLstVbm-w6aFGwpOu";
	$privatekey = "6Lc7c9ESAAAAAOl8hX99-0CGxUZ6xqbOgOckk6wU";


	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;
*/
	
	
	# was there a reCAPTCHA response?
	if (@$_POST["captcha"]) {
/*		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);

		if ($resp->is_valid) {
*/
	if (!empty($_SESSION['captcha']) && trim(strtolower($_REQUEST['captcha'])) == $_SESSION['captcha']) {				
			if (isset($_POST["btnValida"])) { 
				require_once("Modelos/Usuarios.php"); 
				$validate = new Usuarios(); 
				if($validate->validaPasswd($_POST["usuario"],$_POST["clave"])) { 
					$_SESSION['usuario_cmq'] = $_POST["usuario"]; 
					$_SESSION['passwd_cmq'] = $_POST["clave"]; 
					header("Location: menuPrincipal.php"); 
				} else { 
					$msgAcceso="<script>alert('Usuario o clave invalidos')</script>"; 
				} 
			} 
		} else {
				# set the error code so that we can display it
				// $error = $resp->error;
				$msgAcceso =   "<script>alert('C\u00F3digo de verificaci\u00F3n incorrecto')</script>"; 				
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Gestión de Proyectos Mineros</title>
<style type="text/css">
<!--
.Estilo1 {
font-family: Verdana, Arial, Helvetica, sans-serif;
font-weight: bold;
font-size: 16px;
}
-->
</style>
</head>	



<body>
<table align="center" border="0" cellpadding="0" cellspacing="3" width="860">
<tbody>
  <tr>
    <td colspan="3"><div align="right"><img src="imgs/logoQuimbaya.jpg" width="294" height="68" /></div></td>
  </tr>  
  <tr>
    <td width="9%"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="195" height="51" border="0" /></a></div></td>
    <td colspan="2" bgcolor="#2872A3"><div align="center"><img src="imgs/textoCMQ.jpg" width="562" height="51" /></div></td>
  </tr>
<tr>
<td colspan="3">
<table border="1" bordercolor="#D60B0A" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<form id="form1" name="form1" method="post" action="">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="244">
<tbody>
<tr>
<td bgcolor="#672322">
<div align="center"><img src="imgs/accesoSistema.jpg" height="22" width="163" /></div>
</td>
</tr>
<tr>
<td background="imgs/claveAcceso.jpg">
<div class="Estilo1" align="center">
<table border="0" cellpadding="0" cellspacing="5" width="100%">
<tbody>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>
<div class="Estilo1" align="center">Usuario</div>
</td>
</tr>
<tr>
<td>
<div align="center"> <input name="usuario" type="text" /> </div>
</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td class="Estilo1">
<div align="center">Password</div>
</td>
</tr>
<tr>
<td>
<div align="center"> <input name="clave" id="pwd_cmq" type="password" /> </div>
</td>
</tr>
<tr>
<td>
	<br/>
	<br/>
	<?php 
		// echo recaptcha_get_html($publickey, $error); 
	?>
	<center>
	<div>
		<img src="http://www.sigmin.co/finder/captcha.php" id="captcha" /><br/>
		<input type="text" name="captcha" id="captcha-form" autocomplete="off" placeholder="Type the Text"/> &nbsp;	
		<a href="javascript:" style="text-decoration:none" onclick="document.getElementById('captcha').src='http://www.sigmin.co/finder/captcha.php?'+Math.random();	document.getElementById('captcha-form').focus();"	id="change-image"><img width="25" height="17" src="http://www.google.com/recaptcha/api/img/red/refresh.gif" title="Get a new challenge"></a>														
	</div>	
	</center>
</td>
</tr>
<tr>
<td>
<div align="center"> <input name="btnValida" value="Iniciar Sesión" type="submit" /></div>
</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>
</td>
</tr>
<tr>
<td bgcolor="#b5975c">&nbsp;</td>
</tr>
</tbody>
</table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<?php if($msgAcceso!="") echo $msgAcceso; ?>
</body></html>
