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
			$("#options-content").html("<a href='admin-misc-options.php'><button type='button' class='btn btn-lg btn-default'>Back</button></a><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Misc > Options > Result");
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
				
				#DECOM# /* LICENSES */
				#DECOM# require("snippets/licenses.php");
				
				/* AUTHENTICATE */
				require("snippets/admin-auth-test.php");
				
				if($result) {
					
					/* EXPECTED DATA FORMAT
					*
					*   EACH ROW HAS THE FOLLOWING:
					*
					*	INPUT NAME								INPUT VALUE
					*	VALUE/{setting = LIC_CC_NAME}/INPUT		James Wilmoth							# 
					*	VALUE/{setting = LIC_CC}/INPUT			jwilmoth@biztechnologysolutions			# 
					*	VALUE/{setting = LIC_REN_REM}/INPUT		20										# 
					*
					*   - EACH ROW IS UNIQUE BECAUSE OF THE SETTING VALUE.
					*/
					
					/* LOGIC: CREATE AN ARRAY FROM THE _POST DATA 
					*  1. Create a two-dimensional array sized the same as $_POST
					*  2. Fill the two-dimensional array with all the values from $_POST
					*/
										
					/* 1-- */
					$options = array();						
					foreach ($_POST as $name => $value) {
						$parse = explode("/",$name);
						if($value == "on")
							$value = 1;
						$options[$parse[1]] = $value;
						/* Example iteration 1:
						*		$name 	= VALUE/LIC_CC_NAME/INPUT
						*		$value	= James Wilmoth
						*		$parse 	= {"VALUE","LIC_CC_NAME","INPUT"}
						*		$options["LIC_CC_NAME"] = James Wilmoth
						*
						* Example iteration 2:
						*		$name 	= VALUE/LIC_CC/INPUT
						*		$value	= jwilmoth@biztechnologysolutions.com
						*		$parse 	= {"VALUE","LIC_CC","INPUT"}
						*		$options["LIC_CC"] = jwilmoth@biztechnologysolutions.com
						*
						* Example iteration 3:
						*		$name 	= VALUE/LIC_REN_REM/INPUT
						*		$value	= 30
						*		$parse 	= {"VALUE","LIC_REN_REM","INPUT"}
						*		$options["LIC_REN_REM"] = 30
						*
						*/
					}
					
					/* RESET ALL BOOLEAN VALUES */
					
					try {								
					
						$sql = "UPDATE Admin_Misc_Options SET VALUE=\"0\" WHERE TYPE=\"boolean\" AND VALUE IS NOT NULL";								
						$db_conn->query($sql);
						
					} catch(PDOException $e) { 
						print("FAILED: Reset of boolean values failed.<br><a href=\"$e\">View error</a><br><br>");
					}

					/* BUILD AN UPDATE QUERY */
					
					try {		
						$errorCount = 0;					
						foreach($options as $setting => $value) {						
							try {								
							
								$sql = "UPDATE Admin_Misc_Options SET VALUE=\"" . $value . "\" WHERE SETTING=\"" . $setting ."\"";								
								$db_conn->query($sql);
								
							} catch(PDOException $e) { 
								print("FAILED: Update of option $setting with value \"" . $value . "\" failed.<br><a href=\"$e\">View error</a><br><br>");
								$errorCount++;									
							}								
						}
						
						if ($errorCount > 0) {
							if (count($options) > $errorCount)
								print("All other updates were successful!");
						} else
							print("All updates were successful!");															
					}
					catch(PDOException $e) {
						//die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
					}

					#DECOM# /* MISC TODO ITEMS */
					#DECOM# updateLicenseReminderCronjobs($db_conn);
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