<?php
	session_start();

	require("Acceso/Config.php");
	require("Modelos/DescargarShapes.php");
	require("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");
	

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	
	$msgProceso = "";

		if(@$_POST["txtPlacas"]!= "") {		
			$idSession = session_id();
			$shpFiles = new DescargarShapes;
			$placas = $shpFiles->borrarCaracteres($_POST["txtPlacas"]);
			$resultado = $shpFiles->getShapeListaExpedientesBog($placas, $idSession);
			
			// Nombre del archivo de descarga:
			$archivoDescarga = "DwnShapes/geoSIGMIN_Bog_".$idSession.".zip";
			if($resultado == "O.K")	
				// Hay que configurar el pg_hba en md5 para que esto funcione
				// local   all             all                                peer-->md5
				$salida = shell_exec("./scriptDownloadShp.sh $idSession");	
				
				$accionPage = new SeguimientosUsuarios;
				$accionPage->generarAccion("Descarga de polígonos (solicitudes, titulos) del sistema SIGMIN, Placas: '$placas'");				
		} 

?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
		<link rel="stylesheet" type="text/css" href="../css/layouts.css"/>
		<link rel="stylesheet" type="text/css" href="../css/general.css"/>
		<script type="text/javascript" src="../js/general.js"></script>
		<title>:: CMQ :: Descarga de Archivos Mineros</title>
		<script src="../js/AC_RunActiveContent.js" type="text/javascript"></script>
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
	<form method="POST">
		<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td>
					<table width="100%" border="0" align="left" cellpadding="0" cellspacing="3">
					  <tr>
						<td colspan="3"><div align="right"><img src="imgs/logoQuimbaya.jpg" width="294" height="68" /></div></td>
					  </tr>  
					  <tr>
						<td width="9%"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="195" height="51" border="0" /></a></div></td>
						<td colspan="2" bgcolor="#2872A3"><div align="center"><img src="imgs/textoCMQ.jpg" width="562" height="51" /></div></td>
					  </tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%" border="1" cellpadding="10" cellspacing="0" bordercolor="#D60B0A">
					<tr><td>
						<table width="100%">
							<tr>
								<td bgcolor="#D60B0A"><div align="left"><span class="Estilo4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DESCARGAR SHAPES DE SIGMIN </span></div></td>
							</tr>
							<tr>
								<td><hr size="1"></td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Ingrese las placas a las que se generar&aacute; poligono <i>(separadas por coma, sin "Enter")</i>:</span> 
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name="txtPlacas" rows="4" cols="60"></textarea><br>
									<hr size="1">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" value="Generar Shapes"/>
									<hr size="1">
								</td>
							</tr>
							<tr>
								<td>
									<span class="Estilo5"><b>Resultado proceso de descarga</b></span><br>
									<hr size="1">
									<?php 
										if(@$placas!= "") {
									
									?>
									<b>Placas Generadas: </b><?php echo @$placas?>
									<p>
										<pre>
											<?php echo @$salida; ?>					
										</pre>
									<p><a href="<?php echo @$archivoDescarga?>" title="Archivo generado"> Download Shape</a>
									<?php
										}
									?>
								</td>
							</tr>
						</table>
					</td><tr>	
					</table>
				</td>				
			</tr>
		</table>
	  </form>
	<?php
		if($msgProceso!="") 
			echo $msgProceso;
	?>
	</body>
</html>
