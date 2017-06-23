<?php
function procesarCoordenadasWKT($cadena) {
	
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

?>

