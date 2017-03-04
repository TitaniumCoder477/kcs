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
		
		$sql = "SELECT * FROM Sites WHERE `SITENAME`='$sitename'";
		$sth = $db_conn->prepare($sql);
		$sth->execute();
		$result = $sth->fetchAll();
		
		$site_email = $result[0]['EMAIL'];
		
		$datetime = date_create();
		$ipaddress = $_SERVER['REMOTE_ADDR'];
		$ipaddress2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		/* Audit this event */
		$description = "Client investigated discontinuing";
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
					<h3 class='panel-title'>Confirmation</h3>
				</div>
				<div class='panel-body'>
					<p>Once discontinued, all data will be removed from our servers. You will receive an email confirmation at $site_email.
					<br><br>
					Please confirm that you wish to discontinue service by typing \"discontinue my service\" below.</p>
					<br>
					<input type=\"text\" id=\"confirmationTxt\" name=\"confirmationTxt\" class=\"form-control\" required autofocus>
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
	function processChanges() {
		var form = document.getElementById("processChanges");
		form.submit();
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>