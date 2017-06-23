<?php

	session_start();

	require("Acceso/Config.php");
	require("Modelos/ConsultasCMQ.php");
	require("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	$placa = "";
	$msgProceso = "";
	$tabla = "";
	
	$consultar = new ConsultasCMQ;
	
	// Procesamiento de solicitudes	
	$listadoSolicitudes = $consultar->selectSolicitudesConsultas($_GET["codExpediente"], $_GET["mineral"], $_GET["mpio"], $_GET["depto"], $_GET["persona"]);	
	
	$accionPage = new SeguimientosUsuarios;
	$accionPage->generarAccion("Consulta (Excel) Detallada en CMQ por solicitudes, titulos y prospectos.");
	

	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=ConsultaDetallada".date("Ymd_His").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");		
	
	
	if(!empty($listadoSolicitudes)){		
		$nroSolicitudes = sizeof($listadoSolicitudes);
		$tabla ="<h3>B&uacute;squeda Solicitudes</h3><table border='1'>";
		$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";
	
		foreach($listadoSolicitudes[0] as $k=>$v)
			$tabla .= "<td align='center'><b>".$k."</b></td>";
		$tabla .= "</tr>";	
		
		for($i=0;$i<$nroSolicitudes;$i++) {
			if(!empty($listadoSolicitudes[$i]["centroide"]))
				$enlace = "<a href='#' onclick=\"consultarURL('".$consultar->generaUrlViewMap($listadoSolicitudes[$i]["placa"], $listadoSolicitudes[$i]["centroide"], $listadoSolicitudes[$i]["area_definitiva_ha"])."', '".$listadoSolicitudes[$i]["placa"]."')\">[o]</a>";
			else
				$enlace = "&nbsp;";	
			$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
			foreach($listadoSolicitudes[$i] as $k=>$v)
				$tabla .= "<td>".$v."</td>";
			$tabla .= "</tr>";	
		}		
		$tabla .= "</table>";	
	}

	//Procesamiento de titulos		
	
	$listadoTitulos = $consultar->selectTitulosConsultas($_GET["codExpediente"], $_GET["mineral"], $_GET["mpio"], $_GET["depto"], $_GET["persona"]);		

	if(!empty($listadoTitulos)){
		$nroTitulos = sizeof($listadoTitulos);
		$tabla .="<hr size='1'><h3>B&uacute;squeda T&iacute;tulos</h3><table border='1'>";
		$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";

		foreach($listadoTitulos[0] as $k=>$v)
			$tabla .= "<td align='center'><b>".$k."</b></td>";
		$tabla .= "</tr>";	
		
		for($i=0;$i<$nroTitulos;$i++) {
			if(!empty($listadoTitulos[$i]["centroide"]))
				$enlace = "<a href='#' onclick=\"consultarURL('".$consultar->generaUrlViewMap($listadoTitulos[$i]["placa"], $listadoTitulos[$i]["centroide"], $listadoTitulos[$i]["area_definitiva_ha"],'titulos_cg','Titulos')."', '".$listadoTitulos[$i]["placa"]."')\">[o]</a>";
			else
				$enlace = "&nbsp;";	
			$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
			foreach($listadoTitulos[$i] as $k=>$v)
				$tabla .= "<td>".$v."</td>";
			$tabla .= "</tr>";	
		}		
		$tabla .= "</table>";	
	}
	//Procesamiento de prospectos		
	
	$listadoProspectos = $consultar->selectProspectosConsultas($_GET["codExpediente"], $_GET["mpio"], $_GET["depto"]);		
	$nroProspectos = sizeof($listadoProspectos);

	if(!empty($listadoProspectos)){
		$tabla .="<hr size='1'><h3>B&uacute;squeda Prospectos</h3><table border='1'>";
		$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";

		foreach($listadoProspectos[0] as $k=>$v)
			$tabla .= "<td align='center'><b>".$k."</b></td>";
		$tabla .= "</tr>";	
		
		for($i=0;$i<$nroProspectos;$i++) {
			if(!empty($listadoProspectos[$i]["centroide"]))
				$enlace = "<a href='#' onclick=\"consultarURL('".$consultar->generaUrlViewMap($listadoProspectos[$i]["placa"], $listadoProspectos[$i]["centroide"], $listadoProspectos[$i]["area_definitiva_ha"],'prospectos','Prospectos')."', '".$listadoProspectos[$i]["placa"]."')\">[o]</a>";
			else
				$enlace = "&nbsp;";	
			$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
			
			foreach($listadoProspectos[$i] as $k=>$v) {
				if($k!="coordenadas_bog")
					$tabla .= "<td>".utf8_decode($v)."</td>";
				else
					$tabla .= "<td><pre>".str_replace(" ",",",str_replace(",","\n",str_replace(")))","))", str_replace("MULTIPOLYGON(","",$v))))."</pre></td>";
			
			}
			$tabla .= "</tr>";	
		}
	}		
	$tabla .= "</table>";	
	
	echo $tabla;
	
?>