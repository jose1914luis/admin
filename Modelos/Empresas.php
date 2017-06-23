<?php
/*
	Clase encargada de la administración y gestión de empresas del SIGMIN
*/

	class Empresas {	
		var $conn;
		
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión en la clase Empresa.\n";
				return 0;
			}
		}
		
		function selectAll() {
			$queryStr =  'select id, nit, nombre, email, fax, apartado_aereo, telefono, direccion, id_ciudad_sede_ppal  from empresas order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Empresas.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectIdNameAll() {
			$queryStr =  'select id, nombre from empresas order by nombre';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Id y Nombre de Empresas.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		
		function selectByNIT($nit) {	
			$queryStr =  'select id, nit, nombre, email, fax, apartado_aereo, telefono, direccion, id_ciudad_sede_ppal from empresas where nit=$1 limit 1 order by nombre';			
			
			$result = pg_query_params($this->conn, $queryStr, array($nit));
			if (!$result) {
			  echo "Error al consultar Empresa bajo el nit $nit.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		

		function selectByID($id) {
			$queryStr =  'select id, nit, nombre, email, fax, apartado_aereo, telefono, direccion, id_ciudad_sede_ppal from empresas where id=$1 limit 1';			
			
			$result = pg_query_params($this->conn, $queryStr, array($id));
			if (!$result) {
			  echo "Error al consultar Empresa bajo el Id $Id.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		

		function selectByNombre($nombre) {
			$queryStr =  'select id, nit, nombre, email, fax, apartado_aereo, telefono, direccion, id_ciudad_sede_ppal from empresas where nombre=$1 order by nombre';			
			
			$result = pg_query_params($this->conn, $queryStr, array($nombre));
			if (!$result) {
			  echo "Error al consultar Empresa bajo el Nombre $nombre.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}		
		
		function insertAll($emp) {
			$queryStr =  "select empresas_insert($1, $2, $3, $4, $5, $6, $7, $8) as result";			
			
			$params = array(trim(utf8_encode($emp["emp_nit"])), trim(utf8_encode($emp["emp_nombre"])), trim($emp["emp_email"]), trim($emp["emp_fax"]), trim($emp["emp_apartado_aereo"]), trim($emp["emp_telefono"]), trim(utf8_encode($emp["emp_direccion"])), $emp["emp_id_ciudad"]);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			if (pg_last_error($this->conn)) {
				return pg_last_error($this->conn);	// proceso con errores de almacenamiento
			}	
			$lista = pg_fetch_all($result);	
			return  $lista[0]["result"];

		}		
	}	
?>

