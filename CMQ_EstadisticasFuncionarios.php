<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Usuarios.php");
	require_once("Modelos/SeguimientosUsuarios.php");		
	

	$placa = "";
	$msgError = "";
	$clasificacion = "";
	$tituloClasificacion = "";
	$observacion = "";
	
	$validate = new Usuarios();	
	$accionPage = new SeguimientosUsuarios;
	$accionPage->generarAccion("Consulta de Tareas Funcionarios.");
	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(!empty($_POST["tipo_analisis"])) {	
		$listaTareas = $accionPage->getTareasFuncionarios(@$_POST["fechaIni"], @$_POST["fechaFin"], @$_POST["tipo_analisis"]);
		
		$cadaFecha = "";
		foreach($listaTareas as $cadaTarea) {
			if($cadaFecha == "" || $cadaFecha != $cadaTarea["fecha"]) {
				$cadaFecha = $cadaTarea["fecha"];
				$cadaTipoOperacion = $cadaTarea["tipo_operacion"];			
			} else if($cadaTipoOperacion != $cadaTarea["tipo_operacion"]) {
				$cadaTipoOperacion = $cadaTarea["tipo_operacion"];
			} 
			
			$indiceTareas[$cadaFecha]["rowspan"] ++;
			$indiceTareas[$cadaFecha][$cadaTipoOperacion]["rowspan"] ++;
			
			$indiceTareas[$cadaFecha]["total"] += $cadaTarea["total"];
			$indiceTareas[$cadaFecha][$cadaTipoOperacion]["total"] += $cadaTarea["total"];
		}
			
		//echo "<pre>".print_r($indiceTareas, true)."</pre>";
		$cadaFecha = "";
		$tabla = "
					<table border='1' width='95%' align='center' cellpadding='0' cellspacing='0'>
						<tr bgcolor='#ededed'>
							<th>PERIODO</th>
							<th>OPERACI&Oacute;N</th>
							<th>FUNCIONARIO</th>
							<th>TOTAL OPERACI&Oacute;N</th>
							<th>PORCENTAJE OPERACI&Oacute;N</th>
						</tr>
		";
		foreach($listaTareas as $cadaTarea) {
			if($cadaFecha == "" || $cadaFecha != $cadaTarea["fecha"]) {
				$cadaFecha = $cadaTarea["fecha"];
				$cadaTipoOperacion = $cadaTarea["tipo_operacion"];
				$tabla .= "<tr align='center'><td rowspan='{$indiceTareas[$cadaFecha]["rowspan"]}'>$cadaFecha</td><td rowspan='{$indiceTareas[$cadaFecha][$cadaTipoOperacion]["rowspan"]}'>$cadaTipoOperacion</td>";	
			} else if($cadaTipoOperacion != $cadaTarea["tipo_operacion"]) {
				$cadaTipoOperacion = $cadaTarea["tipo_operacion"];
				$tabla .= "<tr align='center'><td rowspan='{$indiceTareas[$cadaFecha][$cadaTipoOperacion]["rowspan"]}'>$cadaTipoOperacion</td>";			
			} else $tabla .= "<tr align='center'>";
			
			$tabla .= "<td>{$cadaTarea["login"]}</td><td>{$cadaTarea["total"]}</td><td>".(round(100*$cadaTarea["total"]/$indiceTareas[$cadaFecha][$cadaTipoOperacion]["total"],2))." %</td></tr>";		
		}
		$tabla .= "</table>";
		
		$_SESSION["tabla_tareas"] = $tabla;
	}		
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SIGMIN :: Análisis Estadístico de Funcionarios SIGMIN</title>
<script>
	
	function getReporteConsultas() {
		alert("La consulta puede tardar algún tiempo...");
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
		
		// validacion de la fecha final:
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
        <td><form id="form1" name="form1" method="post" action="CMQ_EstadisticasFuncionarios.php">
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
            <tr>
              <td colspan="2" bgcolor="#D60B0A" class="Estilo1"><div align="center" class="Estilo2">AN&Aacute;LISIS ESTAD&Iacute;STICO DE FUNCIONARIOS SIGMIN</div></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
				<td><span class="Estilo3">Fechas de an&aacute;lisis [dd/mm/aaaa]</span>:</td>
				<td>
					<input name="fechaIni" type="text" id="fechaIni" value="<?php if(@$fechaIni) echo @$fechaIni; else echo '01/01/2016'; ?>" size="12" placeholder="Fecha Inicio"/> - 
					<input name="fechaFin" type="text" id="fechaFin" value="<?php if(@$fechaFin) echo @$fechaFin; else echo date("d/m/Y"); ?>" size="12" placeholder="Fecha Fin"/>
				</td>
			</tr>	
			<tr>
				<td>
					<span class="Estilo3">Tipo de An&aacute;lisis:</span>
				</td>
				<td>
						<select name="tipo_analisis">
							<option value="YYYY-MM">Análisis por Mes</option>
							<option value="YYYY-MM-DD">Análisis por Día</option>
						</select><br/>
				</td>
			</tr>
            <tr>
              <td colspan="2"><hr size="1" /></td>
            </tr>
			<tr>
				<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="buscar" value="Buscar" onclick="getReporteConsultas()"/>                
					<input name="estado" type="hidden" id="estado" value="BUSCAR" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="CMQ_EstadisticasFuncionarios.php"><strong>[Nuevo Reporte]</strong></a>
				</td>
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
              <td colspan="2">
			  <h2><a href='CMQ_EstadisticasFuncionarios_Excel.php' style='text-decoration:none' title="Descarga del reporte de tareas de funcionarios SIGMIN">Descargar Reporte en Excel</a></h2>
			  <p/>
				<?=$tabla;?>			  
			  </td>
            </tr>

<?php 
		} 
?>			
          </table>
                  </form>
		  </td>
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
