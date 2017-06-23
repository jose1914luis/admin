<?php

class procesarIdentifySolicitud {
	var $archivo;
	var $campos;

	function __construct($textoArchivo) {
		$this->archivo = $textoArchivo;
	}

	function generarInformacion() {
		$resultados = "";
		$textoArchivo = $this->archivo;
		
		$cadenaDatos = "";
		
		$cadenaDatos .= $this->capturarCampo("CODIGO_EXPEDIENTE"); 
		$cadenaDatos .= $this->capturarCampo("FECHA_RADICACION"); 		
		$cadenaDatos .= $this->capturarCampo("OBSERVACION"); 
		$cadenaDatos .= $this->capturarCampo("JUSTIFICACION_EXTEMPORANEA"); 
		$cadenaDatos .= $this->capturarCampo("DOCUMENTO_SOPORTE");
		$cadenaDatos .= $this->capturarCampo("DIRECCION_CORRESPONDENCIA");
		$cadenaDatos .= $this->capturarCampo("TELEFONO_CONTACTO");

		$cadenaDatos = "<table border=1>".$cadenaDatos."</table>";	
		return $cadenaDatos;
	}
		
	function capturarCampo($campo, $textoArchivo="") {
		$tablaCampo		= "";
		$buscar 		= $campo;
		$longArchivo	= strlen($textoArchivo);
		
		if($textoArchivo=="")
			$textoArchivo = $this->archivo;
		
		$posRel = stripos($textoArchivo, $buscar);
		$subTextoArchivo = substr($textoArchivo, 0, $posRel);
		
		//echo "<hr>posRel: $posRel :: longArchivo:$longArchivo";
		
		$posIni  = strripos($subTextoArchivo, "<tr");
		$posFin  = stripos($textoArchivo, "</tr>", $posRel);
		
		$fila  = substr($textoArchivo, $posIni, ($posFin - $posIni + strlen("</tr>")));

		$valor = trim(substr($fila, strripos($fila, 'ItemClass">') + strlen('ItemClass">'), strripos($fila, "</td>") - strripos($fila, 'ItemClass">') - strlen('ItemClass">')));
		$this->campos[$campo] = $valor;
		
		return "<tr><td>$campo</td><td>".$this->campos[$campo]."</td></tr>";
	}
	
	function getCodigoExpediente() {
		return $this->campos["CODIGO_EXPEDIENTE"];
	}
	
	function getAll() {
		return $this->campos;
	}	
}
?>



