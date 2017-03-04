<?php

	try {
		$db_servername = "localhost";
		$db_username = "root";
		$db_password = "0:OuzKVA";
		$sitename = "TESTING";
				
		$db_conn = new PDO("mysql:host=$db_servername;dbname=" . $sitename, $db_username, $db_password);
		$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
	} 
	catch(PDOException $pdoe) {
		die("A connection to the database could not be made. Please contact the Webmaster.<br>" . $pdoe->getMessage());
	}
	catch(Exception $e) {
		die($e->getMessage());
	}
	
?>
