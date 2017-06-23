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
	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
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
    <td colspan="3" bgcolor="#000000"><div align="right"><img src="imgs/logoQuimbaya.jpg" width="294" height="68" /></div></td>
  </tr>
  
  <tr>
    <td width="9%" bgcolor="#672322"><div align="center"><a href="menuPrincipal.php"><img src="imgs/cmq_logo.jpg" title="Ir al Menu Principal" width="112" height="51" border="0" /></a></div></td>
    <td colspan="2" bgcolor="#B5975C"><div align="center"><img src="imgs/textoCMQ.jpg" width="492" height="51" /></div></td>
  </tr>
  
  <tr>
    <td colspan="3"><table width="100%" border="1" cellpadding="10" cellspacing="0" bordercolor="#990000">
      <tr>
        <td><form action="CMQ_ConsultaRMN_Excel.php" method="post" enctype="multipart/form-data" name="form1">
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
            <tr>
              <td colspan="4" bgcolor="#672324" class="Estilo1"><div align="center" class="Estilo2">FORMULARIO DE MUESTREO</div></td>
            </tr>
            
            <tr>
              <td width="18%" class="Estilo3">Proyecto:</td>
              <td colspan="3" class="Estilo3"><select name="idProyecto">
                <option value="1">PRY-G&oacute;mez Plata</option>
                <option value="2">PRY_Remedios</option>
              </select>              </td>
            </tr>
            <tr>
              <td colspan="4" class="Estilo3"><hr size=1 /></td>
              </tr>
            <tr>
              <td class="Estilo3">Departamento:</td>
              <td class="Estilo3"><select name="idDepartamento">
                <option value="0">Seleccionar ...</option>
                <option value="1">Antioquia</option>
                <option value="2">Tolima</option>
                            </select></td>
              <td class="Estilo3">Municipio:</td>
              <td class="Estilo3"><select name="idMunicipio">
                <option value="0">Seleccionar...</option>
                <option value="1">G&oacute;mez Plata</option>
                <option value="2">Remedios</option>
                <option value="3">Rovira</option>
                            </select></td>
            </tr>
            
            <tr>
              <td colspan="4" class="Estilo3"><hr size=1 /></td>
              </tr>
            <tr>
              <td class="Estilo3">N&uacute;mero Muestra: </td>
              <td width="30%" class="Estilo3"><input name="nroMuestra" type="text" id="nroMuestra" size="5" /></td>
              <td width="11%" class="Estilo3">Fecha:</td>
              <td width="41%" class="Estilo3"><input name="fechaReporte" type="text" id="fechaReporte" size="20" maxlength="10" /></td>
            </tr>
            
            <tr>
              <td colspan="4" class="Estilo3"><hr size=1 /></td>
              </tr>
            <tr>
              <td colspan="2" class="Estilo3">Coordenadas Localizaci&oacute;n WGS84 &nbsp;(Ej: N7.02900 W74.53606): </td>
              <td colspan="2" class="Estilo3"><input name="coordenadasWGS84" type="text" id="coordenadasWGS84" size="47" maxlength="45" /></td>
              </tr>
            <tr>
              <td class="Estilo3">Descripci&oacute;n de Localizaci&oacute;n:</td>
              <td colspan="3" class="Estilo3"><textarea name="descLocalizacion" cols="78" rows="2" id="descLocalizacion"></textarea></td>
            </tr>
            <tr>
              <td colspan="4" class="Estilo3"><hr size=1 /></td>
              </tr>
            <tr>
              <td class="Estilo3">Tipo de Muestra: </td>
              <td colspan="3" class="Estilo3"><select name="tipoMuestra">
                <option value="0">Seleccionar ...</option>
                <option value="1">BG - Volumen de oro extraible por lixiviacion</option>
              </select>              </td>
            </tr>
            <tr>
              <td class="Estilo3">Espesor de la muestra: </td>
              <td class="Estilo3"><input name="nroMuestra2" type="text" id="nroMuestra2" size="10" maxlength="10" /></td>
              <td class="Estilo3">Espesor de la Veta: </td>
              <td class="Estilo3"><input name="nroMuestra22" type="text" id="nroMuestra22" size="10" maxlength="10" /></td>
            </tr>
            <tr>
              <td class="Estilo3">Orientaci&oacute;n:</td>
              <td colspan="3" class="Estilo3"><textarea name="textarea" cols="78" rows="2"></textarea></td>
            </tr>
            <tr>
              <td colspan="4" class="Estilo3"><hr size=1 /></td>
              </tr>
            <tr>
              <td class="Estilo3">Sulfuros:</td>
              <td class="Estilo3"><textarea name="descSulfuros" cols="35" rows="3" id="descSulfuros"></textarea></td>
              <td class="Estilo3">Porcentaje Sulfuros: </td>
              <td class="Estilo3"><input name="porcentajeSulfuros" type="text" id="porcentajeSulfuros" size="10" maxlength="10" /></td>
            </tr>
            <tr>
              <td colspan="4" class="Estilo3"><hr size=1 /></td>
              </tr>
            <tr>
              <td class="Estilo3">Descripci&oacute;n de los Respaldos: </td>
              <td colspan="3" class="Estilo3"><textarea name="textarea2" cols="78" rows="2"></textarea></td>
            </tr>
            <tr>
              <td class="Estilo3">Tipo de Estructura: </td>
              <td colspan="3" class="Estilo3"><select name="tipoEstructura" id="tipoEstructura">
                <option value="0">Seleccionar ...</option>
                <option value="1">FWS - Estructura de piso</option>
              </select>
              </td>
            </tr>
            <tr>
              <td class="Estilo3">Datos Estructurales: </td>
              <td colspan="3" class="Estilo3"><textarea name="datosEstructurales" cols="78" rows="3" id="datosEstructurales"></textarea></td>
            </tr>
            <tr>
              <td class="Estilo3">Cargar Esquema: </td>
              <td colspan="3" class="Estilo3"><input name="fileEsquema" type="file" id="fileEsquema" size="60" /></td>
            </tr>
            <tr>
              <td colspan="4" class="Estilo3"><hr size=1 /></td>
              </tr>
            <tr>
              <td class="Estilo3">Observaciones:</td>
              <td colspan="3" class="Estilo3">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" class="Estilo3"><textarea name="textarea3" cols="110" rows="6" id="textarea"></textarea></td>
              </tr>
            
            

            <tr>
              <td colspan="4"><hr size="1" /></td>
              </tr>
            <tr>
              <td colspan="4"><div align="center"><span class="Estilo3">
                <input type="submit" name="Submit" value="Guardar Muestra" />
              </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>[Cancelar]</strong></div>                </td>
              </tr>
            <tr>
              <td colspan="4"><hr size=1 /></td>
              </tr>
            
            
            
            <tr>
              <td colspan="4">			  </td>
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
