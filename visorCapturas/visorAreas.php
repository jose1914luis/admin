<?php
	$codigoExpediente = $_GET["codigoExpediente"];
	$centroideLon  = $_GET["centroideLon"];
	$centroideLat  = $_GET["centroideLat"];
	$servicio = "http://www.sigmin.co:8080/geoserver/CMQ/wms";
	$cobertura = $_GET["cobertura"];
	$tituloCobertura = $_GET["tituloCobertura"];
	$areaPoly =  $_GET["areaPoly"];
	//$perimetroPoly = $_GET["perimetroPoly"];
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
			
			var cmqLayer = new OpenLayers.Layer.WMS("prueba Polys",
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
			
			var cmqLayer2 = new OpenLayers.Layer.WMS("<?php echo $tituloCobertura ?>",
					"<?php echo $servicio ?>", {
                    layers: "<?php echo $cobertura ?>",
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
			
            map.addLayers([osm, ghyb, gmap, gphy, cmqLayer2, cmqLayer3]);

            //map.setCenter(new OpenLayers.LonLat(<?php echo $centroideLon ?>, <?php echo $centroideLat ?>), 10);
			map.setCenter(centroCoords, 10);			
        }
    </script>
  </head>
  <body onload="init()">
    <div id="map" class="smallmap" style="width:100%; height:100%"></div>
    <div id="docs">
		<?php echo "Ubicaci&oacute;n del expediente: <b>".$codigoExpediente."</b>. Area Has: ".($areaPoly); ?>
    </div>
  </body>
</html>
