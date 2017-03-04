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
			<a href='index.php'>Users</a>
		</h3>
	</div>
	<div class="panel-body">		
		<div class='panel panel-info'>
			<div class='panel-heading'>
				<h3 class='panel-title'>Error</h3>
			</div>
			<div class='panel-body'>
			<?php
				print("	You are not logged in or your login session has expired!<br>Please try logging in again.<br><br>
						<span id=\"return-timer\">You will be redirected to the home page.</span>");
			?>		
			</div>
		</div>		
	</div>
</div>

<?php require("snippets/options.php"); ?>

<script>
	window.onload = function () {
		var display = document.querySelector('#return-timer');
		redirectPageOnCountdown("Home in... ", 5, " seconds", display, "index.php");
	};
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>