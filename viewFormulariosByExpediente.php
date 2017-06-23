<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require_once("Modelos/SubSeriesDocumentales.php");
	
	if (isset($_GET["selSerie"]) && $_GET["selSerie"] != "") {
		
		$subSerieDoc = new SubSeriesDocumentales();
		$cadaSubSerie = $subSerieDoc->selectSubSerieByIdSerie($_GET["selSerie"]);
				
		echo "<option value='0'>Seleccione Formulario</option>\n";
		foreach($cadaSubSerie as $reg) {
			echo "<option value='".$reg["id_subserie"]."'>".($reg["nombre"])."</option>\n";
		}		
	}

?>