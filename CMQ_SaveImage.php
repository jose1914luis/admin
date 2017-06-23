<?php
	session_start();
	
	require_once("Acceso/Config.php"); // Definición de las variables globales	
	require("Modelos/SeguimientosUsuarios.php");	
	require_once("Modelos/Usuarios.php");
	
	// validación de usuarios en CMQ
	$validate = new Usuarios();	
	//$validate->validaAccesoPagina($_SESSION["usuario_cmq"], $_SESSION["passwd_cmq"]);
		
	$msgAcceso = "";
	
	print_r($_FILES);
	
	if (isset($_FILES["imagenFile"]["name"]) && $_FILES["imagenFile"]["name"]!="") {

		//datos del arhivo 
		$nombre_archivo = $_POST["nameImgFile"];	//$HTTP_POST_FILES['imagenFile']['name']; 
		$carpetaArchivo = $_POST["folderImg"];
		$tipo_archivo = $_FILES['imagenFile']['type']; 
		$tamano_archivo = $_FILES['imagenFile']['size']; 
		
		//compruebo si las características del archivo son las que deseo 
		if (!strpos($tipo_archivo, "pdf")) { 
			echo "La extensi&oacute;n no es correcta. <br><br><table><tr><td><li>Se permiten &uacute;nicamente archivos pdf.<br></td></tr></table>"; 
		}else{ 
			if (move_uploaded_file($_FILES['imagenFile']['tmp_name'], $carpetaArchivo.$nombre_archivo)){ 
				 echo "El archivo ha sido cargado correctamente."; 
			}else{ 
				 echo "Error al guardar la imagen"; 
			} 
		} 
		header("Location: ".$carpetaArchivo.$nombre_archivo); 
	} 
		//header("Location: DocumentosElectronicos/r2d2DocumentManagement.pdf"); 
	
?>	

