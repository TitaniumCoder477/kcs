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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='processChanges()' id='processBtn'>Process</button><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
			//Disable the discontinue button			
			$("#processBtn").prop("disabled",true);
			//Enable the discontinue button if the user types in the confirmation
			$("#confirmationTxt").keyup(function() {
				var confirmationTxt = $(this).val();
				if(confirmationTxt === "discontinue my service")
					$("#processBtn").prop("disabled",false);
				else
					$("#processBtn").prop("disabled",true);
			});
		});
	</script>
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<form id="processChanges" action="admin-misc-discoserv-process.php" method="post">
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<script>
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Misc > Discontinue service");
			</script>	
		</h3>
	</div>
	<div class='panel-body'>
	<?php
	
		/** Outline
			1. Connect to KCSSites DB and record that client was thinking about discontinuing service
			2. Connect to client DB and make sure logged in as admin
			3. Provide warning and confirmation boxes etc
			4. POST to -process page if client confirms intent
		*/
	
		/* CONNECT TO KCSSITES DATABASE */	
		require("../kcssites/snippets/database.php");
				
		$pathStr = realpath("");
		$pathArr = explode("/", $pathStr);
		$sitename = $pathArr[count($pathArr)-1];
		
		try {
			$sql = "SELECT * FROM Sites WHERE `SITENAME`='$sitename'";
			$sth = $db_conn->prepare($sql);
			$sth->execute();
			$result = $sth->fetchAll();
		
			// We have to use the subscription reference to call the API
			$subRef = $result[0]['SUB_REF'];
			$site_email = $result[0]['EMAIL'];
							
			if(empty($subRef) || empty($site_email)) {							
				throw new Exception("Received invalid subRef and site_email.");							
			}
		} catch(PDOException $pdoe) {
			$db_conn = null;
			die("There was a problem communicating with the database. Please contact the Webmaster. Error #2017-02-25-16-59-03<br>" . $pdoe->getMessage());
		} catch(Exception $e) {
			$db_conn = null;
			die("There was a problem communicating with the database. Please contact the Webmaster. Error #2017-02-25-16-59-10<br>" . $pdoe->getMessage());
		}		
		
		$datetime = date_create();
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$ipaddress2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		// Attempt to discontinue service
		try {
			
			// Instruct payment gateway to delete subscription
			exec("curl -i -X DELETE -H 'Content-Type: application/xml' -u lands.wilmoth@gmail.com:FUMb0Rr460te https://api.fastspring.com/company/wilmoth/subscription/$subRef",$results,$returnVal);
									
			// Check payment gateway call results
			$success = strpos($results, "200 OK");						
			if(!is_int($success) || $success < 0) {
				
				// Audit this event
				$description = "Failed subscription delete request with payment gateway; " . $results;						
				$sql = "INSERT INTO AuditTrail (SITENAME,IPADDRESS,IPADDRESS2,DESCRIPTION) VALUES('" . $sitename . "','" . $ipaddress . "','" . $ipaddress2 . "','" .  $description .  "')";
				$sth = $db_conn->prepare($sql);
				$sth->execute();
				// Advise user
				throw new Exception("Error contacting payment gateway to discontinue service. Error #2017-02-25-12-22-21.");
				
			} elseif ($returnVal !== 0) {
				
				// Audit this event
				$description = "Failed attempt to send delete request to payment gateway; " . $results;
				$sql = "INSERT INTO AuditTrail (SITENAME,IPADDRESS,IPADDRESS2,DESCRIPTION) VALUES('" . $sitename . "','" . $ipaddress . "','" . $ipaddress2 . "','" .  $description .  "')";
				$sth = $db_conn->prepare($sql);
				$sth->execute();
				// Advise user
				throw new Exception("Error processing request to discontinue service. Error #2017-02-25-12-23-04.");
				
			}		
		
		
		
		/* Audit this event */
		$description = "Client updated payment information";
		$sql = "INSERT INTO AuditTrail (SITENAME,IPADDRESS,IPADDRESS2,DESCRIPTION) VALUES('" . $sitename . "','" . $ipaddress . "','" . $ipaddress2 . "','" .  $description .  "')";
		$sth = $db_conn->prepare($sql);
		$sth->execute();
		
		/* CLOSE DATABASE CONNECTION */
		$db_conn = null;
		
		/* CONNECT TO  DATABASE */	
		require("snippets/database.php");
		
		/* AUTHENTICATE */
		require("snippets/admin-auth-test.php");
		
		print("
			<div class='panel panel-info'>
				<div class='panel-heading'>
					<h3 class='panel-title'>Update payment info</h3>
				</div>
				<div class='panel-body'>
					<p>We use the FastSpring payment gateway to handle all transactions. You will be redirected to FastSpring page to update your information.					
				</div>
			</div>
		");	
		
		/* CLOSE DATABASE CONNECTION */
		$db_conn = null;
	?>		
	</div>
</div>
</form>


<?php require("snippets/options.php"); ?>

<script>
	window.onload = function () {
		display = document.querySelector('#success-timer');
		if(display) {
			var url  = "";
			redirectPageOnCountdown("Redirection in... ", 5, " seconds", display, url);			
		}	
	};
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>