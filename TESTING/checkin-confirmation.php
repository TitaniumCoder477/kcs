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
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
		<script>
			document.write("<a href='index.php'>Users</a> > " + getCookie("kcs_user_name") + " > <a href='" + getCookie("kcs_option_path") + "'>" + getCookie("kcs_option") + "</a> > Check In Confirmation");
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
				
				/* UPDATE ITEMS AND DELETE RELATIONS */
				try {			
					require("snippets/log_event.php");
					
					/* UPDATE THE RELATED TAGS/TAG OPTIONS */
					$postKeys = array_keys($_POST);
					foreach($postKeys as $tag_repl) {
						$tag = str_replace('##', ' ', $tag_repl);
						$tag_option = $_POST[$tag_repl];
						
						$sql = "UPDATE Items_Tags SET TAG_OPTION='$tag_option' WHERE CATEGORY_FK='$category' AND ITEM_FK='$item' AND TAG_FK='$tag'";
						$sth = $db_conn->prepare($sql);
						$sth->execute();						
					}
									
					/** UPDATE THE ITEM */
					$sql = "UPDATE Items SET USER_EMAIL_FK='admin@kioskcheckoutsystem.com',DATE_OUT=NULL WHERE CATEGORY_FK='$category' AND ITEM='$item'";
					$sth = $db_conn->prepare($sql);
					$sth->execute();
					
					/** DELETE THE USER'S CHECK IN OPTION */
					$sql = "DELETE FROM User_Options WHERE EMAIL_FK='$email' AND OPTION_FK='Check In' AND CATEGORY_FK='$category' AND ITEM_FK='$item'";
					$sth = $db_conn->prepare($sql);
					$sth->execute();
					
					/** UPDATE THE USER'S RANKING */
					$sql = "UPDATE Users SET RANKING=RANKING+1 WHERE EMAIL='$email'";
					$sth = $db_conn->prepare($sql);
					$sth->execute();
					
					print("Success!");
					print("<br><br><span id='return-timer'>You will be redirected to the home page.</span>");
				}
				catch(PDOException $e) {
					die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
				}
				
				/* CLOSE DATABASE CONNECTION */
				$db_conn = null;
			?>
			</div>
		</div>
	</div>
</div>

<?php //require("snippets/options.php"); ?>

<script>
	function selectItem(item) {
		document.cookie="kcs_item=" + item;
		//document.cookie="kcs_user_email=" + email;
	}
	
	window.onload = function () {
		var display = document.querySelector('#return-timer');
		redirectPageOnCountdown("Home in... ", 3, "", display, "index.php");
	};
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>