<?php
	// verificación de la existencia del expediente que se pretende capturar en CMQ
	
	require_once("Acceso/Config.php");
	require_once("Modelos/Solicitudes.php");
	require_once("Modelos/Titulos.php");
	
	$sol = new Solicitudes;
	$tit = new Titulos;
	
	$codigoExpediente 	= $_GET["placa"];
	$tipoExpediente 	= $_GET["tipo"];
	
	if($tipoExpediente=="SOLICITUD") {
		$idSolicitud 	= $sol->getIdPlaca($codigoExpediente);
		if(isset($idSolicitud))
/*
			echo '
				<script>alert("El expediente ingresado '.$codigoExpediente.' ya se encuentra registrado en CMQ como SOLICITUD. NO SE REQUIERE CAPTURARLO")</script>				
			';		
*/			
			echo '
			<script>
				editar = confirm("El expediente ingresado '.$codigoExpediente.' ya se encuentra registrado en CMQ como SOLICITUD. DESEA ACTUALIZAR LOS DATOS B&#193;SICOS DE TODOS MODOS???")
				if(editar!=true) document.location.href="IDB_DatosBasicos.php"; 
			</script>				
			';		
		
	} else {
		if($tipoExpediente=="TITULO") {
			$idTitulo	= $tit->getIdPlaca($codigoExpediente);
			if(isset($idTitulo))
/*
				echo '
					<script>alert("El expediente ingresado '.$codigoExpediente.' ya se encuentra registrado en CMQ como TITULO. NO SE REQUIERE CAPTURARLO")</script>				
				';	
*/				
				echo '
				<script>
				editar = confirm("El expediente ingresado '.$codigoExpediente.' ya se encuentra registrado en CMQ como SOLICITUD. DESEA ACTUALIZAR LOS DATOS B&#193;SICOS DE TODOS MODOS???")
				if(editar!=true) document.location.href="IDB_DatosBasicos.php"; 
				</script>				
				';		
			
		}
	}
	
	
?>