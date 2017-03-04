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
			$("#options-content").html("<a href='admin-users-updatedelete.php'><button type='button' class='btn btn-lg btn-default'>Back</button></a><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Users > Update/Delete > Result");
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
					
					/** DEBUG
					
					print("<table>");
						print("<thead>");
							print("<td>KEY</td>");
							print("<td>VALUE</td>");
						print("</thead>");
						print("<tbody>");
							foreach ($_POST as $key => $value) {
								echo "<tr>";
								echo "<td>";
								echo $key;
								echo "</td>";
								echo "<td>";
								echo $value;
								echo "</td>";
								echo "</tr>";
							}
						print("</tbody>");
					print("</table>");
					
					**/
					
					/* COMPILE AN ARRAY */
					
					$users = array();		
					foreach ($_POST as $key => $value) {
						if(strpos($key, "/KEY") !== false)
							$users[$value] = array("KEY"=>$value,"NAME"=>null,"EMAIL"=>null,"PIN"=>null,"ADMIN"=>null,"HIDE"=>null,"RANKING"=>null,"delete"=>null);
					}
					foreach ($_POST as $key => $value) {
						$parse = explode("/",$key);
						$email = str_replace("\\",".",$parse[0]);
						$field = $parse[1];
						$users[$email][$field] = $value;
					}

					/* BUILD AN UPDATE QUERY */
					
					try {
						$errorCount = 0;
						foreach ($users as $key => $value) {
							$value["ADMIN"] = ($value["ADMIN"] == "on" ? 1 : 0);
							$value["HIDE"] = ($value["HIDE"] == "on" ? 1 : 0);
							$value["delete"] = ($value["delete"] == "on" ? 1 : 0);
							
							if(strcmp($value["KEY"],"admin@kioskcheckoutsystem.com") !== 0) {								
								
								try {								
									if($value["delete"]) {
										
										/* CHECKED OUT ITEMS? */
										
										$sql = "SELECT * FROM User_Options WHERE EMAIL_FK='" . $value["KEY"] . "' AND OPTION_FK='Check In'";
										$sth = $db_conn->prepare($sql);
										$sth->execute();
										$result = $sth->fetchAll();										
										if($result) throw new PDOException('User with checked out items cannot be deleted. Return the items first.');
										
										/* NO CHECKED OUT ITEMS */
										
										//Delete User_Options so that we can delete the User too
										$sql = "DELETE FROM User_Options WHERE EMAIL_FK='" . $value["KEY"] . "'";
										$sth = $db_conn->prepare($sql);
										$sth->execute();										
										
										$sql = "DELETE FROM Users WHERE EMAIL='" . $value["KEY"] . "'";
										
									} else {
										if($value["PIN"] === '****')
											$sql = "UPDATE Users SET NAME='" . $value["NAME"] . "',EMAIL='" . $value["EMAIL"] . "',ADMIN='" . $value["ADMIN"] . "',HIDE='" . $value["HIDE"] . "',RANKING='" . $value["RANKING"] . "' WHERE EMAIL='" . $value["KEY"] . "'";
										else
											$sql = "UPDATE Users SET NAME='" . $value["NAME"] . "',EMAIL='" . $value["EMAIL"] . "',PIN='" . $value["PIN"] . "',ADMIN='" . $value["ADMIN"] . "',HIDE='" . $value["HIDE"] . "',RANKING='" . $value["RANKING"] . "' WHERE EMAIL='" . $value["KEY"] . "'";
									}
									
									$db_conn->query($sql);
									
								} catch(PDOException $e) { 
									if($value["delete"])
										print("FAILED: Deletion of user with email = " . $value["KEY"] . "<br>" . $e->getMessage() . "<br><br>");
									else
										print("FAILED: Update of user with email = " . $value["KEY"] . "<br><a href=\"$e\">View error</a><br><br>");
									$errorCount++;									
								}	
								
							} else {
								
								try {								
								
									if($value["PIN"] !== '****') {
										$sql = "UPDATE Users SET PIN='" . $value["PIN"] . "' WHERE EMAIL='" . $value["KEY"] . "'";
										$db_conn->query($sql);
									}
									
								} catch(PDOException $e) { 
									print("FAILED: Update of user with email = " . $value["KEY"] . "<br><a href=\"$e\">View error</a><br><br>");
									$errorCount++;									
								}	
								
							}
						}
						
						if ($errorCount > 0) {
							if (count($users) > $errorCount)
								print("All other updates/deletions were successful!");							
						} else
							print("All updates were successful!");
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
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>