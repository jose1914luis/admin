<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/SeriesDocumentales.php");
	
	if (isset($_GET["idEmpresa"]) && $_GET["idEmpresa"] != "") {
		
		$serieDoc = new SeriesDocumentales();
		$cadaSerie = $serieDoc->selectSerieByIdEmpresa($_GET["idEmpresa"]);
				
		echo "<option value='0'>Seleccione un Folder</option>\n";
		foreach($cadaSerie as $reg) {
			echo "<option value='".$reg["id"]."'>".($reg["nombre"])."</option>\n";
		}		
	}

?>