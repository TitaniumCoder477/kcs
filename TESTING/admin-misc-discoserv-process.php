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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Misc > Discontinue service > Result");
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
	
				/** Outline
					1. Connect to KCSSites DB and record that client confirmed discontinuing service
					2. Set site status to discontinue
					3. Log user out of admin
					4. Return user to site login page
				*/
				
				$confirmationTxt = strtolower($_POST['confirmationTxt']);				
				if(strcmp($confirmationTxt, "discontinue my service") === 0) {
			
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
						die("There was a problem communicating with the database. Please contact the Webmaster. Error #2017-02-25-12-21-23<br>" . $pdoe->getMessage());
					} catch(Exception $e) {
						$db_conn = null;
						die("There was a problem communicating with the database. Please contact the Webmaster. Error #2017-02-25-14-35-43<br>" . $pdoe->getMessage());
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
						
						// Audit this event
						$description = "Successful subscription delete request with payment gateway";						
						$sql = "INSERT INTO AuditTrail (SITENAME,IPADDRESS,IPADDRESS2,DESCRIPTION) VALUES('" . $sitename . "','" . $ipaddress . "','" . $ipaddress2 . "','" .  $description .  "')";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
						
						// Flag site for deletion
						$sql = "UPDATE Sites SET STATUS_ID='9' WHERE Sitename='" . $sitename . "'";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
						
						// Audit this event
						$description = "Successful account discontinuation flag";						
						$sql = "INSERT INTO AuditTrail (SITENAME,IPADDRESS,IPADDRESS2,DESCRIPTION) VALUES('" . $sitename . "','" . $ipaddress . "','" . $ipaddress2 . "','" .  $description .  "')";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
												
						// Delete site from webserver and database
						// NOTE: Remember to check /etc/sudoers to make sure this script can be called by sudo
						exec("sudo /var/www/html/installation/666c-deleteExistingSite.sh -s $sitename",$results,$returnVal);
							
						if($returnVal === 0) {
							
							/* Audit this event */
							$description = "Successful site deletion";				
							$sql = "INSERT INTO AuditTrail (SITENAME,IPADDRESS,IPADDRESS2,DESCRIPTION) VALUES('" . $sitename . "','" . $ipaddress . "','" . $ipaddress2 . "','" .  $description .  "')";
							$sth = $db_conn->prepare($sql);
							$sth->execute();

						} else {
							
							// Audit this event
							$description = "Failed attempt to delete site; " . $results;
							$sql = "INSERT INTO AuditTrail (SITENAME,IPADDRESS,IPADDRESS2,DESCRIPTION) VALUES('" . $sitename . "','" . $ipaddress . "','" . $ipaddress2 . "','" .  $description .  "')";
							$sth = $db_conn->prepare($sql);
							$sth->execute();
							// Advise user
							throw new Exception("Error processing request to delete site.");
						}
						
						// TODO: Send email confirmation: Subscription has been discontinued and all data removed from server.
						//$site_email
						
					} catch(PDOException $pdoe) {
						$db_conn = null;
						die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $pdoe->getMessage());
					} catch(Exception $e) {
						$db_conn = null;
						die("There was a problem processing your request. Please contact support.<br>" . $e->getMessage());
					}
						
					/* Close the database connection */
					$db_conn = null;
					
					/* Success; return user to site login page */
					print("We're sorry to see you go! Your subscription has been discontinued, and all data has been removed from our servers.
						<br><br>
						<span id=\"success-timer\">You will be redirected back to the site login page.</span>"
					);
					
				} else {
					
					//We shouldn't even get to this code, but if we do, let's display a problem message and advise the user to reach out for support.
					
					// Return user to admin page
					print("There was a problem confirming your request. Please contact support. Error #2017-02-25-12-13-15
						<br><br>
						<span id=\"fail-timer\">You will be redirected back to the admin page.</span>"
					);
					
				}
			?>
			</div>
		</div>
	</div>
</div>

<?php require("snippets/options.php"); ?>

<script>
	window.onload = function () {
		var display = document.querySelector('#fail-timer');
		if(display) 
			redirectPageOnCountdown("Admin page in... ", 5, " seconds", display, "admin.php");
		else {			
			display = document.querySelector('#success-timer');
			if(display) {
				var url  = "https://login.kioskcheckoutsystem.com";
				redirectPageOnCountdown("Redirection in... ", 5, " seconds", display, url);			
			}
		}
	};
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>