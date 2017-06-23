<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Usuarios.php");
	
	$validate = new Usuarios();	
	
	if(empty($_SESSION["usuario_cmq"])||empty($_SESSION["passwd_cmq"]))
		echo "<script>document.location.href='".$GLOBALS ["url_error"]."'</script>";
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
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
    <td colspan="3"><table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#D60B0A">
      <tr>
        <td><p>&nbsp;</p>
          <table width="95%" border="0" align="center" cellpadding="0" cellspacing="5">
            <tr>
              <td>
				  <?php
					$img = "";
					$enlace = "IDB_DatosBasicos.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
					<div align="center"><a href="IDB_DatosBasicos.php"><img src="imgs/CDBasicos.jpg" title="Captura de Datos B&aacute;sicos" width="176" height="200" border="0" /></a></div>
				  <?php
					} else {
				  ?>		
					<div align="center"><a href="#"><img src="imgs/CDBasicos_gray.jpg" title="Captura de Datos B&aacute;sicos" width="176" height="200" border="0" /></a></div>
				  <?php
					}
				  ?>			  </td>
              <td>
				  <?php
					$img = "";
					$enlace = "CMQ_PagosOnline.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
				  <div align="center"><a href="CMQ_PagosOnline.php"><img src="imgs/PagosElectronicos.jpg" title="Reporte del estado de los pagos electrónicos realizados a SIGMIN" width="176" height="200" border="0" /></a></div>
				  <?php
					} else {
				  ?>		
				  <div align="center"><a href="#"><img src="imgs/PagosElectronicos_gray.jpg" title="Reporte del estado de los pagos electrónicos realizados a SIGMIN" width="176" height="200" border="0" /></a></div>
				  <?php
					}
				  ?>		    </td>
              <td>
				  <?php
					$img = "";
					$enlace = "CMQ_ConsultarDetalle.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
					<div align="center"><a href="CMQ_ConsultarDetalle.php"><img src="imgs/ConsultarExpedientes.jpg" title="Consulta Detallada de Expedientes Mineros" width="176" height="200" border="0"/></a></div>
				  <?php
					} else {
				  ?>		
				  <div align="center"><a href="#"><img src="imgs/ConsultarExpedientes_gray.jpg" title="Consulta Detallada de Expedientes Mineros" width="176" height="200"  border="0"/></a></div>
				  <?php
					}
				  ?>			  </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>
				  <?php
					$img = "";
					$enlace = "CMQ_ActualizarEstado.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
					<div align="center"><a href="CMQ_ActualizarEstado.php"><img src="imgs/ActualizarEstados.jpg" title="Actualizaci&oacute;n del estado jur&iacute;dico de t&iacute;tulos y solicitudes" width="176" height="200" border="0"/></a></div>
				  <?php
					} else {
				  ?>		
				  <div align="center"><a href="#"><img src="imgs/ActualizarEstados_gray.jpg" title="Actualizaci&oacute;n del estado jur&iacute;dico de t&iacute;tulos y solicitudes" width="176" height="200"  border="0"/></a></div>
				  <?php
					}
				  ?>			  
			  </td>
              <td>
				  <?php
					$img = "";
					$enlace = "CMQ_Prospectos.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
				  <div align="center"><a href="CMQ_Prospectos.php"><img src="imgs/GenerarProspectos.jpg" title="Generaci&oacute;n de Nuevas &Aacute;reas de &Iacute;nteres Minero" width="176" height="200" border="0" /></a></div>
				  <?php
					} else {
				  ?>		
				  <div align="center"><a href="#"><img src="imgs/GenerarProspectos_gray.jpg" title="Generaci&oacute;n de Nuevas &Aacute;reas de &Iacute;nteres Minero" width="176" height="200" border="0" /></a></div>
				  <?php
					}
				?>		    </td>
				<td>
				  <?php
					$img = "";
					$enlace = "CMQ_ConsultarEstudioDetalle.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
						<div align="center"><a href="CMQ_ConsultarEstudioDetalle.php"><img src="imgs/realizarSuperposicion.jpg" title="Análisis de superposiciones con solicitudes o prospectos de interés " width="176" height="200" border="0"/></a></div>
				 <?php
					} else {
				  ?>		
					<div align="center"><a href="#"><img src="imgs/realizarSuperposicion_gray.jpg" title="Análisis de superposiciones con solicitudes o prospectos de interés" width="176" height="200"  border="0"/></a></div>
				  <?php
					}
				  ?>			    </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><?php
					$img = "";
					$enlace = "CMQ_EliminarProspecto.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
                <div align="center"><a href="CMQ_EliminarProspecto.php"><img src="imgs/EliminarProspecto.jpg" title="Eliminar prospecto minero de CMQ" width="176" height="200" border="0"/></a></div>
                <?php
					} else {
				  ?>
                <div align="center"><a href="#"><img src="imgs/EliminarProspecto_gray.jpg" title="Eliminar prospecto minero de CMQ" width="176" height="200"  border="0"/></a></div>
                <?php
					}
				  ?></td>			
              <td><?php
					$img = "";
					$enlace = "CMQ_Restricciones.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
                <div align="center"><a href="CMQ_Restricciones.php"><img src="imgs/guardarRestriccion.jpg" title="Ingreso de Restricciones al Sistema CMQ" width="176" height="200" border="0"/></a></div>
                <?php
					} else {
				  ?>
                <div align="center"><a href="#"><img src="imgs/guardarRestriccion_gray.jpg" title="Ingreso de Restricciones al Sistema CMQ" width="176" height="200"  border="0"/></a></div>
                <?php
					}
				  ?></td>
              <td><?php
					$img = "";
					$enlace = "CMQ_Detalle_RMN.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
                <div align="center"><a href="CMQ_Detalle_RMN.php"><img src="imgs/ingresarAnotacion.jpg" title="Ingreso de Anotacion en el RMN" width="176" height="200" border="0"/></a></div>
                <?php
					} else {
				  ?>
                <div align="center"><a href="#"><img src="imgs/ingresarAnotacion_gray.jpg" title="Ingreso de Anotacion en el RMN" width="176" height="200"  border="0"/></a></div>
                <?php
					}
				  ?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><?php
					$img = "";
					$enlace = "CMQ_Consulta_RMN.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
                <div align="center"><a href="CMQ_Consulta_RMN.php"><img src="imgs/consultarAnotacion.jpg" title="Consulta de anotaciones mediante filtros" width="176" height="200" border="0"/></a></div>
                <?php
					} else {
				  ?>
                <div align="center"><a href="#"><img src="imgs/consultarAnotacion_gray.jpg" title="Consulta de anotaciones mediante filtros" width="176" height="200"  border="0"/></a></div>
                <?php
					}
				  ?></td>
              <td><?php
					$img = "";
					$enlace = "CMQ_EstadisticasConsulta.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
                <div align="center"><a href="CMQ_EstadisticasConsulta.php"><img src="imgs/reportesWEB.jpg" title="Reporte estadístico de consultas Web" width="176" height="200" border="0"/></a></div>
                <?php
					} else {
				  ?>
                <div align="center"><a href="#"><img src="imgs/reportesWEB_gray.jpg" title="Reporte estadístico de consultas Web" width="176" height="200"  border="0"/></a></div>
                <?php
					}
				  ?></td>
              <td><?php
					$img = "";
					$enlace = "CMQ_DownloadShapes.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
                <div align="center"><a href="CMQ_DownloadShapes.php"><img src="imgs/DescargarShapes.jpg" title="Descarga de Shapes ingresando las placas de los expedientes" width="176" height="200" border="0"/></a></div>
                <?php
					} else {
				  ?>
                <div align="center"><a href="#"><img src="imgs/DescargarShapes_gray.jpg" title="Descarga de Shapes ingresando las placas de los expedientes" width="176" height="200"  border="0"/></a></div>
                <?php
					}
				  ?></td>				  
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>			
			<tr>
              <td><?php
					$img = "";
					$enlace = "CMQ_UpdateOrigen.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
                <div align="center"><a href="CMQ_UpdateOrigen.php"><img src="imgs/CambioOrigenPoligono.jpg" title="Cambio de origen del area del expediente" width="176" height="200" border="0"/></a></div>
                <?php
					} else {
				  ?>
                <div align="center"><a href="#"><img src="imgs/CambioOrigenPoligono_gray.jpg" title="Cambio de origen del area del expediente" width="176" height="200"  border="0"/></a></div>
                <?php
					}
				  ?></td>	
				<td>
					<?php
						$img = "";
						$enlace = "CMQ_TareasAsignadas.php";
						if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
						?>
						<div align="center"><a href="CMQ_TareasAsignadas.php"><img src="imgs/AsignarTareas.jpg" title="Notificaci&oacute;n de actividades pendientes a los colaboradores" width="176" height="200" border="0"/></a></div>				  
						<?php
						} else {
						?>		
						<div align="center"><a href="#"><img src="imgs/AsignarTareas_gray.jpg" title="Notificaci&oacute;n de actividades pendientes a los colaboradores" width="176" height="200"  border="0"/></a></div>				  <?php
						}
					?>			  </td>				  
              <td><?php
					$img = "";
					$enlace = "cambiarClave.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
                <div align="center"><a href="cambiarClave.php"><img src="imgs/CambiarClave.jpg" title="Cambio de contrase&ntilde;a al usuario de CMQ" width="176" height="200" border="0"/></a></div>
                <?php
					} else {
				  ?>
                <div align="center"><a href="#"><img src="imgs/CambiarClave_gray.jpg" title="Cambio de contrase&ntilde;a al usuario de CMQ" width="176" height="200"  border="0"/></a></div>
                <?php
					}
				  ?></td>				
			</tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>			
			<tr>
              <td><?php
					$img = "";
					$enlace = "CMQ_EstadisticasFuncionarios.php";
					if($validate->validaEnlacePagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"], $enlace)) {
				  ?>
                <div align="center"><a href="CMQ_EstadisticasFuncionarios.php"><img src="imgs/trabajoFuncionarios.jpg" title="Estadisticas de trabajo de Funcionarios SIGMIN" width="176" height="200" border="0"/></a></div>
                <?php
					} else {
				  ?>
                <div align="center"><a href="#"><img src="imgs/trabajoFuncionarios_gray.jpg" title="Estadisticas de trabajo de Funcionarios SIGMIN" width="176" height="200"  border="0"/></a></div>
                <?php
					}
				  ?></td>
			</tr>			
          </table>
          <p>&nbsp;</p>
          </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
