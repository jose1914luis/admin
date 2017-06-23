<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");
	
	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
		
	$msgAcceso = "";
	
	if (isset($_POST["pwdAnterior"])) {
		$msgAcceso = $validate->updatePasswd($_SESSION["usuario_cmq"], $_POST["pwdAnterior"], $_POST["pwdNew1"]);
		$accionPage = new SeguimientosUsuarios;
		$accionPage->generarAccion("Cambio de clave de usuario");			
	}

?>	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Gesti&oacute;n de Proyectos Mineros</title>
<script>
	function verificaPwds() {
		if(document.forms[0].pwdAnterior.value=="") {
			alert("Clave anterior está vacía");
			return 0;
		}

		if(document.forms[0].pwdNew1.value=="") {
			alert("La nueva clave está vacía");
			return 0;
		}
		
		if(document.forms[0].pwdNew1.value == document.forms[0].pwdNew2.value)
			document.forms[0].submit();
		else
			alert("Las nuevas claves no coinciden");
	}
</script>
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
<table width="860" border="0" align="center" cellpadding="0" cellspacing="3">
			  <tr>
				<td colspan="3"><div align="right"><img src="imgs/logoQuimbaya.jpg" width="294" height="68" /></div></td>
			  </tr>  
			  <tr>
				<td width="9%"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="195" height="51" border="0" /></a></div></td>
				<td colspan="2" bgcolor="#2872A3"><div align="center"><img src="imgs/textoCMQ.jpg" width="562" height="51" /></div></td>
			  </tr>
  
  <tr>
    <td colspan="3"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#D60B0A">
      <tr>
        <td><p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <form id="form1" name="form1" method="post" action="">
            <table width="244" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td bgcolor="#672322"><div align="center"><img src="imgs/cambioClave.jpg" width="155" height="22" /></div></td>
              </tr>
              <tr>
                <td background="imgs/claveAcceso.jpg"><div align="center" class="Estilo1">
                  <table width="100%" border="0" cellspacing="5" cellpadding="0">
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><div align="center" class="Estilo1">Clave Anterior </div></td>
                    </tr>
                    <tr>
                      <td><div align="center">
                        <input name="pwdAnterior" type="password" id="pwdAnterior" />
                      </div></td>
                    </tr>
                    <tr>
                      <td><div align="center" class="Estilo1">Nueva Clave </div></td>
                    </tr>
                    <tr>
                      <td class="Estilo1"><div align="center">
                        <input name="pwdNew1" type="password" id="pwdNew1" />
                      </div></td>
                    </tr>
                    <tr>
                      <td><div align="center" class="Estilo1">Nueva Clave Again </div></td>
                    </tr>
                    <tr>
                      <td><div align="center">
                        <input name="pwdNew2" type="password" id="pwdNew2" />
                      </div></td>
                    </tr>
                    <tr>
                      <td><div align="center"></div></td>
                    </tr>
                    
                    <tr>
                      <td><div align="center">
                        <input type="button" name="btnValida" value="Ejecutar Cambio" onclick="verificaPwds()"/>
                      </div></td>
                    </tr>
                  </table>
                  </td>
              </tr>
              
              <tr>
                <td bgcolor="#B5975C">&nbsp;</td>
              </tr>
            </table>
                    </form>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php
	if($msgAcceso!="")
		echo $msgAcceso;
?>
</body>
</html>
