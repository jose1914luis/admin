<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/SubSeriesDocumentales.php");
	require_once("Modelos/DocumentosSubseries.php");
	require_once("Modelos/TiposFormularios.php");
	require_once("Modelos/Usuarios.php");
	require_once("Modelos/SeguimientosUsuarios.php");		

	function generarCampo($idIndice, $tipoCampo, $valorLista="") {
		$valorLista = utf8_decode($valorLista);
		$tipoCampo 	= utf8_decode($tipoCampo);
	
		if($tipoCampo=="TEXTO") {
			echo "<input type='text' name='indice_$idIndice' size='40'>";
		} else if($tipoCampo=="TEXTO LARGO") {
			echo "<textarea name='indice_$idIndice' cols='70' rows='4'></textarea>";
		} else if($tipoCampo=="ENTERO") {
			echo "<input type='text' name='indice_$idIndice'>";
		} else if($tipoCampo=="DECIMAL") {
			echo "<input type='text' name='indice_$idIndice'>";
		} else if(strpos(" ".$tipoCampo, "FECHA")>0) {
			echo "<input type='text' name='indice_$idIndice' size='15'> (dd/mm/yyyy [hh:mi:ss])";
		} else if($tipoCampo=="LISTA DE SELECCION") {
			echo "<hr size=1>";
			echo "<select name='indice_$idIndice'>";
			echo "<option value='0'>Seleccion...";
			$itemsMenu = split(',', $valorLista);
			foreach($itemsMenu as $cadaItem)
				if($cadaItem != "")
					echo "<option value='".trim($cadaItem)."'>".trim($cadaItem);
			echo "</select>";
		} else if($tipoCampo=="LISTA SELECCION MULTIPLE") {
			echo "<hr size=1>";
			echo "<select name='indice_{$idIndice}[]' size='7' multiple=true>";
			echo "<option value='0'>Seleccion...";
			$itemsMenu = split(',', $valorLista);
			foreach($itemsMenu as $cadaItem)
				if($cadaItem != "")
					echo "<option value='".trim($cadaItem)."'>".trim($cadaItem);
			echo "</select>";

		} else if($tipoCampo=="LISTA DE CHEQUEO") {
			echo "<hr size=1>";
			$itemsMenu = split(',', $valorLista);
			$i=1;
			foreach($itemsMenu as $cadaItem) {
				if($cadaItem != "")
					echo "<input type='checkbox' name='indice_{$idIndice}[]' value='".trim($cadaItem)."'>".trim($cadaItem)."<br>";
					//echo "<input type='checkbox' name='indice_".$idIndice."_".$i."' value='".trim($cadaItem)."'>".trim($cadaItem)."<br>";
				$i++;
			}
		} else if($tipoCampo=="EMAIL") {
			echo "<input type='text' name='indice_$idIndice' size='50'>";
		} else if($tipoCampo=="LISTA CON RADIOBOTON") {
			echo "<hr size=1>";
			$itemsMenu = split(',', $valorLista);
			foreach($itemsMenu as $cadaItem)
				if($cadaItem != "")
					echo "<input type='radio' name='indice_$idIndice' value='".trim($cadaItem)."'>".trim($cadaItem)."<br>";
		}
	}	

	$placa = "";
	$msgError = "";
	$clasificacion = "";
	$tituloClasificacion = "";
	$observacion = "";
	$fechaHora = @date("d-m-Y H:i:s");
	$nombreImagen = session_id().md5($fechaHora).".pdf";
	$carpetaImagenes = "DocumentosElectronicos/";
	
	
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	$tiposForm = new TiposFormularios;
	
	
	$accionPage = new SeguimientosUsuarios;
	
	if(!empty($_GET)) {		
		$subserie = new SubSeriesDocumentales();
		$listaIndices = $subserie->selectIndicesByIdSubSerie($_GET["idSubSerie"]);
		$form1Accion = "CMQ_AdmiDocIndexarImagen.php?idSubSerie=".$_GET["idSubSerie"];
		//print_r($listaIndices);	
	}
	
	if(isset($_POST["codigoExpediente"])&&$_POST["codigoExpediente"]!="") {	
	
		$docSubserie = new DocumentosSubseries;
		$operacion = $docSubserie->insertAll($_POST);
		if( $operacion == 'OK') {
				//acciones con almacenamiento de cada indice
				$msgError = "<script>alert('Indexamiento almacenado correctamente'); </script>";
		} else
			$msgError = "<script>alert('".$operacion."'); </script>";
	}	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> -->
<script type="text/javascript" src="Utilidades/jquery.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Administraci&oacute;n Documental de Expedientes</title>
<script>
	function selExpediente(idEmpresa)	{
			 $("#selSerie").load('viewExpedienteByEmpresa.php?idEmpresa='+idEmpresa);
		};	

	function selFormulario(idSerie)	{
			 $("#selSubSerie").load('viewFormulariosByExpediente.php?selSerie='+idSerie);
		};	
	function loadDocRequeridos(placa) {
			if(placa != "")
			 $("#docQueRequiere").load('viewDocumentosRequieren.php?placa='+placa);
		};	
</script>
<style type="text/css">
<!--
.Estilo4 {
	color: #672324;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="3">
  
  
  <tr>
    <td width="9%" bgcolor="#672322"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="112" height="51" border="0" /></a></div></td>
    <td colspan="2" bgcolor="#B5975C"><div align="center"><img src="imgs/textoCMQ.jpg" width="492" height="51" /></div></td>
  </tr>
  
  <tr>
    <td colspan="3"><table width="100%" border="1" cellpadding="10" cellspacing="0" bordercolor="#990000">
      <tr>
        <td>
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
            

            <tr>
              <td>
				<table align=center width="100%">
					<tr>
						<td colspan=2 bgcolor="#B5975C" align="center">
							<span class="Estilo4">::&nbsp;&nbsp;&nbsp;&nbsp;INDEXAR DOCUMENTOS &nbsp;&nbsp;&nbsp;&nbsp;::</span> </td>
					</tr>			
					<tr>
						<td colspan=2 align="center">
							<hr size=1/>						</td>
					</tr>
					
					<tr>
					  <td width="50%" valign="top">
					  <p>
					  <center>
					  <form action="CMQ_SaveImage.php" method="post" enctype="multipart/form-data" name="form2" id="form2" target="pdfImages">
					     <p>Seleccionar Imagen :&nbsp;&nbsp;&nbsp; 
					       <input type="file" name="imagenFile" />
						   <input type="hidden" name="nameImgFile" value="<?php echo $nombreImagen; ?>"/>
						   <input type="hidden" name="folderImg" value="<?php echo $carpetaImagenes; ?>"/>						   
						   <input type="submit" name="imagenSave" value="Guardar Imagen" />
					     </p>
					  </form>
					  </center>
					  <iframe name="pdfImages" align="left" width="100%" height="600" src="DocumentosElectronicos/r2d2DocumentManagement.pdf" ></iframe></td>
					  <td width="50%" align="left" valign="top">&nbsp;
					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					  
					  <form action="<?php echo $form1Accion; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
					  <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="2" bgcolor="#EFEFEF"><div align="center"><strong><?php echo utf8_decode(strtoupper($listaIndices[0]["nombre_subserie"])); ?></strong> </div></td>
                          </tr>
                        <tr>
                          <td colspan="2"><hr size="1" /></td>
                          </tr>
						<tr>
						  
						  <td>CODIGO EXPEDIENTE  <input type="hidden" name="idEmpresa" value="<?php echo $_GET["idEmpresa"] ?>"/></td>
						  <td><input type="text" name="codigoExpediente" size="20" onchange="loadDocRequeridos(this.value.toUpperCase())" onBlur="this.value = this.value.toUpperCase();" /></td>
						  </tr>		
						<tr>
						  <td>TIPO DE FORMULARIO</td>
						  <td>
							<select name="tipoFormulario">
							<?php
								$forms = $tiposForm->selectAll();
								foreach($forms as $cadaForm)
									echo "<option value = '{$cadaForm["id"]}'>".utf8_decode($cadaForm["nombre"]);
							?>
							</select>
						  </td>
						</tr>	
						<tr>
						  <td>DOCUMENTOS A LOS QUE RESPONDE:</td>
						  <td>
							<select name="docQueRequiere[]"  id="docQueRequiere" multiple="multiple" size="5">
							</select>
						  </td>
						</tr>	
						
                        <?php 
							foreach($listaIndices as $cadaIndice) {
						?>
						<tr>
                          <td width="35%" align="left"><?php echo utf8_decode($cadaIndice["nombre_indice"]); ?></td>
                          <td width="65%"><?php echo generarCampo($cadaIndice["id_indice"], $cadaIndice["tipo_dato"], $cadaIndice["lista_parametros"]);  ?>
                            <div align="left"></div></td>
                        </tr>
						<?php
							}
						?>
                        <tr>
                          <td colspan="2"><hr size="1" /></td>
                          </tr>
                        <tr>
                          <td colspan="2"><div align="center">
                            <label>
                            <input type="submit" name="Submit" value="Guardar Informaci&oacute;n" />
                            </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label>
                            <input type="reset" name="Submit2" value="Restablecer Formulario" />
                            </label>
                          </div></td>
                        </tr>
                        <tr>
                          <td colspan="2"><hr size="1" /></td>
                        </tr>
                      </table>					  
					  <p>
					    <input name="idSubSerie" type="hidden" id="idSubSerie" value="<?php echo $_GET["idSubSerie"] ?>" />
						   <input type="hidden" name="nameImgFile" value="<?php echo $nombreImagen; ?>"/>
						   <input type="hidden" name="folderImg" value="<?php echo $carpetaImagenes; ?>"/>						
						
						
						</form>					</td>
					  </tr>
				</table>
<!-- 
	CREACIÓN DE LA SUBSERIE DOCUMENTAL
-->              </td>
            </tr>			
          </table>
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
