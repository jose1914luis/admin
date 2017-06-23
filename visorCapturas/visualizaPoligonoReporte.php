<?php

	session_start();

	require("../Acceso/Config.php");
	require("../Modelos/ConsultasCMQ.php");
	require("../Modelos/SeguimientosUsuarios.php");	
	require_once("../Modelos/Usuarios.php");

	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
	
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
	} else if ($clasificacion=='PROSPECTO') {
		$cobertura = "prospectos";
		$tituloCobertura = "Prospectos";
			
	} else if ($clasificacion=='ESTUDIO_TECNICO') {
		$cobertura = "areas_superposiciones";
		$tituloCobertura = "Estudios_Tecnicos";
	}
	
		$info = $consulta->generarViewMap($codigoExpediente, $clasificacion);
		// Poblamiento de variables
		$centroidesLonLat = explode(" ", substr($info["centroide"],6,-1)); 		
		$centroideLon = $centroidesLonLat[0];
		$centroideLat = $centroidesLonLat[1];			
		
		$coordenadas = $info["coordenadas"];
		$areaPoly =  $info["area_has"];
		//$perimetroPoly = $_GET["perimetroPoly"];	
		
		$capasET = "cmqLayerSol, cmqLayerTit, cmqLayerProspect, ";
		

?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>CMQ :: Maps</title>
	<link rel="stylesheet" href="estilos/style_theme.css" type="text/css">
	<link rel="stylesheet" href="estilos/style.css" type="text/css">
	<script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=true"></script>
	<script src="http://dev.openlayers.org/OpenLayers.js"></script>
	<script src="http://maps.google.com/maps/api/js?v=3.5&key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM&amp;sensor=false"></script>	

    <script type="text/javascript">
        var map,
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
			projection = new OpenLayers.Projection("EPSG:900913"),
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
			     
			var anomalias = new OpenLayers.Layer.WMS("Anomalias Geoqu&iacute;micas",
					"http://www.sigmin.co:8080/geoserver/CMQ/wms", {
                    layers: "Anomalias_Geoquimicas",
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

			var cmqLayerSol = new OpenLayers.Layer.WMS("Solicitudes",
					"http://www.sigmin.co:8080/geoserver/CMQ/wms", {
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
					"http://www.sigmin.co:8080/geoserver/CMQ/wms", {
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
				
			var cmqLayerProspect = new OpenLayers.Layer.WMS("Prospectos",
					"http://www.sigmin.co:8080/geoserver/CMQ/wms", {
                    layers: "prospectos",
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

				// crear una feature polygon
				var polygonFeature = new OpenLayers.Feature.Vector(
					new OpenLayers.Geometry.fromWKT("<?php echo $coordenadas ?>").transform(
						displayProjection,
						projection						
					)
				);

				// Crear una capa vectorial
				var vectorLayer = new OpenLayers.Layer.Vector("Area Consultada");

			// Añadir las features a la capa vectorial
				vectorLayer.addFeatures(
						[polygonFeature]);

            map.addLayers([ ghyb, gmap, gphy, osm, anomalias, <?php echo $capasET ?> cmqLayer3, vectorLayer]); //cmqLayer2,
			
			bounds = vectorLayer.getDataExtent();
			map.zoomToExtent(bounds);	
        }
    </script>
  </head>
  <body onLoad="init()">
	<center>	
		<div id="map"  style='width: 500px; height: 450px; border: 0px;'></div>
		<div id="docs">
			<?php echo "Ubicaci&oacute;n del expediente: <b>".$codigoExpediente."</b>. Area Has: ".($areaPoly); ?>
		</div>
	</center>
  </body>
</html>
