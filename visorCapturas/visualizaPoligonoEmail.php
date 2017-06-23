<?php

	session_start();

	require_once("../Acceso/Config.php");
	require_once("../Modelos/ConsultasCMQ.php");
	require_once("../Modelos/SeguimientosUsuarios.php");	
	require_once("/home/sigmin/public_html_finder/Modelos/EstadisticasUsuarios.php"); 
	require_once("/home/sigmin/public_html_finder/Modelos/geoiploc.php"); 	
	require_once("../Modelos/Usuarios.php");

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);

	// Ingreso de dato a estadísticas del sistema (estas alertas quedan a nombre de sigmin_sas, ya que provienen de emails)	
	$controlUsuario 		= new EstadisticasUsuarios();	
	$estadoLogueo 			= $controlUsuario->setLogeoUsuario('sigmin_sas');
	if($estadoLogueo=='O.K') {
		$controlUsuario->setConsultaUsuario('sigmin_sas', $_GET["codExpediente"]);
		$controlUsuario->setEstadisticasPlaca('sigmin_sas',  $_GET["codExpediente"]);	
	}
	
	$codigoExpediente = $_GET["codExpediente"];
	$clasificacion = $_GET["clasificacion"];
	
	$consulta = new ConsultasCMQ();
	
	$servicio = "http://www.sigmin.co:8080/geoserver/CMQ/wms";
	
	if($clasificacion=='SOLICITUD') {
		$cobertura = "solicitudes_cg";
		$tituloCobertura = "Solicitudes";		
	} else if ($clasificacion=='TITULO') {
		$cobertura = "titulos_cg";
		$tituloCobertura = "Titulos";
/*	} else if ($clasificacion=='PROSPECTO') {
		$cobertura = "prospectos";
		$tituloCobertura = "Prospectos";			
*/		
	} else if ($clasificacion=='ESTUDIO_TECNICO') {
		$cobertura = "areas_superposiciones";
		$tituloCobertura = "Estudios_Tecnicos";
	} else if ($clasificacion=='RESTRICCION') {
		$cobertura = "zonas_excluibles_col";
		$tituloCobertura = "Zonas_Excluibles";	
	}
	
		$info = $consulta->generarViewMap($codigoExpediente, $clasificacion);
		$areaPoly =  $info["area_has"];
				
		// Poblamiento de variables
	if($areaPoly) {		
		$centroidesLonLat = explode(" ", substr($info["centroide"],6,-1)); 		
		$centroideLon = $centroidesLonLat[0];
		$centroideLat = $centroidesLonLat[1];			
		$coordenadas = $info["coordenadas"];		
	}	else {
		$centroideLon = -74.08905;
		$centroideLat = 4.63802;			
		$coordenadas = "";
	}
	
		//$perimetroPoly = $_GET["perimetroPoly"];	
		
	$capasET = "cmqLayerSol, cmqLayerTit, cmqRestricciones, ";
		

?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">	
    <title>SIGMIN :: Area de Interés</title>
	<link rel="stylesheet" href="estilos/style_theme.css" type="text/css">
	<link rel="stylesheet" href="estilos/style.css" type="text/css">
	<!-- <script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=true"></script> -->
	<script src="http://dev.openlayers.org/OpenLayers.js"></script>
	<script src="http://maps.google.com/maps/api/js?v=3.5&key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM&amp;sensor=false"></script>	

    <script type="text/javascript">
        var map, vectorLayer, polygonFeature,
			osm = new OpenLayers.Layer.OSM(),		
			gmap = new OpenLayers.Layer.Google(
				"Google Streets", // the default
				{numZoomLevels: 20, visibility: false}
			),
			gsat = new OpenLayers.Layer.Google(
				"Google Satellite", 
				{type: google.maps.MapTypeId.SATELLITE, transparent: true, numZoomLevels: 22}
			),
			gphy = new OpenLayers.Layer.Google(
				"Google Physical",
				{type: google.maps.MapTypeId.TERRAIN, visibility: false}
			),
			ghyb = new OpenLayers.Layer.Google(
				"Google Hybrid",
				{type: google.maps.MapTypeId.HYBRID, numZoomLevels: 22, visibility: false}
			),
			projection = new OpenLayers.Projection("EPSG:900913");
			displayProjection = new OpenLayers.Projection("EPSG:4326");	
			centroCoords =	new OpenLayers.LonLat(<?php echo $centroideLon ?>, <?php echo $centroideLat ?>).transform(
								displayProjection,
								projection
							);			
			

		OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
		OpenLayers.Util.onImageLoadErrorColor = "transparent";
		OpenLayers.ImgPath = "http://js.mapbox.com/theme/dark/";

        function init() {	
		  var
			options = {
			  controls: [
				new OpenLayers.Control.Navigation(),
				new OpenLayers.Control.PanZoomBar(),
				new OpenLayers.Control.LayerSwitcher(),
				new OpenLayers.Control.MouseDefaults(),
				new OpenLayers.Control.KeyboardDefaults()
			  ],
			  projection: projection,
			  displayProjection: displayProjection,
			  units: "meters",
			  numZoomLevels: 22 /* 18 */
			};		
			
            map = new OpenLayers.Map('map', options);
            map.addControl(new OpenLayers.Control.LayerSwitcher());			     
			
			var cmqLayerSol = new OpenLayers.Layer.WMS("Solicitudes",
					"<?php echo $servicio ?>", {
                    layers: "solicitudes_col",
                    transparent: true,
                    format: "image/png"
                }, 
				{ opacity: 1, singleTile: true },
				{
                    isBaseLayer: false,
                    buffer: 0,
                    // exclude this layer from layer container nodes
                    displayInLayerSwitcher: false,
                    visibility: true
                }				
            );			

			var cmqLayerTit = new OpenLayers.Layer.WMS("Titulos",
					"<?php echo $servicio ?>", {
                    layers: "titulos_col",
                    transparent: true,
                    format: "image/png",
                    tiled: true,
                    tilesOrigin : map.maxExtent.left + ',' + map.maxExtent.bottom										
                }, 
				{ opacity: 1},
				{
                    isBaseLayer: true,
                    buffer: 0,
                    // exclude this layer from layer container nodes
                    displayInLayerSwitcher: false,
					displayOutsideMaxExtent: true,
                    visibility: true
                }				
            );	
							
			var cmqLayer3 = new OpenLayers.Layer.WMS("Municipios",
					"<?php echo $servicio ?>", {
                    layers: "Municipios",
                    transparent: true,
                    format: "image/png"
                }, 
				{ opacity: 1, singleTile: true },
				{
                    isBaseLayer: false,
                    buffer: 0,
                    // exclude this layer from layer container nodes
                    displayInLayerSwitcher: false,
                    visibility: true
                }
            );

			var cmqRestricciones = new OpenLayers.Layer.WMS("Zonas Excluibles",
					"http://www.sigmin.co:8080/geoserver/CMQ/wms", {
                    layers: "zonas_excluibles_col",
                    transparent: true,
                    format: "image/png"
                }, 
				{ opacity: 1, singleTile: true },
				{
                    isBaseLayer: false,
                    buffer: 0,
                    // exclude this layer from layer container nodes
                    displayInLayerSwitcher: false,
                    visibility: true
                }				
            );			


			// dar color al layer de resultados			
				var styleMap = new OpenLayers.Style({
             			strokeColor: "#FF0000",
			                strokeOpacity: 1,
			                strokeWidth: 3,
			                fillColor: "#FF0000",
			                fillOpacity: 0.3,
			                pointRadius: 2,
			                pointerEvents: "visiblePainted",
			                label : "<?php echo $clasificacion ?>: ${placa}\n\n Area: ${area} Has",                    
		                        fontColor: "black",
		                        fontSize: "15px",
		                        fontFamily: "Verdana",
		                        fontWeight: "bold",
		                        labelOutlineColor: "white",
		                        labelOutlineWidth: 3
				});				
						
				vectorLayer = new OpenLayers.Layer.Vector("Area Consultada",
							{
								styleMap: styleMap,
								projection: projection,
								displayProjection: displayProjection
							});			
				
            map.addLayers([ghyb, gmap, gphy, osm, <?php echo $capasET ?> cmqLayer3, vectorLayer]); //cmqLayer2,
			
			vectorLayer.removeAllFeatures();
			polygonFeature = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.fromWKT("<?php echo $coordenadas ?>").transform(
			displayProjection,
			projection
			));     			
			
			polygonFeature.attributes = {
				placa: "<?php echo $codigoExpediente ?>",
				area: "<?php echo $areaPoly ?>",
			};
			
			vectorLayer.addFeatures([polygonFeature]);
			bounds = vectorLayer.getDataExtent();
			map.zoomToExtent(bounds);			
        }	
			
			

    </script>
	<style>
		.smallmapEmail {
			width: 900px;
			height: 600px;
			border: 1px solid #ccc;
		}	
	</style>
  </head>
  <body onLoad="init()">
    <div id="map" class="smallmapEmail olMap"></div>
    <div id="docs">
		<?php echo "Ubicaci&oacute;n del expediente: <b>".$codigoExpediente."</b>. Area Has: ".($areaPoly); ?>
    </div>
  </body>
</html>
