<?php
	require_once("Acceso/Config.php");
	require_once("Modelos/AnotacionesRMN.php");	
	require_once("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");

	$anotacion = new AnotacionesRMN();
	
	// Procesamiento de expedientes, que pueden ser titulos o solicitudes	
	$listaAnotaciones = $anotacion->selectByPlaca($placa);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>:: CMQ :: Reporte de Expedientes</title>
<style type="text/css">
<!--
.Estilo1 {
	color: #672225;
	font-weight: bold;
}

.tituloArea {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="860" border="0" align="center" cellpadding="0" cellspacing="5">
  
  <tr>
    <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">ANOTACIONES ASOCIADAS AL EXPEDIENTE  <?php echo $placa; ?></div></td>
  </tr>
  <tr>
    <td width="64" bgcolor="#F2EDE1"><div align="center"><strong>No.</strong></div></td>
    <td width="149" bgcolor="#F2EDE1"><div align="center"><strong>Fecha Anotaci&oacute;n </strong></div></td>
    <td width="120" bgcolor="#F2EDE1"><div align="center"><strong>Fecha Ejecutoria </strong></div></td>
    <td width="177" bgcolor="#F2EDE1"><div align="center"><strong>Tipo Anotaci&oacute;n </strong></div></td>
    <td colspan="2" bgcolor="#F2EDE1"><div align="center"><strong>Observaci&oacute;n</strong></div></td>
  </tr>
<?php
	$nroAnotacion = 1;
	foreach($listaAnotaciones as $cadaAnotacion) {
?>
  <tr>
    <td align="center"><?php echo $nroAnotacion++; ?></td>
    <td align="center"><?php echo $cadaAnotacion["fecha_anotacion"]; ?>&nbsp;</td>
    <td align="center"><?php  echo $cadaAnotacion["fecha_ejecutoria"]; ?>&nbsp;</td>
    <td align="center"><?php  echo utf8_decode($cadaAnotacion["tipo_anotacion"]); ?>&nbsp;</td>
    <td width="131" colspan="2"><?php  echo utf8_decode($cadaAnotacion["observacion"]); ?>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
<?php
	}
?>

</table>

</body>
</html>
