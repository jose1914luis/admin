<?php
	/*
		Controlador que define las acciones a realizar sobre 
		el formulario de consulta de proyectos quimbaya
	*/

	// Librerias necesarias para instanciamiento de los modelos:
		require_once("../WebConfig/Acceso/Config.php"); // Definición de las variables globales
		require_once("../WebConfig/Modelos/ProyectosQuimbaya.php");

	// Consulta del tipo de identificacion
		$proysCMQ = new ProyectosQuimbaya;
		$listadoCMQ = $proysCMQ->selectALL();
	
	// Llamado a la vista para impresión de datos
		include("../WebConfig/Vistas/cmq.admin.proyects.select.php");
		
?>
