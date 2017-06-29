<?php
/*
	Clase encargada de realizar la conexión a los servicios del servidor www.sigmin.com.co
*/

	class LibCurl {			
		function curl_download($Url, $params)	{
		 
			// is cURL installed yet?
			if (!function_exists('curl_init')){
				die('cURL no está instalado!');
			}
		 
			// OK cool - then let's create a new cURL resource handle
			$ch = curl_init();
		 
			// Now set some options (most are optional)
		 
			// Set URL to download
			curl_setopt($ch, CURLOPT_URL, $Url);
		 
			// Set a referer
			curl_setopt($ch, CURLOPT_REFERER, "http://www.sigmin.co");
		 
			// User agent
			curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		 
			// Include header in result? (0 = yes, 1 = no)
			curl_setopt($ch, CURLOPT_HEADER, 0);
		 
			// Should cURL return or print out the data? (true = return, false = print)
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
			// Timeout in seconds
			curl_setopt($ch, CURLOPT_TIMEOUT, 300);
			
			// que no retorne las cabeceras en la respuesta
			curl_setopt( $ch, CURLOPT_HEADER, false );

			// indicamos que utilizaremos POST
			curl_setopt( $ch, CURLOPT_POST, true );

			// indicamos los parametros
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $params);		
		 
			// Download the given URL, and return output
			$output = curl_exec($ch);
		 
			// Close the cURL resource, and free system resources
			curl_close($ch);
		 
			return $output;
		}
	}
?>