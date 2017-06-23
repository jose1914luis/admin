<?php
	session_start();

	require("Acceso/Config.php");
	require("Modelos/procesarDatosSolicitud.php");
	require("Modelos/procesarDatosTitulo.php");
	require("Modelos/procesarIdentifySolicitud.php");
	require("Modelos/procesarIdentifyTitulo.php");

	require("Modelos/Solicitudes.php");
	require("Modelos/SolicitudesMpiosDeptos.php");
	require("Modelos/SolicitudesMinerales.php");
	require("Modelos/SolicitudesArcifinios.php");
	require("Modelos/SolicitudesPersonas.php");

	require("Modelos/Titulos.php");
	require("Modelos/TitulosMpiosDeptos.php");
	require("Modelos/TitulosMinerales.php");
	require("Modelos/TitulosArcifinios.php");
	require("Modelos/TitulosPersonas.php");
	require("Modelos/AsignacionesTareas.php");	

	require("Modelos/SeguimientosUsuarios.php");		
	require_once("Modelos/Usuarios.php");

	//require_once('recaptcha/recaptchalib.php');

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	// Se crea el objeto para actualización de tareas
	$actualizaTarea = new AsignacionesTareas();
	
	$placa = "";
	$msgAcceso="";
	
	$msgSalvarDatos = "";
	
	// Variable de Captcha	
	// Get a key from https://www.google.com/recaptcha/admin/create
	$publickey = "6Lc7c9ESAAAAADp2w51MWnzDLstVbm-w6aFGwpOu";
	$privatekey = "6Lc7c9ESAAAAAOl8hX99-0CGxUZ6xqbOgOckk6wU";
	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;
	# was there a reCAPTCHA response?
	if (1==1) { //@$_POST["recaptcha_response_field"]) {
/*
	@$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);
*/
	if (1==1) {//$resp->is_valid) {		
			if(isset($_POST["fuentePlaca"]) && strlen($_POST["fuentePlaca"])>800) {
				$processData = new procesarDatosSolicitud($_POST["fuentePlaca"]);
				$processIdentify = new procesarIdentifySolicitud($_POST["fuenteIdentify"]);

				if(stripos(strtoupper($processData->getEstadoJuridico()), "TITULO")===false) 		{  // Operación si corresponde a una solicitud
					$msgSalvarDatos .=  "<p align='left'><h3>DATOS BASICOS CONSULTA SOLICITUD</h3></p>".$processData->generarInformacion()."<hr>";
					$msgSalvarDatos .=  "<p align='left'><h3>DATOS IDENTIFY SOLICITUD</h3></p>".$processIdentify->generarInformacion()."<hr>";
					$msgSalvarDatos .=  "<p align='left'><h3>PROPONENTES</h3></p>".str_replace("\n","<br>",$_POST["solicitantes"])."<hr>";
				
					if($processData->getCodigoExpediente()!=$processIdentify->getCodigoExpediente()) {
						$msgSalvarDatos .=   "<table bgcolor=red><tr><td><b>Verificar las fuentes de informaci&oacute;n. Los c&oacute;digos de expediente son diferentes. ".$processData->getCodigoExpediente()." Diferente a ".$processIdentify->getCodigoExpediente()."</b><BR><b>LOS DATOS NO FUERON GUARDADOS EN EL SISTEMA</b></td></tr></table>";
					} 
					else if(!$processData->esConsistentePorcentajeMunicipios()) {
						$msgSalvarDatos .=   "<table bgcolor=red><tr><td>Los datos capturados son inconsistentes y el porcentaje de superposici&oacute;n es mayor al 100%, recuerde ELIMINAR LOS TEMPORALES al realizar la consulta del expediente (Control Shift Suprimir y luego Eliminar). <BR><b>LOS DATOS NO FUERON GUARDADOS EN EL SISTEMA</b></td></tr></table>";			
					}  
					else {	
						// Guardar los campos en base de datos.	
						$sol = new Solicitudes;
						$datos = array_merge((array)$processData->getAll(), (array)$processIdentify->getAll());										
						
						// verificar si el proceso es de inserción o actualización de solicitud
						$IdPlaca  		= $sol->getIdPlaca($datos["codigoExpediente"]);														
						$solMpioDepto 	= new SolicitudesMpiosDeptos;
						$solMinerals 	= new SolicitudesMineralesTMP;						
						$solArcifinios 	= new SolicitudesArcifiniosTMP;
						$solPersonas 	= new SolicitudesPersonasTMP;
						
						if(empty($IdPlaca))	{
							$sol->insertAll($datos);
							$IdPlaca  = $sol->getIdPlaca($datos["codigoExpediente"]);									
						} else {
							$sol->updateAll($datos);					
							$solMpioDepto->deleteMpiosDeptos($IdPlaca);
							$solMinerals->deleteMinerals($IdPlaca);
							$solArcifinios->deleteArcifinios($IdPlaca);
							$solPersonas->deletePersonas($IdPlaca);						
						}

						$placa = $datos["codigoExpediente"];
						$datos = array_merge((array)$datos,array("idPlaca"=>$IdPlaca));
						$datos = array_merge((array)$datos,array("persona"=>$_POST["solicitantes"]));					
						
						$solMpioDepto->insertAll($datos);
						$solMinerals->insertAll($datos);
						$solArcifinios->insertAll($datos);
						$solPersonas->insertAll($datos);
						
						$accionPage = new SeguimientosUsuarios;
						$accionPage->generarAccion("Almacenamiento de Datos Basicos de Solicitudes (Textual), Placa '$placa'");
						
						// Actualización del polígono almacenado como solicitud
						$actualizaTarea->asignacionTarea("Ingresar Datos Básicos", $placa, "SOLICITUD", $_SESSION["usuario_cmq"]);
						$actualizaTarea->actualizacionEstadoTarea("Ingresar Datos Básicos", $placa, $_SESSION["usuario_cmq"]);
						$actualizaTarea->asignacionTarea("Capturar Polígono", $placa, "SOLICITUD", $_SESSION["usuario_cmq"]);
						
						// Aqui va funcion para generar tarea en 'Capturar Polígono' para solicitudes
						
						if($_POST["solicitantes"]=="")
							echo "<table bgcolor='orange' border = 0><tr><td>Advertencia, no fueron diligenciados los proponentes de $placa</td></tr></table>";
					}
				} else {			
					$processData = new procesarDatosTitulo($_POST["fuentePlaca"]);
					$processIdentify = new procesarIdentifyTitulo($_POST["fuenteIdentify"]);
					$msgSalvarDatos .=  "<p align='left'><h3>DATOS BASICOS CONSULTA TITULOS</h3></p>".$processData->generarInformacion()."<hr>";
					$msgSalvarDatos .=  "<p align='left'><h3>DATOS IDENTIFY SOLICITUD</h3></p>".$processIdentify->generarInformacion()."<hr>";

					if($processData->getCodigoExpediente()!=$processIdentify->getCodigoExpediente()) {
						$msgSalvarDatos .=   "<table bgcolor=red><tr><td><b>Verificar las fuentes de informaci&oacute;n. Los c&oacute;digos de expediente son diferentes. ".$processData->getCodigoExpediente()." Diferente a ".$processIdentify->getCodigoExpediente()."</b><BR><b>LOS DATOS NO FUERON GUARDADOS EN EL SISTEMA</b></td></tr></table>";
					}
					else if(!$processData->esConsistentePorcentajeMunicipios()) {
						$msgSalvarDatos .=   "<table bgcolor=red><tr><td>Los datos capturados son inconsistentes y el porcentaje de superposici&oacute;n es mayor al 100%, recuerde ELIMINAR LOS TEMPORALES al realizar la consulta del expediente (Control Shift Suprimir y luego Eliminar). <BR><b>LOS DATOS NO FUERON GUARDADOS EN EL SISTEMA</b></td></tr></table>";			
					}  
					else {	
						// Guardar los campos en base de datos.	
						$tit = new Titulos;
						$datos = array_merge((array)$processData->getAll(), (array)$processIdentify->getAll());

						// verificar si el proceso es de inserción o actualización de solicitud
						$IdPlaca  		= $tit->getIdPlaca($datos["codigoExpediente"]);														
						$titMpioDepto 	= new TitulosMpiosDeptos;
						$titMinerals 	= new TitulosMineralesTMP;						
						$titArcifinios 	= new TitulosArcifiniosTMP;
						$titPersonas 	= new TitulosPersonasTMP;
						
						if(empty($IdPlaca))	{
							$tit->insertAll($datos);
							$IdPlaca  = $tit->getIdPlaca($datos["codigoExpediente"]);									
						} else {
							$tit->updateAll($datos);					
							$titMpioDepto->deleteMpiosDeptos($IdPlaca);
							$titMinerals->deleteMinerals($IdPlaca);
							$titArcifinios->deleteArcifinios($IdPlaca);
							$titPersonas->deletePersonas($IdPlaca);						
						}
						
						$placa = $datos["codigoExpediente"];
						$datos = array_merge((array)$datos,array("idPlaca"=>$IdPlaca));
						
						$titMpioDepto->insertAll($datos);
						$titMinerals->insertAll($datos);
						$titArcifinios->insertAll($datos);
						$titPersonas->insertAll($datos);						
						
						$accionPage = new SeguimientosUsuarios;
						$accionPage->generarAccion("Almacenamiento de Datos Basicos de Titulos (Textual), Placa '$placa'");	
						
						// Actualización del polígono almacenado como titulo
						$actualizaTarea->asignacionTarea("Ingresar Datos Básicos", $placa, "TITULO", $_SESSION["usuario_cmq"]);
						$actualizaTarea->actualizacionEstadoTarea("Ingresar Datos Básicos", $placa, $_SESSION["usuario_cmq"]);
						$actualizaTarea->asignacionTarea("Capturar Polígono", $placa, "TITULO", $_SESSION["usuario_cmq"]);				

						// Aqui va funcion para generar tarea en 'Capturar Polígono' para titulos				
					}			
				}
			}
		} else {
				# set the error code so that we can display it
				//$error = $resp->error;
				$msgAcceso =  "<script>alert('Código de verificación incorrecto')</script>"; ;
		}
	}	
?>

<html>
<head>
<title>:: CMQ :: Captura de datos basicos</title>
<style type="text/css">
<!--
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 14px;
}
.Estilo4 {	font-size: 16px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #FFFFFF;
}
.Estilo5 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
}
.Estilo7 {color: #000000}
.Estilo11 {
	color: #000000;
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo15 {
	color: #000000;
	font-size: 14px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo16 {
	color: #FFFFFF;
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
-->
</style>
<script src="jquery.min.js"></script>
<script>
	function existePlaca(placa,tipo)	{
		 $("#validaPlaca").load('validaExpediente.php?placa='+placa+'&tipo='+tipo);
	};		

	function procesoGrafico() {
		if(document.forms[0].codigoExpediente.value!="") {
			alert("Placa a analizar geográficamente: "+document.forms[0].codigoExpediente.value);
			document.forms[0].action = "CMQ_Coords.php";
			document.forms[0].submit();
		}
		else
			alert("No se ha realizado el proceso de cargue de información textual");
	}
	
	function consultarURL() {
		if(document.forms[0].fuentePlaca.value=="")
			return "";
	
		campoFuente = document.forms[0].fuentePlaca.value;
		buscarIni = /:codigoExpediente">/i;
		buscarFin = /<\/span>/i;
		posIni = campoFuente.search(buscarIni) + 19; // se suma longitud del string de busqueda inicial
		subCadena = campoFuente.substr(posIni, 50);
		posFin = subCadena.search(buscarFin);
		codExp = subCadena.substr(0, posFin);
		
		iframe = document.getElementById('iframecontenido');
		iframe.src = 'http://esmeralda2.anm.gov.co:8080/CMCSIG/faces/indexBuscarExpediente.jsp?codigoExpediente='+codExp.toUpperCase();
		
		// verificación de la existencia del expediente en CMQ
		buscarTipo 	= /:estadoJuridico">SOLICITUD/i;
		existeSol 	= campoFuente.search(buscarTipo);
		tipo = "";
		if(existeSol>(-1)) 			tipo = "SOLICITUD";
		else {
			buscarTipo 	= /:estadoJuridico">TITULO/i;
			existeTit 	= campoFuente.search(buscarTipo);				
			if(existeTit>(-1))		tipo = "TITULO";
		}
		
		existePlaca(codExp, tipo);
	
		buscarEstado = /:estadoJuridico">SOLICITUD ARCHIVADA|:estadoJuridico">TITULO TERMINADO/i;
		existe = campoFuente.search(buscarEstado);		
		if(existe>(-1)) 
			alert("El expediente '"+codExp+"' se encuentra Archivado o Terminado, Por lo tanto NO DEBE CAPTURARSE.");

	};
	
</script>
</head>
<body>
<script>
	//	Eliminar cookies CMC
	Cookie.set("JSESSIONID",'',-1);
</script>

<form action="" method="post" name="idb" id="idb">
	<p>
	  <input type="hidden" name="codigoExpediente" value="<?php echo $placa ?>">
  </p>
	<table width="1000" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="3"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="3">
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
        <td colspan="3" bgcolor="#D60B0A"><div align="left"><span class="Estilo4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CAPTURAR DATOS B&Aacute;SICOS :: Ingreso de Datos B&aacute;sicos ::  </span></div></td>
      </tr>
      <tr>
        <td colspan="3"><hr size="1"></td>
      </tr>
    </table>
    <table width="1000" border="0" cellspacing="0" cellpadding="0">
    
    <tr>
      <td colspan="2">
		<div id="contenido_idb">
			<?php
				if($msgSalvarDatos != "") {
					echo '<table "width=100%" border=0 align="left" width="1000"><tr><td align="left" bgcolor="#E1E1E1" ><span class="Estilo15">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INFORMACI&Oacute;N PROCESADA</span></td></tr><tr><td>';
					echo $msgSalvarDatos;
					echo '</td></tr></table><hr>';
				}
			?>
		</div>	  </td>
    </tr>
    <tr>
      <td colspan="2"><hr size="1"></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#E1E1E1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo15">CAPTURA DE SOLICITANTES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo1"><a href="IDB_DatosBasicos.php" class="Estilo1">[NUEVA CAPTURA DE DATOS B&Aacute;SICOS]</a> &nbsp;&nbsp;&nbsp;<a href="CMQ_Coords.php" class="Estilo1">[ACTUALIZAR POLIGONO]</a></span></span></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Copiar los solicitantes de la p&aacute;gina de consulta (solo v&aacute;lido para solicitudes) </span></td>
    </tr>
    <tr>
      <td colspan="2"><textarea name="solicitantes" cols="70" rows="4" id="solicitantes"></textarea></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#E1E1E1"><span class="Estilo1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo7">INGRESO DE DATOS BASICOS DE SOLICITUD</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
    </tr>
    <tr>
      <td colspan="2"><hr size="1"></td>
    </tr>
    <tr>
      <td width="76%"><span class="Estilo5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pegar C&oacute;digo de la p&aacute;gina de consulta</span> </td>
      <td width="24%">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><textarea name="fuentePlaca" cols="85" rows="8" onBlur="consultarURL()"></textarea></td>
    </tr>
    
    <tr>
      <td colspan="2"><div id="validaPlaca">&nbsp; </div></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><hr size=1></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#E1E1E1"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo11">CARACTER&Iacute;STICAS DEL POL&Iacute;GONO</span></strong></td>
    </tr>
    <tr>
      <td colspan="2" class="Estilo5"><hr size=1></td>
    </tr>
    <tr>
      <td colspan="2" class="Estilo5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Realizar <strong>Identify</strong> al &aacute;rea de la solicitud <b>
      <?php $placa ?>
      </b> (Debe primero pegar el c&oacute;digo de p&aacute;gina de consulta):<br>
      <iframe id="iframecontenido" align="left" frameborder="0" src="http://esmeralda2.anm.gov.co:8080/CMCSIG/faces/indexBuscarExpediente.jsp?codigoExpediente=<?php if($placa)echo $placa; else echo "000-00"; ?>" width="1000" height="420" scrolling="true" marginheight="0"	marginwidth="0" allowtransparency="true"></iframe></td>
    </tr>
    <tr>
      <td colspan="2" class="Estilo5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pegar c&oacute;digo generado por el identify </td>
    </tr>
    <tr>
      <td colspan="2" valign="top"><textarea name="fuenteIdentify" cols="85" rows="8" id="fuenteIdentify"></textarea>
        <br>
        <br></td>
    </tr>
    
    <tr>
      <td colspan="2" bgcolor="#E1E1E1"><strong class="Estilo5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;An&aacute;lisis de Contenido de Datos</strong>      </td>
    </tr>
    <tr>
      <td colspan="2">
	  <hr size="1">	  </td>
    </tr>
    <tr>
      <td colspan="2">
	  <?php 
		// echo recaptcha_get_html($publickey, $error); 
	   ?> 
  	  <hr size="1">	  
	  </td>
    </tr>
    <tr>
      <td colspan="2">
	  <input type="submit" name="Submit" value="Procesar Contenido">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit2" value="Captura Geogr&aacute;fica" onClick="procesoGrafico()">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="IDB_DatosBasicos.php" class="Estilo1">[NUEVA CAPTURA DE DATOS B&Aacute;SICOS]</a> 
  	  <hr size="1">	  
	  </td>	  
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<?php if($msgAcceso!="") echo $msgAcceso; ?>
</body>
</html>
