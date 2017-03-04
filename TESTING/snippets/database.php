<?php

	//First connect to the KCSSites DB
	require("../kcssites/snippets/database.php");
	
	$pathStr = realpath("");
	$pathArr = explode("/", $pathStr);
	$sitename = $pathArr[count($pathArr)-1];
	
	try {
		
		$sql = "SELECT * FROM Sites WHERE `SITENAME`='$sitename'";
		$sth = $db_conn->prepare($sql);
		$sth->execute();
		$sites = $sth->fetchAll();
	
		//Second connect to the client's site DB
		if($sites) {
			
			$password = $sites[0]['PASSWORD'];
			
			$db_conn = new PDO("mysql:host=$db_servername;dbname=" . $sitename, $sitename, $password);
			$db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
			
		} else {
			
			$db_conn = NULL;
			throw new Exception("Could not find a matching sitename.");			
			
		}
		
	} 
	catch(PDOException $pdoe) {
		die("A connection to the database could not be made. Please contact the Webmaster.<br>" . $pdoe->getMessage());
	}
	catch(Exception $e) {
		die($e->getMessage());
	}
	
?>
