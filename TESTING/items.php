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
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<div class="panel panel-primary">
	<div class="panel-heading" style="overflow:auto">
		<h3 class="panel-title">
			<script>
				document.write("<a href='index.php'>Users</a> > " + getCookie("kcs_user_name") + " > <a href='" + getCookie("kcs_option_path") + "'>" + getCookie("kcs_option") + "</a> > " + getCookie("kcs_category"));
			</script>	
			<?php require("snippets/return-timer.php"); ?>
		</h3>
	</div>
	<div class="panel-body">
		<div class="buttons_wrapper" id="default">
			
			<div class="row">
			<?php
				/* CONNECT TO DATABASE */	
				require("snippets/database.php");
				
				/* GET LIST OF USERS */
				try {
					/* GET CHECK OUT ICON */
					$sql2 = "SELECT * FROM Options WHERE Options.OPTION='Check Out'";
					$sth2 = $db_conn->prepare($sql2);
					$sth2->execute();
					$result2 = $sth2->fetchAll();
					
					$option = $result2[0]['OPTION_FK'];
					$path = $result2[0]['PATH'];
					$image = $result2[0]['IMAGE'];
					$imageHover = $result2[0]['IMAGEHOVER'];
					
					$category = $_COOKIE[kcs_category];
					
					/* GET LIST OF ITEMS */
					$sql = "SELECT Items.ITEM,Items.DESCRIPTION,Items.USER_EMAIL_FK FROM Items LEFT JOIN Categories ON Items.CATEGORY_FK=Categories.CATEGORY WHERE Categories.CATEGORY='$category' ORDER BY Items.USER_EMAIL_FK ASC";
					$sth = $db_conn->prepare($sql);
					$sth->execute();
					$result = $sth->fetchAll();
					
					if($result) {				
						$i = 0;
						foreach($result as $items) {
							$item = $items['ITEM'];
							$description = $items['DESCRIPTION'];
							$email = $items['USER_EMAIL_FK'];							
							
							/* GET LIST OF TAGS AND TAG OPTIONS */
							$sql2 = "SELECT TAG_FK,TAG_OPTION FROM Items_Tags WHERE CATEGORY_FK='$category' AND ITEM_FK='$item'";
							$sth2 = $db_conn->prepare($sql2);
							$sth2->execute();
							$result2 = $sth2->fetchAll();
							
							if(empty($email) || strcmp($email, "admin@kioskcheckoutsystem.com") === 0) {
								print("	<div class='col-xs-6 col-sm-3 col-md-2 col-lg-2'>
											<div class='panel panel-info' onclick='selectItem(\"$item\"); loadPage(\"checkout-confirmation.php\")'>
												<div class='panel-heading' id='light'>
													<img class='button_chkinot header' src='$image'>
																<h3 class='panel-title' style='display:inline;'><div class='wrap'>$item</div></h3>
												</div>
												<div class='panel-body'>
													$description<br>");
														
								foreach($result2 as $item_tags) {
									$tag = $item_tags['TAG_FK'];
									$tag_option = $item_tags['TAG_OPTION'];
									
									print("<br>$tag : $tag_option");
								}
														
								print("			</div>
											</div>
										</div>");
							} else {
								print("	<div class='col-xs-6 col-sm-3 col-md-2 col-lg-2'>
											<div class='panel panel-warning'>
												<div class='panel-heading'>
													<h3 class='panel-title'>OUT - $item</h3>
												</div>
												<div class='panel-body'>
													$description<br><br><b>User: <div class='wrap'>$email</div></b>");
								
								foreach($result2 as $item_tags) {
									$tag = $item_tags['TAG_FK'];
									$tag_option = $item_tags['TAG_OPTION'];
									print("<br>$tag : $tag_option");
								}
														
								print("			</div>
											</div>
										</div>");
							}
							
							$i++;
							if($i % 6 == 0) { 
								print("<div class='clearfix visible-md-block visible-lg-block'></div>"); 								
							}
							if($i % 4 == 0) {
								print("<div class='clearfix visible-sm-block'></div>"); 
							}								
							if($i % 2 == 0) {
								print("<div class='clearfix visible-xs-block'></div>"); 
							}								
						}						
					} else {
						print("	<div class='col-xs-12 col-sm-6'>
									<div class='panel panel-info'>
										<div class='panel-heading'>
											<h3 class='panel-title'>Empty</h3>
										</div>
										<div class='panel-body'>
											There are no items in this category!
										</div>
									</div>
								</div>");
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
	function selectItem(item) {
		document.cookie="kcs_item=" + item;
		//document.cookie="kcs_user_email=" + email;
	}
</script>

<?php require("snippets/return-timer-activator.php"); ?>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>