<?php
	$codigoExpediente = 'ABC-123';
	$centroideLon  = -75.1934034922966;    
	$centroideLat  = 6.67480607911482;
	$servicio = "http://www.sigmin.co:8080/geoserver/CMQ/wms";
	$cobertura = "solicitudes_cg";
	$tituloCobertura = "Solicitudes";
	$areaPoly =  4321;
	//$perimetroPoly = $_GET["perimetroPoly"];
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>OpenLayers Google Layer Example</title>
    <link rel="stylesheet" href="estilos/style.css" type="text/css">
    <link rel="stylesheet" href="estilos/google.css" type="text/css">
    <link rel="stylesheet" href="style.css" type="text/css">
    <!-- this gmaps key generated for http://openlayers.org/dev/ -->
    <script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyBXS5guPsMcAdCwrujD-1KsyYkgoE87PUM'></script>
    <script src="OpenLayers.js"></script>
    <script type="text/javascript">
        var map;

        function init() {
            map = new OpenLayers.Map('map');
            map.addControl(new OpenLayers.Control.LayerSwitcher());
            
			var osm = new OpenLayers.Layer.OSM();
            var gphy = new OpenLayers.Layer.Google(
                "Google Physical",
                {type: G_PHYSICAL_MAP}
            );
            var gmap = new OpenLayers.Layer.Google(
                "Google Streets", // the default
                {numZoomLevels: 20}
            );
            var ghyb = new OpenLayers.Layer.Google(
                "Google Hybrid",
                {type: G_HYBRID_MAP, numZoomLevels: 20}
            );
            var gsat = new OpenLayers.Layer.Google(
                "Google Satellite",
                {type: G_SATELLITE_MAP, numZoomLevels: 22}
            );
			
			var cmqLayer = new OpenLayers.Layer.WMS("prueba Polys",
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
			
			var cmqLayer2 = new OpenLayers.Layer.WMS("<?php echo $tituloCobertura ?>",
					"<?php echo $servicio ?>", {
                    layers: "<?php echo $cobertura ?>",
                    transparent: true,
                    format: "image/png"
                }, 
				{ opacity: 0.65, singleTile: true },
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
				{ opacity: 0.65, singleTile: true },
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
					new OpenLayers.Geometry.fromWKT("MULTIPOLYGON(((-75.1934034922966 6.67480607911482,-75.1897395630788 6.67481433759791,-75.1896989033635 6.65672683094189,-75.1902049897634 6.65671201788788,-75.1925196427496 6.65670681553234,-75.1925195019805 6.65664426596167,-75.1925229072754 6.65664416627167,-75.1924623872565 6.66366564362019,-75.2051333316089 6.66371326323689,-75.2051175160021 6.65676830634443,-75.2258786732102 6.65664431128768,-75.2258786717322 6.65664367369795,-75.2259693466655 6.65664346355655,-75.2259484888752 6.64764002510039,-75.2259479908667 6.64764002625303,-75.2259467578362 6.64760760939502,-75.2267570085786 6.64758540233488,-75.2267567554232 6.64756042556794,-75.2267735564021 6.64756038665482,-75.2267295867226 6.64487977315476,-75.2267020478139 6.64216231253946,-75.2267020007236 6.64214196799612,-75.2348515401749 6.64212304174597,-75.2348202615279 6.6286911634474,-75.2801561017992 6.62858364513631,-75.2802677542108 6.67460230898777,-75.2530943410846 6.67466769927194,-75.2531396452218 6.66161732896791,-75.2440641614319 6.66156254171733,-75.244063124798 6.66112265081421,-75.2440553614818 6.661122669103,-75.2440551390452 6.66102827464191,-75.2354302029772 6.6611248353654,-75.2354257252595 6.66114292328794,-75.2353286171825 6.66114315034734,-75.2353292885549 6.6614302028342,-75.2351484403323 6.6614296266561,-75.2351484002075 6.66141246854619,-75.2320423237503 6.66141972029485,-75.2215388964355 6.66138607674729,-75.2215394255416 6.66161503005372,-75.2121505898459 6.66148399535249,-75.2123128731695 6.66568113598383,-75.2025426291156 6.66570344261727,-75.2025539136406 6.67066412895643,-75.1978977231631 6.67067469935343,-75.1978982420326 6.67090365444939,-75.193470690949 6.67076102761536,-75.1934034922966 6.67480607911482),(-75.2372341296876 6.65066037317374,-75.2281660732793 6.65068149440721,-75.2281871286733 6.65974843092999,-75.237255351417 6.65972728066354,-75.2372341296876 6.65066037317374)))")
				);

				// Crear una capa vectorial
				var vectorLayer = new OpenLayers.Layer.Vector("Capa Vectorial");

			// Añadir las features a la capa vectorial
				vectorLayer.addFeatures(
						[polygonFeature]);

				// Añadir la capa vectorial al mapa
				//map.addLayer(vectorLayer);


            map.addLayers([ osm, ghyb, gmap, gphy, cmqLayer2, cmqLayer3, vectorLayer]);


            //map.setCenter(new OpenLayers.LonLat(<?php echo $centroideLon ?>, <?php echo $centroideLat ?>), 10);
			map.setCenter(new OpenLayers.LonLat(<?php echo $centroideLon ?>, <?php echo $centroideLat ?>).transform(
		        new OpenLayers.Projection("EPSG:4326"),
		        map.getProjectionObject()
		    ), 10);				
        }
    </script>
  </head>
  <body onload="init()">
    <div id="map" class="smallmap"></div>
    <div id="docs">
		<?php echo "Ubicaci&oacute;n del expediente: <b>".$codigoExpediente."</b>. Area Has: ".($areaPoly); ?>
    </div>
  </body>
</html>
