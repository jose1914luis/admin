<?php
/*
	Clase encargada de administrar la información relacionada a solicitudes y sus correspondientes puntos arcifinios
	en el CMQ
*/

	class SolicitudesArcifiniosTMP {		
		var $conn;
		var $planchasIgac = array( "24"=>"BOGOTA", "240"=>"OESTE", "241"=>"OESTE", "242"=>"OESTE", "243"=>"OESTE", "244"=>"BOGOTA", "245"=>"BOGOTA", "247"=>"BOGOTA", "248"=>"BOGOTA", "249"=>"BOGOTA", "25"=>"BOGOTA", "250"=>"ESTE", "251"=>"ESTE", "252"=>"ESTE", "253"=>"ESTE", "254"=>"ESTE", "255"=>"ESTE", "257"=>"ESTE-ESTE", "258"=>"ESTE-ESTE", "258BIS"=>"ESTE-ESTE", "259"=>"OESTE", "26"=>"BOGOTA", "260"=>"OESTE", "261"=>"OESTE", "262"=>"OESTE", "264"=>"BOGOTA", "265"=>"BOGOTA", "266"=>"BOGOTA", "267"=>"BOGOTA", "268"=>"BOGOTA", "269"=>"ESTE", "27"=>"BOGOTA", "270"=>"ESTE", "271"=>"ESTE", "273"=>"ESTE", "274"=>"ESTE", "275"=>"ESTE-ESTE", "276"=>"ESTE-ESTE", "277"=>"ESTE-ESTE", "277BIS"=>"ESTE-ESTE", "278"=>"OESTE", "279"=>"OESTE", "28"=>"BOGOTA", "281"=>"OESTE", "282"=>"BOGOTA", "283"=>"BOGOTA", "284"=>"BOGOTA", "285"=>"BOGOTA", "286"=>"BOGOTA", "287"=>"BOGOTA", "288"=>"ESTE", "29"=>"OESTE", "290"=>"ESTE", "291"=>"ESTE", "292"=>"ESTE", "293"=>"ESTE", "294"=>"ESTE-ESTE", "295"=>"ESTE-ESTE", "296"=>"ESTE-ESTE", "297BIS"=>"ESTE-ESTE", "298"=>"OESTE", "299"=>"OESTE", "3"=>"ESTE", "30"=>"BOGOTA", "300"=>"OESTE", "301"=>"OESTE", "302"=>"BOGOTA", "304"=>"BOGOTA", "305"=>"BOGOTA", "306"=>"BOGOTA", "307"=>"BOGOTA", "308"=>"ESTE", "309"=>"ESTE", "31"=>"BOGOTA", "310"=>"ESTE", "312"=>"ESTE", "313"=>"ESTE", "314"=>"ESTE-ESTE", "315"=>"ESTE-ESTE", "316"=>"ESTE-ESTE", "317"=>"ESTE-ESTE", "317BIS"=>"ESTE-ESTE", "319"=>"OESTE", "32"=>"BOGOTA", "320"=>"OESTE", "321"=>"OESTE", "322"=>"OESTE", "323"=>"BOGOTA", "324"=>"BOGOTA", "326"=>"BOGOTA", "327"=>"BOGOTA", "328"=>"BOGOTA", "329"=>"ESTE", "33"=>"BOGOTA", "330"=>"ESTE", "331"=>"ESTE", "332"=>"ESTE", "333"=>"ESTE", "334"=>"ESTE", "336"=>"ESTE-ESTE", "337"=>"ESTE-ESTE", "338"=>"ESTE-ESTE", "339"=>"OESTE", "34"=>"BOGOTA", "340"=>"OESTE", "341"=>"OESTE", "343"=>"OESTE", "344"=>"OESTE", "345"=>"BOGOTA", "346"=>"BOGOTA", "347"=>"BOGOTA", "348"=>"BOGOTA", "349"=>"BOGOTA", "35"=>"BOGOTA", "350"=>"BOGOTA", "352"=>"ESTE", "353"=>"ESTE", "354"=>"ESTE", "355"=>"ESTE", "356"=>"ESTE", "357"=>"ESTE-ESTE", "358"=>"ESTE-ESTE", "36"=>"OESTE", "360"=>"ESTE-ESTE", "360BIS"=>"ESTE-ESTE", "361"=>"OESTE", "361BIS"=>"OESTE", "363"=>"OESTE", "364"=>"OESTE", "365"=>"OESTE", "366"=>"OESTE", "367"=>"BOGOTA", "368"=>"BOGOTA", "369"=>"BOGOTA", "37"=>"BOGOTA", "370"=>"BOGOTA", "372"=>"BOGOTA", "373"=>"ESTE", "374"=>"ESTE", "375"=>"ESTE", "376"=>"ESTE", "377"=>"ESTE", "378"=>"ESTE", "379"=>"ESTE-ESTE", "38"=>"BOGOTA", "381"=>"ESTE-ESTE", "382"=>"ESTE-ESTE", "382BIS"=>"ESTE-ESTE", "383"=>"OESTE", "384"=>"OESTE", "386"=>"OESTE", "387"=>"OESTE", "388"=>"OESTE", "389"=>"OESTE", "39"=>"BOGOTA", "390"=>"BOGOTA", "391"=>"BOGOTA", "392"=>"BOGOTA", "393"=>"BOGOTA", "395"=>"BOGOTA", "396"=>"ESTE", "397"=>"ESTE", "398"=>"ESTE", "399"=>"ESTE", "4"=>"ESTE", "40"=>"BOGOTA", "400"=>"ESTE", "401"=>"ESTE", "402"=>"ESTE-ESTE", "404"=>"ESTE-ESTE", "405"=>"ESTE-ESTE", "406"=>"ESTE-ESTE", "406BIS"=>"ESTE-ESTE", "407"=>"OESTE", "408"=>"OESTE", "41"=>"BOGOTA", "410"=>"OESTE", "411"=>"OESTE", "412"=>"OESTE", "413"=>"OESTE", "414"=>"BOGOTA", "415"=>"BOGOTA", "416"=>"BOGOTA", "418"=>"BOGOTA", "419"=>"BOGOTA", "42"=>"BOGOTA", "420"=>"ESTE", "421"=>"ESTE", "422"=>"ESTE", "423"=>"ESTE", "424"=>"ESTE", "424BIS"=>"ESTE", "426"=>"ESTE-ESTE", "427"=>"OESTE", "427BIS"=>"OESTE", "428"=>"OESTE", "429"=>"OESTE", "43"=>"OESTE", "431"=>"OESTE", "432"=>"OESTE", "433"=>"BOGOTA", "434"=>"BOGOTA", "435"=>"BOGOTA", "436"=>"BOGOTA", "437"=>"BOGOTA", "438"=>"BOGOTA", "43BIS"=>"OESTE", "44"=>"BOGOTA", "440"=>"ESTE", "441"=>"ESTE", "442"=>"ESTE", "443"=>"ESTE", "444"=>"ESTE", "445"=>"ESTE-ESTE", "446"=>"ESTE-ESTE", "447"=>"OESTE", "448"=>"OESTE", "449"=>"OESTE", "45"=>"BOGOTA", "450"=>"OESTE", "451"=>"OESTE", "452"=>"BOGOTA", "454"=>"BOGOTA", "455"=>"BOGOTA", "456"=>"BOGOTA", "457"=>"BOGOTA", "458"=>"ESTE", "459"=>"ESTE", "46"=>"BOGOTA", "461"=>"ESTE", "462"=>"ESTE", "463"=>"ESTE", "464"=>"ESTE-ESTE", "464BIS"=>"ESTE-ESTE", "465"=>"OESTE", "466"=>"OESTE", "468"=>"OESTE", "469"=>"BOGOTA", "47"=>"BOGOTA", "470"=>"BOGOTA", "471"=>"BOGOTA", "472"=>"BOGOTA", "473"=>"BOGOTA", "474"=>"BOGOTA", "475"=>"ESTE", "477"=>"ESTE", "478"=>"ESTE", "479"=>"ESTE", "48"=>"BOGOTA", "480"=>"ESTE", "480BIS"=>"ESTE-ESTE", "482"=>"OESTE", "483"=>"OESTE", "484"=>"BOGOTA", "485"=>"BOGOTA", "486"=>"BOGOTA", "487"=>"BOGOTA", "488"=>"BOGOTA", "489"=>"BOGOTA", "491"=>"ESTE", "492"=>"ESTE", "493"=>"ESTE", "494"=>"ESTE", "495"=>"BOGOTA", "496"=>"BOGOTA", "497"=>"BOGOTA", "498"=>"BOGOTA", "499"=>"BOGOTA", "50"=>"OESTE", "500"=>"BOGOTA", "501"=>"ESTE", "502"=>"ESTE", "503"=>"ESTE", "504"=>"ESTE", "505"=>"ESTE", "506"=>"ESTE", "507"=>"BOGOTA", "508"=>"BOGOTA", "509"=>"BOGOTA", "51"=>"OESTE", "510"=>"BOGOTA", "511"=>"ESTE", "512"=>"ESTE", "513"=>"ESTE", "514"=>"ESTE", "516"=>"ESTE", "516BIS"=>"ESTE-ESTE", "517"=>"BOGOTA", "518"=>"BOGOTA", "519"=>"BOGOTA", "52"=>"BOGOTA", "520"=>"BOGOTA", "522"=>"ESTE", "523"=>"ESTE", "524"=>"ESTE", "525"=>"ESTE", "526"=>"ESTE", "526BIS"=>"ESTE-ESTE", "527"=>"BOGOTA", "529"=>"BOGOTA", "53"=>"BOGOTA", "530"=>"ESTE", "531"=>"ESTE", "532"=>"ESTE", "533"=>"ESTE", "534"=>"ESTE", "535"=>"ESTE", "535BIS"=>"ESTE-ESTE", "537"=>"BOGOTA", "538"=>"ESTE", "539"=>"ESTE", "54"=>"BOGOTA", "540"=>"ESTE", "541"=>"ESTE", "542"=>"ESTE", "543"=>"ESTE", "544"=>"BOGOTA", "545"=>"BOGOTA", "546"=>"ESTE", "547"=>"ESTE", "548"=>"ESTE", "549"=>"ESTE", "55"=>"BOGOTA", "550"=>"ESTE", "551"=>"ESTE", "552"=>"BOGOTA", "553"=>"BOGOTA", "554"=>"ESTE", "555"=>"ESTE", "556"=>"ESTE", "557"=>"ESTE", "558"=>"ESTE", "559"=>"ESTE", "56"=>"BOGOTA", "560"=>"ESTE", "561"=>"ESTE", "562"=>"ESTE", "563"=>"ESTE", "565"=>"ESTE", "566"=>"ESTE", "566BIS"=>"ESTE", "568"=>"ESTE", "568BIS"=>"ESTE", "569"=>"ESTE", "569BIS"=>"ESTE", "57"=>"BOGOTA", "58"=>"OESTE", "59"=>"OESTE", "6"=>"ESTE", "60"=>"OESTE", "61"=>"OESTE", "62"=>"BOGOTA", "64"=>"BOGOTA", "1"=>"ESTE", "10"=>"ESTE", "100"=>"OESTE", "101"=>"OESTE", "102"=>"OESTE", "103"=>"OESTE", "104"=>"OESTE", "105"=>"BOGOTA", "106"=>"BOGOTA", "107"=>"BOGOTA", "108"=>"BOGOTA", "109"=>"BOGOTA", "10BIS"=>"ESTE", "11"=>"BOGOTA", "110"=>"BOGOTA", "111"=>"ESTE", "111BIS"=>"ESTE", "112"=>"OESTE", "112BIS"=>"OESTE", "113"=>"OESTE", "114"=>"OESTE", "115"=>"OESTE", "116"=>"BOGOTA", "117"=>"BOGOTA", "118"=>"BOGOTA", "119"=>"BOGOTA", "12"=>"BOGOTA", "120"=>"BOGOTA", "121"=>"BOGOTA", "122"=>"ESTE", "123"=>"ESTE", "124"=>"ESTE", "125"=>"ESTE", "126"=>"ESTE", "126BIS"=>"ESTE", "127"=>"OESTE", "128"=>"OESTE", "129"=>"OESTE", "13"=>"BOGOTA", "130"=>"OESTE", "131"=>"BOGOTA", "132"=>"BOGOTA", "133"=>"BOGOTA", "134"=>"BOGOTA", "139"=>"ESTE", "153"=>"ESTE", "163"=>"OESTE", "175"=>"ESTE", "183"=>"OESTE", "193"=>"ESTE", "202"=>"OESTE", "212"=>"ESTE", "221"=>"OESTE", "23"=>"BOGOTA", "239"=>"ESTE-ESTE", "246"=>"BOGOTA", "256"=>"ESTE-ESTE", "263"=>"BOGOTA", "272"=>"ESTE", "280"=>"OESTE", "289"=>"ESTE", "297"=>"ESTE-ESTE", "303"=>"BOGOTA", "311"=>"ESTE", "318"=>"OESTE", "325"=>"BOGOTA", "335"=>"ESTE-ESTE", "342"=>"OESTE", "351"=>"ESTE", "359"=>"ESTE-ESTE", "362"=>"OESTE", "371"=>"BOGOTA", "380"=>"ESTE-ESTE", "385"=>"OESTE", "394"=>"BOGOTA", "403"=>"ESTE-ESTE", "409"=>"OESTE", "417"=>"BOGOTA", "425"=>"ESTE-ESTE", "430"=>"OESTE", "439"=>"ESTE", "444BIS"=>"ESTE-ESTE", "453"=>"BOGOTA", "460"=>"ESTE", "467"=>"OESTE", "476"=>"ESTE", "481"=>"OESTE", "490"=>"ESTE", "5"=>"ESTE", "506BIS"=>"ESTE-ESTE", "515"=>"ESTE", "521"=>"ESTE", "528"=>"BOGOTA", "536"=>"BOGOTA", "543BIS"=>"ESTE-ESTE", "551BIS"=>"ESTE-ESTE", "559BIS"=>"ESTE-ESTE", "567"=>"ESTE", "63"=>"BOGOTA", "65"=>"BOGOTA", "66"=>"BOGOTA", "67"=>"BOGOTA", "68"=>"OESTE", "69"=>"OESTE", "7"=>"BOGOTA", "70"=>"OESTE", "71"=>"OESTE", "72"=>"BOGOTA", "73"=>"BOGOTA", "74"=>"BOGOTA", "75"=>"BOGOTA", "76"=>"BOGOTA", "77"=>"BOGOTA", "78"=>"ESTE", "79"=>"OESTE", "79BIS"=>"OESTE", "8"=>"BOGOTA", "80"=>"OESTE", "81"=>"OESTE", "82"=>"BOGOTA", "83"=>"BOGOTA", "84"=>"BOGOTA", "85"=>"BOGOTA", "86"=>"BOGOTA", "87"=>"BOGOTA", "88"=>"ESTE", "89"=>"OESTE", "89BIS"=>"OESTE", "9"=>"ESTE", "90"=>"OESTE", "91"=>"OESTE", "92"=>"OESTE", "93"=>"BOGOTA", "94"=>"BOGOTA", "95"=>"BOGOTA", "96"=>"BOGOTA", "97"=>"BOGOTA", "98"=>"BOGOTA", "99"=>"ESTE", "135"=>"BOGOTA", "136"=>"BOGOTA", "137"=>"ESTE", "138"=>"ESTE", "14"=>"BOGOTA", "140"=>"ESTE", "141"=>"ESTE", "142"=>"ESTE", "143"=>"OESTE", "144"=>"OESTE", "145"=>"OESTE", "146"=>"OESTE", "147"=>"BOGOTA", "148"=>"BOGOTA", "149"=>"BOGOTA", "15"=>"ESTE", "150"=>"BOGOTA", "151"=>"BOGOTA", "152"=>"BOGOTA", "154"=>"ESTE", "155"=>"ESTE", "156"=>"ESTE", "157"=>"ESTE", "158"=>"ESTE", "159"=>"ESTE-ESTE", "15BIS"=>"ESTE", "16"=>"BOGOTA", "160"=>"ESTE-ESTE", "161"=>"ESTE-ESTE", "162"=>"ESTE-ESTE", "162BIS"=>"ESTE-ESTE", "164"=>"OESTE", "165"=>"OESTE", "166"=>"OESTE", "167"=>"BOGOTA", "168"=>"BOGOTA", "169"=>"BOGOTA", "17"=>"BOGOTA", "170"=>"BOGOTA", "171"=>"BOGOTA", "172"=>"BOGOTA", "173"=>"ESTE", "174"=>"ESTE", "176"=>"ESTE", "177"=>"ESTE", "178"=>"ESTE", "179"=>"ESTE-ESTE", "18"=>"BOGOTA", "180"=>"ESTE-ESTE", "181"=>"ESTE-ESTE", "182"=>"ESTE-ESTE", "182BIS"=>"ESTE-ESTE", "184"=>"OESTE", "185"=>"OESTE", "186"=>"OESTE", "187"=>"BOGOTA", "188"=>"BOGOTA", "189"=>"BOGOTA", "19"=>"BOGOTA", "190"=>"BOGOTA", "191"=>"BOGOTA", "192"=>"BOGOTA", "194"=>"ESTE", "195"=>"ESTE", "196"=>"ESTE", "197"=>"ESTE", "198"=>"ESTE", "199"=>"ESTE-ESTE", "2"=>"ESTE", "20"=>"BOGOTA", "200"=>"ESTE-ESTE", "201"=>"ESTE-ESTE", "201BIS"=>"ESTE-ESTE", "203"=>"OESTE", "204"=>"OESTE", "205"=>"OESTE", "206"=>"BOGOTA", "207"=>"BOGOTA", "208"=>"BOGOTA", "209"=>"BOGOTA", "21"=>"BOGOTA", "210"=>"BOGOTA", "211"=>"BOGOTA", "213"=>"ESTE", "214"=>"ESTE", "215"=>"ESTE", "216"=>"ESTE", "217"=>"ESTE", "218"=>"ESTE-ESTE", "219"=>"ESTE-ESTE", "22"=>"ESTE", "220"=>"ESTE-ESTE", "220BIS"=>"ESTE-ESTE", "222"=>"OESTE", "223"=>"OESTE", "224"=>"OESTE", "225"=>"BOGOTA", "226"=>"BOGOTA", "227"=>"BOGOTA", "228"=>"BOGOTA", "229"=>"BOGOTA", "230"=>"BOGOTA", "231"=>"ESTE", "232"=>"ESTE", "233"=>"ESTE", "234"=>"ESTE", "235"=>"ESTE", "236"=>"ESTE", "237"=>"ESTE-ESTE", "238"=>"ESTE-ESTE", "239BIS"=>"ESTE-ESTE", ""=> "BOGOTA");		
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);
		}
		
		function selectAll() {
			if (!$this->conn) {
			  echo "Error de Conexión con enlaces superiores.\n";
			  return 0;
			}

			$queryStr =  'SELECT * FROM SOL_ARCIFINIOS_TMP ORDER BY id_solicitud';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al seleccionar el punto arcifinio de la Placa.\n";
			  return 0;
			}					
			
			$lista = pg_fetch_all($result);
			pg_free_result($result);
			
			return  $lista;
		}

		function insertAll($sol) {
			if (!$this->conn) {
			  echo "Error de Conexión con enlaces superiores.\n";
			  return 0;
			}
			
			$queryStr =  " insert into SOL_ARCIFINIOS_TMP (
				ID_SOLICITUD,		
				PLANCHA_IGAC,		
				SISTEMA_ORIGEN,		
				DESCRIPCION_PA
				) values (
					$1, $2, $3, $4
				)
			";			
					
			if(strlen($sol["sistemaOrigen"])>15)  	$sol["sistemaOrigen"] = $this->planchasIgac[trim($sol["planchaIgac"])];			
			$descripcionPA = substr($sol["DescripcionPA"],0,320);
			
			$params = array($sol["idPlaca"], substr($sol["planchaIgac"],0,6), substr($sol["sistemaOrigen"],0,15), $descripcionPA);
			$result = pg_query_params($this->conn, $queryStr, $params);
			
			if(pg_last_error($this->conn))
				echo "<table bgcolor='yellow' border = 0><tr><td>Error al almacenar puntos arcifinios</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";
		}
		
		function setSistemaOrigen($placa, $origen) {
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
			
			$queryStr =  "select setOrigenInSIGMIN($1, $2) as result";			
			
			$params = array($placa, $origen);
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];				
		}

		function deleteArcifinios($idSolicitud) {
			if (!$this->conn) {
				echo "Error de Conexión con enlaces superiores.\n";
				return 0;
			}
			
			$queryStr =  "delete from SOL_ARCIFINIOS_TMP where id_solicitud=$1";			
			$params = array($idSolicitud);
			$result = pg_query_params($this->conn, $queryStr, $params);
			if(pg_last_error($this->conn))
			echo "<table bgcolor='yellow' border = 0><tr><td>Error al eliminar punto arcifinio de solicitud</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
		}
	}	
?>

