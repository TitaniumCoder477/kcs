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
	
	<link href="css/fatscrollbars.css" rel="stylesheet">
	
	<!-- Cutomize (if needed) the options footer -->
	<script>
		$(document).ready(function() {
			$("#options-content").html("<a href='admin-auth.php'><button type='button' class='btn btn-lg btn-default'>Admin</button></a>");
		});
	</script>	
	<?php require("snippets/return-timer.php"); ?>
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<div class="panel panel-primary">
	<div class="panel-heading" style="overflow:auto">
		<h3 class="panel-title">
			<script>
				document.write("<a href='index.php'>Users</a> > " + getCookie("kcs_user_name"));
				document.write("<span style='float:right;' onclick='window.history.back();'>(back)</span>");
			</script>
		</h3>
	</div>
	<div class="panel-body">
		<div class="buttons_wrapper" id="default">
			<div class="row">
			<?php
				/* CONNECT TO DATABASE */	
				require("snippets/database.php");
				
				/* GET USER'S OPTIONS */
				try {
					$options_sql = "SELECT DISTINCT OPTION_FK,PATH,IMAGE_ALT,IMAGEHOVER_ALT FROM User_Options RIGHT JOIN Options ON User_Options.OPTION_FK=Options.OPTION WHERE EMAIL_FK='$_COOKIE[kcs_user_email]'";
					$options_sth = $db_conn->prepare($options_sql);
					$options_sth->execute();
					$options = $options_sth->fetchAll();
					
					if($options) {	
						$i = 0;
						foreach($options as $option) {
							$option_fk = $option['OPTION_FK'];
							$path = $option['PATH'];
							$image = $option['IMAGE_ALT'];
							$imageHover = $option['IMAGEHOVER_ALT'];
							print("	<div class='col-xs-6 col-sm-3 col-md-2'>
										<div class='panel panel-info' onclick='selectOption(\"$option_fk\",\"$path\"); loadPage(\"$path\")'>
											<div class='panel-heading' id='hover'>
												<h3 class='panel-title' style='display:inline;'>$option_fk</h3>												
											</div>
											<div class='panel-body'>
												<div style='text-align:center'>");
													
													print("<img class='button_chkinot' src='$image' onclick='selectOption(\"$option_fk\",\"$path\"); loadPage(\"$path\")' onmouseover='this.src=\"$imageHover\"' onmouseout='this.src=\"$image\"'>");
													
							print("				</div>
											</div>
										</div>
									</div>");
							if(++$i % 3 == 0) { print("</div><div class='row'>"); }
						}
					}
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
	function selectOption(option,path) {
		document.cookie="kcs_option=" + option;
		document.cookie="kcs_option_path=" + path;
	}
</script>

<?php require("snippets/return-timer-activator.php"); ?>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>