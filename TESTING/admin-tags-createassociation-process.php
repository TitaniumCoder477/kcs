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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Tags > Manage Associations > Create > Result");
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
					
					/* GET LIST OF ITEM-TAG ASSOCIATIONS */
					try {
						$category_item_set = $_POST['category_item_set'];
						$parse = explode("-",$category_item_set);

						$category = $parse[0];
						$item = $parse[1];

						$tag = $_POST['tag'];	
						$apply_to_category = (isset($_POST['apply_to_category']) && $_POST['apply_to_category'] == 'on') ? '1' : '0';
						
						/** ONLY APPLY TO ONE CATEGORY-ITEM SET */
						if(!$apply_to_category) {
							$sql = "SELECT * FROM Items_Tags WHERE CATEGORY_FK='$category' AND ITEM_FK='$item' AND TAG_FK='$tag'";
							$sth = $db_conn->prepare($sql);
							$sth->execute();
							$result = $sth->fetchAll();
							
							if($result) {
								print("This association already exists!");
							}
							else {
								
								$sql = "SELECT * FROM Tags_Options WHERE TAG_FK='$tag'";
								$sth = $db_conn->prepare($sql);
								$sth->execute();
								$result = $sth->fetchAll();
								$first_tag_option = $result[0]['TAG_OPTION'];
															
								$sql = "INSERT INTO Items_Tags VALUES ('$category','$item','$tag','$first_tag_option')";
								$sth = $db_conn->prepare($sql);
								$sth->execute();				
								
								print("Success!");
							}
						/** APPLY TO ENTIRE CATEGORY */
						} else { 
							$sql = "SELECT * FROM Tags_Options WHERE TAG_FK='$tag'";
							$sth = $db_conn->prepare($sql);
							$sth->execute();
							$result = $sth->fetchAll();
							$first_tag_option = $result[0]['TAG_OPTION'];

							/** GET ALL THE ITEMS FOR THIS CATEGORY */
							$sql = "SELECT ITEM FROM Items WHERE CATEGORY_FK='$category'";
							$sth = $db_conn->prepare($sql);
							$sth->execute();
							$result = $sth->fetchAll();
							
							foreach ($result as $items_row) {
								$item = $items_row['ITEM'];	
								try {
									$sql = "INSERT INTO Items_Tags VALUES ('$category','$item','$tag','$first_tag_option')";
									$sth = $db_conn->prepare($sql);
									$sth->execute();
								}
								catch(PDOException $e) {
									/** JUST CONTINUE WITH THE NEXT ITEM IF THERE IS AN EXCEPTION */
								}
							}
							
							print("Success!");
						}
							
						print("<br><br>You can return to the tag manage associations page or return to the admin page.");
						
					}
					catch(PDOException $e) {
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

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>