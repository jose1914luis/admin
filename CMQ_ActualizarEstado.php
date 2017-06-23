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
	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["codExpediente"])&&trim($_POST["codExpediente"])!="") {		
		if($_POST["estado"] == "BUSCAR") {
			$placa = trim(strtoupper($_POST["codExpediente"]));
			$titulo = new Titulos;
			$solicitud = new Solicitudes;

			if($titulo->getIdPlaca($placa)) {
				$tituloClasificacion = "TITULO";
				$tituloClasificacionEstado = $titulo->getEstadoPlaca($placa);
				$clasificacion = '<input name="clasificacion" type="hidden" value="TITULO" />';
			} else if($solicitud->getIdPlaca($placa)) {
				$tituloClasificacion = "SOLICITUD";
				$tituloClasificacionEstado = $solicitud->getEstadoPlaca($placa);
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
						$accionPage->generarAccion("Actualizacion satisfactoria del estado del titulo {$_POST["codExpediente"]} a VIGENTE");
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
.Estilo3 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
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
              <td colspan="2" bgcolor="#D60B0A" class="Estilo1"><div align="center" class="Estilo2">ACTUALIZACI&Oacute;N DE ESTADO JUR&Iacute;DICO DE EXPEDIENTE </div></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2"><span class="Estilo3">Placa:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span> <input name="codExpediente" type="text" id="codExpediente" value="<?php echo $placa;?>" <?php if($placa) echo "readonly='readonly'" ?> />                <?php 
					if($clasificacion)
						echo $clasificacion;
				?>
                <input type="button" name="buscar" value="Buscar" onclick="buscarExpediente()"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="estado" type="hidden" id="estado" value="ACTUALIZAR" />
                <a href="CMQ_ActualizarEstado.php"><strong>[Nueva B&uacute;squeda]</strong></a>
                <div align="left">&nbsp;</div></td>
              </tr>
            
            <tr>
              <td colspan="2"><hr size="1" /></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
<?php
	if(isset($_POST["estado"]) && $_POST["estado"]=="BUSCAR" && !$msgError) {
?>
            <tr>
              <td width="47%" class="Estilo3">Clasificaci&oacute;n:</td>
              <td width="53%"><b><?php echo $tituloClasificacionEstado ?></b></td>
            </tr>
            <tr>
              <td class="Estilo3">N&uacute;mero de Resoluci&oacute;n: </td>
              <td><input name="nroResolucion" type="text" id="nroResolucion" size="45" /></td>
            </tr>
            <tr>
              <td class="Estilo3">Fecha Resoluci&oacute;n: (dd/mm/yyyy):</td>
              <td><input name="fechaResolucion" type="text" id="fechaResolucion" size="30" /></td>
            </tr>
            <tr>
              <td class="Estilo3">Fecha de Ejecutoria (dd/mm/yyyy): </td>
              <td><input name="fecha" type="text" id="fecha" size="30" /></td>
            </tr>
            <tr>
              <td class="Estilo3">Seleccionar Nuevo Estado Jur&iacute;dico: </td>
              <td><select name="estadoJuridico" id="estadoJuridico">
			  <?php if ($tituloClasificacion=='SOLICITUD') 
			  			{
			  ?>
                <option value="SOLICITUD ARCHIVADA">SOLICITUD ARCHIVADA</option>
                <option value="SOLICITUD OTORGADA">SOLICITUD OTORGADA</option>
                <option value="SOLICITUD VIGENTE">SOLICITUD VIGENTE</option>				
			  <?php
			  	} else if ($tituloClasificacion=='TITULO') { 
			  ?>
                <option value="TITULO TERMINADO">TITULO TERMINADO</option>
                <option value="TITULO VIGENTE">TITULO VIGENTE</option>
				<?php
					}
				?>
              </select>              </td>
            </tr>
            <tr>
              <td colspan="2"><hr size="1" /></td>
              </tr>
            <tr>
              <td><input type="button" name="Submit2" value="Actualizar Estado" onclick="validaFecha()"/></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2"><hr size="1" /></td>
            </tr>
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
