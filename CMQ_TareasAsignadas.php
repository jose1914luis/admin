<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/AsignacionesTareas.php");
	require_once("Modelos/Usuarios.php");

	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["codExpediente"])&&trim($_POST["codExpediente"])!="") {		

	
	} else {
		$asignaciones = new AsignacionesTareas();
		$listaTareas  = $asignaciones->selectAsignacionesByUsuario($_SESSION["usuario_cmq"]);		
	}
	


	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Gesti&oacute;n de Proyectos Mineros</title>
<script>
	function procesoGrafico(placa, tarea) {
		document.forms[0].codigoExpediente.value=placa;
		if(tarea=="Capturar Polígono")		
			document.forms[0].action = "CMQ_Coords.php"
		else
			document.forms[0].action = "menuPrincipal.php";
		document.forms[0].submit();
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
.Estilo4 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; }
.Estilo5 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
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
              <td width="100%" bgcolor="#D60B0A" class="Estilo1"><div align="center" class="Estilo2">LISTADO DE TAREAS ASIGNADAS A:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&quot; <?php echo  $_SESSION["usuario_cmq"]; ?> &quot; </div></td>
            </tr>
            <tr>
              <td><input type="hidden" name="codigoExpediente"/></td>
            </tr>
            <tr>
              <td><table width="100%" border="1" cellspacing="0" cellpadding="0">
                <tr>
                  <td bgcolor="#E5E5E5" class="Estilo4"><div align="center">No</div></td>
				  <td bgcolor="#E5E5E5" class="Estilo4"><div align="center">Placa</div></td>
                  <td bgcolor="#E5E5E5" class="Estilo4"><div align="center">Tarea</div></td>
                  <td bgcolor="#E5E5E5" class="Estilo4"><div align="center">Fecha Asignaci&oacute;n </div></td>
                  <td bgcolor="#E5E5E5" class="Estilo4"><div align="center">Tipo de Expediente </div></td>
                </tr>
<?php
	if(isset($listaTareas) && $listaTareas) {
		$nroTareas = 1;
		foreach($listaTareas as $cadaTarea) {
?>
                <tr>
				  <td bgcolor="#E5E5E5" class="Estilo4"><div align="center"><?php echo $nroTareas++; ?></div></td>
                  <td><div align="center"><a href="javascript:" onclick="procesoGrafico('<?php echo  utf8_decode($cadaTarea["placa"]); ?>','<?php echo  utf8_decode($cadaTarea["tarea"]); ?>')"><?php echo  utf8_decode($cadaTarea["placa"]); ?></a></div></td>
                  <td><div align="center"><?php echo  utf8_decode($cadaTarea["tarea"]); ?></div></td>
                  <td><div align="center"><?php echo  utf8_decode($cadaTarea["fecha_asigna"]); ?></div></td>
                  <td><div align="center"><?php echo  utf8_decode($cadaTarea["tipo_expediente"]); ?></div></td>
                </tr>
<?php
		}
	} else {
?>
<tr><td colspan="5"><div align="center" class="Estilo5">No tiene tareas asociadas</div></td>
</tr>
<?php
	}
?>				
              </table></td>
            </tr>
            <tr>
              <td>&nbsp;&nbsp;</td>
            </tr>       
          </table>
                  </form>
          </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
