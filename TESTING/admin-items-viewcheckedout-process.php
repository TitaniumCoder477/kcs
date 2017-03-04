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
			$("#options-content").html("<a href='admin-items-viewcheckedout.php'><button type='button' class='btn btn-lg btn-default'>Back</button></a><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Items > <a href='admin-items-viewcheckedout.php'>View checked out</a> > Send emails > Result");
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
					*	INPUT NAME					INPUT VALUE
					*	category_key/{i}/INPUT		{$category} = Computers									# Original value
					*	item_key/{i}/INPUT			{$item} = Laptop01										# Original value
					*	user_key/{i}/INPUT			{$user_email} = jwilmoth@biztechnologysolutions.com		# Original value
					*	email_action/{i}/INPUT		{} = on													# Action value
					*	hold/{i}/INPUT				{$hold} = on											# New values
					*	checkin_action/{i}/INPUT	{} = on													# Action values
					*
					*   - EACH ROW IS UNIQUE BECAUSE OF THE INDEX VALUE.
					*	- IF DELETE IS NOT CHECKED FOR A ROW, THEN IT DOES NOT HAVE A DELETE INPUT VALUE.
					*/
					
					/* LOGIC: CREATE AN ARRAY FROM THE _POST DATA 
					*  1. Create a two-dimensional array sized the same as $_POST
					*  2. Fill the two-dimensional array with all the values from $_POST
					*/
					
					/* LOGIC: CREATE AN ARRAY FROM THE _POST DATA 
					*  1. Create a two-dimensional array sized the same as $_POST
					*  2. Fill the two-dimensional array with all the values from $_POST
					*/
					
					/* 1-- */
					
					$index = 0;
					$items = array();						
					foreach ($_POST as $key => $value)
						$items[$index] = array("category_key"=>null,"item_key"=>null,"user_key"=>null,"email_action"=>null,"hold"=>null,"checkin_action"=>null);
					
					/* 2-- */
					foreach ($_POST as $key => $value) {
						$parse = explode("/",$key);
						$items[$parse[1]][$parse[0]] = $value;
						/* Example iteration 1:
						*		$key 	= category_key/0/INPUT
						*		$value	= Computers
						*		$parse 	= {"category_key","0","INPUT"}
						*		$items["0"]["category_key"] = Computers
						*
						* Example iteration 2: 
						*		$key 	= item_key/0/INPUT
						*		$value	= Laptop01
						*		$parse 	= {"item_key","0","INPUT"}
						*		$items["0"]["item_key"] = Laptop01
						*
						* Example iteration 3: 
						*		$key 	= user_key/0/INPUT
						*		$value	= jwilmoth@biztechnologysolutions.com
						*		$parse 	= {"user_key","0","INPUT"}
						*		$items["0"]["user_key"] = jwilmoth@biztechnologysolutions.com
						*
						* Example iteration 4: 
						*		$key 	= email_action/0/INPUT
						*		$value	= on
						*		$parse 	= {"email_action","0","INPUT"}
						*		$items["0"]["email_action"] = on
						*
						* Example iteration 5: 
						*		$key 	= hold/0/INPUT
						*		$value	= on
						*		$parse 	= {"hold","0","INPUT"}
						*		$items["0"]["hold"] = on
						*
						* Example iteration 6: 
						*		$key 	= checkin_action/0/INPUT
						*		$value	= on
						*		$parse 	= {"checkin_action","0","INPUT"}
						*		$items["0"]["checkin_action"] = on
						*/
					}
					
					/* SMTP OPTIONS > $smtp_settings ARRAY */
						
					try {
						
						$sql = "SELECT * FROM Admin_Misc_Options WHERE SETTING LIKE 'SMTP%'";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
						$result = $sth->fetchAll();
						// $result contains our SMTP settings												

						$smtp_settings = array();
						foreach($result as $setting_row) {
							$smtp_settings[$setting_row['SETTING']] = $setting_row['VALUE'];
						}
						$smtp_from_name = $smtp_settings['SMTP_FROM_NAME'];
						
					} catch(PDOException $e) {
						die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
					}
					
					/* PROCESS LOGIC: THREE STAGES
					*  1. HANDLE EMAILS
					*  2. HANDLE HOLD ACTION
					*  3. HANDLE CHECK IN ACTION
					*/
						
					try {
						$emailActionCount = 0;
						$emailActionErrorCount = 0;
						foreach ($items as $row) {							
							$row["email_action"] = ($row["email_action"] == "on" ? 1 : 0);
							$row["hold"] = ($row["hold"] == "on" ? 1 : 0);
							$row["checkin_action"] = ($row["checkin_action"] == "on" ? 1 : 0);
							
							$category = $row["category_key"];
							$item = $row["item_key"];
							
							/* STAGE 1: HANDLE EMAILS */
							if($row['email_action']) {
								$emailActionCount++;
								try {										
									$item_sql = "SELECT * FROM Items WHERE CATEGORY_FK='$category' AND ITEM='$item'";
									$item_sth = $db_conn->prepare($item_sql);
									$item_sth->execute();
									$item_result = $item_sth->fetchAll();
								
									foreach($item_result as $item_row) {									
										$item_description = $item_row['DESCRIPTION'];
										$item_to = $item_row['USER_EMAIL_FK'];
										$item_date_out = $item_row['DATE_OUT'];
										
										/* GET THE NAME THAT MATCHES THE EMAIL ABOVE */
										
										$user_sql = "SELECT * FROM Users WHERE EMAIL='$item_to'";
										$user_sth = $db_conn->prepare($user_sql);
										$user_sth->execute();
										$user_result = $user_sth->fetchAll();
										
										foreach($user_result as $user_row) {
											$user_name = $user_row['NAME'];								
											
											/* CREATE THE EMAIL */
											
											$subject = "REMINDER: Checked out item needs to be returned";
											$body = "
												<html>
													<head>$user_name,</head>
													<body>
														<br><br>
														This is a reminder to check in the following item:
														<br><br>
														<table style='border:1px solid black;border-collapse: collapse'>
															<tr style='border:1px solid black'>
																<td style='color:#f68d37;padding:5px'>CATEGORY:</td>
																<td style='padding:5px'>$category</td>
															</tr>
															<tr style='border:1px solid black'>
																<td style='color:#f68d37;padding:5px'>ITEM:</td>
																<td style='padding:5px'>$item</td>
															</tr>
															<tr style='border:1px solid black'>
																<td style='color:#f68d37;padding:5px'>DESCRIPTION:</td>
																<td style='padding:5px'>$item_description</td>
															</tr>
															<tr style='border:1px solid black'>
																<td style='color:#f68d37;padding:5px'>DATE OUT:</td>
																<td style='padding:5px'>$item_date_out</td>
															</tr>
														</table>
														<br><br>
														Thank you!
														<br><br>
														$smtp_from_name
													</body>
												</html>";
											
											/* SEND THE EMAIL */
											date_default_timezone_set('Etc/UTC');
											require_once 'PHPMailer-master/PHPMailerAutoload.php';
											
											$mail = new PHPMailer;
											$mail->isSMTP();
											$mail->SMTPDebug = 0; //1 for client; 2 for client and server; 0 for off
											$mail->Debugoutput = 'html';
											$mail->Host = $smtp_settings['SMTP_SERVER'];
											$mail->Port = $smtp_settings['SMTP_PORT'];
											$mail->SMTPAuth = false;
											$mail->setFrom($smtp_settings['SMTP_FROM'],$smtp_settings['SMTP_FROM_NAME']);
											$mail->addReplyTo($smtp_settings['SMTP_FROM']);
											$mail->addAddress($item_to, $user_name);										
											$mail->addCC($smtp_settings['SMTP_CC'],$smtp_settings['SMTP_CC_NAME']);
											$mail->Subject = $subject;
											$mail->msgHTML($body);
											
											if(!$mail->send()) {
												throw new PDOException($mail->ErrorInfo);
											}
										}
									}
								} catch(PDOException $e) { 
									print("FAILED: Could send email to user with email = " . $item_to . "<br><a href=\"$e\">View error</a><br><br>");
									$emailActionErrorCount++;
								}	
							} // if($row['email_action'])						
							
							if($row['hold']) {}
							
							if($row['email_action']) {}
								
						} // foreach ($items as $row)
						
						/* DETERMINE HOW TO LET THE USER KNOW OF FAILED ATTEMPTS */
						if ($emailActionCount == 0)
							print("Nothing to process!");
						else {
							if ($emailActionErrorCount > 0) {
								if ($emailActionCount > $emailActionErrorCount)
									print("Emails were sent to all the others!");							
							} elseif ($emailActionCount > 0)
								print("Emails were sent to all that you selected!");
						}
					} catch(PDOException $e) {
						die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
					}					
				} // if($result)
					
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