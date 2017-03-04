<?php
	/* AUTHENTICATE */
	try {
		$email = $_COOKIE['kcs_user_email'];
		$sql = "SELECT * FROM Users WHERE EMAIL='$email' AND TIMEOUT > NOW()";
		$sth = $db_conn->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll();
		
		if(!$result) {
			
			/* CLOSE DATABASE CONNECTION */
			$db_conn = null;
		
			print("	<script>
						window.location='admin-auth-expired.php';
					</script>");
					
		}				
	} catch(PDOException $e) {
		die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
	}
?>