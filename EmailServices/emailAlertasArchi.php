<?php

require_once("LibCurl.php");

include './Correo.php';

//$body = file_get_contents("http://www.sigmin.co/administracion/SendEmail/prueba_mail.php");	
// envio de alertas de archivo
$url = "http://www.sigmin.co/administracion/SendEmail/EnviosAlertas.php";
$connCurl = new LibCurl;
$resultado = $connCurl->curl_download($url, array("email_envia" => "sigmin@sigmin.com.co"));
$emailRs = json_decode($resultado, true);

echo $emailRs;

//$emailRs 	= $resultado;

/*
  $url = "http://www.sigmin.co/administracion/SendEmail/EnviosAlertas.php";
  $cc = curl_init($url);
  curl_setopt($cc, CURLOPT_RETURNTRANSFER, true);
  $body =  curl_exec($cc);

  curl_error($cc);
  curl_close($cc);
 */
if (!empty($emailRs["mensaje"])) {

    $correo = new Correo();
    $msg = $correo->enviar_email($emailRs["emails_alertas"], "Alerta Liberaci�n de �reas :: RELEASE", $emailRs["mensaje"]);
} else
    $msg = "Error en envio";

echo "Hola Mundo..." . $body . "<hr>";
echo "<hr>Fecha: " . date("j del n de Y") . "<br>$msg</hr>";
