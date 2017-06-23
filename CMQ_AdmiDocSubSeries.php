<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/SeriesDocumentales.php");	
	require_once("Modelos/SubSeriesDocumentales.php");
	//require_once("Modelos/Empresas.php");	
	require_once("Modelos/TiposDatos.php");	
	require_once("Modelos/Usuarios.php");
	require_once("Modelos/SeguimientosUsuarios.php");		
	

	$placa = "";
	$msgError = "";
	$clasificacion = "";
	$tituloClasificacion = "";
	$observacion = "";
	
	$validate = new Usuarios();	
	$accionPage = new SeguimientosUsuarios;
	
	//$empresa 		= new Empresas();
	//$listaEmpresas 	= $empresa->selectIdNameAll();

	$tipoDato 		= new TiposDatos();
	$listaTipos 	= $tipoDato->selectAll();
	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	if(!empty($_POST["paraGuardar"])&&$_POST["paraGuardar"]!="") {
		$subserie = new SubSeriesDocumentales();	
		if(!$subserie->existeSubSerie($_POST)) {
			$operaInsert = $subserie->insertAll($_POST);
			if($operaInsert == "OK") 
				$msgError = "<script> alert('El formulario para {$_POST["txtNombreSubserie"]} ha sido generado exitosamente')</script>";
			else
				$msgError = "<script> alert('$operaInsert')</script>";
			 
		} else
			$msgError = "<script> alert('El formulario para {$_POST["txtNombreSubserie"]} ya se encuentra definido en el sistema')</script>";	
	} 
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> -->
<script type="text/javascript" src="Utilidades/jquery.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Administraci&oacute;n Documental de Expedientes</title>
<script>
	// Definición de variables locales y globales de la página
	/*function selExpediente(idEmpresa)	{
			 $("#selSerie").load('viewExpedienteByEmpresa.php?idEmpresa='+idEmpresa);
		};	
	*/	
	function habilitaLista(tipoDato) {
		buscar = /LISTA/;
		
		if(buscar.test(tipoDato)) {
			document.forms[0].lista.disabled = false; 
		} else {
			document.forms[0].lista.disabled = true; 
			document.forms[0].lista.value = ""; 		
		}
	}

	/*
	 * DEFINICION DE LOS TIPOS DE CAMPOS:
	 * 1. De texto obligatorio     -> "texto"
	 * 2. solo numérico entero     -> "entero"
	 * 3. para numéricos flotantes -> "decimal"
	 * 4. Direcciòn electrònica    -> "email"
	 * 5. Contraseña    		   -> "password"
	 * 6. campo Examinar		   -> "Examinar"
	 * 7. Campo nulo, no evaluable -> "nulo"
	 */

	/* Función de expresiones regulares */
	function regExpresion(tipo_de_campo) {
	// por defecto es tipo texto:
		expresion = /[(\w)]/;
		tipo_de_campo = tipo_de_campo.toLowerCase();

		if(tipo_de_campo == "texto") 			expresion = /(\w)+/;
		else if(tipo_de_campo == "password")	expresion = /((\W)|(\w))/;
		else if(tipo_de_campo == "examinar")	expresion = /((\W)|(\w))/;	
		else if(tipo_de_campo == "entero") 		expresion = /^(\d+)$/;
		else if(tipo_de_campo == "decimal") 	expresion = /^(((\d)+(\.)(\d)*)|(\d)+)$/;
		else if(tipo_de_campo == "email") 		expresion = /^(\w)+((\.)|-)*(\w)+(@)(\w)+(\.)(\w)*/; 	
		else if(tipo_de_campo == "fecha") 		expresion = /^(\d){2,2}(\/)(\d){2,2}(\/)(\d){4,4}$/; 	
		else ; 					
		return expresion;
	}


/* Esta función valida los campos de un formulario */
	function validar_campos(tipo_campos, nom_campos) {
		// Si no hay nro de campos, cancelar
		nro_campos = nom_campos.length;
		if(nro_campos <= 0) return 0; 

		var i=0;  
		var ret;
		
		while(i < nro_campos) {
			if (tipo_campos[i] != "lista") 
				ret = document.forms[0].elements[i].value.match(regExpresion(tipo_campos[i]));
			else
				ret = document.forms[0].elements[i].length;
			if(!ret && (tipo_campos[i] != "nulo")) {
				alert("Verifique el contenido del Campo " + nom_campos[i]);
				if(tipo_campos[i] != "lista") {
					document.forms[0].elements[i].focus();
					document.forms[0].elements[i].select();							
				}
			return 0;	
			}
		i++;
		}	
	return 1;
	}

// Esta función envía los datos.
	function guardar(){
			for(i=0;i<document.form1.listaIndice.length;i++){
				document.form1.listaIndice.options[i].selected = true;
				document.form1.listaTipoIndice.options[i].selected = true;

				// Nota: los indices son enviados en un string separados por : uno de otro, el fin del string lo delimita la barra vertical
				document.form1.indices.value += document.form1.listaIndice.options[i].value + ":" + document.form1.listaTipoIndice.options[i].value + "|";
				}
			document.form1.indices.value +=	"-";

		tipo_campos = ["nulo","texto","nulo","nulo","nulo","nulo","texto","nulo","texto"];
		nom_campos  = ["","Nombre del Formulario","","","","","Campos Generados","","Tipos de Datos"];
		if(validar_campos(tipo_campos, nom_campos))   {
			document.form1.paraGuardar.value=1;
		document.form1.submit();
		}
	}

// anexarIndice: genera un nuevo indice con su respectivo tipo de dato y lo almacena en una lista
	function anexarIndice(){
		correcto = true;
		//regExp=/[(\w)(\W)]/;
		regExp=/[(\w)]/;
		buscarLista = /LISTA/;
		paraLista = "";
		if(buscarLista.test(document.form1.tipoCampo.value))
			paraLista = ">>" + document.form1.lista.value;
		

		//if(campoVacio(" Indices ",3,regExp))
		if(document.form1.indice.value=="") {
			alert("No hay informaci&oacute;n para generar el Campo");
			document.form1.indice.focus();
			correcto = false
		}

		if(correcto){
			campo = new Option(document.form1.selDatoObligatorio.value+document.form1.indice.value+paraLista, document.form1.selDatoObligatorio.value+document.form1.indice.value+paraLista, false, false);
			document.form1.listaIndice.options[document.form1.listaIndice.length] = campo;

			campo1 = new Option(document.form1.tipoCampo.value, document.form1.tipoCampo.value, false, false);
			document.form1.listaTipoIndice.options[document.form1.listaTipoIndice.length] = campo1;
		}

		document.form1.indice.value = "";
		document.form1.lista.value = "";
		document.form1.indice.focus();
	}

// eliminarIndice: Elimina de la lista el indice con su respectivo tipo de dato
	function eliminarIndice(){
		nro_seleccionados = document.form1.listaIndice.options.length;
		for (var i = nro_seleccionados - 1; i >= 0 ; i--) {
			if (document.form1.listaIndice[i].selected||document.form1.listaTipoIndice[i].selected){
				document.form1.listaIndice[i] = null;
				document.form1.listaTipoIndice[i] = null;
				}
			}
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
.Estilo4 {
	color: #672324;
	font-weight: bold;
}
.Estilo11 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
.Estilo12 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>
</head>

<body>
<table width="860" border="0" align="center" cellpadding="0" cellspacing="3">
  <tr>
    <td colspan="3" bgcolor="#000000"><div align="right"><img src="imgs/logoQuimbaya.jpg" width="294" height="68" /></div></td>
  </tr>
  
  <tr>
    <td width="9%" bgcolor="#672322"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="112" height="51" border="0" /></a></div></td>
    <td colspan="2" bgcolor="#B5975C"><div align="center"><img src="imgs/textoCMQ.jpg" width="492" height="51" /></div></td>
  </tr>
  
  <tr>
    <td colspan="3"><table width="100%" border="1" cellpadding="10" cellspacing="0" bordercolor="#990000">
      <tr>
        <td>
		<form id="form1" name="form1" method="post" action="">
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
            <tr>
              <td bgcolor="#672324" class="Estilo1"><div align="center" class="Estilo2">ADMINISTRACI&Oacute;N DOCUMENTAL MINERA  </div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            
            
            <tr>
              <td><table width="650" border="1" cellspacing="0" cellpadding="0" align="left">
                <tr>
                  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocSeries.php" style="text-decoration:none">Crear Folder </a> </span></div></td>
                  <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo11">Asociar Formularios </span></div></td>
                  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocIndexar.php" style="text-decoration:none">Indexar Formularios </a></span></div></td>
				  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocReportes.php" style="text-decoration:none">Generar Reportes</a></span></div></td>
                  </tr>
              </table></td>
              </tr>      
            <tr>
              <td>
  <table align=center width="100%">
    <tr>
      <td colspan=3 bgcolor="#B5975C" align="center">
        <span class="Estilo4">::&nbsp;&nbsp;&nbsp;&nbsp;ASOCIAR FORMULARIOS &nbsp;&nbsp;&nbsp;&nbsp;::</span> </td>
					  </tr>			
    <tr>
      <td colspan=3 align="center">
        <hr size=1/>        </td>
					  </tr>

					  
<!--
    <tr>
      <td width="28%" class="Estilo12">Empresa: </td>
		<td colspan="2">
			<select name="idEmpresa" onChange="selExpediente(this.value)">
				<option value="0" selected="selected">Seleccione la Empresa
			<?php
				foreach($listaEmpresas as $cadaEmpresa)
					echo "<option value='{$cadaEmpresa["id"]}'>{$cadaEmpresa["nombre"]}";
			?>
			</select>		</td>
	  </tr>
-->
  <tr>
	<tr>
      <td width="28%" class="Estilo12">Folder: </td>
						 <td colspan="2">
						    <select name="selSerie" id="selSerie">
						      <option value="0" selected="selected">Seleccione el Folder
							<?php
								$serieDoc = new SeriesDocumentales();
								$cadaSerie = $serieDoc->selectAll();						
								foreach($cadaSerie as $reg) {
									echo "<option value='".$reg["id"]."'>".($reg["nombre"])."</option>\n";
								}						
							?>			                
							
							</select>						</td>
					  </tr>
    <tr>
      <td width="28%" class="Estilo12">Nombre del Formulario: </td>
						  <td colspan="2"><input type="text" name="txtNombreSubserie" size="45" onBlur="this.value = this.value.toUpperCase();"></td>
					  </tr>
    <tr>
		<td colspan=3 align="center" ><hr size="1"/></td>			
	</tr>  
    <tr>
		<td colspan=3 align="center" class="Estilo12" bgcolor="#DDDDDD"><div align="center"><b>CAMPOS DEL FORMULARIO</b></div></td>			
	</tr> 
	<tr>
		<td colspan=3 align="center" ><hr size="1"/></td>			
	</tr> 
	
					  <tr>
					    <td valign="top">
					      <span class="Estilo12">Nombre del Campo:</span><br>
					      <input type="text" name="indice" onBlur="this.value = this.value.toUpperCase();">
					      <br><span class="Estilo12">Valores de Lista separados por coma:</span><br>
					      <input type="text" name="lista" disabled onBlur="this.value = this.value.toUpperCase();">
					      <br><span class="Estilo12">Tipo de Campo</span>
					        <select name="tipoCampo" onchange="habilitaLista(this.value)">
							<?php
								foreach($listaTipos as $cadaTipo)
									echo "<option value='{$cadaTipo["nombre"]}'>{$cadaTipo["nombre"]}";
							?>
                            </select>
				          <p>
					          <span class="Estilo12">Obligatorio: </span>&nbsp;
					          <select name="selDatoObligatorio">
					            <option value="°">SI
				                <option value="" selected>NO
			                </select>
				          </td>
						  <td width="32%" valign="top">
						    <span class="Estilo12">Campos Generados:</span><br>
						    <select name="listaIndice" multiple size=9>
						    </select></td>			
					      <td width="40%" valign="top"><span class="Estilo12">Tipos de datos :</span><br />
                            <select name="listaTipoIndice" size="9" multiple="multiple" id="listaTipoIndice">
                            </select></td>
					  <tr>
					    <td align="center"><div align="left">
					      <input type="button" name="add" value="Anexar Campo ->>" id="add" onclick="anexarIndice()" />
				        </div></td>
				        <td align="center"><div align="left">
				          <input name="button" type="button" onclick="eliminarIndice()" value="<<- Eliminar Campo" />
			            </div></td>
				        <td align="center">&nbsp;</td>
					  </tr>
					  <tr>
					    <td colspan=3 align="center">
					      <hr size=1/>
					      <input type="button" value="Asociar Documento" onclick="guardar()">
					      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					      <input type="submit" value="Limpiar Formulario">
					      <hr size=1/>				        </td>
					  </tr>								
    </table>
			  </td></tr>
            <tr>
              <td>&nbsp;</td>
            </tr>			
          </table>
			<input name="indices" type="hidden" value = "">
			<input name="paraGuardar" type="hidden" id="paraGuardar">		  
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
