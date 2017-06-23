<?php
/*
	Clase encargada de traer los municipios existentes en el CMC 
*/

	class Municipios {	
		function selectALL($idDepto) {
			try {
				$db = new PDO('oci:dbname='.$GLOBALS["cmc_sid"], $GLOBALS["cmc_user"],$GLOBALS["cmc_password"]);

				// Consulta del tipo de identificacion
				// Consulta filtrada solamente a tipos de identificación válidos:
				
				$queryMpios = 'SELECT * FROM municipios where id_departamento = ? and nombre not like \'%DEFINIR%\' order by nombre';
				$consulta = $db->prepare($queryMpios);
				$consulta->execute(array($idDepto));
				return $consulta->fetchAll();
			} catch (PDOException $e) {
				echo 'Excepcion al Seleccionar Todos los Municipios: ',  $e->getMessage(), "\n";
			}				
		}
	}

?>

