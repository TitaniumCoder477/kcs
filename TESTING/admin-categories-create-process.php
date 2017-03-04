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
			$("#options-content").html("<a href='admin-categories-create.php'><button type='button' class='btn btn-lg btn-default'>Create another category</button></a><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
		});
	</script>		
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Categories > Create > Result
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
				require("snippets/admin-auth-test.php");
				
				if($result) {
						
					/* GET LIST OF CATEGORIES */
					try {
						$category = $_POST['category'];
						$description = $_POST['description'];
						
						$sql = "SELECT CATEGORY,DESCRIPTION FROM Categories WHERE CATEGORY='$category'";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
						$result = $sth->fetchAll();
						
						if($result) {
							print("Category already exists!");
						}
						else {
							try {
								$sql = "INSERT INTO Categories VALUES ('$category','$description',999,0)";
								$sth = $db_conn->prepare($sql);
								$sth->execute();				
							}
							catch(PDOException $e) { 
								die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
							}
							
							print("Success!");
						}
						
						print("<br><br>You can create another category or return to the admin page.");
						
					} catch(PDOException $e) {
						die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
					}
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
	function selectOption(option,path) {
		document.cookie="kcs_option=" + option;
		document.cookie="kcs_option_path=" + path;
	}
	
	function goBack() {
		window.history.back();
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>