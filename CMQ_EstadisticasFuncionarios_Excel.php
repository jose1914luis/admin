<?php

	session_start();

	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/Usuarios.php");
	require_once("Modelos/SeguimientosUsuarios.php");		
	
	
	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
	$accionPage = new SeguimientosUsuarios;
	$accionPage->generarAccion("Consulta (Excel) de Tareas Funcionarios.");
	

	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=EstadUsrsSGM_".date("Ymd_His").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");		
	
	echo $_SESSION["tabla_tareas"];
	
?>