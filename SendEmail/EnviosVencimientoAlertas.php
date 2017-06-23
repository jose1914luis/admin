<?php
	require_once("../Acceso/Config.php"); // Definición de las variables globales	
	require_once("../Modelos/Alertas.php");

	
	$notificar 		= new Alertas();	
	$emails			= $notificar->vencimientoAlertasRelease();
	
	$json = json_encode($emails);

	header("Content-type:application/json"); 	
	echo $json;	

	
?>