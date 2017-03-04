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
			$("#options-content").html("<a href='admin-categories-updatedelete.php'><button type='button' class='btn btn-lg btn-default'>Back</button></a><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
		});
	</script>		
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Categories > Update/Delete > Result
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
					*	key/0/INPUT				Computers
					*	category/0/INPUT		Computers2
					*	description/0/INPUT				
					*	delete/0/INPUT			on
					*	key/1/INPUT				Flash drives
					*	category/1/INPUT		Flash drives
					*	description/1/INPUT				
					*/
					
					/* LOGIC: CREATE AN ARRAY FROM THE _POST DATA 
					*  1. Create a two-dimensional array sized the same as $_POST
					*  2. Fill the two-dimensional array with all the values from $_POST
					*/
					
					/* 1-- */
					$index = 0;
					$categories = array();
					foreach ($_POST as $key => $value) {
						if(strpos($key, "key/") !== false) { /* Identify all the key rows */
							$parse = explode("/",$key);
							$index = $parse[1];
							$categories[$index] = array("key"=>$value,"CATEGORY"=>null,"DESCRIPTION"=>null,"RANKING"=>null,"EXPANDED"=>null,"delete"=>null);							
							$index++;
						}
					}
					/* 2-- */
					foreach ($_POST as $key => $value) {
						$parse = explode("/",$key);							
						$categories[$parse[1]][$parse[0]] = $value;
						/* Example iteration 1:
						*		$key 	= key/0/INPUT
						*		$value	= Computers
						*		$parse 	= {"key","0","INPUT"}
						*		$categories["0"]["key"] = Computers
						*
						* Example iteration 2: 
						*		$key 	= category/0/INPUT
						*		$value	= Computers2
						*		$parse 	= {"category","0","INPUT"}
						*		$categories["0"]["category"] = Computers2
						*
						* Example iteration 3: 
						*		$key 	= description/0/INPUT
						*		$value	= 
						*		$parse 	= {"description","0","INPUT"}
						*		$categories["0"]["description"] = 
						*
						* Example iteration 4: 
						*		$key 	= delete/0/INPUT
						*		$value	= on
						*		$parse 	= {"delete","0","INPUT"}
						*		$categories["0"]["delete"] = on
						*/
					}

					/* BUILD AN UPDATE QUERY */
					
					try {
						$errorCount = 0;
						foreach ($categories as $row) {
							$row["EXPANDED"] = ($row["EXPANDED"] == "on" ? 1 : 0);
							$row["delete"] = ($row["delete"] == "on" ? 1 : 0);
							
							try {								
								if($row["delete"]) {
									
									/* DETERMINE IF THIS CATEGORY HAS RELATED ITEMS */
									
									$sql = "SELECT * FROM Items WHERE CATEGORY_FK='" . $row["key"] . "'";
									$sth = $db_conn->prepare($sql);
									$sth->execute();
									$result = $sth->fetchAll();										
									if($result) throw new PDOException('Category with related items cannot be deleted. Delete the related items first.');
									
									/* THIS CATEGORY DOES NOT HAVE RELATED ITEMS */
									
									$sql = "DELETE FROM Categories WHERE CATEGORY='" . $row["key"] . "'";
									
								} else {
									$sql = "UPDATE Categories SET CATEGORY='" . $row["CATEGORY"] . "',DESCRIPTION='" . $row["DESCRIPTION"] . "',RANKING='" . $row["RANKING"] . "',EXPANDED='" . $row["EXPANDED"] . "' WHERE CATEGORY='" . $row["key"] . "'";
								}
								
								$db_conn->query($sql);
								
							} catch(PDOException $e) { 
								if($row["delete"])
									print("FAILED: Deletion of category '" . $row["key"] . "'<br>" . $e->getMessage() . "<br><br>");
								else
									print("FAILED: Update of category '" . $row["key"] . "'<br><a href=\"$e\">View error</a><br><br>");
								$errorCount++;									
							}								
						}
						
						if ($errorCount > 0) {
							if (count($categories) > $errorCount)
								print("All other updates/deletions were successful!");
						} else
							print("All updates were successful!");															
					} catch(PDOException $e) {
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