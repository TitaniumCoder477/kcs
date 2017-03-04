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
			$("#options-content").html("<a href='admin-tags-createupdatedelete.php'><button type='button' class='btn btn-lg btn-default'>Back</button></a><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Tags > Update/Delete > Result");
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
					*	tag_key/0/INPUT			Format?
					*	tag_option_key/0/INPUT	Maybe
					*	TAG_FK/0/INPUT			Format?
					*	TAG_OPTION/0/INPUT		Maybe
					*	delete/0/INPUT			
					*/
					
					/* LOGIC: CREATE AN ARRAY FROM THE _POST DATA 
					*  1. Create a two-dimensional array sized the same as $_POST
					*  2. Fill the two-dimensional array with all the values from $_POST
					*/
					
					/* 1-- */
					
					$index = 0;
					$tags = array();						
					foreach ($_POST as $key => $value)
						$tags[$index] = array("tag_key"=>null,"tag_option_key"=>null,"TAG_FK"=>null,"TAG_OPTION"=>null,"delete"=>null);					
					
					/* 2-- */
					foreach ($_POST as $key => $value) {
						$parse = explode("/",$key);
						$tags[$parse[1]][$parse[0]] = $value;
					}

					/* BUILD AN UPDATE QUERY */
					
					try {
						$errorCount = 0;
						foreach ($tags as $row) {
							$row["delete"] = ($row["delete"] == "on" ? 1 : 0);
							
							try {								
								if($row["delete"]) {
									
									/* DETERMINE IF THIS SET SET HAS RELATED ITEMS */
									
									$sql = "SELECT * FROM Items_Tags WHERE TAG_FK='" . $row['tag_key'] . "' AND TAG_OPTION='" . $row['tag_option_key'] . "'";
									$sth = $db_conn->prepare($sql);
									$sth->execute();
									$result = $sth->fetchAll();										
									if($result) throw new PDOException('Tag and tag option is assigned to one or more items. Please unassign it first.');
									
									/* THIS TAG SET IS NOT ASSIGNED TO ANY ITEMS */
									
									$sql = "DELETE FROM Tags_Options WHERE TAG_FK='" . $row['tag_key'] . "' AND TAG_OPTION='" . $row['tag_option_key'] . "'";
									
								} else {
									$sql = "UPDATE Tags_Options SET TAG_FK='" . $row['TAG_FK'] . "',TAG_OPTION='" . $row['TAG_OPTION'] . "' WHERE TAG_FK='" . $row['tag_key'] . "' AND TAG_OPTION='" . $row['tag_option_key'] . "'";
								}
								
								$db_conn->query($sql);
								
							} catch(PDOException $e) { 
								if($row["delete"])
									print("FAILED: Deletion of tag \"" . $row["tag_key"] . "\" and tag option \"" . $row["tag_option_key"] . "\"<br>" . $e->getMessage() . "<br><br>");
								else
									print("FAILED: Update of tag \"" . $row["tag_key"] . "\" and tag option \"" . $row["tag_option_key"] . "\"<br><a href=\"$e\">View error</a><br><br>");
								$errorCount++;									
							}								
						}
						
						if ($errorCount > 0) {
							if (count($tags) > $errorCount)
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