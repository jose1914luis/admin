<?php

	session_start();

	require("Acceso/Config.php");
	require("Modelos/ConsultasCMQ.php");
	require("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	$accionPage = new SeguimientosUsuarios;
	$accionPage->generarAccion("Consulta (Excel) Detallada de Ventas.");
	

	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=ConsultaDetallada".date("Ymd_His").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");		
	
	echo $_SESSION["tabla_ventas"];
	
?>