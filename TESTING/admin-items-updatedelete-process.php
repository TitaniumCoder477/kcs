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
			$("#options-content").html("<a href='admin-items-updatedelete.php'><button type='button' class='btn btn-lg btn-default'>Back</button></a><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Items > Update/Delete > Result");
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
					*   EACH ROW HAS THE FOLLOWING:
					*
					*	INPUT NAME				INPUT VALUE
					*	category_key/{i}/INPUT	{$category} = Computers									# Original value
					*	item_key/{i}/INPUT		{$item} = Laptop01										# Original value
					*	category_fk/{i}/INPUT	{$category} = Computers									# New values
					*	item/{i}/INPUT			{$item} = Laptop01										# New values
					*	description/{i}/INPUT	{$description} = 										# New values
					*	user_email_fk/{i}/INPUT	{$user_email} = jwilmoth@biztechnologysolutions.com		# New values
					*	delete/{i}/INPUT		{} = on													# Action value
					*
					*   - EACH ROW IS UNIQUE BECAUSE OF THE INDEX VALUE.
					*	- IF DELETE IS NOT CHECKED FOR A ROW, THEN IT DOES NOT HAVE A DELETE INPUT VALUE.
					*/
					
					/* LOGIC: CREATE AN ARRAY FROM THE _POST DATA 
					*  1. Create a two-dimensional array sized the same as $_POST
					*  2. Fill the two-dimensional array with all the values from $_POST
					*/
					
					/* 1-- */
					
					$index = 0;
					$items = array();						
					foreach ($_POST as $key => $value) {
						$items[$index] = array("category_key"=>null,"item_key"=>null,"CATEGORY_FK"=>null,"ITEM"=>null,"DESCRIPTION"=>null,"USER_EMAIL_FK"=>"admin@kioskcheckoutsystem.com","delete"=>null);
						/**if(strpos($key, "key/") !== false) {
							$parse = explode("/",$key);
							if(strpos($key, "category_key/") !== false)
								$items[$parse[1]]["category_key"] = $value;
							elseif(strpos($key, "item_key/") !== false)
								$items[$parse[1]]["item_key"] = $value;
							$index++;
						}**/
					}
					
					
					/* 2-- */
					foreach ($_POST as $key => $value) {
						$parse = explode("/",$key);
						$items[$parse[1]][$parse[0]] = $value;
						/* Example iteration 1:
						*		$key 	= key/0/INPUT
						*		$value	= Computers
						*		$parse 	= {"key","0","INPUT"}
						*		$items["0"]["key"] = Computers
						*
						* Example iteration 2: 
						*		$key 	= category/0/INPUT
						*		$value	= Computers2
						*		$parse 	= {"category","0","INPUT"}
						*		$items["0"]["category"] = Computers2
						*
						* Example iteration 3: 
						*		$key 	= description/0/INPUT
						*		$value	= 
						*		$parse 	= {"description","0","INPUT"}
						*		$items["0"]["description"] = 
						*
						* Example iteration 4: 
						*		$key 	= delete/0/INPUT
						*		$value	= on
						*		$parse 	= {"delete","0","INPUT"}
						*		$items["0"]["delete"] = on
						*/
					}

					/* BUILD AN UPDATE QUERY */
					
					try {
						$errorCount = 0;
						foreach ($items as $row) {
							$row["delete"] = ($row["delete"] == "on" ? 1 : 0);
							
							try {								
								if($row["delete"]) {
									
									/* DETERMINE IF THIS ITEM HAS RELATED USERS */
									
									$sql = "SELECT * FROM Items WHERE CATEGORY_FK=\"" . $row["category_key"] . "\" AND ITEM=\"" . $row["item_key"] . "\" AND NOT USER_EMAIL_FK='admin@kioskcheckoutsystem.com'";
									$sth = $db_conn->prepare($sql);
									$sth->execute();
									$result = $sth->fetchAll();										
									if($result) throw new PDOException('Item is checked out by a user and cannot be deleted. Check in the item first.');
									
									/* THIS CATEGORY DOES NOT HAVE RELATED ITEMS */
									
									$sql = "DELETE FROM Items WHERE CATEGORY_FK=\"" . $row["category_key"] . "\" AND ITEM=\"" . $row["item_key"] . "\"";
									
								} else {
									$sql = "UPDATE Items SET CATEGORY_FK=\"" . $row["CATEGORY_FK"] . "\",ITEM=\"" . $row["ITEM"] . "\",DESCRIPTION=\"" . $row["DESCRIPTION"] . "\" WHERE CATEGORY_FK=\"" . $row["category_key"] . "\" AND ITEM=\"" . $row["item_key"] . "\"";
								}
								
								$db_conn->query($sql);
								
							} catch(PDOException $e) { 
								if($row["delete"])
									print("FAILED: Deletion of item \"" . $row["item_key"] . "\" in category \"" . $row["category_key"] . "\"<br>" . $e->getMessage() . "<br><br>");
								else
									print("FAILED: Update of item \"" . $row["item_key"] . "\" in category \"" . $row["category_key"] . "\"<br><a href=\"$e\">View error</a><br><br>");
								$errorCount++;									
							}								
						}
						
						if ($errorCount > 0) {
							if (count($items) > $errorCount)
								print("All other updates/deletions were successful!");
						} else
							print("All updates were successful!");															
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