<?php
	$date = date("Y-m-d");
	$time = date("H:i:s");
	$name = $_COOKIE[kcs_user_name];
	$email = $_COOKIE[kcs_user_email];
	$option = $_COOKIE[kcs_option];
	$category = $_COOKIE[kcs_category];
	$item = $_COOKIE[kcs_item];
	
	$sql = "INSERT INTO History VALUES('$date','$time','$name','$email','$option','$category','$item','')";
	$sth = $db_conn->prepare($sql);
	$sth->execute();
?>