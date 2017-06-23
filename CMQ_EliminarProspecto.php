<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/ProspectosBog.php");
	require_once("Modelos/Usuarios.php");
	require_once("Modelos/SeguimientosUsuarios.php");		
	

	$placa = "";
	$msgError = "";
	$clasificacion = "";
	$tituloClasificacion = "";
	
	$validate = new Usuarios();	
	$accionPage = new SeguimientosUsuarios;
	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["codExpediente"])&&trim($_POST["codExpediente"])!="") {		
		$prospecto = new ProspectosBog($_POST["codExpediente"]);
		if(!$prospecto->existePlacaDelete($_POST["codExpediente"]))
			$msgError = "<script>alert('El Prospecto {$_POST["codExpediente"]} no existe en el sistema.');</script>";
		else {
			if($prospecto->deleteProspecto($_POST["codExpediente"])) {
				$msgError = "<script>alert('Prospecto {$_POST["codExpediente"]} eliminado satisfactoriamente.');</script>";
				$accionPage = new SeguimientosUsuarios;
				$accionPage->generarAccion("Eliminación del prospecto {$_POST["codExpediente"]}.");				
			}
			else
				$msgError = "<script>alert('Error durante el proceso de eliminación del prospecto {$_POST["codExpediente"]}')</script>";	
		}
	} 
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Gesti&oacute;n de Proyectos Mineros</title>
<script>
	function eliminarProspecto() {
		if(document.forms[0].codExpediente.value!="") {
			if(confirm("Este proceso es Irreversible, está seguro de eliminar el prospecto " + document.forms[0].codExpediente.value))
				document.forms[0].submit();
			else
				document.forms[0].reset();
			
		} else {
			alert("Se requiere el ingreso del codigo del prospecto");	
		}
	}	
</script>
<style type="text/css">
<!--
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 16px;
}
.Estilo2 {color: #FFFFFF}
.Estilo3 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
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
    <td colspan="3"><table width="100%" border="1" cellpadding="10" cellspacing="0" bordercolor="#D60B0A">
      <tr>
        <td><form id="form1" name="form1" method="post" action="">
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
            <tr>
              <td width="100%" bgcolor="#D60B0A" class="Estilo1"><div align="center" class="Estilo2">ELIMINAR PROSPECTO EXISTENTE EN CMQ  </div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><span class="Estilo3">Placa generada por el CMQ :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span> <input name="codExpediente" type="text" id="codExpediente" value="<?php echo $placa;?>" <?php if($placa) echo "readonly='readonly'" ?> />                
                <input type="button" name="buscar" value="Eliminar" onclick="eliminarProspecto()"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="estado" type="hidden" id="estado" value="ACTUALIZAR" />
                <a href="CMQ_EliminarProspecto.php"><strong>[Eliminar otro prospecto]</strong></a>
                <div align="left">&nbsp;</div></td>
              </tr>
			              <tr>
              <td><hr size="1" /></td>
            </tr>
          </table>
                  </form>
          <p>&nbsp;</p>
          <p>&nbsp;</p>          </td>
      </tr>
    </table></td>
  </tr>
</table>
<?php
	if($msgError!="")
		echo $msgError;
?>

</body>
</html>
