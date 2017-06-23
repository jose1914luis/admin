<?php
	$codigoExpediente = "QMJ4110825";

	// "POINT(-75.9113736798932 5.95426827728259)"
	$centroideLon = -75.9113736798932;
	$centroideLat = 5.95426827728259;
	$servicio = "http://www.sigmin.co:8080/geoserver/CMQ/wms";
	$cobertura = "prospectos";
	$tituloCobertura = "Prospectos Mineros";
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>:: CMQ :: Visualizacion</title>
	<!-- Hoja de estilo para OpenLayers -->
	<link rel="stylesheet" href="http://www.openlayers.org/dev/theme/default/style.css" type="text/css"> 
	 
    <!-- Hoja de estilo de Google para situar correctamente el attribution -->
    <link rel="stylesheet" href="http://www.openlayers.org/dev/theme/default/google.css" type="text/css">
        
    <!-- Cargar la librería API V3 de Google -->
    <script src="http://maps.google.com/maps/api/js?sensor=false"></script> 
    
    <!-- Cargar la librería OpenLayers -->
    <script src="http://dev.openlayers.org/OpenLayers.js"></script>
	
	<!-- Hoja de estilo de nuestro programa -->
	<style type="text/css">
		#tituloPagina {
			font-family: Verdana, sans-serif;
			font-size: 1.2em;
			font-weight: bold;
			text-decoration: underline;
			color: #af00ff;
		}
		#viewMap {
			width: 480px;
			height: 350px;
			border: solid 1px #33ff33;
		}
		#comentario {
			font-family: 'Century Gothic', sans-serif;
			font-size: 1.0em;
			font-weight: normal;
			color: #0000ff;		
		}
	</style>
	<!-- Script con nuestro programa -->
	<script type="text/javascript">
		var map;
		function init() {
		    map = new OpenLayers.Map('viewMap');
		    map.addControl(new OpenLayers.Control.LayerSwitcher());
		    
		    var gphy = new OpenLayers.Layer.Google(
		        "Google Physical",
		        {type: google.maps.MapTypeId.TERRAIN}
		    );
		    var gmap = new OpenLayers.Layer.Google(
		        "Google Streets", // the default
		        {numZoomLevels: 20}
		    );
		    var ghyb = new OpenLayers.Layer.Google(
		        "Google Hybrid",
		        {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20}
		    );
		    var gsat = new OpenLayers.Layer.Google(
		        "Google Satellite",
		        {type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22}
		    );

			var cmqLayer = new OpenLayers.Layer.WMS("<?php echo $tituloCobertura ?>",
					"<?php echo $servicio ?>", {
                    layers: "CMQ.<?php echo $cobertura ?>",
                    transparent: true,
                    format: "image/png"
                }, 
				{ opacity: 1, singleTile: true },
				{visibility: true} 
            );

			var cmqLayer2 = new OpenLayers.Layer.WMS("prueba Polys",
					"http://www.sigmin.co:8080/geoserver/CMQ/wms", {
                    layers: "prospectos",
                    transparent: true,
                    format: "image/png"
                }, 
				{ opacity: .65, singleTile: true },
				{
                    isBaseLayer: false,
                    buffer: 0,
                    // exclude this layer from layer container nodes
                    displayInLayerSwitcher: false,
                    visibility: true
                }				
            );
				
			/*
		    var osmbLayer = new OpenLayers.Layer.OSM("OSM base", "http://tah.openstreetmap.org/Tiles/tile/${z}/${x}/${y}.png");
		    var osmLayer = new OpenLayers.Layer.OSM("OSM", "http://tah.openstreetmap.org/Tiles/tile/${z}/${x}/${y}.png"); 
		    osmLayer.isBaseLayer=false;
		    osmLayer.setOpacity(0.35); 
			*/
		    map.addLayers([gphy, ghyb, gmap, cmqLayer2]);// 
		    
		    // Google.v3 uses EPSG:900913 as projection, so we have to
		    // transform our coordinates


		    map.setCenter(new OpenLayers.LonLat(<?php echo $centroideLon ?>, <?php echo $centroideLat ?>).transform(
		        new OpenLayers.Projection("EPSG:4326"),
		        map.getProjectionObject()
		    ), 11);			
		}
	</script>
</head>
<body onload="init()">
	<p id="tituloPagina">Visualizaci&oacute;n de Area <?php echo $codigoExpediente ?></p>
	<div id="viewMap"></div>
</body>
</html>
