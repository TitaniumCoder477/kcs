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
	
	<!-- Customize (if needed) the options footer -->
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
				document.write("<a href='index.php'>Users</a> > " + getCookie("kcs_user_name") + " > " + getCookie("kcs_option"));	
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
				
				$email = $_COOKIE["kcs_user_email"];
		
				try {
					/* GET CHECK OUT ICON */
					$sql2 = "SELECT * FROM Options WHERE Options.OPTION='Check Out'";
					$sth2 = $db_conn->prepare($sql2);
					$sth2->execute();
					$result2 = $sth2->fetchAll();
					
					$option = $result2[0]['OPTION_FK'];
					$path = $result2[0]['PATH'];
					$image = $result2[0]['IMAGE_ALT'];
					$imageHover = $result2[0]['IMAGEHOVER_ALT'];
				
					/* GET LIST OF CATEGORIES */
					$sql = "SELECT * FROM Categories ORDER BY EXPANDED DESC,RANKING ASC";
					$sth = $db_conn->prepare($sql);
					$sth->execute();
					$result = $sth->fetchAll();
					
					if($result) {
						$i = 0;
						foreach($result as $categories) {
							$category = $categories['CATEGORY'];
							$description = $categories['DESCRIPTION'];
							$expanded = $categories['EXPANDED'];
							
							/* DISPLAY EXPANDED CATEGORIES */
							if($expanded) {
								
								/* GET LIST OF ITEMS */
								$sql3 = "SELECT Items.ITEM,Items.DESCRIPTION,Items.USER_EMAIL_FK FROM Items LEFT JOIN Categories ON Items.CATEGORY_FK=Categories.CATEGORY WHERE Categories.CATEGORY='$category' ORDER BY Items.USER_EMAIL_FK ASC";
								$sth3 = $db_conn->prepare($sql3);
								$sth3->execute();
								$result3 = $sth3->fetchAll();		
								
								if($result3) {
									$j = 0;
									foreach($result3 as $items) {
										$item = $items['ITEM'];
										$item_description = $items['DESCRIPTION'];
										$email = $items['USER_EMAIL_FK'];							
										
										/* GET LIST OF TAGS AND TAG OPTIONS */
										$sql4 = "SELECT TAG_FK,TAG_OPTION FROM Items_Tags WHERE CATEGORY_FK='$category' AND ITEM_FK='$item'";
										$sth4 = $db_conn->prepare($sql4);
										$sth4->execute();
										$result4 = $sth4->fetchAll();
										
										if(empty($email) || strcmp($email, "admin@kioskcheckoutsystem.com") === 0) {
											print("	<div class='col-xs-6 col-sm-3 col-md-2 col-lg-2'>
														<div class='panel panel-info' onclick='selectItem(\"$item\",\"$category\",\"$description\"); loadPage(\"checkout-confirmation.php\")'>
															<div class='panel-heading' id='dark'>
																<img class='button_chkinot header' src='$image'>
																<h3 class='panel-title' style='display:inline;'><div class='wrap'>$item</div></h3>
															</div>
															<div class='panel-body'>
																$item_description<br>");
																	
											foreach($result4 as $item_tags) {
												$tag = $item_tags['TAG_FK'];
												$tag_option = $item_tags['TAG_OPTION'];
												
												if($tag_option != "1-NA")
													print("<br>$tag : $tag_option");
											}
																	
											print("			</div>
														</div>
													</div>");
										} else {
											print("	<div class='col-xs-6 col-sm-3 col-md-2 col-lg-2'>
														<div class='panel panel-warning'>
															<div class='panel-heading'>
																<h3 class='panel-title'><div class='wrap'>OUT - $item</div></h3>
															</div>
															<div class='panel-body'>
																$description<br><br><b><div class='wrap'>User: $email</div></b>");
											
											foreach($result4 as $item_tags) {
												$tag = $item_tags['TAG_FK'];
												$tag_option = $item_tags['TAG_OPTION'];
												print("<br>$tag : $tag_option");
											}
																	
											print("			</div>
														</div>
													</div>");
										}	
										
										$j++;
										if($j % 6 == 0) { 
											print("<div class='clearfix visible-md-block visible-lg-block'></div>"); 								
										}
										if($j % 4 == 0) {
											print("<div class='clearfix visible-sm-block'></div>"); 
										}								
										if($j % 2 == 0) {
											print("<div class='clearfix visible-xs-block'></div>"); 
										}	
									}						
								}
								print("<div class='clearfix visible-md-block visible-lg-block'></div>"); 			
								print("<div class='clearfix visible-sm-block'></div>"); 
								print("<div class='clearfix visible-xs-block'></div>"); 
								$i = 0;
							/* DISPLAY NON-EXPANDED CATEGORIES */
							} else {																
								print("	<div class='col-xs-6 col-sm-3 col-md-2 col-lg-2'>
											<div class='panel panel-info'>
												<div class='panel-heading' id='light' onclick=\"selectCategory('$category','$description'); loadPage('items.php')\" onmouseover=\"document.getElementById('$category/$description/IMAGE').src='$imageHover'\" onmouseout=\"document.getElementById('$category/$description/IMAGE').src='$image'\">
													<h3 class='panel-title' style='display:inline;'>$category</h3>
												</div>
												<div class='panel-body'>
													<div style='text-align:center'>
														<img class='button_chkinot image-button2' id='$category/$description/IMAGE' src='$image' onclick=\"selectCategory('$category','$description'); loadPage('items.php')\" onmouseover=\"document.getElementById('$category/$description/IMAGE').src='$imageHover'\" onmouseout=\"document.getElementById('$category/$description/IMAGE').src='$image'\">
													</div>
												</div>
											</div>
										</div>");
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
	function selectCategory(category,description) {
		document.cookie="kcs_category=" + category;
		document.cookie="kcs_category_description=" + description;
	}
	
	function selectItem(item,category,description) {
		document.cookie="kcs_item=" + item;
		selectCategory(category,description);
		//document.cookie="kcs_user_email=" + email;
	}
</script>

<?php require("snippets/return-timer-activator.php"); ?>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>