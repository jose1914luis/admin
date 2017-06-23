<?php
	session_start();

	require("Acceso/Config.php");
	require("Modelos/ConsultasCMQ.php");
	require("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");
	
	//require_once('recaptcha/recaptchalib.php');	

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	
	$placa = "";
	$msgProceso = "";
	$tabla = "";
	
	$consultar = new ConsultasCMQ;

	// Variable de Captcha	
	// Get a key from https://www.google.com/recaptcha/admin/create
	$publickey = "6Lc7c9ESAAAAADp2w51MWnzDLstVbm-w6aFGwpOu";
	$privatekey = "6Lc7c9ESAAAAAOl8hX99-0CGxUZ6xqbOgOckk6wU";
	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;
	# was there a reCAPTCHA response?
	if (1==1) { //(@$_POST["recaptcha_response_field"]) {
		/* @$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);
		*/
		if (1==1) { //($resp->is_valid) {		
			if(@$_POST["ejecutaBusqueda"]=="YES") {
				// Procesamiento de solicitudes	
				$listadoSolicitudes = $consultar->selectSolicitudesConsultas($_POST["codExpediente"], $_POST["mineral"], $_POST["mpio"], $_POST["depto"], $_POST["persona"]);	
				
				$accionPage = new SeguimientosUsuarios;
				$accionPage->generarAccion("Consulta Detallada en CMQ por solicitudes, titulos y prospectos.");


				if(!empty($listadoSolicitudes)){		
					$nroSolicitudes = sizeof($listadoSolicitudes);
					$tabla ="<h3>B&uacute;squeda Solicitudes</h3><table border='1'>";
					$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";
				
					foreach($listadoSolicitudes[0] as $k=>$v)
						$tabla .= "<td align='center'><b>".$k."</b></td>";
					$tabla .= "</tr>";	
					
					for($i=0;$i<$nroSolicitudes;$i++) {
						if(!empty($listadoSolicitudes[$i]["centroide"]))
							$enlace = "<a href='#' onclick=\"consultarURL('".$listadoSolicitudes[$i]["placa"]."','SOLICITUD')\">[o]</a>";
						else
							$enlace = "&nbsp;";	
						$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
						foreach($listadoSolicitudes[$i] as $k=>$v)
							$tabla .= "<td>".$v."</td>";
						$tabla .= "<td><a href='http://www.sigmin.co/CMQ_Pruebas/IDB/reporteExpedientes.php?placa=".$listadoSolicitudes[$i]["placa"]."&tipoExpediente=SOLICITUD' target='_blank' >Report</a></td>";
							
						$tabla .= "</tr>";	
					}	
						
					$tabla .= "</table>";	
				}
				
				//Procesamiento de titulos		
				
				$listadoTitulos = $consultar->selectTitulosConsultas($_POST["codExpediente"], $_POST["mineral"], $_POST["mpio"], $_POST["depto"], $_POST["persona"]);		

				if(!empty($listadoTitulos)){
					$nroTitulos = sizeof($listadoTitulos);
					$tabla .="<hr size='1'><h3>B&uacute;squeda T&iacute;tulos</h3><table border='1'>";
					$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";

					foreach($listadoTitulos[0] as $k=>$v)
						$tabla .= "<td align='center'><b>".$k."</b></td>";
					$tabla .= "</tr>";	
					
					for($i=0;$i<$nroTitulos;$i++) {
						if(!empty($listadoTitulos[$i]["centroide"]))
							$enlace = "<a href='#' onclick=\"consultarURL('".$listadoTitulos[$i]["placa"]."','TITULO')\">[o]</a>";
						else
							$enlace = "&nbsp;";	
						$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
						foreach($listadoTitulos[$i] as $k=>$v)
							$tabla .= "<td>".$v."</td>";
						$tabla .= "<td><a href='http://www.sigmin.co/CMQ_Pruebas/IDB/reporteExpedientes.php?placa=".$listadoTitulos[$i]["placa"]."&tipoExpediente=TITULO' target='_blank' >Report</a></td>";
							
						$tabla .= "</tr>";	
					}		
					$tabla .= "</table>";	
				}
				//Procesamiento de prospectos		
				
				$listadoProspectos = $consultar->selectProspectosConsultas($_POST["codExpediente"], $_POST["mpio"], $_POST["depto"]);		
				$nroProspectos = sizeof($listadoProspectos);

				if(!empty($listadoProspectos)){
					$tabla .="<hr size='1'><h3>B&uacute;squeda Prospectos</h3><table border='1'>";
					$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";

					foreach($listadoProspectos[0] as $k=>$v)
						$tabla .= "<td align='center'><b>".$k."</b></td>";
					$tabla .= "</tr>";	
					
					for($i=0;$i<$nroProspectos;$i++) {
						if(!empty($listadoProspectos[$i]["centroide"]))
							$enlace = "<a href='#' onclick=\"consultarURL('".$listadoProspectos[$i]["placa"]."','PROSPECTO')\">[o]</a>";
						else
							$enlace = "&nbsp;";	
						$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
						
						foreach($listadoProspectos[$i] as $k=>$v) {
							//if($k!="coordenadas_bog")
								$tabla .= "<td>".utf8_decode($v)."</td>";
							//else
							//	$tabla .= "<td><pre>".str_replace(" ",",",str_replace(",","\n",str_replace(")))","))", str_replace("MULTIPOLYGON(","",$v))))."</pre></td>";
						
						}
						$tabla .= "<td><a href='http://www.sigmin.co/CMQ_Pruebas/IDB/reporteExpedientes.php?placa=".$listadoProspectos[$i]["placa"]."&tipoExpediente=PROSPECTO' target='_blank' >Report</a></td>";
						$tabla .= "</tr>";	
					}
				}		
				$tabla .= "</table>";			
			}	
		} else {
				# set the error code so that we can display it
				// $error = $resp->error;
				$msgProceso =  "<script>alert('Código de verificación incorrecto')</script>";
		}
	}	
	
?>

<html xmlns:v>
<head>
<style>v\:*{behavior:url(#default#VML);position:absolute}</style>
<script src="Utilidades/jquery.min.js"></script>
<script>
	function consultarURL(referenciaMapa, clasificacion) {
		iframe2 = document.getElementById('iframecontenido');		
		iframe2.src = "visorCapturas/visualizaPoligono.php?codExpediente=" + referenciaMapa+"&clasificacion="+clasificacion;
			
		iframe = document.getElementById('iframecontenido2');		
		iframe.src = "http://esmeralda2.anm.gov.co:8080/CMCSIG/faces/indexBuscarExpediente.jsp?codigoExpediente=" + referenciaMapa;

		iframe2.reload(); 
		iframe.reload(); 
	};

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />

<link rel="stylesheet" type="text/css" href="../css/layouts.css"/>
<link rel="stylesheet" type="text/css" href="../css/general.css"/>
<script type="text/javascript" src="../js/general.js"></script>
<title>:: CMQ :: Generación de Prospectos Mineros</title>
<script src="../js/AC_RunActiveContent.js" type="text/javascript"></script>
<style type="text/css">
<!--
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
-->
</style>
</head>

<body>
<div id="contenido">
<script>
	//	Eliminar cookies CMC
	Cookie.set("JSESSIONID",'',-1);
</script>
	<div id="menuNavegacion">
<form id="consulta" name="consulta" method="post" action="" enctype="application/x-www-form-urlencoded">
  <table width="1000" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="3">
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
      <td colspan="4" bgcolor="#D60B0A"><div align="left"><span class="Estilo4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONSULTAR EXPEDIENTES  </span></div></td>
    </tr>
    <tr>
      <td colspan="4"><hr size=1></td>
    </tr>
    
    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">C&oacute;digo de Expediente: </span></td>
      <td><input name="codExpediente" type="text" id="codExpediente" size="25"></td>
      <td class="Estilo5">Mineral:</td>
      <td><input name="mineral" type="text" id="mineral" size="30"></td>
    </tr>
    <tr>
      <td colspan="4"><hr size=1></td>
    </tr>
    <tr>
      <td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Municipio:</span></td>
      <td width="40%"><input name="mpio" type="text" id="mpio" size="35"></td>
      <td width="15%" class="Estilo5">Departamento:</td>
      <td width="25%"><input name="depto" type="text" id="depto" size="35"></td>
    </tr>
    <tr>
      <td colspan="4"><hr size=1></td>
    </tr>
    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Titular/Solicitante:</span></td>
      <td><input name="persona" type="text" id="persona" size="60"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">
	  <hr size=1>
	  <?php 
		//echo recaptcha_get_html($publickey, $error); 
	  ?>
	  <hr size="1"></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="buscar" value="Realizar Búsqueda" />
        <input name="ejecutaBusqueda" type="hidden" id="ejecutaBusqueda" value="YES"></td>
    </tr>
    <tr>
      <td colspan="4"><hr size="1"></td>
    </tr>
    <tr>
      <td colspan="4" bgcolor="#E6E6E6"><span class="Estilo5"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ubicacion del Area</strong></span></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="top"><iframe id="iframecontenido"  name="iframecontenido" src="visorCapturas/visorAreas.php?codigoExpediente=NoDefinido&centroideLon=-74&centroideLat=5&cobertura=solicitudes_col&tituloCobertura=Solicitudes&areaPoly=0.00" width="550" height="450"></iframe></td>
      <td colspan="2" align="left" valign="top"><iframe id="iframecontenido2"  name="iframecontenido2" src="http://esmeralda2.anm.gov.co:8080/CMCSIG/faces/indexBuscarExpediente.jsp?codigoExpediente=0000-00" width="650" height="450">
 
      </iframe></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" bgcolor="#E6E6E6"><span class="Estilo5"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Listado de Resultados </strong></span></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Descarga de Archivo en Excel: <strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="CMQ_ConsultarDetalleExcel.php?codExpediente=<?php echo (@$_POST["codExpediente"]!="")? $_POST["codExpediente"] : ""; ?>&mineral=<?php echo (@$_POST["mineral"]!="")? $_POST["mineral"] : ""; ?>&mpio=<?php echo (@$_POST["mpio"]!="")? $_POST["mpio"] : ""; ?>&depto=<?php echo (@$_POST["depto"]!="")? $_POST["depto"] : ""; ?>&persona=<?php echo (@$_POST["persona"]!="")? $_POST["persona"] : ""; ?>" target="_blank">Download_FILE</a></strong> </span></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">&nbsp;Criterios de Consulta: { <?php echo $consultar->criteriosConsulta() ?> } </span></td>
    </tr>
  </table>
  <table width="1000" border="0" cellspacing="0" cellpadding="0">
    
    <tr>
      <td width="100%" colspan="3" valign="top"><hr size=1></td>
    </tr>
  </table>
  <?php
		if($tabla)
			echo $tabla;	  
	  ?>
</form>
<?php
	if($msgProceso!="") 
		echo $msgProceso;
?>
</body>
</html>
