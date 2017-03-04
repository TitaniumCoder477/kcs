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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='window.history.back();'>Back</button>");
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
				document.write("<a href='index.php'>Home</a> > About");
			</script>
		</h3>
	</div>
	<div class="panel-body">
		<div class='panel panel-info'>
			<div class='panel-heading'>
				<h3 class='panel-title'>Information</h3>
			</div>
			<div class='panel-body'>
				<p>Kiosk Checkout System (KCS). A simple web application for checking items in and out.</p>
				<?php print("Copyright &copy; " . date("Y") . " by James Wilmoth<br>"); ?>
				<br>
				<p>This program is free software: you can redistribute it and/or modify
				it under the terms of the GNU General Public License as published by
				the Free Software Foundation, either version 3 of the License, or
				(at your option) any later version.</p>
				<br>
				<p>This program is distributed in the hope that it will be useful,
				but WITHOUT ANY WARRANTY; without even the implied warranty of
				MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
				GNU General Public License for more details.</p>
				<br>
				<p>You should have received a copy of the GNU General Public License
				along with this program.  If not, see <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>
				<br>
				<p><em>This release is version 2.0.0 of KCS.</em><br>
				<a href="http://www.kioskcheckoutsystem.com">www.kioskcheckoutsystem.com</a></p>
			</div>
		</div>
	</div>
</div>

<?php require("snippets/options.php"); ?>

<script>	
	function authenticateAdmin() {
		var form = document.getElementById("adminUserFrm");
		form.submit();
	}
	
	function keydown() {
		var e = window.event;
		if (e.keyCode == 13) authenticateAdmin();
	}
	
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>