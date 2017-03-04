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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='authenticateAdmin()'>Authenticate</button><a href='index.php'><button type='button' class='btn btn-lg btn-default'>Cancel</button></a>");
			$("#email").keypress(function(e) {
				if(event.which == 13) {
					if($("#email").val() && $("#pin").val()) {
						authenticateAdmin();
					}
				}					
			});
			$("#pin").keypress(function(e) {
				if(event.which == 13) {
					if($("#email").val() && $("#pin").val()) {
						authenticateAdmin();
					}
				}					
			});
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
				document.write("<a href='index.php'>Home</a> > Admin authentication");
			</script>
		</h3>
	</div>
	<div class="panel-body">
		<div class='panel panel-info'>
			<div class='panel-heading'>
				<h3 class='panel-title'>Details</h3>
			</div>
			<div class='panel-body'>
				<form class="form-horizontal" id="adminUserFrm" action="admin-authUser.php" method="post">
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input class="form-control" type="email" name="email" id="email" maxlength="45">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">PIN</label>
						<div class="col-sm-10">
							<input class="form-control" type="password" name="pin" id="pin" maxlength="4" size="4" style="width:auto;">
						</div>
					</div>
				</form>
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