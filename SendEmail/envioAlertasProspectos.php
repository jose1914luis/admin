<?php
	
	require_once("../Acceso/Config.php"); // Definición de las variables globales	
	require_once("../Modelos/Alertas.php");
	
	$notificar 		= new Alertas();	
	$listaNotificar	= $notificar->listaAlertasAreasLibresProspectos();
	
	header('Content-type: application/json');		
	echo json_encode($listaNotificar);
	
?>