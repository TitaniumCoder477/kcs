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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='createUser()'>Create user</button><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Cancel</button></a>");
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Users > Create");
			</script>
		</h3>
	</div>
	<div class="panel-body">
		<div class='panel panel-info'>
			<div class='panel-heading'>
				<h3 class='panel-title'>Details</h3>
			</div>
			<div class='panel-body'>
			<?php
				/* CONNECT TO DATABASE */	
				require("snippets/database.php");
				
				/* AUTHENTICATE */
				require("snippets/admin-auth-test.php");
				
				if($result) {						
					print("	<form class='form-horizontal' id='newUserFrm' action='admin-users-create-process.php' method='post' autocomplete='off'>
								<div class='form-group'>
									<label class='col-sm-2 control-label' for='name'>Name</label>
									<div class='col-sm-10'>
										<input class='form-control' type='text' name='name' id='name' maxlength='25' placeholder='Name is required'>
									</div>									
								</div>
								<div class='form-group'>
									<label class='col-sm-2 control-label' for='email'>Email</label>
									<div class='col-sm-10'>
										<input class='form-control' type='email' name='email' id='email' maxlength='45' style='min-width:45%' placeholder='Email address is required'>
									</div>
								</div>
								<div class='form-group'>
									<label class='col-sm-2 control-label' for='password'>Password</label>									
									<div class='col-sm-10'>
										<input class='form-control' type='password' name='pin' id='pin' maxlength='4' size='4'>
									</div>
								</div>
								<div class='form-group'>
									<div class='col-sm-offset-2 col-sm-10'>
										<div class='checkbox'>
											<label>
												<input type='checkbox' name='admin' value='Yes' style='width:left'>Admin
											</label>
										</div>
									</div>
								</div>
							</form>");
							
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
	function selectUser(name,email) {
		document.cookie="kcs_user_name=" + name;
		document.cookie="kcs_user_email=" + email;
	}
	
	function validateName(id) {		
		var name = document.getElementById(id).value;
		return (name.length != 0);
	}
	
	function validateEmail(id) {
		var email = document.getElementById(id).value;
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;    		
		return (re.test(email) && email.length != 0);		
	}
	
	function validatePIN(id) {
		var pin = document.getElementById(id).value;
		var isNum = /^\d+$/.test(pin);
		return (pin.length == 4 && isNum);
	}
	
	function createUser() {
		if (!validateName("name"))
			alert("The name is a required value and must be one or more alpha-numeric characters!");
		else if (!validateEmail("email"))
			alert("The email address is required and must follow standard email format!");
		else if (!validatePIN("pin"))
			alert("The PIN is required and must be exactly four digits!");
		else {
			var form = document.getElementById("newUserFrm");
			form.submit();
		}
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>