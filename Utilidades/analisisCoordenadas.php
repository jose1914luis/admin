<?php


$Areas = null;

function procesar_coordenadas($cadena, $pos, &$Areas, $nro_parentesis_izq, $indice) {
	if(strlen($cadena)> $pos) {
		$posIzq = strpos($cadena, "(", $pos);
		$posDer = strpos($cadena, ")", $pos);
		
//echo "<hr>$nro_parentesis_izq";		
		
		if($posIzq && $posDer) {
			if($posIzq<$posDer) {
				$nro_parentesis_izq++;
				if($nro_parentesis_izq ==3 ) {
					//$indice = (isset($Areas[1]))? (sizeof($Areas) + 1) : 1;	
//echo 	substr($cadena,0,$posIzq)."<hr>";				
//echo 	substr($cadena,$posIzq-1,1)."<hr>";				
					//if(substr($cadena,$posIzq -1,1)==",")
					//	$indice --;
					$sub_indice = (isset($Areas[$indice][1]))? (sizeof($Areas[$indice]) + 1) : 1;
//echo "Area[$indice][$sub_indice] =".substr($cadena, ($posIzq+2), $posDer - $posIzq -1)."<hr>"; 
					$Areas[$indice][$sub_indice] = str_replace("","", str_replace("", "",substr($cadena, ($posIzq+1), $posDer - $posIzq -1))); 					
					
					//$nro_parentesis_izq++;
					procesar_coordenadas($cadena, $posDer, $Areas, $nro_parentesis_izq, $indice);
				} else {
					//$nro_parentesis_izq++;
					if($nro_parentesis_izq==2)
						$indice++;					
					procesar_coordenadas($cadena, ($posIzq+1), $Areas, $nro_parentesis_izq, $indice);
				}
			} else	{
				$nro_parentesis_izq--;
				procesar_coordenadas($cadena, ($posDer+1), $Areas, $nro_parentesis_izq, $indice);
			}
		} 
	}	
}

function procesar_coordenadas_ciclo($cadena) {
	
	$cadena = str_replace("\r","",str_replace("\n", "", $cadena));
	$pos = 0;
	$nro_parentesis_izq = 0;
	$indice = 0;
	$Area=null;
	$longitud_cadena = strlen($cadena);
	
	while($longitud_cadena> $pos) {
		$posIzq = strpos($cadena, "(", $pos);
		$posDer = strpos($cadena, ")", $pos);
	
		if($posIzq && $posDer) {
			if($posIzq<$posDer) {
				$nro_parentesis_izq++;
				if($nro_parentesis_izq ==3 ) {
					$sub_indice = (isset($Areas[$indice][1]))? (sizeof($Areas[$indice]) + 1) : 1;
					$Areas[$indice][$sub_indice] = substr($cadena, ($posIzq+1), $posDer - $posIzq -1); 			
					$pos = $posDer;
				} else {
					if($nro_parentesis_izq==2)
						$indice++;		
					$pos = ($posIzq + 1);
				}
			} else	{
				$nro_parentesis_izq--;
				$pos = ($posDer+1);
			}
		} else
			$pos++;
	}	
	
	return $Areas;
}

$coordenadas = "
MULTIPOLYGON(((850006.593544951 1151027.19898989,849997.465226796 1149995.69903836,844009.288516998 1149995.69903836,844009.246394398 1149976.03127863,843998.358501753 1149976.00419432,844005.868603017 1148398.88292881,844002.868285687 1146997.98364483,843680.652926771 1146997.98364483,843683.907627366 1147069.58705792,843360.765057234 1147253.23164673,843779.22984161 1149635.96806553,843450.091510946 1149635.96806553,843450.091510946 1150318.93010166,843153.867013348 1150323.04433079,840570.601812429 1149681.48366477,840139.697579557 1149784.55188614,840160.02523475 1150979.81801148,847003.356340393 1150974.71605215,847003.356340393 1150995.83068011,847999.966780349 1151000.05360571,847999.966780349 1151954.43478973,848084.42529221 1151958.65771533,848088.648217803 1150983.16190334,848413.813488466 1150974.71605215,848409.972677488 1151999.96842239,848412.649279189 1151999.96842239,848413.66893698 1151037.15655351,850006.593544951 1151027.19898989),(835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571)),((840149.658808147 1148206.35132691,840143.35897706 1149770.52098583,840573.51799471 1149670.73488142,840676.001020859 1149328.22582035,841115.599264602 1149328.22582035,841285.505334269 1149843.33787283,843138.290570168 1150285.63303832,843068.170604908 1150026.7285512,843441.098434455 1150024.03162946,843443.074086428 1149613.0960191,843227.728021384 1149152.76910942,843654.468847526 1148994.71695159,843348.100402399 1147241.51271166,843148.132385144 1147043.44915171,843188.125988595 1146999.64663365,842445.387638791 1146995.83771903,842441.578724177 1148203.26365179,840149.658808147 1148206.35132691)),((839568.758434796 1149055.92076688,838250.58458414 1149059.47730072,838250.134859263 1149069.37124802,839530.951310748 1149066.22317388,839530.951310748 1149264.10212004,839548.940305853 1149264.10212004,839548.940305853 1149463.7799657,839530.951310748 1149463.7799657,839530.951310748 1149920.70044137,839572.325999489 1149911.70594382,839568.758434796 1149055.92076688),(835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571),(835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571),(835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571),(835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571),(835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571)),((839570.527099979 1149921.15016625,839530.50158587 1149931.49383844,839530.50158587 1149989.50834765,839569.627650223 1149989.50834765,839570.527099979 1149921.15016625)),((838359.38175892 1148375.74995588,838250.421984671 1148376.26881195,838249.903128603 1148379.38194836,838359.122330886 1148379.64137639,838359.38175892 1148375.74995588)),((835261.191983355 1144417.52715003,833208.690160101 1144417.52715003,833258.946258341 1151657.01433653,833220.965479416 1151661.76193389,833220.965479416 1151704.49031018,833700.472813343 1151694.99511545,833690.977618612 1152696.7381596,833320.665024094 1152696.7381596,833325.412621459 1151799.44225749,832997.828403232 1151804.18985486,833002.576000597 1151827.92784169,833296.927037266 1151818.43264696,833301.674634631 1152748.96173062,833766.939176461 1152744.21413325,833766.939176462 1153508.57730912,832997.828403232 1153508.57730912,833016.818792694 1153613.02445116,833216.21788205 1153613.02445116,833225.713076781 1153973.84185095,833016.818792694 1153973.84185095,833016.818792694 1154420.11600331,835219.70397034 1154420.11600331,835219.70397034 1153774.44276159,835134.247217759 1153926.36587729,834640.497091735 1153622.51964589,835300.559318143 1152439.59458202,835184.347023988 1152642.49102174,833897.507854984 1152647.28788743,833877.041228042 1150651.79176055,835770.204220214 1150641.55844708,835770.204220214 1150477.82543154,836169.303445591 1150488.05874501,836169.303445591 1149976.39307145,835279.005173596 1149996.85969839,835254.849448217 1145193.37784507,835748.830625049 1145189.38261891,835255.095001984 1145191.72533458,835261.191983355 1144417.52715003)),((835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571),(835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571),(835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571),(835750.545072768 1152575.58034571,835364.397923473 1152356.02070646,835369.088164079 1152361.32271758,835749.704271888 1152578.27361449,835750.545072768 1152575.58034571)))
";

$nroParentesis = 0;
$pos = 0;
$nro_parentesis_izq = 0;
$indice = 0;

//procesar_coordenadas($coordenadas, $pos, $Areas, $nro_parentesis_izq, $indice);
//print_r($Areas);
echo "<hr>";
print_r(procesar_coordenadas_ciclo($coordenadas));

?>

