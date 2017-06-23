<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("/home/sigmin/public_html_services/Modelos/PagosServicios.php");
	require_once("Modelos/Usuarios.php");
	require_once("Modelos/SeguimientosUsuarios.php");		
	

	$placa = "";
	$msgError = "";
	$clasificacion = "";
	$tituloClasificacion = "";
	$observacion = "";
	
	$validate = new Usuarios();	
	$accionPage = new SeguimientosUsuarios;
	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["estado"])&&($_POST["estado"])=="BUSCAR") {	
		$accionPage->generarAccion("Consulta Web Detallada de Ventas.");	

	
		$fechaIni = trim($_POST["fechaIni"]);
		$fechaFin = trim($_POST["fechaFin"]);			

		$pagos 			= new PagosServicios();
		$listadoPagos 	= $pagos->getPagos($fechaIni, $fechaFin);	

		$nroPagos = sizeof($listadoPagos);
		$tabla ="<table border='1'><tr bgcolor='#DFDFDF'>";	
		foreach($listadoPagos[0] as $k=>$v)
			$tabla .= "<td align='center'><b>".$k."</b></td>";
		$tabla .= "</tr>";	
		
		for($i=0;$i<$nroPagos;$i++) {
			$tabla .= "<tr>";
			foreach($listadoPagos[$i] as $k=>$v)
				$tabla .= "<td>".$v."</td>";
			$tabla .= "</tr>";	
		}		
		$tabla .= "</table>";
		$tabla = utf8_decode($tabla);
		
		$_SESSION["tabla_ventas"] = $tabla;
		
	} 


	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Gesti&oacute;n de Proyectos Mineros</title>
<script>
	
	function getReporteVentas() {
		// reemplazar caracter '-' por '/'
		document.forms[0].fechaIni.value = document.forms[0].fechaIni.value.replace(/[-]/gi,"/");
		document.forms[0].fechaFin.value = document.forms[0].fechaFin.value.replace(/[-]/gi,"/");
	
		// validacion de la fecha inicial:
		patron = /^\d{1,2}\/\d{1,2}\/\d{2,4}([\s](0[1-9]|1\d|2[0-3]):([0-5]\d):([0-5]\d))*$/;
		if (document.forms[0].fechaIni.value.search(patron)<0&&document.forms[0].fechaIni.value!="") {
			alert("El campo 'Fecha Inicial' se encuentra vacio o no posee un formato válido");
			document.forms[0].fechaIni.select();
			return 0;
		}
		
		// validacion de la fecha de resolucion:
		patron = /^\d{1,2}\/\d{1,2}\/\d{2,4}([\s](0[1-9]|1\d|2[0-3]):([0-5]\d):([0-5]\d))*$/;
		if (document.forms[0].fechaFin.value.search(patron)<0&&document.forms[0].fechaFin.value!="") {
			alert("El campo 'Fecha Final' se encuentra vacio o no posee un formato válido");
			document.forms[0].fechaFin.select();
			return 0;
		}		
		
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
              <td colspan="2" bgcolor="#D60B0A" class="Estilo1"><div align="center" class="Estilo2">REPORTE DE PAGOS ELECTR&Oacute;NICOS </div></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2"><span class="Estilo3">Intervalo de Fechas [dd/mm/aaaa]</span>:&nbsp; &nbsp;&nbsp;
				<input name="fechaIni" type="text" id="fechaIni" value="<?php echo @$fechaIni;?>" <?php if(@$fechaIni) echo "readonly='readonly'" ?>  size="12"/> - 
				<input name="fechaFin" type="text" id="fechaFin" value="<?php echo @$fechaFin;?>" <?php if(@$fechaFin) echo "readonly='readonly'" ?> size="12" /> 
				&nbsp;&nbsp;<input type="button" name="buscar" value="Buscar" onclick="getReporteVentas()"/>                
                <input name="estado" type="hidden" id="estado" value="BUSCAR" />
				&nbsp;&nbsp;&nbsp;<a href="CMQ_PagosOnline.php"><strong>[Nuevo Reporte]</strong></a>
                <div align="left">&nbsp;</div></td>
              </tr>
            
            <tr>
              <td colspan="2"><hr size="1" /></td>
            </tr>
<?php
	if(isset($_POST["estado"]) && $_POST["estado"]=="BUSCAR" && !$msgError) {
?>
            <tr>
              <td width="47%" class="Estilo3">&nbsp;</td>
              <td width="53%">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" class="Estilo3">
			  <h2><a href='CMQ_PagosOnline_Excel.php' style='text-decoration:none' title="Descarga del reporte de ventas de servicios SIGMIN">Descargar Reporte en Excel</a></h2>
			  <p/>
			  <?php
				echo $tabla;
			  ?>
			  
			  </td>
            </tr>

<?php 
		} 
?>			
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
