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
			$("#options-content").html("");
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
				document.write("Admin > Log off > Result");
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
				
				/* ASSUMPTION UPDATE */
				try {
					$email = $_COOKIE['kcs_user_email'];
					$sql = "UPDATE Users SET TIMEOUT=NULL WHERE EMAIL='$email'";
					$db_conn->query($sql);						
					
					print(" Success!<br><br>");
					print("<span id=\"success-timer\">You will be redirected to the home page.</span>");
				} catch(PDOException $e) { 
					
					print(" FAILED: Could not log off user with email '$email'<br><a href=\"$e\">View error</a><br><br>");
					print("	Your user account could not be located! Either it has been deleted, or the email address has been changed.<br>
							If you changed it, please re-login to confirm access. Then you can log off again.<br>
							If you did not change it, then you will need to recreate it using another admin account.<br><br>");
					print("<span id=\"fail-timer\">You will be redirected to the home page.</span>");
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
		var display = document.querySelector('#success-timer');
		var delay = (display) ? 2 : 5;		
		redirectPageOnCountdown("Redirecting... ", delay, " seconds", display, "index.php");
	};
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>