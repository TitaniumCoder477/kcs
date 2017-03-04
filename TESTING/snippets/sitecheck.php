<?php
	$db_servername = "localhost";
	$db_username = "root";
	$db_password = "0:OuzKVA";
	$db_conn = null;
	
	try {
		$db_conn = new PDO("mysql:host=$db_servername;dbname=KCS", $db_username, $db_password);
		$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} 
	catch(PDOException $e) {
		die("A connection to the database could not be made. Please contact the Webmaster.<br>" . $e->getMessage());
	}
?>
