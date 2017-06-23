<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/AnotacionesRMN.php");
	require_once("Modelos/Usuarios.php");
	require_once("Modelos/SeguimientosUsuarios.php");		
	

	$placa = "";
	$msgError = "";
	$validate = new Usuarios();	
	$accionPage = new SeguimientosUsuarios;
	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	if(isset($_POST["codExpediente"])&&trim($_POST["codExpediente"])!="") {		
		$placa = trim(strtoupper($_POST["codExpediente"]));
		$anotacion = new AnotacionesRMN;

		if($anotacion->insertAll($_POST)) {
			$msgError = "<script> alert('Anotación del título $placa almacenada satisfactoriamente')</script>";	
			$accionPage->generarAccion("Ingreso de Anotación a la placa $placa");
		} else 
			$msgError = "<script> alert('Error en el almacenamiento de la anotación del título $placa')</script>";	
	} 
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Gesti&oacute;n de Proyectos Mineros</title>
<script>

	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g,"");
	}
	
	String.prototype.enter = function() {
		return this.replace(/\s*[\r\n][\r\n \t]*/g," ");
	}	

	function validaForm() {
	
		document.forms[0].codExpediente.value		= document.forms[0].codExpediente.value.trim();
		document.forms[0].fechaAnotacion.value		= document.forms[0].fechaAnotacion.value.trim();
		document.forms[0].fechaEjecutoria.value		= document.forms[0].fechaEjecutoria.value.trim();
		document.forms[0].tipoAnotacion.value		= document.forms[0].tipoAnotacion.value.trim();
		document.forms[0].tipoAnotacion.value		= document.forms[0].tipoAnotacion.value.enter();
		document.forms[0].observacionAnota.value	= document.forms[0].observacionAnota.value.trim();
	
	
		patron = /^([a-zA-Z0-9]|-)+$/;
		if (document.forms[0].codExpediente.value.search(patron)<0) {
			alert("El campo 'Placa' se encuentra vacio o no posee una extensión válida");
			document.forms[0].codExpediente.select();
			return 0;
		}

		patron = /^\d{1,2}-\d{1,2}-\d{4}$/;  //^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$
		if (document.forms[0].fechaAnotacion.value.search(patron)<0) {
			alert("El campo 'Fecha Anotación' se encuentra vacio o no posee un formato válido");
			document.forms[0].fechaAnotacion.select();
			return 0;
		}
		
		patron = /^\d{1,2}-\d{1,2}-\d{4}$/;
		if (document.forms[0].fechaEjecutoria.value.search(patron)<0) {
			alert("El campo 'Fecha Ejecutoria' se encuentra vacio o no posee un formato válido");
			document.forms[0].fechaEjecutoria.select();
			return 0;
		}
		
		patron = /^\w+/;
		if (document.forms[0].tipoAnotacion.value.search(patron)<0) {
			alert("El campo 'Tipo de Anotación' se encuentra vacio");
			document.forms[0].tipoAnotacion.select();
			return 0;
		}		

		patron = /^\w+/;
		if (document.forms[0].observacionAnota.value.search(patron)<0) {
			alert("El campo 'Observación' se encuentra vacio");
			document.forms[0].observacionAnota.select();
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
.Estilo5 {font-size: 10px}
-->
</style>
</head>

<body>
<table width="860" border="0" align="center" cellpadding="0" cellspacing="3">
		  <tr>
			<td colspan="3"><div align="right"><img src="imgs/logoQuimbaya.jpg" width="294" height="68" /></div></td>
		  </tr>  
		  <tr>
			<td width="195"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="195" height="51" border="0" /></a></div></td>
			<td colspan="2" bgcolor="#2872A3"><div align="center"><img src="imgs/textoCMQ.jpg" width="562" height="51" /></div></td>
		  </tr>    
  <tr>
    <td colspan="3"><table width="100%" border="1" cellpadding="10" cellspacing="0" bordercolor="#D60B0A">
      <tr>
        <td><form id="form1" name="form1" method="post" action="">
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
            <tr>
              <td colspan="2" bgcolor="#D60B0A" class="Estilo1"><div align="center" class="Estilo2">INGRESO DE ANOTACI&Oacute;N - REGISTRO MINERO</div></td>
            </tr>
            
            <tr>
              <td colspan="2"><a href="CMQ_Detalle_RMN.php"></a></td>
            </tr>
            <tr>
              <td width="50%" class="Estilo3">C&oacute;digo de Expediente :&nbsp;
                <input name="codExpediente" type="text" id="codExpediente"/>
                <input name="estado" type="hidden" id="estado" value="GUARDAR" /></td>
              <td width="50%" class="Estilo3"><a href="CMQ_Detalle_RMN.php"><strong>[Nueva Anotaci&oacute;n]</strong></a></td>
            </tr>
            <tr>
              <td class="Estilo3">Fecha Anotaci&oacute;n &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                <input name="fechaAnotacion" type="text" size="15"/> 
                <span class="Estilo5">(dd-mm-aaaa)</span> </td>
              <td class="Estilo3">Fecha Ejecutoria &nbsp;&nbsp;: 
                <input name="fechaEjecutoria" type="text" size="15" />
                <span class="Estilo5">                (dd-mm-aaaa)</span> </td>
            </tr>
            <tr>
              <td colspan="2" class="Estilo3">Tipo Anotaci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
				  <textarea name="tipoAnotacion" cols="75" rows="2"></textarea>
				</td>
              </tr>
            
            <tr>
              <td colspan="2" class="Estilo3">Observaci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<br />
                  <textarea name="observacionAnota" cols="100" rows="5"></textarea></td>
              </tr>
            
            <tr>
              <td colspan="2"><hr size="1" /></td>
              </tr>
            <tr>
              <td colspan="2"><div align="center">
                <input type="button" name="Submit2" value="Guardar Anotaci&oacute;n" onclick="validaForm()"/>

&nbsp;&nbsp;&nbsp;&nbsp;                <a href="CMQ_Detalle_RMN.php" class="Estilo3"><strong>[Nueva Anotaci&oacute;n]</strong></a></div>                </td>
              </tr>
            <tr>
              <td colspan="2"><hr size=1 /></td>
              </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">
<?PHP
if($placa != "")
	include("reportePlacaRMN.php");
?>			  
			  
			  &nbsp;</td>
              </tr>
            
            <tr>
              <td colspan="2">			  </td>
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
