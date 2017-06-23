<?php
	session_start();

	require_once("Acceso/Config.php");
	require_once("Modelos/ConsultasCMQ.php");
	require_once("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	
	$placa = "";
	$msgProceso = "";
	$tabla = "";
	$tipo_analisis = "SOLICITUD";
	$tipoEstudio = "ESTUDIO_TECNICO";
	
	$consultar = new ConsultasCMQ;
	if(@$_GET["tipo_analisis"]=="PROSPECTO") {
		$tipo_analisis = "PROSPECTO";	
		$tipoEstudio = "ESTUDIO_TECNICO_PROSPECTO";
	}
			
	if($tipo_analisis=='SOLICITUD')
		$listadoEstudio = $consultar->selectEstudiosTecnicosConsultas($_GET["codExpediente"], $_GET["mineral"], $_GET["mpio"], $_GET["depto"], $_GET["persona"]);	
	else
		$listadoEstudio = $consultar->selectEstudiosTecnicosProspectos($_GET["codExpediente"], $_GET["mpio"], $_GET["depto"]);	
	
	$accionPage = new SeguimientosUsuarios;
	$accionPage->generarAccion("Consulta Detallada en CMQ de Estudios Tecnicos: Para $tipo_analisis. Archivo Excel");
	

	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=EstudioArea".date("Ymd_His").".xls");
	header("Pragma: no-cache");
	header("Expires: 0");		



	if(!empty($listadoEstudio)){		
		$nroSolicitudes = sizeof($listadoEstudio);
		$tabla ="<h3>AN&Aacute;LISIS DE SUPERPOSICIONES</h3><table border='1'>";
		$tabla .= "<tr><td align='center'><b>Ver Mapa</b></td>";
	
		foreach($listadoEstudio[0] as $k=>$v)
			$tabla .= "<td align='center'><b>".utf8_decode($k)."</b></td>";
		$tabla .= "</tr>";	
		
		for($i=0;$i<$nroSolicitudes;$i++) {
			if(!empty($listadoEstudio[$i]["area_estudio"]))
				$enlace = "<a href='#' onclick=\"consultarURL('".$listadoEstudio[$i]["area_estudio"]."','$tipoEstudio')\">[o]</a>";
			else
				$enlace = "&nbsp;";	
			$tabla .= "<tr><td align='center'><b>$enlace</b></td>";
			foreach($listadoEstudio[$i] as $k=>$v)
				$tabla .= "<td>".utf8_decode($v)."</td>";
			$tabla .= "</tr>";	
		}		
		$tabla .= "</table>";	
	}		

	
	echo $tabla;
	
?>
