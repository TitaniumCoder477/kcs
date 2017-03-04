<?php 
	$email = $_POST['email'];
	if(isset($email))
		setcookie('kcs_user_email',$email);
?>

<!DOCTYPE html>
<?php require("snippets/copyright_code.php"); ?>
<html lang="en">
<head role="heading">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>KCS</title>

    <?php require("snippets/cssfiles.php"); ?>	
	<?php require("snippets/scriptfiles.php"); ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!-- Customizations to the options footer -->
	<script>
		$(document).ready(function() {
			$("#options-content").html("<a href='index.php'><button type='button' class='btn btn-lg btn-default'>Cancel</button></a>");
		});
	</script>	
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<script>
				document.write("<a href='index.php'>Home</a> > Admin authenticate > Result");
			</script>
		</h3>
	</div>
	<div class="panel-body">	
		<div class='panel panel-info'>
			<div class='panel-heading'>
				<h3 class='panel-title'>Result</h3>
			</div>
			<div class='panel-body'>
			<?php
			
				/* CONNECT TO DATABASE */	
				require("snippets/database.php");
				
				/* AUTHENTICATE */
				try {
					$email = $_POST['email'];
					$pin = $_POST['pin'];
					
					$sql = "SELECT * FROM Users WHERE EMAIL='$email' AND PIN='$pin' AND ADMIN='1'";
					$sth = $db_conn->prepare($sql);
					$sth->execute();
					$result = $sth->fetchAll();
					
					if(!$result) {							
						print("	You are not an admin or the credentials you entered are not correct.<br><br>
								<span id=\"fail-timer\">You will be redirected to the home page.</span>");									
					} else {
						$datetime = date_create();
						date_add($datetime,date_interval_create_from_date_string("1 hour"));
						$sql = "UPDATE Users SET TIMEOUT=\"" . date_format($datetime, "Y-m-d H:i:s") . "\" WHERE EMAIL=\"$email\" AND PIN=\"$pin\" AND ADMIN=\"1\"";
						$db_conn->query($sql);
						
						print("	Success!<br><br>
								<span id=\"success-timer\">You will be redirected to the admin management page.</span>");									
					}
				} catch(PDOException $e) {
					die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
				}
				
				/* CLOSE DATABASE CONNECTION */

				$db_conn = null;
			?>
			</div>
		</div>
	</div>
</div>

<?php require("snippets/options.php"); ?>

<script>
	function setAdmin(email) {
		document.cookie="kcs_user_email=" + email;
	}
	
	window.onload = function () {
		var display = document.querySelector('#fail-timer');
		if(display) 
			redirectPageOnCountdown("Home in... ", 5, " seconds", display, "index.php");
		else {
			display = document.querySelector('#success-timer');
			redirectPageOnCountdown("Management page in... ", 2, " seconds", display, "admin.php");
		}
	};
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>