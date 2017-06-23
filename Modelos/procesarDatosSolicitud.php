<?php

class procesarDatosSolicitud {
	var $archivo;
	var $campos;
	var $validaConsistenciaPorcentaje;

	function __construct($textoArchivo) {
		$this->archivo = $textoArchivo;
	}

	function generarInformacion() {
		$resultados = "";
		//$textoArchivo = $this->archivo;

		
		$busqueda = 'codigoExpediente';
		$resultados .= $this->capturarCampo($busqueda);
		
		$busqueda = 'clasificacion';
		$resultados .= $this->capturarCampo($busqueda);

		$busqueda = 'modalidad';
		$resultados .= $this->capturarCampo($busqueda);

		$busqueda = 'estadoJuridico';
		$resultados .= $this->capturarCampo($busqueda);

		$busqueda = 'grupoTrabajo';
		$resultados .= $this->capturarCampo($busqueda);
		
		$busqueda = 'numeroFormulario';
		$resultados .= $this->capturarCampo($busqueda);	
		
		$busqueda = 'fechaIngreso';
		$resultados .= $this->capturarCampo($busqueda);

		$busqueda = 'tipoArea';
		$resultados .= $this->capturarCampo($busqueda);

		$busqueda = 'areaSolicitada';
		$resultados .= $this->capturarCampo($busqueda);
		
		$busqueda = 'areaDefinitiva';
		$resultados .= $this->capturarCampo($busqueda);
		
		$busqueda = 'planchaIgac';
		$resultados .= $this->capturarCampo($busqueda);	
		
		
		$cadenaDatos = "<table border=1>".$resultados."</table><hr size='1'>".$this->procesaArcifinio()."<hr size='1'>".$this->procesaMinerales()."<hr size='1'>".$this->procesaMpiosDeptos();
		return $cadenaDatos;
	}
	
	
	function capturarCampo($campo) {
		$textoArchivo 	= $this->archivo;		
		$buscar 		= ':'.$campo.'">';
		$longitudBuscar = strlen($buscar);
		
		$posIni = stripos($textoArchivo, $buscar);
		if($posIni) 
			$posIni += $longitudBuscar; // numero de caracteres de :codigoExpediente">
		
		$posFin = stripos($textoArchivo, "</", $posIni);
		$dato  = trim(substr($textoArchivo, $posIni, ($posFin-$posIni)));
		$this->campos[$campo] = ($dato!="&lt;Null&gt;") ? $dato : "";
		
		return "<tr><td><b>".$campo."</b></td><td>".$dato."</td></tr>";		
	}
	
	function capturarTabla($campo, $textoArchivo="") {
		$tablaCampo		= "";
		$buscar 		= $campo;
		$longArchivo	= strlen($textoArchivo);
		
		if($textoArchivo=="")
			$textoArchivo = $this->archivo;
		
		$posRel = stripos($textoArchivo, $buscar);
		$subTextoArchivo = substr($textoArchivo, 0, $posRel);
		
		//echo "<hr>posRel: $posRel :: longArchivo:$longArchivo";
		
		$posIni  = strripos($subTextoArchivo, "<table");
		$posFin  = stripos($textoArchivo, "</table>", $posRel);
		
		$tablaCampo  = substr($textoArchivo, $posIni, ($posFin - $posIni+strlen("</table>")));
		
		return $tablaCampo;
	}
	
	function procesaArcifinio() {
		$tabla = $this->capturarTabla("Punto Arcifinio");
		$showTabla = "";
		
		$posIni  = stripos($tabla, "<td>");
		$posFin  = stripos($tabla, "</td>");
		$this->campos["sistemaOrigen"] = trim(substr($tabla, $posIni + strlen("<td>"), ($posFin-$posIni-strlen("<td>")))); 
		$tabla= substr($tabla, $posFin, strlen($tabla) - $posFin - strlen("</td>"));		
		$showTabla .= "<tr><td><b>sistemaOrigen</b></td><td>".$this->campos["sistemaOrigen"]."</td></tr>";

		// salto del campo area, esta puede obtenerse de la parte geográfica
		$posFin  = stripos($tabla, "</td>");
		$tabla= substr($tabla, $posFin, strlen($tabla) - $posFin - strlen("</td>"));

		$posIni  = strripos($tabla, "<td>");
		$posFin  = strripos($tabla, "</td>");
		$this->campos["DescripcionPA"] = trim(substr($tabla, $posIni + strlen("<td>"), ($posFin-$posIni-strlen("<td>")))); 
		$tabla= substr($tabla, $posFin, strlen($tabla) - $posFin - strlen("</td>"));
		$showTabla .= "<tr><td><b>DescripcionPA</b></td><td>".$this->campos["DescripcionPA"]."</td></tr>";
		
		$showTabla = "<table border='1'><tr><td align='LEFT' colspan='2'><b>Punto Arcifinio</b></td></tr>".$showTabla."</table>";
		
		return $showTabla;
	}
	
	function procesaMinerales() {
		$tabla1		= $this->capturarTabla("Minerales</td>");
		$tabla		= $this->capturarTabla("Nombre", $tabla1);
	
		$showTabla 	= "";
		$i			= 0; 
		
		$posIni  = stripos($tabla, "<td>");
		
		while($posIni) {
			$posFin  = stripos($tabla, "</td>");
					
			$this->campos["mineral"][$i] = trim(substr($tabla, $posIni + strlen("<td>"), ($posFin-$posIni-strlen("<td>")))); 
			$showTabla .= "<tr><td>".$this->campos["mineral"][$i]."</td></tr>";
					
			$tabla= substr($tabla, stripos($tabla, "</td>") + strlen("</td>"), strlen($tabla) - stripos($tabla, "</td>"));	
			$tabla= substr($tabla, stripos($tabla, "</td>") + strlen("</td>"), strlen($tabla) - stripos($tabla, "</td>"));	
			$tabla= substr($tabla, stripos($tabla, "</td>") + strlen("</td>"), strlen($tabla) - stripos($tabla, "</td>"));				
				
			$posIni  = stripos($tabla, "<td>");			
			$i++;
		}		
		return "<table border='1'><tr><td><b>Minerales:</b></td></tr>".$showTabla."</table>";
	}	
	
	function procesaMpiosDeptos() {
		$tabla		= $this->capturarTabla("Departamento</th>");
	
		$showTabla 	= "";
		$i			= 0; 
		
		$posIni  = stripos($tabla, "<td>");
		$sumaPorcentajeMpios = 0;
		
		while($posIni) {
			$posFin  = stripos($tabla, "</td>");			
			$this->campos["departamento"][$i] = trim(substr($tabla, $posIni + strlen("<td>"), ($posFin-$posIni-strlen("<td>")))); 			
			$tabla= substr($tabla, stripos($tabla, "</td>") + strlen("</td>"), strlen($tabla) - stripos($tabla, "</td>"));	
				
			$posIni  = stripos($tabla, "<td>");	
			$posFin  = stripos($tabla, "</td>");			
			$this->campos["municipio"][$i] = trim(substr($tabla, $posIni + strlen("<td>"), ($posFin-$posIni-strlen("<td>")))); 			
			$tabla= substr($tabla, stripos($tabla, "</td>") + strlen("</td>"), strlen($tabla) - stripos($tabla, "</td>"));	

			$posIni  = stripos($tabla, "<td>");	
			$posFin  = stripos($tabla, "</td>");			
			$this->campos["porcentaje"][$i] = trim(substr($tabla, $posIni + strlen("<td>"), ($posFin-$posIni-strlen("<td>")))); 			
			$tabla= substr($tabla, stripos($tabla, "</td>") + strlen("</td>"), strlen($tabla) - stripos($tabla, "</td>"));
		
			$sumaPorcentajeMpios += $this->campos["porcentaje"][$i];			
		
			$posIni  = stripos($tabla, "<td>");	
			$showTabla .= "<tr><td>".$this->campos["departamento"][$i]."</td><td>".$this->campos["municipio"][$i]."</td><td>".$this->campos["porcentaje"][$i]."</td></tr>";					
			$i++;
		}		
		$this->validaConsistenciaPorcentaje = $sumaPorcentajeMpios;
		
		return "<table border=1><tr><td><b>Departamento</b></td><td><b>Municipio</b></td><td><b>%Participaci&oacute;n</b></td></tr>".$showTabla."</table>";
	}	
	
	function esConsistentePorcentajeMunicipios() {
		if($this->validaConsistenciaPorcentaje < 101) return 1;
		else return 0;	
	}

	function getCodigoExpediente() {
		return $this->campos["codigoExpediente"];
	}

	function getAll() {
		return $this->campos;
	}
	
	function getEstadoJuridico() {
		$busqueda = 'estadoJuridico';
		$this->capturarCampo($busqueda);
		
		return $this->campos["estadoJuridico"];
	}
}
?>



