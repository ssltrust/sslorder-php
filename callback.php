<?php

$username = 'foo';
$password = 'secret';

// HTTP-Authentifizierung
if($_SERVER['PHP_AUTH_USER'] != $username || $_SERVER['PHP_AUTH_PW'] != $password){
	header('HTTP/1.0 401 Unauthorized');
	die("Unauthorized");
}

// POST-Daten auslesen und decodieren
$data = json_decode(file_get_contents('php://input'));

// Zertifikats-Objekt rausholen
$certificate = $data->certificate;

// aktuellen Status auslesen
$state = $certificate->state;

if($state == 'active'){
	// Server-Zertifikat speichern
	file_put_contents("/tmp/".$certificate->name.".crt", $certificate->server_certificate);
	
	// CA-Bundle speichern
	file_put_contents("/tmp/".$certificate->name."-bundle.crt", $certificate->ca_certificates);
}

?>