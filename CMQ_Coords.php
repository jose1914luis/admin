<?php
	session_start();

	require("Acceso/Config.php");

	require("Modelos/SolicitudesCgBog.php");
	require("Modelos/Solicitudes.php");

	require("Modelos/TitulosCgBog.php");
	require("Modelos/Titulos.php");

	require("Modelos/AsignacionesTareas.php");		
	
	require_once("Modelos/SeguimientosUsuarios.php");		
	require_once("Modelos/Usuarios.php");
	
	//require_once('recaptcha/recaptchalib.php');

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	$idUsuario = $validate->selectUsrByLogin($_SESSION["usuario_cmq"]);	
	
	// Se crea el objeto para actualización de tareas
	$actualizaTarea = new AsignacionesTareas();

	
	$msgProceso = "";	
	$placa  = "";
	
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
		if (1==1) { //$resp->is_valid) {
			if(@$_POST["guardarPoly"]=="YES"&&@$_POST["coordenadas"]!=""&&@$_POST["area_poly_cmc"]!="") {
				$tit = new Titulos;
				$IdPlaca  = $tit->getIdPlaca(strtoupper($_POST["codigoExpediente"])); 
				$placa=$_POST["codigoExpediente"];
				
				if (!empty($IdPlaca)) {
					$titulos_geo = new TitulosCgBog($IdPlaca, $_POST["coordenadas"], $_POST["area_poly_cmc"], $idUsuario);	
					$titulos_geo->insertAll();
					
					$centroides = $titulos_geo->getCentroideWGS84($IdPlaca);
					$areaPoly = $titulos_geo->getArea($IdPlaca);
					
					$msgProceso = "<script>alert('Se ha realizado el almacenamiento gráfico correspondiente al expediente: {$_POST["codigoExpediente"]}')</script>";
					
					$accionPage = new SeguimientosUsuarios;
					$accionPage->generarAccion("Almacenamiento de Datos Básicos de Títulos (Poligono), Placa '$placa'");

					// Actualización del polígono almacenado como titulo
					$actualizaTarea->actualizacionEstadoTarea("Capturar Polígono", $placa, $_SESSION["usuario_cmq"]);
					
					$cobertura = "titulos_cg";
					$tituloCobertura = "Titulos";
					
				} else {
					$sol = new Solicitudes;
					$IdPlaca  = $sol->getIdPlaca(strtoupper($_POST["codigoExpediente"])); 
					$placa=$_POST["codigoExpediente"];

					if (!empty($placa)) {
						$solicitudes_geo = new SolicitudesCgBog($IdPlaca, $_POST["coordenadas"], $_POST["area_poly_cmc"], $idUsuario);	
						$solicitudes_geo->insertAll();
						
						$centroides = $solicitudes_geo->getCentroideWGS84($IdPlaca);
						$areaPoly = $solicitudes_geo->getArea($IdPlaca);
						
						$msgProceso = "<script>alert('Se ha realizado el almacenamiento gráfico correspondiente al expediente: {$_POST["codigoExpediente"]}')</script>";
						
						$accionPage = new SeguimientosUsuarios;
						$accionPage->generarAccion("Almacenamiento de Datos Basicos de Solicitudes (Poligono), Placa '$placa'");
						
						// Actualización del polígono almacenado como solicitud
						$actualizaTarea->actualizacionEstadoTarea("Capturar Polígono", $placa, $_SESSION["usuario_cmq"]);
						
						$cobertura = "solicitudes_cg";
						$tituloCobertura = "Solicitudes";
						
					} else {			
						echo "<table bgcolor=red><tr><td>La placa <b>".strtoupper($_POST["codigoExpediente"])."</b> no posee datos b&aacute;sicos en el sistema. Debe capturarse primero la informaci&oacute;n textual</td></tr></table>";
					}

				}
			}
		} else {
				# set the error code so that we can display it
				//$error = $resp->error;
				$msgProceso =  "<script>alert('Código de verificación incorrecto')</script>";
		}
	}			

?>

<html xmlns:v>
<head>
<style>v\:*{behavior:url(#default#VML);position:absolute}</style>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<script src="Utilidades/jquery.min.js"></script>
<script>
	var COORDENADAS = "";
	var	factor = 1;

	var xMin = 0;
	var xMax = 0;
	var yMin = 0;
	var yMax = 0;
	
	function savePoly() {
		if(document.forms[0].area_poly_cmc.value=="") {
			alert("No se han ingresado el area del poligono CMC");
		} else if(document.forms[0].coordenadas.value!="") {
			document.forms[0].action='CMQ_Coords.php'; 
			document.forms[0].guardarPoly.value='YES'			
			document.forms[0].submit();
		}
		else
			alert("No se han ingresado coordenadas aún");			
	}	
	
	//Hallar máximo en un arreglo numérico
	Array.prototype.max = function( array ){
		return Math.max.apply( Math, array );
	};

	//Hallar Mínimo en arreglo numérico
	Array.prototype.min = function( array ){
		return Math.min.apply( Math, array );
	};	

	function clearTextArea() {
		COORDENADAS = document.forms[0].coordenadas.value;
	};
	
	function consultarURL() {
		alert("Se buscará el expediente "+document.forms[0].codigoExpediente.value);
		iframe = document.getElementById('iframecontenido');
		iframe.src = 'http://esmeralda2.anm.gov.co:8080/CMCSIG/faces/indexBuscarExpediente.jsp?codigoExpediente='+document.forms[0].codigoExpediente.value.toUpperCase();
		COORDENADAS = "";
	};

	function verStatus(){
		COORDENADAS += window.status+"\n";
		COORDENADAS = COORDENADAS.replace("x= ","");
		COORDENADAS = COORDENADAS.replace("y= ","");
		COORDENADAS = COORDENADAS.replace(" ","");
		$("#areaCoordenadas").html("<textarea name='coordenadas' cols='40' rows='15' onblur='clearTextArea()'>"+COORDENADAS+"</textarea>");
	};
	
	function hallarMaxMin(coord_poly) {
		vector_coord = coord_poly.split(",");
		linea = 0;	
		coordX = [0];
		coordY = [0];		
		
		for(i=0; i<vector_coord.length; i++) {
			if(vector_coord[i]!=0 && vector_coord[i]!=""&&!isNaN(vector_coord[i])) {
				if(linea%2==0) coordX.push(vector_coord[i]); 
				else coordY.push(vector_coord[i]);
				linea++;
			}
		}

		// Eliminar primer registro para arreglos de coordenadas, el cual equivale a ceros	
		coordX.shift();
		coordY.shift();
		
		xMin=coordX.min(coordX);
		xMax=coordX.max(coordX);
		yMin=coordY.min(coordY);
		yMax=coordY.max(coordY);
		
		factor = ((xMax-xMin)>(yMax-yMin))?(xMax-xMin):(yMax-yMin);
	};	
	
	function drawPoly(coord_poly, esExclusion) {	
		if(esExclusion)			colorPoly = "#FFFFFF";
		else					colorPoly = "#C1FFFF";
		
		coordX = [0];
		coordY = [0];
		linea = 0;

		cadenaCoord = "";
		vector_coord = coord_poly.split(",");
		
		for(i=0; i<vector_coord.length; i++) {
			if(vector_coord[i]!=0 && vector_coord[i]!="") {
				if(linea%2==0) coordX.push(vector_coord[i]); 
				else coordY.push(vector_coord[i]);
				linea++;
			}
		}

		// Eliminar primer registro para arreglos de coordenadas, el cual equivale a ceros	
		coordX.shift();
		coordY.shift();
	
		for(i=0; i<coordX.length; i++) {
			cadenaCoord += Math.round((coordX[i] - xMin)*150/factor) + " ";
			cadenaCoord += Math.round(150 - (coordY[i] - yMin)*150/factor) + " ";
		}
		cadenaCoord += Math.round((coordX[0] - xMin)*150/factor) + " ";
		cadenaCoord += Math.round(150 - (coordY[0] - yMin)*150/factor) + " ";
		
		polyFormat = '<v:polyline  points="'+cadenaCoord+'"  style=\'visibility: visible\'  opacity="1.0"  chromakey="null"  stroke="true" strokecolor="cyan"  strokeweight="1"  fill="true"  fillcolor="'+colorPoly+'"  print="true"  coordsize="1000,1000"  coordorigin="1000 1000"></v:polyline>';
		
		return polyFormat;
	};

	function procesarCoordenadas() {
		visualizaPoly = "";
		coord_poly = COORDENADAS;
		coord_poly = coord_poly.replace(/\n/gi,",");	

		hallarMaxMin(coord_poly);		
		
		// Arreglo de varias áreas:		
		listaAreas = coord_poly.split("A");

		for(ii=0;ii<listaAreas.length;ii++) {
			area = listaAreas[ii].split("E");
			visualizaPoly += drawPoly(area[0], false);
			for(j=1;j<area.length;j++)
				visualizaPoly += drawPoly(area[j], true);
		}
		$("#dibujaPoly").html(visualizaPoly);
	};
	
	// ------------ depuracion de codigo 
	function print_r(theObj){
	  if(theObj.constructor == Array ||
		 theObj.constructor == Object){
		document.write("<ul>")
		for(var p in theObj){
		  if(theObj[p].constructor == Array||
			 theObj[p].constructor == Object){
	document.write("<li>["+p+"] => "+typeof(theObj)+"</li>");
			document.write("<ul>")
			print_r(theObj[p]);
			document.write("</ul>")
		  } else {
	document.write("<li>["+p+"] => "+theObj[p]+"</li>");
		  }
		}
		document.write("</ul>")
	  }
	}
	// ------------ depuracion de codigo

	function getAreaIEv10() {
		document.location.href="CMQ_Coords_ie10.php?codigoExpediente="+document.forms[0].codigoExpediente.value;
	}
	
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<link rel="stylesheet" type="text/css" href="../css/layouts.css"/>
<link rel="stylesheet" type="text/css" href="../css/general.css"/>
<script type="text/javascript" src="../js/general.js"></script>
<title>:: CMQ :: Generación de Prospectos</title>
<script src="../js/AC_RunActiveContent.js" type="text/javascript"></script>
<style type="text/css">
<!--
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 14px;
}
.Estilo4 {font-size: 16px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #FFFFFF;
}
.Estilo5 {font-size: 14px; font-family: Verdana, Arial, Helvetica, sans-serif;}
-->
</style>
</head>

<body>
<div id="contenido">
<script>
	//	Eliminar cookies CMC
	Cookie.set("JSESSIONID",'',-1);
</script>
<form id="menuRadicacion" name="menuRadicacion" method="post" action="" enctype="application/x-www-form-urlencoded">
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
      <td colspan="3" bgcolor="#D60B0A"><div align="left"><span class="Estilo4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CAPTURAR DATOS B&Aacute;SICOS :: Captura de Coordenadas :: </span></div></td>
    </tr>
    <tr>
      <td colspan="3"><hr size=1></td>
    </tr>
    <tr>
      <td colspan="3"><input type="hidden" name="menuRadicacion" value="menuRadicacion" />
        <input type="hidden" name="javax.faces.ViewState" id="javax.faces.ViewState" value="j_id1:j_id8" />
        <?php
	if(!isset($_POST["codigoExpediente"]) || $_POST["codigoExpediente"]=="") {
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Ingrese el c&oacute;digo del expediente:</span>&nbsp;
<input type="text" name="codigoExpediente" value="<?php echo @$_POST["codigoExpediente"]; ?>" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="Submit" value="Buscar Expediente" onClick="consultarURL()"/>
&nbsp;&nbsp;
<?php 
	} else {
		echo '<input type="hidden" name="codigoExpediente" value="'.@$_POST["codigoExpediente"].'" />';
		echo "C&oacute;digo del Expediente: ".$_POST["codigoExpediente"];
	}
?>
<div id="contenidoPagina">
  <input type="hidden" name="j_id_jsp_1243720112_5" value="j_id_jsp_1243720112_5" />
</div></td>
    </tr>
    <tr>
      <td colspan="3"><hr size=1></td>
    </tr>
    <tr>
      <td colspan="3"><input name="button" type="button" onClick="verStatus()" value="Obtener Coordenadas" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="IDB_DatosBasicos.php" class="Estilo1">[NUEVA CAPTURA DE DATOS B&Aacute;SICOS]</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:getAreaIEv10()" class="Estilo1">[CAPTURA AREA EXPLORER_Ver10]</a>			
	  </td>
    </tr>
    <tr>
      <td colspan="3"><hr size=1></td>
    </tr>
  </table>
  <table width="1000" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20px">
				<table cellpadding="0" cellspacing="0" border="0" width="1000">
					<tr>
					  <td class="campoForm negrilla bordeAbajoBris"><hr /></td>
				  </tr>
					<tr>
						<td><span class="Estilo1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="Estilo5">Ubicacion de Area:</span> </td>
					</tr>
					<tr>
						<td class="">
							
							
							<iframe name="contenido" id="iframecontenido" width="1000" height="420"
						src="http://esmeralda2.anm.gov.co:8080/CMCSIG/faces/indexBuscarExpediente.jsp?codigoExpediente=<?php echo @$_POST["codigoExpediente"] ?>" scrolling="true" marginheight="0"	marginwidth="0" allowtransparency="true"> </iframe>					</td>
					</tr>
					<tr>
						<td>
							<hr size="1" />						</td>
					</tr>
				</table>				</td>
		<tr>
			<td></td>
		</tr>
  </table>
  <table width="1000" border="0" cellspacing="0" cellpadding="0">
    <tr>
              <td><input type="hidden" name="javax.faces.ViewState2" id="javax.faces.ViewState2" value="j_id1:j_id8" />
				Ingrese area de poligono (SHAPE.AREA): <input type="text" name="area_poly_cmc" value="<?php echo @$_POST["area_poly_cmc"] ?>"/> <a href="javascript:" onclick="window.open('imgs/ejemploAreaPoligono.JPG','winEjmp','toolbar=no,scrollbars=no,resizable=yes,top=150,left=150,width=600,height=420');">[Ver Ejemplo]</a><br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      <span class="Estilo5">Lista de Coordenadas</span> </td>
              <td width="19%" rowspan="2" valign="top">
			  
<style type="text/css"> v:*{behavior:url(#default#VML);}   </style>
<div id="dibujaPoly">
	 <v:polyline  points=""  style='visibility: visible'  opacity="1.0"  chromakey="null"  stroke="true" strokecolor="cyan"  strokeweight="1"  fill="true"  fillcolor="#C1FFFF"  print="true"  coordsize="1000,1000"  coordorigin="1000 1000"></v:polyline>
</div>		</td>
              <td width="55%" rowspan="2" valign="top">
			  <?php
			  if($placa != "") { 
					if($tituloCobertura=='Solicitudes')
						$clasificacion = "SOLICITUD";
					else 
						$clasificacion = "TITULO";
					$url = "visorCapturas/visualizaPoligono.php?codExpediente=$placa&clasificacion=$clasificacion";
			  ?>
	  		  <iframe src="<?php echo $url ?>" width="550" height="350"></iframe>
			  <?php
			  	}
			  ?>
			  </td>			  			  
			  <td width="0%"></td>
    </tr>
    <tr>
      <td width="26%" valign="top"><div id="areaCoordenadas">
        <textarea name="coordenadas" cols="40" rows="15" onBlur="clearTextArea()"><?php echo @$_POST["coordenadas"] ?></textarea>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
    </tr>
    <tr>
      <td>
	  <div align="center">
  <input type="button" name="Submit2" value="Simular Area" onClick="procesarCoordenadas()"/>
  &nbsp; &nbsp; &nbsp;
  <input type="hidden" name="guardarPoly" value="NO"/>
  <input type="button" name="Submit3" value="Guardar Poligono" onClick="savePoly()"/>
      </div></td>
      <td colspan="2" valign="top"><a href="IDB_DatosBasicos.php" class="Estilo1">[NUEVA CAPTURA DE DATOS B&Aacute;SICOS]</a></td>
    </tr>
    <tr>
      <td colspan="3"><div align="center">
        <hr size=1>
      </div></td>
    </tr>
  </table>
  </form>
<?php
	if($msgProceso!="")
		echo $msgProceso;
?>
</body>
</html>
