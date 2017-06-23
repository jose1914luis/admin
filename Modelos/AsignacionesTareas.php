<?php
/*
	Clase encargada de gestionar las tareas asociadas a los usuarios
	en el CMQ
*/

	class AsignacionesTareas {	
		var $conn;
	
		function __construct() {
			$this->conn = pg_connect($GLOBALS ["db1"]);			
			if (!$this->conn) {
				echo "Error de Conexión clase AsignacionesTareas.\n";
				return 0;
			}
		}
		
		function actualizacionEstadoTarea($nombreTarea, $placa, $usuario) {
			$idAsignacion 	= $this->getTareaAsignada($placa, $nombreTarea);
			$idTareaUsuario	= $this->getIdTareaUsuario($nombreTarea, $usuario);
			
			if($idAsignacion != 0 && $idTareaUsuario != 0) {
				if($this->updateAsignacionTareaUsuario($idTareaUsuario, $idAsignacion) == 0) {
					echo "<table bgcolor='red' border=0><tr><td>Error al actualizar el estado de la tarea realizada</td></tr></table>";				
					return 0;
				}				
			} 
			return 1;
		}
		
		function asignacionTarea($nombreTarea, $placa, $tipoExpediente, $usuario) {
			//$idTareaUsuario = $this->getUsuarioTareaMenorAsignacion($nombreTarea);
			$idTareaUsuario = $this->getIdTareaUsuario($nombreTarea, $usuario);
			
			if($idTareaUsuario != 0) {
				if($this->insertAsignacionTarea($idTareaUsuario, $placa, $tipoExpediente)) {
					if($this->updateNroAsignacionesTareaUsuario($idTareaUsuario)==0) {
						echo "<table bgcolor='red' border=0><tr><td>Error al generar asignacion de tarea <b>No se actualizó el número de tareas asignadas</b></td></tr></table>";				
						return 0;						
					}
				} else {
					echo "<table bgcolor='red' border=0><tr><td>Error al generar asignacion de tarea <b>No se creo registro satisfactoriamente</b></td></tr></table>";				
					return 0;
				}				
			} else {
				echo "<table bgcolor='red' border=0><tr><td>Error al generar asignacion de tarea: No existen usuarios definidos para la tarea <b>$nombreTarea</b></td></tr></table>";
				return 0;
			}
			return 1;
		}		

		function getUsuarioTareaMenorAsignacion($nombreTarea) {
			$queryStr =  "
				select tu3.id 
				from tareas_usuarios tu3,
					tareas t3,
					(
						select  min(numero_asignaciones) as menor_asignacion
						from 	tareas_usuarios tu, 
							tareas t
						where	t.nombre = $1
							and tu.id_tarea=t.id
							and tu.fecha_terminacion is null
					) tu2
				where tu3.numero_asignaciones=tu2.menor_asignacion
					and t3.nombre=$1
					and t3.id=tu3.id_tarea
					and tu3.fecha_terminacion is null	
				limit 1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array(utf8_encode($nombreTarea)));
			if (!$result) {
			  echo "Error al consultar registro TareaUsuario con menor asignaci&oacute;n.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["id"];
		}

		function getTareaAsignada($placa, $nombreTarea) {
			$queryStr =  "
				select  at.id
				from 	asignaciones_tareas at,
					tareas_usuarios tu,
					tareas t
				where	at.placa=$1
					and at.id_tarea_usr_asigna=tu.id
					and tu.id_tarea=t.id
					and t.nombre=$2
					and at.fecha_termina is null	
					limit 1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array(utf8_encode($placa), utf8_encode($nombreTarea)));
			if (!$result) {
			  echo "Error al consultar el ID de una tarea previamente asignada.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["id"];
		}
		
		function getIdTareaUsuario($nombreTarea, $usuario) {
			$queryStr =  "
				select  tu.id 
				from 	tareas_usuarios tu,
					usuarios u,
					tareas t
				where 	t.nombre=$1 
					and t.id=tu.id_tarea
					and u.login=$2
					and u.id=tu.id_usuario
					and tu.fecha_terminacion is null
					limit 1
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array(utf8_encode($nombreTarea), utf8_encode($usuario)));
			if (!$result) {
			  echo "Error al consultar el ID de tarea mediante usuario-nombre tarea.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista[0]["id"];
		}		
		
		function updateNroAsignacionesTareaUsuario($idTareaUsuario) {		
			$queryStr		= "update tareas_usuarios set numero_asignaciones=numero_asignaciones+1 where id = $1";			
			$result = pg_query_params($this->conn, $queryStr, array($idTareaUsuario));
			$ERROR = pg_last_error($this->conn);
			if($ERROR) {
				echo "<table bgcolor='red' border = 0><tr><td>Error al actualizar el número de asignaciones:</td></tr><tr><td>$ERROR</td></tr></table>";
				return 0;
			}
			return 1;
		}	
		
		function updateAsignacionTareaUsuario($idTareaUsuario, $idAsignacion) {		
			$queryStr		= "update asignaciones_tareas set id_tarea_usr_realiza=$1, fecha_termina=now() where id=$2";			
			$result = pg_query_params($this->conn, $queryStr, array($idTareaUsuario, $idAsignacion));
			$ERROR = pg_last_error($this->conn);
			if($ERROR) {
				echo "<table bgcolor='red' border = 0><tr><td>Error al actualizar tarea existente:</td></tr><tr><td>$ERROR</td></tr></table>";
				return 0;
			}
			return 1;
		}	
		
		function selectAsignacionesByUsuario($loginUsuario) {
			$queryStr =  "
				select  at.placa,
					at.tipo_expediente,
					at.fecha_asigna,
					t.nombre as tarea
				from 	usuarios u,
					tareas_usuarios tu,
					asignaciones_tareas at,	
					tareas t
				where 	u.login=$1
					and u.id=tu.id_usuario
					and tu.id_tarea=t.id		
					and tu.id=at.id_tarea_usr_asigna
					and at.fecha_termina is null
				order by at.fecha_asigna
			";			
			
			$result = pg_query_params($this->conn, $queryStr, array(utf8_encode($loginUsuario)));
			if (!$result) {
			  echo "Error al consultar tareas asignadas al usuario <b>$loginUsuario</b>.\n";
			  return 0;
			}						
			$lista = pg_fetch_all($result);			
			pg_free_result($result);

			return  $lista;
		}
		
		
		function insertAsignacionTarea($idTareaUsuario, $placa, $tipoExpediente) {
			$queryStr		= "
				insert into asignaciones_tareas(id_tarea_usr_asigna, placa, tipo_expediente) 
					values ($1, $2, $3)
			";			
			$params 		= array($idTareaUsuario, utf8_encode($placa), utf8_encode($tipoExpediente));
	
			$result = pg_query_params($this->conn, $queryStr, $params);
			$ERROR = pg_last_error($this->conn);
			if($ERROR) {
				echo "<table bgcolor='red' border = 0><tr><td>Error al Insertar Asignaci&oacute;n de Tarea:</td></tr><tr><td>$ERROR</td></tr></table>";
				return 0;
			}
			return 1;
		}				

	}	
?>

