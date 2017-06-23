<?php
	session_start();

	require("Acceso/Config.php");
	require("Modelos/ProspectosBog.php");
	require("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");
	
	require_once('recaptcha/recaptchalib.php');

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
//	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	
	$placa = "";
	$msgProceso = "";
	

	// Variable de Captcha	
	// Get a key from https://www.google.com/recaptcha/admin/create
	$publickey = "6Lc7c9ESAAAAADp2w51MWnzDLstVbm-w6aFGwpOu";
	$privatekey = "6Lc7c9ESAAAAAOl8hX99-0CGxUZ6xqbOgOckk6wU";
	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;
	# was there a reCAPTCHA response?
/*	if (@$_POST["recaptcha_response_field"]) {
		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);
*/										
		if (1==1) {  //$resp->is_valid) {	
			if(@$_POST["guardarPoly"]=="YES"&&@$_POST["coordenadas"]!="") {		
			
				$placa  = (isset($_POST["codigoProyecto"])) ? $_POST["codigoProyecto"] : ""; 
				$prospectos_geo = new ProspectosBog($placa, $_POST["coordenadas"]);		
				
				$prospectos_geo->insertAll($_POST["sistemaOrigen"]);
				$placa = $prospectos_geo->getProspecto();
				$centroides = $prospectos_geo->getCentroideWGS84($placa);
				
				$areaPerimetro = $prospectos_geo->getArea($placa);
				$areaPoly = $areaPerimetro["area"];
				$perimetroPoly = $areaPerimetro["perimetro"];
				
				if($prospectos_geo->existePlaca($placa)) {
					$msgProceso = "<script>alert('Se ha generado el Codigo de Prospecto $placa')</script>";
					$accionPage = new SeguimientosUsuarios;
					$accionPage->generarAccion("Generacion del prospecto '$placa'");
				} else 
					$msgProceso = "<script>alert('::ERROR:: La placa $placa no fue almacenada satisfactoriamente')</script>";
			}
		} else {
				# set the error code so that we can display it
				// $error = $resp->error;
				$msgProceso =  "<script>alert('Código de verificación incorrecto')</script>";
		}
	//}			
			

?>

<!DOCTYPE html>
<html>
<head>
<script src="Utilidades/jquery.min.js"></script>
<script>
	var COORDENADAS = "";
	var	factor = 1;

	var xMin = 0;
	var xMax = 0;
	var yMin = 0;
	var yMax = 0;
	
	function savePoly() {
		if(document.forms[0].coordenadas.value!="") {
			document.forms[0].action='CMQ_Prospectos.php'; 
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

		polyFormat = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="190"><polygon points="'+cadenaCoord+'" style="fill:'+colorPoly+';stroke:cyan;stroke-width:2;fill-rule:evenodd;"></svg>';
		
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
	
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<link rel="stylesheet" type="text/css" href="../css/layouts.css"/>
<link rel="stylesheet" type="text/css" href="../css/general.css"/>
<script type="text/javascript" src="../js/general.js"></script>
<title>:: CMQ :: Generación de Prospectos Mineros</title>
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
<div id="contenido">
<script>
	//	Eliminar cookies CMC
	Cookie.set("JSESSIONID",'',-1);
</script>
	<div id="menuNavegacion">
<form id="menuRadicacion" name="menuRadicacion" method="post" action="/CmcFrontEnd/consulta/detalleInfoGeograficaExpediente.cmc" enctype="application/x-www-form-urlencoded">
  <table width="1000" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="3">
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
      <td bgcolor="#D60B0A"><div align="left"><span class="Estilo4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GENERAR PROSPECTOS </span></div></td>
    </tr>
    <tr>
      <td><hr size="1"></td>
    </tr>
    <tr>
      <td><input type="hidden" name="menuRadicacion" value="menuRadicacion" />
        <span class="Estilo5">
        <input type="hidden" name="j_id_jsp_1243720112_5" value="j_id_jsp_1243720112_5" />
        </span>
        <input type="hidden" name="javax.faces.ViewState" id="javax.faces.ViewState" value="j_id1:j_id8" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">C&oacute;digo del expediente para ubicar el &aacute;rea de inter&eacute;s:</span>        <input type="text" name="codigoExpediente" value="<?php echo @$_POST["codigoExpediente"]; ?>" />        <input type="button" name="Submit" value="Buscar Expediente" onClick="consultarURL()"/></td>
    </tr>
    <tr>
      <td><hr size="1" /></td>
    </tr>
    <tr>
      <td><span class="Estilo5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C&oacute;digo del Prospecto Minero:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo6">&nbsp;</span>        <input type="text" name="codigoProyecto" value="<?php echo $placa ?>"  readonly=""/>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Sistema de Origen:</span>
        <select name="sistemaOrigen">
          <option value="OESTE">OESTE</option>
          <option value="BOGOTA" selected="selected">CENTRO</option>
          <option value="ESTE-ESTE">ESTE-ESTE</option>
          <option value="ESTE">ESTE</option>
		  <?php
			if(isset($_POST["sistemaOrigen"]))
				echo '<option value="'.$_POST["sistemaOrigen"].'" selected>'.$_POST["sistemaOrigen"].'</option>';
		  ?>	
      </select></td>
    </tr>
    <tr>
      <td><hr size="1" /></td>
    </tr>
    <tr>
      <td><input name="button" type="button" onClick="verStatus()" value="Obtener Coordenadas" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="CMQ_Prospectos.php" class="Estilo1">[NUEVA CAPTURA DE PROSPECTO MINERO]</a></td>
    </tr>
  </table>
  <table width="1000" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20px">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
					  <td class="campoForm negrilla bordeAbajoBris"><hr /></td>
				  </tr>
					<tr>
						<td class="Estilo5"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>Ubicacion de Area: </td>
					</tr>
					<tr>
						<td class="">
							
							
							<iframe name="contenido" id="iframecontenido" width="1000"
						src="http://esmeralda2.anm.gov.co:8080/CMCSIG/faces/indexBuscarExpediente.jsp?codigoExpediente=<?php echo @$_POST["codigoExpediente"] ?>" frameborder="0" scrolling="0" marginheight="0"
						marginwidth="0" allowtransparency="true" height="410"> </iframe>					</td>
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
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo5">Lista de Coordenadas: </span></td>
              <td width="19%" rowspan="2" valign="top">
			  
<style type="text/css"> v:*{behavior:url(#default#VML);}   </style>
<div id="dibujaPoly">
	 <v:polyline  points=""  style='visibility: visible'  opacity="1.0"  chromakey="null"  stroke="true" strokecolor="cyan"  strokeweight="1"  fill="true"  fillcolor="#C1FFFF"  print="true"  coordsize="1000,1000"  coordorigin="1000 1000"></v:polyline>
</div>		</td>
              <td width="55%" rowspan="2" valign="top">
			  <?php
			  if($placa != "") { 
					$clasificacion = "PROSPECTO";
					$url = "visorCapturas/visualizaPoligono.php?codExpediente=$placa&clasificacion=$clasificacion";
			  ?>
	  		  <iframe src="<?php echo $url ?>" width="550" height="350"></iframe>
			  <?php
			  	}
			  ?>
			  
		</td>
	</tr>
    <tr>
      <td width="26%" valign="top"><div id="areaCoordenadas">
        <textarea name="coordenadas" cols="40" rows="15" onBlur="clearTextArea()"><?php echo @$_POST["coordenadas"] ?></textarea>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>      </td>
    </tr>
<!--
    <tr>
      <td colspan="3" valign="top">
	  <hr size=1>
	  <?php 
			// echo recaptcha_get_html($publickey, $error); 
	  ?>	  	  	  
	  <hr size=1></td>
    </tr>
-->
    <tr>
      <td colspan="3" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" name="Submit2" value="Simular Area" onClick="procesarCoordenadas()"/>
        <input type="hidden" name="guardarPoly" value="NO"/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="Submit3" value="Guardar Poligono" onClick="savePoly()"/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="CMQ_Prospectos.php" class="Estilo1">[NUEVA CAPTURA DE PROSPECTO MINERO]</a></td>
    </tr>
    <tr>
      <td colspan="3" valign="top"><hr size=1></td>
    </tr>
  </table>
  </form>
<?php
	if($msgProceso!="") 
		echo $msgProceso;
?>
</body>
</html>
