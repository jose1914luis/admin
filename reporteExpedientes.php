<?php
	session_start();

	require("Acceso/Config.php");
	require("Modelos/ConsultasCMQ.php");
	require("Modelos/SeguimientosUsuarios.php");	
	require("Utilidades/procesarCoordenadasWKT.php");	
	require_once("Modelos/Usuarios.php");

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	
	$placa 				= $_GET["placa"];
	$tipoExpediente 	= $_GET["tipoExpediente"];
	$msgProceso 		= "";
	$tabla 				= "";
	$existeExpediente 	= 0;
	
	$consultar = new ConsultasCMQ;
	
	// Procesamiento de expedientes, que pueden ser titulos o solicitudes	
	$expediente = $consultar->generarReporte($placa, $tipoExpediente);	
	
	$accionPage = new SeguimientosUsuarios;
	$accionPage->generarAccion("Generacion de reporte de expediente. $tipoExpediente: $placa.");


	if(!empty($expediente))
		$existeExpediente = 1; // Si existen resultados al expediente en cuestión
		
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>:: CMQ :: Reporte de Expedientes</title>
<style type="text/css">
<!--
.Estilo1 {
	color: #D60B0A;
	font-weight: bold;
}

.tituloArea {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<?php 
	if($tipoExpediente == 'SOLICITUD') {
?>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="5">
  <tr>
    <td colspan="6"><img src="imgs/reporteLogoSIGMIN.jpg" width="901" height="121" /></td>
  </tr>
  <tr>
    <td colspan="6" bgcolor="#EDEDED"><div align="center" class="Estilo1">REPORTE DE INFORMACI&Oacute;N DE EXPEDIENTE </div></td>
  </tr>
  <tr>
    <td width="64"><strong>Placa:</strong></td>
    <td width="149"><?php echo utf8_decode($expediente["placa"]); ?></td>
    <td width="120"><strong>Radicaci&oacute;n:</strong></td>
    <td width="177"><?php echo utf8_decode($expediente["fecha_radicacion"]); ?></td>
    <td width="131"><strong>Modalidad:</strong></td>
    <td width="235"><?php echo utf8_decode($expediente["modalidad"]); ?></td>
  </tr>
  <tr>
    <td><strong>Tipo:</strong></td>
    <td><?php echo $tipoExpediente; ?></td>
    <td><strong>Estado Jur&iacute;dico:</strong> </td>
    <td><?php echo utf8_decode($expediente["estado_juridico"]); ?></td>
    <td><strong>Grupo de Trabajo: </strong></td>
    <td><?php echo utf8_decode($expediente["grupo_trabajo"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Solicitante(s):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["solicitantes"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Direcci&oacute;n de Correspondencia: </strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["direccion_correspondencia"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Tel&eacute;fono de Contacto:</strong> </td>
    <td colspan="4"><?php echo utf8_decode($expediente["telefono_contacto"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Mineral(es):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["minerales"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Municipio(s):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["municipios"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Sistema de Origen:</strong> </td>
    <td><?php echo utf8_decode($expediente["sistema_origen"]); ?></td>
    <td><strong>Descripci&oacute;n PA:</strong> </td>
    <td colspan="2"><?php echo utf8_decode($expediente["descripcion_pa"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Area Solicitada Has: </strong></td>
    <td><?php echo utf8_decode($expediente["area_solicitada_ha"]); ?></td>
    <td><strong>Area Definitiva Has: </strong></td>
    <td colspan="2"><?php echo utf8_decode($expediente["area_def_has"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="6"><strong>Coordenadas de Pol&iacute;gono:</strong> </td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">
<?php
	$a = 0;
	$areasPoly = procesarCoordenadasWKT($expediente["coordenadas"]);
	
	foreach($areasPoly as $area) {
		$exc=0;
		foreach($area as $cadaArea) {
			$coords = explode(",", $cadaArea);
			$punto 	= 0;
			
			echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";
			
			$nroCoordenadas = sizeof($coords);
			foreach($coords as $cadaCoord) {
				if($punto == ($nroCoordenadas-1))	break;
				if($punto==0 && $exc==0) {
					echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA ".($a + 1)."</b></div></td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
				else if($punto==0 && $exc>0) {
					echo "<tr><td colspan=3>AREA ".($a + 1)." : Exclusi&oacute;n ".($exc)."</td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
					
				$xy = explode(" ",$cadaCoord);
				echo "<tr><td><center>".($punto + 1)."</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

				$punto++;
			}			
			$exc++;
		}	
		$a++;
		echo "</table>";
	}
?></td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
</table>

<?php
	}
?>

<?php 
	if($tipoExpediente == 'TITULO') {
?>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="5">
  <tr>
    <td colspan="6"><img src="imgs/reporteLogoCMQ.jpg" width="901" height="121" /></td>
  </tr>
  <tr>
    <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">REPORTE DE INFORMACI&Oacute;N DE EXPEDIENTE </div></td>
  </tr>
  <tr>
    <td><strong>Placa:</strong></td>
    <td><?php echo utf8_decode($expediente["placa"]); ?></td>
    <td><strong>C&oacute;digo RMN: </strong></td>
    <td><?php echo utf8_decode($expediente["codigo_rmn"]); ?></td>
    <td><strong>C&oacute;digo Anterior: </strong></td>
    <td><?php echo utf8_decode($expediente["codigo_anterior"]); ?></td>
  </tr>
  <tr>
    <td width="112"><strong>Fecha Inscripci&oacute;n :</strong></td>
    <td width="172"><?php echo utf8_decode($expediente["fecha_inscripcion"]); ?></td>
    <td width="129"><strong>Fecha Terminaci&oacute;n: </strong></td>
    <td width="168"><?php echo utf8_decode($expediente["fecha_terminacion"]); ?></td>
    <td width="110"><strong>Modalidad:</strong></td>
    <td width="185"><?php echo utf8_decode($expediente["modalidad"]); ?></td>
  </tr>
  
  <tr>
    <td><strong>Tipo:</strong></td>
    <td><?php echo $tipoExpediente; ?></td>
    <td><strong>Estado Jur&iacute;dico:</strong> </td>
    <td><?php echo utf8_decode($expediente["estado_juridico"]); ?></td>
    <td><strong>Grupo de Trabajo: </strong></td>
    <td><?php echo utf8_decode($expediente["grupo_trabajo"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Titular(s):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["titulares"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Direcci&oacute;n de Correspondencia: </strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["direccion_correspondencia"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Tel&eacute;fono de Contacto:</strong> </td>
    <td colspan="4"><?php echo utf8_decode($expediente["telefono_contacto"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Mineral(es):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["minerales"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Municipio(s):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["municipios"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Sistema de Origen:</strong> </td>
    <td><?php echo utf8_decode($expediente["sistema_origen"]); ?></td>
    <td><strong>Descripci&oacute;n PA:</strong> </td>
    <td colspan="2"><?php echo utf8_decode($expediente["descripcion_pa"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Area Otorgada Has: </strong></td>
    <td><?php echo utf8_decode($expediente["area_otorgada_ha"]); ?></td>
    <td><strong>Area Definitiva Has: </strong></td>
    <td colspan="2"><?php echo utf8_decode($expediente["area_def_has"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="6"><strong>Coordenadas de Pol&iacute;gono:</strong> </td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">
<?php
	$a = 0;
	$areasPoly = procesarCoordenadasWKT($expediente["coordenadas"]);
	
	foreach($areasPoly as $area) {
		$exc=0;
		foreach($area as $cadaArea) {
			$coords = explode(",", $cadaArea);
			$punto 	= 0;
			
			echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";
			
			$nroCoordenadas = sizeof($coords);
			foreach($coords as $cadaCoord) {
				if($punto == ($nroCoordenadas-1))	break;
				if($punto==0 && $exc==0) {
					echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA ".($a + 1)."</b></div></td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
				else if($punto==0 && $exc>0) {
					echo "<tr><td colspan=3>AREA ".($a + 1)." : Exclusi&oacute;n ".($exc)."</td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
					
				$xy = explode(" ",$cadaCoord);
				echo "<tr><td><center>".($punto + 1)."</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

				$punto++;
			}			
			$exc++;
		}	
		$a++;
		echo "</table>";
	}
?></td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
</table>

<?php
	}
?>

<?php 
	if($tipoExpediente == 'PROSPECTO') {
?>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="5">
  <tr>
    <td colspan="5"><img src="imgs/reporteLogoCMQ.jpg" width="901" height="121" /></td>
  </tr>
  <tr>
    <td colspan="5" bgcolor="#E1E1E1"><div align="center" class="Estilo1">REPORTE DE INFORMACI&Oacute;N DE PROSPECTO MINERO </div></td>
  </tr>
  <tr>
    <td><strong>Placa:</strong></td>
    <td><?php echo utf8_decode($expediente["placa"]); ?></td>
    <td><strong>Fecha Creaci&oacute;n: </strong></td>
    <td><?php echo utf8_decode($expediente["fecha_creacion"]); ?></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="5"><hr size="1" /></td>
  </tr>
  
  <tr>
    <td><strong>Sistema de Origen:</strong> </td>
    <td width="264"><?php echo utf8_decode($expediente["sistema_origen"]); ?></td>
    <td width="160"><strong>Area Definitiva Has:</strong></td>
    <td width="283"><?php echo utf8_decode($expediente["area_has"]); ?></td>
  </tr>
  
  <tr>
    <td colspan="5"><hr size="1" /></td>
  </tr>  
  <tr>
    <td width="179"><strong>Municipio(s):</strong></td>
    <td colspan="3"><?php echo utf8_decode($expediente["municipios"]); ?></td>
  </tr>
  <tr>
    <td colspan="5"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="5"><strong>Coordenadas de Pol&iacute;gono:</strong> </td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5">
<?php
	$a = 0;
	$areasPoly = procesarCoordenadasWKT($expediente["coordenadas"]);
	
	foreach($areasPoly as $area) {
		$exc=0;
		foreach($area as $cadaArea) {
			$coords = explode(",", $cadaArea);
			$punto 	= 0;
			
			echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";
			
			$nroCoordenadas = sizeof($coords);
			foreach($coords as $cadaCoord) {
				if($punto == ($nroCoordenadas-1))	break;
				if($punto==0 && $exc==0) {
					echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA ".($a + 1)."</b></div></td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
				else if($punto==0 && $exc>0) {
					echo "<tr><td colspan=3>AREA ".($a + 1)." : Exclusi&oacute;n ".($exc)."</td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
					
				$xy = explode(" ",$cadaCoord);
				echo "<tr><td><center>".($punto + 1)."</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

				$punto++;
			}			
			$exc++;
		}	
		$a++;
		echo "</table>";
	}
?>  </td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><hr size="1" /></td>
  </tr>
  
  <tr>
    <td colspan="5">	
	<center><iframe id="iframecontenido"  name="iframecontenido" src="visorCapturas/visualizaPoligonoReporte.php?codExpediente=<?php echo utf8_decode($expediente["placa"]); ?>&clasificacion=PROSPECTO" width="800" height="500"></iframe></center>	
	</td>
  </tr>
  <tr>
    <td colspan="5"><hr size=1 /></td>
  </tr>
</table>

<?php
	}
?>

<?php 
	if($tipoExpediente == 'ESTUDIO_SOLICITUD') {

?>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="5">
  <tr>
    <td colspan="6"><img src="imgs/reporteLogoCMQ.jpg" width="901" height="121" /></td>
  </tr>
  <tr>
    <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">REPORTE DE INFORMACI&Oacute;N DE SOLICITUD EVALUADA </div></td>
  </tr>
  <tr>
    <td width="64"><strong>Placa:</strong></td>
    <td width="149"><?php echo utf8_decode($expediente["placa"]); ?></td>
    <td width="120"><strong>Radicaci&oacute;n:</strong></td>
    <td width="177"><?php echo utf8_decode($expediente["fecha_radicacion"]); ?></td>
    <td width="131"><strong>Modalidad:</strong></td>
    <td width="235"><?php echo utf8_decode($expediente["modalidad"]); ?></td>
  </tr>
  <tr>
    <td><strong>Tipo:</strong></td>
    <td><?php echo $tipoExpediente; ?></td>
    <td><strong>Estado Jur&iacute;dico:</strong> </td>
    <td><?php echo utf8_decode($expediente["estado_juridico"]); ?></td>
    <td><strong>Grupo de Trabajo: </strong></td>
    <td><?php echo utf8_decode($expediente["grupo_trabajo"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Solicitante(s):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["solicitantes"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Direcci&oacute;n de Correspondencia: </strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["direccion_correspondencia"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Tel&eacute;fono de Contacto:</strong> </td>
    <td colspan="4"><?php echo utf8_decode($expediente["telefono_contacto"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Mineral(es):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["minerales"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Municipio(s):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["municipios"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Sistema de Origen:</strong> </td>
    <td><?php echo utf8_decode($expediente["sistema_origen"]); ?></td>
    <td><strong>Descripci&oacute;n PA:</strong> </td>
    <td colspan="2"><?php echo utf8_decode($expediente["descripcion_pa"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Area Solicitada Has:</strong></td>
    <td><?php echo utf8_decode($expediente["area_solicitada_ha"]); ?></td>
    <td><strong>Area Definitiva Has: </strong></td>
    <td colspan="2"><?php echo utf8_decode($expediente["area_def_has"]); ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Area Antes Estudio Ha:</strong> </td>
    <td><?php echo utf8_decode($expediente["area_def_has"]); ?></td>
    <td><strong>&Aacute;rea Despues Estudio Ha: </strong></td>
    <td colspan="2"><?php echo utf8_decode($expediente["area_def_estudio"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  
  <tr>
    <td colspan="6" bgcolor="#EEEEEE"><div align="center"><strong>Coordenadas Antes de Estudio</strong> </div></td>
  </tr>
  <tr>
    <td colspan="6">
<?php
	$a = 0;
	$areasPoly = procesarCoordenadasWKT($expediente["coordenadas"]);
	
	foreach($areasPoly as $area) {
		$exc=0;
		foreach($area as $cadaArea) {
			$coords = explode(",", $cadaArea);
			$punto 	= 0;
			
			echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";
			
			$nroCoordenadas = sizeof($coords);
			foreach($coords as $cadaCoord) {
				if($punto == ($nroCoordenadas-1))	break;
				if($punto==0 && $exc==0) {
					echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA ".($a + 1)."</b></div></td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
				else if($punto==0 && $exc>0) {
					echo "<tr><td colspan=3>AREA ".($a + 1)." : Exclusi&oacute;n ".($exc)."</td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
					
				$xy = explode(" ",$cadaCoord);
				echo "<tr><td><center>".($punto + 1)."</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

				$punto++;
			}			
			$exc++;
		}	
		$a++;
		echo "</table>";
	}
?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="6" bgcolor="#EEEEEE"><div align="center"><strong>Coordenadas Despues de Estudio </strong></div></td>
  </tr>
  <tr>
    <td colspan="6">
      <?php
	$a = 0;
	$areasPoly = procesarCoordenadasWKT($expediente["coordenadas_estudio"]);
	
	foreach($areasPoly as $area) {
		$exc=0;
		foreach($area as $cadaArea) {
			$coords = explode(",", $cadaArea);
			$punto 	= 0;
			
			echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";
			
			$nroCoordenadas = sizeof($coords);
			foreach($coords as $cadaCoord) {
				if($punto == ($nroCoordenadas-1))	break;
				if($punto==0 && $exc==0) {
					echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA ".($a + 1)."</b></div></td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
				else if($punto==0 && $exc>0) {
					echo "<tr><td colspan=3>AREA ".($a + 1)." : Exclusi&oacute;n ".($exc)."</td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
					
				$xy = explode(" ",$cadaCoord);
				echo "<tr><td><center>".($punto + 1)."</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

				$punto++;
			}			
			$exc++;
		}	
		$a++;
		echo "</table>";
	}
?>
    </td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  
  <tr>
    <td colspan="6">&nbsp;</td>
  </tr>
</table>

<?php
	}
?>


<?php 
	if($tipoExpediente == 'ESTUDIO_PROSPECTO') {

?>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="5">
  <tr>
    <td colspan="6"><img src="imgs/reporteLogoCMQ.jpg" width="901" height="121" /></td>
  </tr>
  <tr>
    <td colspan="6" bgcolor="#E1E1E1"><div align="center" class="Estilo1">REPORTE DE INFORMACI&Oacute;N DE PROSPECTO EVALUADO </div></td>
  </tr>
  <tr>
    <td width="64"><strong>Placa:</strong></td>
    <td width="149" colspan="2"><?php echo utf8_decode($expediente["placa"]); ?></td>
    <td width="177" colspan="2"><strong>Fecha Creaci&oacute;n :</strong></td>
    <td width="235"><?php echo utf8_decode($expediente["fecha_creacion"]); ?></td>
  </tr>
  
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  
  
  <tr>
    <td colspan="2"><strong>Municipio(s):</strong></td>
    <td colspan="4"><?php echo utf8_decode($expediente["municipios"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Sistema de Origen:</strong> </td>
    <td colspan="4"><?php echo utf8_decode($expediente["sistema_origen"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="2"><strong>Area Antes Estudio Ha:</strong> </td>
    <td width="120"><?php echo utf8_decode($expediente["area_def_has"]); ?></td>
    <td width="177"><strong>&Aacute;rea Despues Estudio Ha: </strong></td>
    <td colspan="2"><?php echo utf8_decode($expediente["area_def_estudio"]); ?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  
  <tr>
    <td colspan="6" bgcolor="#EEEEEE"><div align="center"><strong>Coordenadas Antes de Estudio</strong> </div></td>
  </tr>
  <tr>
    <td colspan="6">
<?php
	$a = 0;
	$areasPoly = procesarCoordenadasWKT($expediente["coordenadas"]);
	
	foreach($areasPoly as $area) {
		$exc=0;
		foreach($area as $cadaArea) {
			$coords = explode(",", $cadaArea);
			$punto 	= 0;
			
			echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";
			
			$nroCoordenadas = sizeof($coords);
			foreach($coords as $cadaCoord) {
				if($punto == ($nroCoordenadas-1))	break;
				if($punto==0 && $exc==0) {
					echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA ".($a + 1)."</b></div></td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
				else if($punto==0 && $exc>0) {
					echo "<tr><td colspan=3><center><b>AREA ".($a + 1)." : Exclusi&oacute;n ".($exc)."</b></center></td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
					
				$xy = explode(" ",$cadaCoord);
				echo "<tr><td><center>".($punto + 1)."</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

				$punto++;
			}			
			$exc++;
		}	
		$a++;
		echo "</table>";
	}
?></td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  <tr>
    <td colspan="6" bgcolor="#EEEEEE"><div align="center"><strong>Coordenadas Despues de Estudio </strong></div></td>
  </tr>
  <tr>
    <td colspan="6">
      <?php
	$a = 0;
	$areasPoly = procesarCoordenadasWKT($expediente["coordenadas_estudio"]);
	
	foreach($areasPoly as $area) {
		$exc=0;
		foreach($area as $cadaArea) {
			$coords = explode(",", $cadaArea);
			$punto 	= 0;
			
			echo "<table  width='75%' border=1 align='center' cellpadding=0 cellspacing=0>";
			
			$nroCoordenadas = sizeof($coords);
			foreach($coords as $cadaCoord) {
				if($punto == ($nroCoordenadas-1))	break;
				if($punto==0 && $exc==0) {
					echo "<tr><td colspan=3 bgcolor='#000033'><div align='center' class='tituloArea'><b>AREA ".($a + 1)."</b></div></td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
				else if($punto==0 && $exc>0) {
					echo "<tr><td colspan=3><center><b>AREA ".($a + 1)." : Exclusi&oacute;n ".($exc)."</b></center></td></tr>";
					echo "<tr><td><div align='center'><strong>No.</strong></div></td><td><div align='center'><strong>Este</strong></div></td><td><div align='center'><strong>Norte</strong></div></td></tr>";
				}
					
				$xy = explode(" ",$cadaCoord);
				echo "<tr><td><center>".($punto + 1)."</center></td><td>{$xy[0]}</td><td>{$xy[1]}</td></tr>";

				$punto++;
			}			
			$exc++;
		}	
		$a++;
		echo "</table>";
	}
?>    </td>
  </tr>
  <tr>
    <td colspan="6"><hr size="1" /></td>
  </tr>
  
  <tr>
    <td colspan="6"><center><iframe id="iframecontenido"  name="iframecontenido" src="visorCapturas/visualizaPoligonoReporte.php?codExpediente=<?php echo utf8_decode($expediente["placa"]); ?>&clasificacion=ESTUDIO_TECNICO_PROSPECTO" width="800" height="500"></iframe></center></td>
  </tr>
  <tr>
    <td colspan="6"><hr size=1 /></td>
  </tr>
</table>

<?php
	}
?>
</body>
</html>
