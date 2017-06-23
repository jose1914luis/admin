<?php
/*
	Clase encargada de administrar la información relacionada a solicitudes
	en el CMQ
*/

	class Usuarios {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase Usuarios.\n";
				return 0;
			}
		}
	
		function selectAll() {
			$queryStr =  'select * from usuarios order by login';			
			
			$result = pg_query($this->conn, $queryStr);
			if (!$result) {
			  echo "Error al consultar Usuarios.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}

		function selectUsrByLogin($login) {
			$queryStr =  'select id as id_usuario from usuarios where login=$1 limit 1';			
			
			$result = pg_query_params($this->conn, $queryStr, array($login));
			$ERROR = pg_last_error($this->conn);
			if (!$result) {
			  echo "Error en selectUsrByLogin. $ERROR\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["id_usuario"];
		}		
		
		function insertAll($usuario) {
		
			$queryStr		= "insert into usuarios (login, contrasenia, numero_documento, nombre, descripcion_contrato) values ($1, md5($2), $3, $4, $5)";				
			$params 		= array($usuario["login"], $usuario["contrasenia"], $usuario["numero_documento"], $usuario["nombre"], $usuario["descripcion"]);
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR) 
				echo "<table bgcolor='red' border = 0><tr><td>Error al Insertar Usuarios: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";						
		}		

		function validaPasswd($login, $passwd) {	
			// Validación de la contraseña y el usuario
			$queryStr =  "SELECT count(1) as existe FROM USUARIOS where login=$1 and contrasenia= md5($2) and \"ESTADO\"='ACTIVO' limit 1";			
			$params = array($login, $passwd);
			
			$msgError = "";
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			
			$lista = pg_fetch_all($result);
			
			if($ERROR) {
				echo "<table bgcolor='red' border = 0><tr><td>Error al validar usuario/clave: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
				return 0;
			}
			
			return $lista[0]["existe"];
		}
		
		function updatePasswd($login, $oldPasswd, $newPasswd) {	
			if($this->validaPasswd($login, $oldPasswd)) {
				$queryStr =  "update usuarios set contrasenia=md5($1) where login=$2";			
				$params = array($newPasswd, $login);
				$result = pg_query_params($this->conn, $queryStr, $params);
				$ERROR = pg_last_error($this->conn);	

				if($ERROR) 
					echo "<table bgcolor='red' border = 0><tr><td>Error al actualizar clave de Usuario: $ERROR</td></tr></table>";			
				else
					$msgError = "<script>alert('Clave modificada satisfactoriamente')</script>";
				
			} else 	$msgError = "<script>alert('Usuario o Clave Inválidos')</script>";
			
			return $msgError;
		}	

		function validaAccesoPagina($login, $password) {
			// Validación de la contraseña y el usuario suministrados
			$redireccionError = "";
			
			$pathStr = explode("/",$_SERVER["PHP_SELF"]);					
			
			$pagina = trim($pathStr[sizeof($pathStr)-1]);
			
			if(!$this->validaPasswd($login, $password)) {
				$redireccionError =  "<script>document.location.href='".$GLOBALS ["url_error"]."'</script>";
			} else {
				// Validación de la contraseña y el usuario
				$queryStr =  "
					select count(login) as acceder
					from 	usuarios u,
						paginas p,
						roles r,
						paginas_roles pr,
						usuarios_roles ur
					where
						u.id=ur.id_usuario
						and ur.id_rol=r.id
						and r.id=pr.id_rol
						and pr.id_pagina=p.id
						and u.login = $1
						and p.nombre = $2
						limit 1
				";			
				$params = array($login, $pagina);
				
				$msgError = "";
		
				$result = pg_query_params($this->conn, $queryStr, $params);
				$ERROR = pg_last_error($this->conn);
				
				$lista = pg_fetch_all($result);
				
				if($ERROR) {
					echo "<table bgcolor='red' border = 0><tr><td>Error al validar acceso de usuario a página: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
					return 0;
				}
				
				if(!$lista[0]["acceder"])
					$redireccionError =  "<script>document.location.href='".$GLOBALS ["url_error"]."'</script>";
			}
			if($redireccionError != "")
				echo $redireccionError;
		}
		
		function validaEnlacePagina($login, $password, $pagina) {
			$pathStr = explode("/",$_SERVER["PHP_SELF"]);					
			
			if(!$this->validaPasswd($login, $password)) {
				$redireccionError =  "<script>document.location.href='".$GLOBALS ["url_error"]."'</script>";
			} else {
				// Validación de la contraseña y el usuario
				$queryStr =  "
					select count(login) as acceder
					from 	usuarios u,
						paginas p,
						roles r,
						paginas_roles pr,
						usuarios_roles ur
					where
						u.id=ur.id_usuario
						and ur.id_rol=r.id
						and r.id=pr.id_rol
						and pr.id_pagina=p.id
						and u.login = $1
						and p.nombre = $2
						limit 1
				";			
				$params = array($login, $pagina);
				
				$msgError = "";
		
				$result = pg_query_params($this->conn, $queryStr, $params);
				$ERROR = pg_last_error($this->conn);
				
				$lista = pg_fetch_all($result);
				
				if($ERROR) {
					echo "<table bgcolor='red' border = 0><tr><td>Error al validar enlace de pagina: $ERROR</td></tr><tr><td>".pg_last_error($this->conn)."</td></tr></table>";			
				} else
					return $lista[0]["acceder"];
			}

			return 0;
		}		
	}	
?>

