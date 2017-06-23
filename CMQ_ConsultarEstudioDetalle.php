<?php
	session_start();

	require_once("Acceso/Config.php");
	require_once("Modelos/ConsultasCMQ.php");
	require_once("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");
	
	// require_once('recaptcha/recaptchalib.php');		

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	
	$placa = "";
	$msgProceso = "";
	$tabla = "";
	$tipo_analisis = "SOLICITUD";
	$tipoEstudio = "ESTUDIO_TECNICO";
	
	$consultar = new ConsultasCMQ;
	if(@$_GET["tipo_analisis"]=="PROSPECTO") {
		$tipo_analisis = "PROSPECTO";	
		$tipoEstudio = "ESTUDIO_TECNICO_PROSPECTO";
	}
		
	

	// Variable de Captcha	
	// Get a key from https://www.google.com/recaptcha/admin/create
	$publickey = "6Lc7c9ESAAAAADp2w51MWnzDLstVbm-w6aFGwpOu";
	$privatekey = "6Lc7c9ESAAAAAOl8hX99-0CGxUZ6xqbOgOckk6wU";
	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;
	# was there a reCAPTCHA response?
	//if (@$_POST["recaptcha_response_field"]) {
/*		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);
*/
		if (1==1) //$resp->is_valid) 
			{
	
			if(@$_POST["ejecutaBusqueda"]=="YES") {
				
				if(@$_POST["tipo_analisis"]=='SOLICITUD') {
					$tipo_analisis = "SOLICITUD";
					$listadoEstudio = $consultar->selectEstudiosTecnicosConsultas($_POST["codExpediente"], $_POST["mineral"], $_POST["mpio"], $_POST["depto"], $_POST["persona"]);	
				}	else   {
					$tipo_analisis = "PROSPECTO";
					$listadoEstudio = $consultar->selectEstudiosTecnicosProspectos($_POST["codExpediente"], $_POST["mpio"], $_POST["depto"]);
				}
				
				$accionPage = new SeguimientosUsuarios;
				$accionPage->generarAccion("Consulta Detallada en CMQ de Estudios Tecnicos: Para $tipo_analisis.");

				if(!empty($listadoEstudio)){		
					$nroSolicitudes = sizeof($listadoEstudio);
					$tabla ="<h3>AN&Aacute;LISIS DE SUPERPOSICIONES</h3><table border='1'>";
					$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";
				
					foreach($listadoEstudio[0] as $k=>$v)
						$tabla .= "<td align='center'><b>".utf8_decode($k)."</b></td>";
					$tabla .= "</tr>";	
					
					for($i=0;$i<$nroSolicitudes;$i++) {
						if(!empty($listadoEstudio[$i]["area_estudio"]))
							$enlace = "<a href='#' onclick=\"consultarURL('".$listadoEstudio[$i]["area_estudio"]."','$tipoEstudio','$tipo_analisis')\">[o]</a>";
						else
							$enlace = "&nbsp;";	
						$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
						foreach($listadoEstudio[$i] as $k=>$v)
							$tabla .= "<td>".utf8_decode($v)."</td>";
						$tabla .= "<td><a href='http://www.sigmin.co/CMQ_Pruebas/IDB/reporteExpedientes.php?placa=".$listadoEstudio[$i]["area_estudio"]."&tipoExpediente=ESTUDIO_".$tipo_analisis."' target='_blank' >Report</a></td>";
							
						$tabla .= "</tr>";	
					}		
					$tabla .= "</table>";	
				}
			}					

		} else {
				# set the error code so that we can display it
				//$error = $resp->error;
				$msgProceso =  "<script>alert('Código de verificación incorrecto')</script>";
		}
	//}
	
?>

<html xmlns:v>
<head>
<style>v\:*{behavior:url(#default#VML);position:absolute}</style>
<script src="Utilidades/jquery.min.js"></script>
<script>
	function consultarURL(referenciaMapa, clasificacion, tipoAnalisis) {
		iframe2 = document.getElementById('iframecontenido');		
		iframe2.src = "visorCapturas/visualizaPoligono.php?codExpediente=" + referenciaMapa+"&clasificacion="+clasificacion+"&tipo_analisis="+tipoAnalisis;
			
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
.Estilo7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; }
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
      <td colspan="4" bgcolor="#D60B0A"><div align="left"><span class="Estilo4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AN&Aacute;LISIS T&Eacute;CNICO DE &Aacute;REAS    </span></div></td>
    </tr>   
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" class="Estilo5"><input type="hidden" name="tipo_analisis" value="<?php echo $tipo_analisis?>">
	  	<div style="padding:6px; padding-left:25px; padding-right:25px; float:left; border:#003300 1px solid; background-color:<?php if($tipo_analisis=="SOLICITUD") echo '#F0F0F0'; else echo '#CCCCCC';?>" align="center"><a href="CMQ_ConsultarEstudioDetalle.php?tipo_analisis=SOLICITUD" STYLE="text-decoration:none">Evaluaci&oacute;n T&eacute;cnica de Solicitudes</a></div>
	  <div style="padding:6px; float:left; border:#003300 1px solid; background:<?php if($tipo_analisis=="PROSPECTO") echo '#F0F0F0'; else echo '#CCCCCC';?>" align="center"><a href="CMQ_ConsultarEstudioDetalle.php?tipo_analisis=PROSPECTO"  STYLE="text-decoration:none">Evaluaci&oacute;n T&eacute;cnica de Prospectos</a></div></td>
    </tr>
    <tr>
      <td colspan="4"><hr size="1"></td>
    </tr>
    
    <tr>
	<?php
		if($tipo_analisis=="SOLICITUD") { 
	?>
      <td colspan="4" bgcolor="#EBEBEB">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo7">EVALUACI&Oacute;N T&Eacute;CNICA DE SOLICITUDES</span>  </td>      
	<?php } else {  ?>  
	  <td colspan="4" bgcolor="#EBEBEB">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo7">EVALUACI&Oacute;N T&Eacute;CNICA DE PROSPECTOS </span>  </td>
	  <?php }   ?>
    </tr>
    <tr>
      <td colspan="4"><hr size="1"></td>
      </tr>
    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">C&oacute;digo de Expediente: </span></td>
      <td><input name="codExpediente" type="text" id="codExpediente" size="25"></td>
      <td class="Estilo5"><?php if($tipo_analisis=="SOLICITUD") echo "Mineral:"; else "&nbsp;"; ?></td>
      <td><?php if($tipo_analisis=="SOLICITUD") { ?><input name="mineral" type="text" id="mineral" size="30"> <?php } else { echo "&nbsp;"; } ?></td>
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
<?php if($tipo_analisis=="SOLICITUD") {?>
    <tr>
      <td colspan="4"><hr size=1></td>
    </tr>
    <tr>
      <td>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Titular/Solicitante:</span></td>
      <td><input name="persona" type="text" id="persona" size="60"></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
<?php } ?>    
<!--
	<tr>
      <td colspan="4">
	  <hr size=1>
	  <?php 
			//echo recaptcha_get_html($publickey, $error); 
	  ?>	  
	  <hr size="1"></td>
    </tr>
-->	
    <tr>
      <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="buscar" value="Realizar Recorte" onClick="alert('El proceso puede tomar algunos minutos ...')"/>
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
      <td colspan="2" align="left"><iframe id="iframecontenido"  name="iframecontenido" src="visorCapturas/visorAreas.php?codigoExpediente=NoDefinido&centroideLon=-74&centroideLat=5&cobertura=solicitudes_col&tituloCobertura=Solicitudes&areaPoly=0.00" width="550" height="350"></iframe></td>
      <td colspan="2" align="left"><iframe id="iframecontenido2"  name="iframecontenido2" src="http://esmeralda2.anm.gov.co:8080/CMCSIG/faces/indexBuscarExpediente.jsp?codigoExpediente=0000-00" width="550" height="350">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
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
      <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Descarga de Archivo en Excel: <strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="CMQ_ConsultarEstudioDetalleExcel.php?codExpediente=<?php echo (@$_POST["codExpediente"]!="")? $_POST["codExpediente"] : ""; ?>&mineral=<?php echo (@$_POST["mineral"]!="")? $_POST["mineral"] : ""; ?>&mpio=<?php echo (@$_POST["mpio"]!="")? $_POST["mpio"] : ""; ?>&depto=<?php echo (@$_POST["depto"]!="")? $_POST["depto"] : ""; ?>&persona=<?php echo (@$_POST["persona"]!="")? $_POST["persona"] : ""; ?>&tipo_analisis=<?php echo (@$_POST["tipo_analisis"]!="")? $_POST["tipo_analisis"] : ""; ?>" target="_blank">Download_FILE</a></strong> </span></td>
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
