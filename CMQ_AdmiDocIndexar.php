<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/SubSeriesDocumentales.php");
	require_once("Modelos/SeriesDocumentales.php");
	require_once("Modelos/Empresas.php");	
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
	
	$empresa 		= new Empresas();
	$listaEmpresas 	= $empresa->selectIdNameAll();

	// listado de las series documentales
	$serieDoc = new SeriesDocumentales();
	$cadaSerie = $serieDoc->selectAll();							

	$tipoDato 		= new TiposDatos();
	$listaTipos 	= $tipoDato->selectAll();
	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	


	
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
/*
	function selExpediente(idEmpresa)	{
			 $("#selSerie").load('viewExpedienteByEmpresa.php?idEmpresa='+idEmpresa);
		};	
*/
	function selFormulario(idSerie)	{
			 $("#selSubSerie").load('viewFormulariosByExpediente.php?selSerie='+idSerie);
		};	
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
        <td><form id="form1" name="form1" method="post" action="">
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
                  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocSeries.php" style="text-decoration:none">Crear Expediente </a> </span></div></td>
                  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocSubSeries.php" style="text-decoration:none">Asociar Documentos </a></span></div></td>
                  <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo11">Indexar Documentos </span></div></td>
				  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocReportes.php" style="text-decoration:none">Generar Reportes</a></span></div></td>
                  </tr>
              </table></td>
              </tr>      
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
					  <td><span class="Estilo12">Empresa: </span></td>
					  <td width="75%"><select name="idEmpresa">
                        <option value="0" selected="selected">Seleccione la Empresa
                        <?php
							foreach($listaEmpresas as $cadaEmpresa)
								echo "<option value='{$cadaEmpresa["id"]}'>{$cadaEmpresa["nombre"]}";
						?>
                        </option>
					    </select></td>
					  </tr>

					<tr>
					  <td><span class="Estilo12">Folder: </span></td>
					  <td><select name="selSerie" id="selSerie" onchange="selFormulario(this.value)">
						    <option value="0" selected="selected">Seleccione el Folder
							<?php
								foreach($cadaSerie as $reg) {
									echo "<option value='".$reg["id"]."'>".($reg["nombre"])."</option>\n";
								}						
							?>	                        
					    </select></td>
					  </tr>
					<tr>
						<td width="25%"><span class="Estilo12">Selecci&oacute;n del Formulario : </span></td>
						<td><select name="selSubSerie" id="selSubSerie" onchange="window.open('CMQ_AdmiDocIndexarImagen.php?idSubSerie='+this.value+'&idEmpresa='+document.forms[0].idEmpresa.value,'')">
							<option value="0">Seleccione Formulario</option>
						</select></td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
				</table>
                <!-- 
	CREACIÓN DE LA SUBSERIE DOCUMENTAL
-->              </td>
            </tr>
            
<?php
	if(isset($_POST["estado"]) && $_POST["estado"]=="BUSCAR" && !$msgError) {
?>
            
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
