<?php

	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Usuarios.php");
	require_once("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=EstadisticasSIGMIN".date("Ymd_His").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");		
	
	$placa = "";
	$msgError = "";
	$clasificacion = "";
	$tituloClasificacion = "";
	$observacion = "";
	
	$accionPage = new SeguimientosUsuarios;
	
	
	if(isset($_POST["estado"])&&($_POST["estado"])=="BUSCAR") {	
		$accionPage->generarAccion("Detalle de consultas WEB.");	

	
		$fechaIni = trim($_POST["fechaIni"]);
		$fechaFin = trim($_POST["fechaFin"]);			

		$consultaRegistros 	= $accionPage->getEstadisticaConsultas($fechaIni, $fechaFin);	

		$nroPagos = sizeof($consultaRegistros);
		$tabla ="<table border='1'><tr bgcolor='#DFDFDF'>";	
		foreach($consultaRegistros[0] as $k=>$v)
			$tabla .= "<td align='center'><b>".$k."</b></td>";
		$tabla .= "</tr>";	
		
		for($i=0;$i<$nroPagos;$i++) {
			$tabla .= "<tr>";
			foreach($consultaRegistros[$i] as $k=>$v)
				$tabla .= "<td>".$v."</td>";
			$tabla .= "</tr>";	
		}		
		$tabla .= "</table>";
		$tabla = utf8_decode($tabla);
		
		echo $tabla;
		
	} 	
	
?>