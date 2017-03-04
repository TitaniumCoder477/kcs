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
			$("#options-content").html("<a href='admin-tags-manageassociations.php'><button type='button' class='btn btn-lg btn-default'>Back</button></a><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Tags > Manage associations > Delete > Result");
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
				require("snippets/admin-auth-test.php");
				
				if($result) {
					
					/* EXPECTED DATA FORMAT
					*
					*	KEY						VALUE
					*	CATEGORY_FK/0/INPUT		Vehicles				
					*	ITEM_FK/0/INPUT			Black Civic
					*	TAG_FK/0/INPUT			Gas level?
					*	delete/0/INPUT			
					*/
					
					/* LOGIC: CREATE AN ARRAY FROM THE _POST DATA 
					*  1. Create a two-dimensional array sized the same as $_POST
					*  2. Fill the two-dimensional array with all the values from $_POST
					*/
					
					/* 1-- */
					
					$index = 0;
					$associations = array();						
					foreach ($_POST as $key => $value)
						$associations[$index] = array("CATEGORY_FK"=>null,"ITEM_FK"=>null,"TAG_FK"=>null,"delete"=>null);					
					
					/* 2-- */
					foreach ($_POST as $key => $value) {
						$parse = explode("/",$key);
						$associations[$parse[1]][$parse[0]] = $value;
					}

					/* BUILD A DELETE QUERY */
					
					try {
						$errorCount = 0;
						foreach ($associations as $row) {
							$row["delete"] = ($row["delete"] == "on" ? 1 : 0);
							try {								
								if($row["delete"]) {
									$sql = "DELETE FROM Items_Tags WHERE CATEGORY_FK='" . $row['CATEGORY_FK'] . "' AND ITEM_FK='" . $row['ITEM_FK'] . "' AND TAG_FK ='" . $row['TAG_FK'] . "'";
									$db_conn->query($sql);
								}
							} catch(PDOException $e) { 
								print("FAILED: Deletion of association " . $row["CATEGORY_FK"] . "-" . $row["ITEM_FK"] . "-" . $row["TAG_FK"] . "<br>" . $e->getMessage() . "<br><br>");
								$errorCount++;									
							}								
						}
						
						if ($errorCount > 0) {
							if (count($associations) > $errorCount)
								print("All other selected association were deleted!");
						} else
							print("All selected associations were deleted!");															
					}
					catch(PDOException $e) {
						//die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
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
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>