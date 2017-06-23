<?php
/*
	Clase encargada de administrar la información relacionada a los prospectos
	del CMQ
*/

	class ProspectosBog {	
		var $conn;
		var $Coordenadas;
		var $placa;
		var $origen 		= 21897;  	// por default Gauss Bogotá
		var $wgs84 			= 4326; 	// Geográfico WGS84
		var $datumBogota 	= 4218; 	// Geográfico Datum Bogotá
		var $sistemasOrigen = array(
			"OESTE"  		=> 21896,		
			"BOGOTA" 		=> 21897,
			"ESTE"   		=> 21898,
			"ESTE-ESTE" 	=> 21899,
			""				=> 21897
		);
		
		var $planchasIgac = array( "240"=>21896, "241"=>21896, "242"=>21896, "243"=>21896, "244"=>21897, "245"=>21897, "247"=>21897, "248"=>21897, "249"=>21897, "25"=>21897, "250"=>21898, "251"=>21898, "252"=>21898, "253"=>21898, "254"=>21898, "255"=>21898, "257"=>21899, "258"=>21899, "258BIS"=>21899, "259"=>21896, "26"=>21897, "260"=>21896, "261"=>21896, "262"=>21896, "264"=>21897, "265"=>21897, "266"=>21897, "267"=>21897, "268"=>21897, "269"=>21898, "27"=>21897, "270"=>21898, "271"=>21898, "273"=>21898, "274"=>21898, "275"=>21899, "276"=>21899, "277"=>21899, "277BIS"=>21899, "278"=>21896, "279"=>21896, "28"=>21897, "281"=>21896, "282"=>21897, "283"=>21897, "284"=>21897, "285"=>21897, "286"=>21897, "287"=>21897, "288"=>21898, "29"=>21896, "290"=>21898, "291"=>21898, "292"=>21898, "293"=>21898, "294"=>21899, "295"=>21899, "296"=>21899, "297BIS"=>21899, "298"=>21896, "299"=>21896, "3"=>21898, "30"=>21897, "300"=>21896, "301"=>21896, "302"=>21897, "304"=>21897, "305"=>21897, "306"=>21897, "307"=>21897, "308"=>21898, "309"=>21898, "31"=>21897, "310"=>21898, "312"=>21898, "313"=>21898, "314"=>21899, "315"=>21899, "316"=>21899, "317"=>21899, "317BIS"=>21899, "319"=>21896, "32"=>21897, "320"=>21896, "321"=>21896, "322"=>21896, "323"=>21897, "324"=>21897, "326"=>21897, "327"=>21897, "328"=>21897, "329"=>21898, "33"=>21897, "330"=>21898, "331"=>21898, "332"=>21898, "333"=>21898, "334"=>21898, "336"=>21899, "337"=>21899, "338"=>21899, "339"=>21896, "34"=>21897, "340"=>21896, "341"=>21896, "343"=>21896, "344"=>21896, "345"=>21897, "346"=>21897, "347"=>21897, "348"=>21897, "349"=>21897, "35"=>21897, "350"=>21897, "352"=>21898, "353"=>21898, "354"=>21898, "355"=>21898, "356"=>21898, "357"=>21899, "358"=>21899, "36"=>21896, "360"=>21899, "360BIS"=>21899, "361"=>21896, "361BIS"=>21896, "363"=>21896, "364"=>21896, "365"=>21896, "366"=>21896, "367"=>21897, "368"=>21897, "369"=>21897, "37"=>21897, "370"=>21897, "372"=>21897, "373"=>21898, "374"=>21898, "375"=>21898, "376"=>21898, "377"=>21898, "378"=>21898, "379"=>21899, "38"=>21897, "381"=>21899, "382"=>21899, "382BIS"=>21899, "383"=>21896, "384"=>21896, "386"=>21896, "387"=>21896, "388"=>21896, "389"=>21896, "39"=>21897, "390"=>21897, "391"=>21897, "392"=>21897, "393"=>21897, "395"=>21897, "396"=>21898, "397"=>21898, "398"=>21898, "399"=>21898, "4"=>21898, "40"=>21897, "400"=>21898, "401"=>21898, "402"=>21899, "404"=>21899, "405"=>21899, "406"=>21899, "406BIS"=>21899, "407"=>21896, "408"=>21896, "41"=>21897, "410"=>21896, "411"=>21896, "412"=>21896, "413"=>21896, "414"=>21897, "415"=>21897, "416"=>21897, "418"=>21897, "419"=>21897, "42"=>21897, "420"=>21898, "421"=>21898, "422"=>21898, "423"=>21898, "424"=>21898, "424BIS"=>21898, "426"=>21899, "427"=>21896, "427BIS"=>21896, "428"=>21896, "429"=>21896, "43"=>21896, "431"=>21896, "432"=>21896, "433"=>21897, "434"=>21897, "435"=>21897, "436"=>21897, "437"=>21897, "438"=>21897, "43BIS"=>21896, "44"=>21897, "440"=>21898, "441"=>21898, "442"=>21898, "443"=>21898, "444"=>21898, "445"=>21899, "446"=>21899, "447"=>21896, "448"=>21896, "449"=>21896, "45"=>21897, "450"=>21896, "451"=>21896, "452"=>21897, "454"=>21897, "455"=>21897, "456"=>21897, "457"=>21897, "458"=>21898, "459"=>21898, "46"=>21897, "461"=>21898, "462"=>21898, "463"=>21898, "464"=>21899, "464BIS"=>21899, "465"=>21896, "466"=>21896, "468"=>21896, "469"=>21897, "47"=>21897, "470"=>21897, "471"=>21897, "472"=>21897, "473"=>21897, "474"=>21897, "475"=>21898, "477"=>21898, "478"=>21898, "479"=>21898, "48"=>21897, "480"=>21898, "480BIS"=>21899, "482"=>21896, "483"=>21896, "484"=>21897, "485"=>21897, "486"=>21897, "487"=>21897, "488"=>21897, "489"=>21897, "491"=>21898, "492"=>21898, "493"=>21898, "494"=>21898, "495"=>21897, "496"=>21897, "497"=>21897, "498"=>21897, "499"=>21897, "50"=>21896, "500"=>21897, "501"=>21898, "502"=>21898, "503"=>21898, "504"=>21898, "505"=>21898, "506"=>21898, "507"=>21897, "508"=>21897, "509"=>21897, "51"=>21896, "510"=>21897, "511"=>21898, "512"=>21898, "513"=>21898, "514"=>21898, "516"=>21898, "516BIS"=>21899, "517"=>21897, "518"=>21897, "519"=>21897, "52"=>21897, "520"=>21897, "522"=>21898, "523"=>21898, "524"=>21898, "525"=>21898, "526"=>21898, "526BIS"=>21899, "527"=>21897, "529"=>21897, "53"=>21897, "530"=>21898, "531"=>21898, "532"=>21898, "533"=>21898, "534"=>21898, "535"=>21898, "535BIS"=>21899, "537"=>21897, "538"=>21898, "539"=>21898, "54"=>21897, "540"=>21898, "541"=>21898, "542"=>21898, "543"=>21898, "544"=>21897, "545"=>21897, "546"=>21898, "547"=>21898, "548"=>21898, "549"=>21898, "55"=>21897, "550"=>21898, "551"=>21898, "552"=>21897, "553"=>21897, "554"=>21898, "555"=>21898, "556"=>21898, "557"=>21898, "558"=>21898, "559"=>21898, "56"=>21897, "560"=>21898, "561"=>21898, "562"=>21898, "563"=>21898, "565"=>21898, "566"=>21898, "566BIS"=>21898, "568"=>21898, "568BIS"=>21898, "569"=>21898, "569BIS"=>21898, "57"=>21897, "58"=>21896, "59"=>21896, "6"=>21898, "60"=>21896, "61"=>21896, "62"=>21897, "64"=>21897, "1"=>21898, "10"=>21898, "100"=>21896, "101"=>21896, "102"=>21896, "103"=>21896, "104"=>21896, "105"=>21897, "106"=>21897, "107"=>21897, "108"=>21897, "109"=>21897, "10BIS"=>21898, "11"=>21897, "110"=>21897, "111"=>21898, "111BIS"=>21898, "112"=>21896, "112BIS"=>21896, "113"=>21896, "114"=>21896, "115"=>21896, "116"=>21897, "117"=>21897, "118"=>21897, "119"=>21897, "12"=>21897, "120"=>21897, "121"=>21897, "122"=>21898, "123"=>21898, "124"=>21898, "125"=>21898, "126"=>21898, "126BIS"=>21898, "127"=>21896, "128"=>21896, "129"=>21896, "13"=>21897, "130"=>21896, "131"=>21897, "132"=>21897, "133"=>21897, "134"=>21897, "139"=>21898, "153"=>21898, "163"=>21896, "175"=>21898, "183"=>21896, "193"=>21898, "202"=>21896, "212"=>21898, "221"=>21896, "23"=>21897, "239"=>21899, "246"=>21897, "256"=>21899, "263"=>21897, "272"=>21898, "280"=>21896, "289"=>21898, "297"=>21899, "303"=>21897, "311"=>21898, "318"=>21896, "325"=>21897, "335"=>21899, "342"=>21896, "351"=>21898, "359"=>21899, "362"=>21896, "371"=>21897, "380"=>21899, "385"=>21896, "394"=>21897, "403"=>21899, "409"=>21896, "417"=>21897, "425"=>21899, "430"=>21896, "439"=>21898, "444BIS"=>21899, "453"=>21897, "460"=>21898, "467"=>21896, "476"=>21898, "481"=>21896, "490"=>21898, "5"=>21898, "506BIS"=>21899, "515"=>21898, "521"=>21898, "528"=>21897, "536"=>21897, "543BIS"=>21899, "551BIS"=>21899, "559BIS"=>21899, "567"=>21898, "63"=>21897, "65"=>21897, "66"=>21897, "67"=>21897, "68"=>21896, "69"=>21896, "7"=>21897, "70"=>21896, "71"=>21896, "72"=>21897, "73"=>21897, "74"=>21897, "75"=>21897, "76"=>21897, "77"=>21897, "78"=>21898, "79"=>21896, "79BIS"=>21896, "8"=>21897, "80"=>21896, "81"=>21896, "82"=>21897, "83"=>21897, "84"=>21897, "85"=>21897, "86"=>21897, "87"=>21897, "88"=>21898, "89"=>21896, "89BIS"=>21896, "9"=>21898, "90"=>21896, "91"=>21896, "92"=>21896, "93"=>21897, "94"=>21897, "95"=>21897, "96"=>21897, "97"=>21897, "98"=>21897, "99"=>21898, "135"=>21897, "136"=>21897, "137"=>21898, "138"=>21898, "14"=>21897, "140"=>21898, "141"=>21898, "142"=>21898, "143"=>21896, "144"=>21896, "145"=>21896, "146"=>21896, "147"=>21897, "148"=>21897, "149"=>21897, "15"=>21898, "150"=>21897, "151"=>21897, "152"=>21897, "154"=>21898, "155"=>21898, "156"=>21898, "157"=>21898, "158"=>21898, "159"=>21899, "15BIS"=>21898, "16"=>21897, "160"=>21899, "161"=>21899, "162"=>21899, "162BIS"=>21899, "164"=>21896, "165"=>21896, "166"=>21896, "167"=>21897, "168"=>21897, "169"=>21897, "17"=>21897, "170"=>21897, "171"=>21897, "172"=>21897, "173"=>21898, "174"=>21898, "176"=>21898, "177"=>21898, "178"=>21898, "179"=>21899, "18"=>21897, "180"=>21899, "181"=>21899, "182"=>21899, "182BIS"=>21899, "184"=>21896, "185"=>21896, "186"=>21896, "187"=>21897, "188"=>21897, "189"=>21897, "19"=>21897, "190"=>21897, "191"=>21897, "192"=>21897, "194"=>21898, "195"=>21898, "196"=>21898, "197"=>21898, "198"=>21898, "199"=>21899, "2"=>21898, "20"=>21897, "200"=>21899, "201"=>21899, "201BIS"=>21899, "203"=>21896, "204"=>21896, "205"=>21896, "206"=>21897, "207"=>21897, "208"=>21897, "209"=>21897, "21"=>21897, "210"=>21897, "211"=>21897, "213"=>21898, "214"=>21898, "215"=>21898, "216"=>21898, "217"=>21898, "218"=>21899, "219"=>21899, "22"=>21898, "220"=>21899, "220BIS"=>21899, "222"=>21896, "223"=>21896, "224"=>21896, "225"=>21897, "226"=>21897, "227"=>21897, "228"=>21897, "229"=>21897, "230"=>21897, "231"=>21898, "232"=>21898, "233"=>21898, "234"=>21898, "235"=>21898, "236"=>21898, "237"=>21899, "238"=>21899, "239BIS"=>21899, ""=> 21897);
		
		function __construct($placa, $coordenadas="") {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
			if($placa=="") 
				$placa = $this->crearProspecto();
			$this->Coordenadas = $coordenadas;	
			$this->placa = $placa;			
			
		}
		
		function asignarSistemaOrigen($sOrigen) {
			return $this->sistemasOrigen[strtoupper($sOrigen)];
		}
		
		function procesarCoordenadas() {
			$coords = trim ($this->Coordenadas);			
			$coords = str_ireplace(" ","",$coords);			
			$coords = str_ireplace(","," ",$coords);
			$coords = str_ireplace("\n",",",$coords);
			$coords = str_ireplace("\r","",$coords);
			$coords = str_ireplace(",E,","E",$coords);
			$coords = str_ireplace(",A,","A",$coords);
			$coords = str_ireplace(",,",",",$coords);

			$formatoAreaPoly = "MULTIPOLYGON(";
			$areasCoord = explode("A", $coords);
			$i = 0;
			$coma="";
			while($i<sizeof($areasCoord)) {
				$areas = explode("E",$areasCoord[$i]);
				$pnt1 = explode(" ",$areas[0],2);
				$pnt2 = explode(",",$pnt1[1],2);
				
				// procesamiento del area principal:
				$formatoAreaPoly .= "$coma((".$areas[0].",".$pnt1[0]." ".$pnt2[0].")";
				
				$j=1;
				// procesamiento de las exclusiones:
				while($j<sizeof($areas)) {
					$pnt1 = explode(" ",$areas[$j],2);
					$pnt2 = explode(",",$pnt1[1],2);				
					$formatoAreaPoly .= ",(".$areas[$j].",".$pnt1[0]." ".$pnt2[0].")";
					$j++;
				}
				
				$formatoAreaPoly .= ")";
				$coma=",";
				$i++;
			}
			$formatoAreaPoly .= ")";
			$formatoAreaPoly = str_ireplace(", )",")",$formatoAreaPoly);

			return $formatoAreaPoly;
		}

		function crearProspecto() {
			$queryStr =  'SELECT crearProspecto() AS new_prospecto';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al generar nueva placa para prospecto.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["new_prospecto"];
		}
		
		function getProspecto() {
			return $this->placa;
		}
		
		function selectAll() {
			$queryStr =  'SELECT * FROM PROSPECTOS_BOG ORDER BY placa';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar prospectos.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		function existePlaca($placa) {
			$queryStr =  'SELECT count(1) as existe FROM PROSPECTOS_BOG WHERE placa=$1 limit 1';			
			$params = array($placa);	
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error al consultar existencia de la placa $placa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["existe"];
		}	
		
		function existePlacaDelete($placa) {
			$queryStr =  '
				select count(1) as existe 
				from prospectos p
					left join prospectos_bog pb on (p.placa=pb.placa)
					left join prospectos_municipios pm on (p.placa=pm.placa)
				where p.placa = $1							
			';			
			$params = array($placa);	
			
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (!$result) {
			  echo "Error al consultar existencia de la placa $placa.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["existe"];
		}	
		
		function insertAll($sOrigen="BOGOTA") {
			$origenBD = $this->origen; // Coordenadas Origen Bogotá por default
			$coords = $this->procesarCoordenadas();
			$this->origen = $this->asignarSistemaOrigen($sOrigen);
		
			// Validación de la geometría
			$hayErroresGeometricos = $this->revisarGeometria($coords);
			
			if (!$hayErroresGeometricos) {
				// Si el polígono ya existe, es eliminado para reemplazarlo con las nuevas coordenadas
				$this->deleteTo($this->placa);		
				$this->deleteWGS84To($this->placa);					
			
				if($origenBD==$this->origen) {
					$queryStr		=  " insert into PROSPECTOS_BOG (placa, sistema_origen, the_geom) values ($1, $2, ST_GeomFromText($3, 21897))";			
					$queryStrWGS84 	=  " insert into PROSPECTOS (placa, the_geom) values ($1, ST_Transform(ST_Transform(ST_GeomFromText($2, 21897),".$this->datumBogota."),".$this->wgs84."))";			
				}	else	{
					$queryStr 		=  " insert into PROSPECTOS_BOG (placa, sistema_origen, the_geom) values (	$1, $2, ST_Transform(ST_GeomFromText($3, ".$this->origen."), $origenBD))";			
					$queryStrWGS84 	=  " insert into PROSPECTOS (placa, the_geom) values (	$1, ST_Transform(ST_Transform(ST_Transform(ST_GeomFromText($2, ".$this->origen."), $origenBD), ".$this->datumBogota."),".$this->wgs84."))";			
				}	
				$params = array($this->placa, $sOrigen, $coords);
		
				$result = pg_query_params($this->conn, $queryStr, $params);
				$ERROR = pg_last_error($this->conn);
				if($ERROR) 
					echo "<table bgcolor='red' border = 0><tr><td>Error al Insertar Coordenadas Planas de proyecto: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";	
				else  
					$this->updateAreaTo($this->placa);

				$params = array($this->placa, $coords);	
				$result = pg_query_params($this->conn, $queryStrWGS84, $params);
				$ERROR = pg_last_error($this->conn);
				if($ERROR)
					echo "<table bgcolor='red' border = 0><tr><td>Error al Insertar Coordenadas Geográficas WGS84 de proyecto: $ERROR</td></tr></table>";
				
			} else 
				echo "<table bgcolor='red' border = 0><tr><td>Errores en la geometr&iacute;a ingresada. $hayErroresGeometricos</tr></table>";
		}		

		function revisarGeometria($coords) {			
			$polyCorrecto = "";
			$queryStr =  "select ST_IsValidReason(ST_GeomFromText($1, ".$this->origen.")) as es_correcto";			
			$params = array($coords);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			
			$ERROR = pg_last_error($this->conn);
			if($ERROR) {
				echo "<table bgcolor='red' border = 0><tr><td>Error al validar coordenadas: Debe verificarse nuevamente la generación del polígono. $ERROR</td></tr></table>";
				$polyCorrecto = "Error de la operaci&oacute;n al validar coordenadas";
			}	else {
				$validacion = pg_fetch_all($result);	
				$polyCorrecto = $validacion[0]["es_correcto"];
				
				if($polyCorrecto=="Valid Geometry")
					$polyCorrecto = "";
			}

			return $polyCorrecto;
		}		
		
		function deleteTo($placa) {	
			$queryStr =  "delete from PROSPECTOS_BOG where placa=$1";			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error en Eliminar Coordenadas: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}		

		function deleteWGS84To($placa) {	
			$queryStr =  "delete from PROSPECTOS where placa=$1";			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error en Eliminar Coordenadas WGS84: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}

		function deleteMunicipiosTo($placa) {	
			$queryStr =  "delete from PROSPECTOS_MUNICIPIOS where placa=$1";			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error en Eliminar los municipios al prospecto $placa: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}		
		
		function deleteProspecto($placa) {
			$this->deleteTo($placa);
			$this->deleteWGS84To($placa);
			$this->deleteMunicipiosTo($placa);
			
			if($this->existePlacaDelete($placa))
				return 0; // Aún existe el polígono, no fue eliminado satisfactoriamente
			return 1;	
		}
		
		function updateAreaTo($placa) {	
			$queryStr =  "update PROSPECTOS_BOG set area=ST_Area(the_geom), perimetro=ST_Perimeter(the_geom) where placa=$1";			
			$params = array($placa);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR)
				echo "<table bgcolor='red' border = 0><tr><td>Error al actualizar area y perimetro del poligono: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}	

		function getCentroideWGS84($placa) {
			$queryStr =  "select ST_AsText(ST_centroid(the_geom)) as centroide from prospectos where placa='$placa'";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al obtener centroide del prospecto.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return explode(" ", substr($lista[0]["centroide"],6,-1)); 		
		}

		function getArea($placa) {
			$queryStr =  "select (area/10000) as area, perimetro from prospectos_bog where placa='$placa'";			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al obtener centroide del prospecto.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);
			
			return $lista[0]; 		
		}		
	}	
?>

