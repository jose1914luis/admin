<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/SeriesDocumentales.php");
	//require_once("Modelos/Empresas.php");
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
	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["txtNombreSerie"])&&trim($_POST["txtNombreSerie"])!="") {		
		$serie = new SeriesDocumentales();
		$placa = strtoupper($_POST["txtNombreSerie"]);		
		if(!$serie->existeSerie($placa)) {
			$campos = array("nombre" => $placa);
			$operaInsert = $serie->insertAll($campos);
			if($operaInsert == "OK") 
				$msgError = "<script> alert('El Folder $placa ha sido generado exitosamente')</script>";
			else
				$msgError = "<script> alert('$operaInsert')</script>";
			 
		} else
			$msgError = "<script> alert('El Folder $placa ya se encuentra definido en el sistema')</script>";	
	} 
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Administraci&oacute;n Documental de Expedientes</title>
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
			<td colspan="3"><div align="right"><img src="imgs/logoQuimbaya.jpg" width="294" height="68" /></div></td>
		  </tr>  
		  <tr>
			<td width="9%"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="195" height="51" border="0" /></a></div></td>
			<td colspan="2" bgcolor="#2872A3"><div align="center"><img src="imgs/textoCMQ.jpg" width="562" height="51" /></div></td>
		  </tr>
  
  <tr>
    <td colspan="3"><table width="100%" border="1" cellpadding="10" cellspacing="0" bordercolor="#D60B0A">
      <tr>
        <td><form id="form1" name="form1" method="post" action="">
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
            <tr>
              <td bgcolor="#D60B0A" class="Estilo1"><div align="center" class="Estilo2">ADMINISTRACI&Oacute;N DOCUMENTAL MINERA  </div></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            
            
            <tr>
              <td><table width="650" border="1" cellspacing="0" cellpadding="0" align="left">
                <tr>
                  <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo11">Crear Folder</span></div></td>
                  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocSubSeries.php" style="text-decoration:none">Asociar Formularios </a></span></div></td>
                  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocIndexar.php" style="text-decoration:none">Indexar Formularios </a></span></div></td>
				  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocReportes.php" style="text-decoration:none">Generar Reportes</a></span></div></td>
                  </tr>
              </table></td>
              </tr>      
            <tr>
              <td>
				<table align=center width="100%">
					<tr>
						<td colspan=2 bgcolor="#FFFF00" align="center">
							<span class="Estilo4">::&nbsp;&nbsp;&nbsp;&nbsp;CREAR FOLDER &nbsp;&nbsp;&nbsp;&nbsp;::</span> </td>
					</tr>			
					<tr>
						<td colspan=2 align="center">
							<hr size=1/>
						</td>
					</tr>
	<!-- Removida para ser incorporada al indexar ::001
					<tr>
						<td width="25%"><span class="Estilo12">Nombre de la Empresa: </span></td>
						<td>
						    <select name="idEmpresa">
								<option value="0" selected="selected">Seleccione la Empresa
							<?php
								foreach($listaEmpresas as $cadaEmpresa)
									echo "<option value='{$cadaEmpresa["id"]}'>{$cadaEmpresa["nombre"]}";
							?>
			                </select>						
						</td>
					</tr>
	::001 -->
					<tr>
						<td width="25%"><span class="Estilo12">Nombre del Folder: </span></td>
						<td><input type="text" name="txtNombreSerie" size=65></td>
					</tr>
					<tr>
						<td colspan=2 align="center">
							<hr size=1/>
							<input type="submit" value="Crear Folder">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="submit" value="Limpiar Formulario">
							<hr size=1/>
						</td>
					</tr>								
				</table>

              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>		
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
