<?php
	session_start();

	require_once("Acceso/Config.php");
	require_once("Modelos/AnotacionesRMN.php");
	require_once("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	
	$placa = "";
	$tabla = "";
	
	$consultarAnotaciones = new AnotacionesRMN;
	$listadoEstudio = $consultarAnotaciones->selectByFilter($_POST);
	
	$accionPage = new SeguimientosUsuarios;
	$accionPage->generarAccion("reporte detallado de anotaciones en formato excel. Criterios: " + $consultarAnotaciones->getCriterios());

	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=ReporteDetalladoAnotaciones".date("Ymd_His").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");		


	$tabla ="<h3>LISTA DE ANOTACIONES REGISTRADAS EN CMQ</h3><table border='1'>";
	
	
	if(!empty($listadoEstudio)){		
		$nroAnotaciones = sizeof($listadoEstudio);		
		$tabla .= "<tr>";
	
		foreach($listadoEstudio[0] as $k=>$v)
			$tabla .= "<td align='center'><b>".utf8_decode($k)."</b></td>";
		$tabla .= "</tr>";	
		
		for($i=0;$i<$nroAnotaciones;$i++) {
			foreach($listadoEstudio[$i] as $k=>$v)
				$tabla .= "<td>".utf8_decode($v)."</td>";
			$tabla .= "</tr>";	
		}		
		$tabla .= "</table>";	
	}		
	echo $tabla;
	
?>
