<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/DocumentosSubseries.php");
	
	if (isset($_GET["placa"]) && $_GET["placa"] != "") {
	
		$docsReq 	= new DocumentosSubseries();
		$seRequiere = $docsReq->selectDocumentosRequieren($_GET["placa"]);
	
		if(!empty($seRequiere))
			foreach($seRequiere as $reg) {
				echo "<option value='".$reg["id_documento"]."'>".$reg["formulario"]." [".$reg["fecha_inicia_termino"]."]</option>\n";
			}
	}

?>