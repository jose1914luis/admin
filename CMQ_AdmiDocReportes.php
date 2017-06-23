<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Solicitudes.php");
	require_once("Modelos/Titulos.php");
	require_once("Modelos/Usuarios.php");
	require_once("Modelos/SeguimientosUsuarios.php");		
	

	$placa = "";
	$msgError = "";
	$clasificacion = "";
	$tituloClasificacion = "";
	$observacion = "";
	
	$validate = new Usuarios();	
	$accionPage = new SeguimientosUsuarios;
	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["codExpediente"])&&trim($_POST["codExpediente"])!="") {		
		if($_POST["estado"] == "BUSCAR") {
			$placa = trim(strtoupper($_POST["codExpediente"]));
			$titulo = new Titulos;
			$solicitud = new Solicitudes;

			if($titulo->getIdPlaca($placa)) {
				$tituloClasificacion = "TITULO";
				$clasificacion = '<input name="clasificacion" type="hidden" value="TITULO" />';
			} else if($solicitud->getIdPlaca($placa)) {
				$tituloClasificacion = "SOLICITUD";
				$clasificacion = '<input name="clasificacion" type="hidden" value="SOLICITUD" />';				
			} else {
				$msgError = "<script> alert('No existe codigo de expediente definido para el proceso de actualizacion de estado')</script>";	
				$placa = "";
			}	
			
		} else if ($_POST["estado"] == "ACTUALIZAR") {
			$observacion = "<br>Fecha Resoluci&oacute;n: ".$_POST["fechaResolucion"]." - Nro Resoluci&oacute;n: ".$_POST["nroResolucion"].".";
				
			if($_POST["clasificacion"]=="TITULO") {
				$titulo = new Titulos;				
				
				if ($_POST["estadoJuridico"]=="TITULO TERMINADO") 
					if($titulo->updateEstado($_POST["codExpediente"], $_POST["estadoJuridico"],  $_POST["fecha"], $observacion)) {
						$msgError = "<script> alert('Actualización satisfactoria del estado del titulo {$_POST["codExpediente"]} a TERMINADO')</script>";							
						$accionPage->generarAccion("Actualizacion satisfactoria del estado del titulo {$_POST["codExpediente"]} a TERMINADO");
					}
					else
						$msgError = "<script> alert('Error en el proceso de actualización del estado del titulo  {$_POST["codExpediente"]} a TERMINADO')</script>";	
				else if($_POST["estadoJuridico"]=="TITULO VIGENTE")
					if($titulo->updateEstado($_POST["codExpediente"], $_POST["estadoJuridico"], null, $observacion)) {
						$msgError = "<script> alert('Actualización satisfactoria del estado del titulo  {$_POST["codExpediente"]}  a VIGENTE')</script>";	
						$accionPage->generarAccion("Actualización satisfactoria del estado del titulo {$_POST["codExpediente"]} a VIGENTE");
					}
					else
						$msgError = "<script> alert('Error en el proceso de actualización del estado del titulo {$_POST["codExpediente"]} a VIGENTE')</script>";	
				else
					$msgError = "<script> alert('Error en el proceso de actualización del estado del titulo  {$_POST["codExpediente"]} a {$_POST["estadoJuridico"]}')</script>";								
			} else if ($_POST["clasificacion"]=="SOLICITUD") {
				$solicitud = new Solicitudes;				
				
				if ($_POST["estadoJuridico"]=="SOLICITUD OTORGADA") 
					if($solicitud->updateEstado($_POST["codExpediente"], $_POST["estadoJuridico"],  $_POST["fecha"], null, $observacion)) {
						$msgError = "<script> alert('Actualización satisfactoria del estado de la solicitud {$_POST["codExpediente"]} a OTORGADA')</script>";	
						$accionPage->generarAccion("Actualizacion satisfactoria del estado de la solicitud {$_POST["codExpediente"]} a OTORGADA");
					}
					else
						$msgError = "<script> alert('Error en el proceso de actualización del estado de la solicitud  {$_POST["codExpediente"]} a OTORGADA')</script>";	
				else if($_POST["estadoJuridico"]=="SOLICITUD VIGENTE")
					if($solicitud->updateEstado($_POST["codExpediente"], $_POST["estadoJuridico"], null, null, $observacion))  {
						$msgError = "<script> alert('Actualización satisfactoria del estado de la solicitud  {$_POST["codExpediente"]}  a VIGENTE')</script>";	
						$accionPage->generarAccion("Actualizacion satisfactoria del estado de la solicitud {$_POST["codExpediente"]} a VIGENTE");
					}
					else
						$msgError = "<script> alert('Error en el proceso de actualización del estado de la solicitud  {$_POST["codExpediente"]} a VIGENTE')</script>";	
				else if($_POST["estadoJuridico"]=="SOLICITUD ARCHIVADA")
					if($solicitud->updateEstado($_POST["codExpediente"], $_POST["estadoJuridico"], null, $_POST["fecha"], $observacion)) {
						$msgError = "<script> alert('Actualización satisfactoria del estado de la solicitud  {$_POST["codExpediente"]}  a ARCHIVADA')</script>";	
						$accionPage->generarAccion("Actualizacion satisfactoria del estado de la solicitud {$_POST["codExpediente"]} a ARCHIVADA");
					}
					else
						$msgError = "<script> alert('Error en el proceso de actualización del estado de la solicitud  {$_POST["codExpediente"]} a ARCHIVADA')</script>";	
				else
					$msgError = "<script> alert('Error en el proceso de actualización del estado de la solicitud  {$_POST["codExpediente"]} a {$_POST["estadoJuridico"]}')</script>";	
			}
		} else
			$msgError = "<script> alert('Error al identificar el estado suministrado...')</script>";
	
	} 


	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Gesti&oacute;n de Proyectos Mineros</title>
<script>
	function buscarExpediente() {
		if(document.forms[0].codExpediente.value!="") {
			document.forms[0].estado.value="BUSCAR";
			document.forms[0].submit();
		} else {
			alert("Se requiere el ingreso del codigo de expediente");	
		}
	}
	
	function validaFecha() {
		// reemplazar caracter '-' por '/'
		document.forms[0].fecha.value 			= document.forms[0].fecha.value.replace(/[-]/gi,"/");
		document.forms[0].fechaResolucion.value = document.forms[0].fechaResolucion.value.replace(/[-]/gi,"/");
	
		// validacion de la fecha:
		patron = /^\d{1,2}\/\d{1,2}\/\d{2,4}([\s](0[1-9]|1\d|2[0-3]):([0-5]\d):([0-5]\d))*$/;
		if (document.forms[0].fecha.value.search(patron)<0) {
			alert("El campo 'Fecha' se encuentra vacio o no posee un formato válido");
			document.forms[0].fecha.select();
			return 0;
		}
		
		// validacion de la fecha de resolucion:
		patron = /^\d{1,2}\/\d{1,2}\/\d{2,4}([\s](0[1-9]|1\d|2[0-3]):([0-5]\d):([0-5]\d))*$/;
		if (document.forms[0].fechaResolucion.value.search(patron)<0) {
			alert("El campo 'Fecha de Resolucion' se encuentra vacio o no posee un formato válido");
			document.forms[0].fechaResolucion.select();
			return 0;
		}		
		
		document.forms[0].submit();
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
                  <td><div align="center"><span class="Estilo11"><a href="CMQ_AdmiDocIndexar.php" style="text-decoration:none">Indexar Documentos </a></span></div></td>
				  <td bgcolor="#CCCCCC"><div align="center"><span class="Estilo11">Generar Reportes</span></div></td>
                  </tr>
              </table></td>
              </tr>      
            <tr>
              <td>
				<table align=center width="100%">
					<tr>
						<td colspan=2 bgcolor="#B5975C" align="center">
							<span class="Estilo4">::&nbsp;&nbsp;&nbsp;GENERACI&Oacute;N DE REPORTES   &nbsp;&nbsp;&nbsp;&nbsp;::</span> </td>
					</tr>			
					<tr>
						<td colspan=2 align="center">
							<hr size=1/>						</td>
					</tr>
					<tr>
					  <td><span class="Estilo12">C&oacute;digo del Expediente: </span></td>
					  <td><input type="text" name="txtNombreSerie" size=65 /></td>
					  </tr>
					<tr>
					  <td class="Estilo12">Tipo de Documento: </td>
					  <td>&nbsp;</td>
					  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
					<tr>
						<td width="25%">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan=2 align="center">
							<hr size=1/>
							<input type="button" value="Buscar">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="submit" value="Limpiar Formulario">
							<hr size=1/>						</td>
					</tr>
					<tr>
					  <td colspan=2 align="center">&nbsp;</td>
					  </tr>								
				</table>
                <!-- 
	CREACIÓN DE LA SUBSERIE DOCUMENTAL
-->              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
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
