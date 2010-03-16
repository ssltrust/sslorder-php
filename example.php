<?php

require_once "lib/rest_client.php";
require_once "config.php";

// REST-Client und Resourcen erzeugen
$restClient           = new RestClient($url, "$customer_id-$username", $password);
$productsResource     = $restClient->resource('products'); 
$certificatesResource = $restClient->resource('certificates');

// Alle Produkte mit Preisen holen
$products = $productsResource->getAll();

// Alle laufenden Bestellungen und gültige Zertifikate holen
$certificates = $certificatesResource->getAll(array('state'=>'pending,active'));

// Bestellung mit einer Bestimmten ID holen
$certificate = $certificatesResource->get(12345);

// ... Server-Zertifikat im PEM-Format
$server_crt = $certificate->certificate->server_certificate;

// Erneuerungs-Hinweise eines Zertifikats deaktivieren
$certificatesResource->update(12345, array("certificate" => array("renewal_notice" => 1)));

// Daten einer neuen Bestellung validieren
$order = array('certificate' => array(
	'csr'      		 => '-----BEGIN CERTIFICATE REQUEST-----[...]-----END CERTIFICATE REQUEST-----',
	'product_id'     => 1,
	'data'           => array(
		'approver_email'  => 'ssladmin@example.com',
		'admin_email'     => 'root@example.com',
		'admin_firstname' => 'Foo',
		'admin_lastname'  => 'Bar',
		'admin_phone'     => '+49 123 12345678',
		'tech_email'      => "root@example.com",
		'tech_firstname'  => 'Foo',
		'tech_lastname'   => 'Bar',
		'tech_phone'      => '+49 123 12345678'
	)
));

$result = $certificatesResource->custom('POST','validate_data',$order);

// Bestellung aufgeben
//$result = $certificatesResource->create($order);

?>