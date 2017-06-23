<?php
	/*
		Variables de configuración de CMQ
	*/	
	header ('Content-type: text/html; charset=ISO-8859-1');
	
	$GLOBALS ["my_server"] 		= "localhost";		// Servidor donde se encuentra instalada la base de datos
	$GLOBALS ["my_database"] 	= "cmqpru";   		// Esquema de la base de datos
	$GLOBALS ["my_user"] 		= "cmqpru";			// Usuario de la base de datos
	$GLOBALS ["my_password"] 	= "2012zygMin";		// Contraseña de la base de datos
	$GLOBALS ["my_port"]		= "5432";			// puerto de la base de datos a utilizar
	
													// url de pagina de error
	$GLOBALS ["url_error"]		= "http://www.sigmin.co/CMQ_Pruebas/IDB/index.php";				
	
	$GLOBALS ["db1"]  			= "host=".$GLOBALS ["my_server"]." port=".$GLOBALS ["my_port"]." dbname=".$GLOBALS ["my_database"]." user=".$GLOBALS ["my_user"]." password=".$GLOBALS ["my_password"]."";	

	
?>	
