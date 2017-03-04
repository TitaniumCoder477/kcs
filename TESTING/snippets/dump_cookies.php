<?php	
	if(false) {
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$name = $_COOKIE[kcs_user_name];
		$email = $_COOKIE[kcs_user_email];
		$option = $_COOKIE[kcs_option];
		$category = $_COOKIE[kcs_category];
		$item = $_COOKIE[kcs_item];
		
		print("COOKIES: Date($date) Time($time)");
		print("<br>----------------------------------------------------------------------------<br>");
		print("kcs_user_name($name)<br>kcs_user_email($email)<br>kcs_option($option)<br>kcs_category($category)<br>kcs_item($item)</p><br>");
	}
?>