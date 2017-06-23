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
			$accionPage->generarAccion("Consulta de anotaciones de RMN");
		} else 
			$msgError = "<script> alert('Error en el almacenamiento de la anotación del título $placa')</script>";	
	} 
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMQ :: Gesti&oacute;n de Proyectos Mineros</title>
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
			<td width="9%"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="195" height="51" border="0" /></a></div></td>
			<td colspan="2" bgcolor="#2872A3"><div align="center"><img src="imgs/textoCMQ.jpg" width="562" height="51" /></div></td>
		  </tr>
  
  <tr>
    <td colspan="3"><table width="100%" border="1" cellpadding="10" cellspacing="0" bordercolor="#D60B0A">
      <tr>
        <td><form name="form1" method="post" action="CMQ_ConsultaRMN_Excel.php">
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
            <tr>
              <td colspan="2" bgcolor="#D60B0A" class="Estilo1"><div align="center" class="Estilo2">CONSULTA DE ANOTACIONES  - REGISTRO MINERO</div></td>
            </tr>
            
            <tr>
              <td colspan="2"><a href="CMQ_Detalle_RMN.php"></a></td>
            </tr>
            <tr>
              <td width="50%" class="Estilo3">C&oacute;digo de Expediente &nbsp;:&nbsp;
                <input name="codExpediente" type="text" id="codExpediente"/>
                <input name="estado" type="hidden" id="estado" value="GUARDAR" /></td>
              <td width="50%" class="Estilo3"><a href="CMQ_Consulta_RMN.php"><strong>Nueva Consulta</strong></a></td>
            </tr>
            <tr>
              <td class="Estilo3">Fecha Anotaci&oacute;n desde:&nbsp;
                <input name="fechaAnotacionDesde" type="text" id="fechaAnotacionDesde" size="12"/> 
                <span class="Estilo5">(dd-mm-aaaa)</span> </td>
              <td class="Estilo3">Fecha Anotaci&oacute;n hasta&nbsp;: 
                <input name="fechaAnotacionHasta" type="text" id="fechaAnotacionHasta" size="12" />
                <span class="Estilo5">                (dd-mm-aaaa)</span> </td>
            </tr>
            <tr>
              <td colspan="2" class="Estilo3">Tipo Anotaci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;
                <input name="tipoAnotacion" type="text" id="tipoAnotacion" size="80" /></td>
              </tr>
            

            <tr>
              <td colspan="2"><hr size="1" /></td>
              </tr>
            <tr>
              <td colspan="2"><div align="center"><span class="Estilo3">
                <input type="submit" name="Submit" value="Descargar Reporte Excel" />
              </span></div>                </td>
              </tr>
            <tr>
              <td colspan="2"><hr size=1 /></td>
              </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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
