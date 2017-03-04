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
								
				try {
					/* GET CHECK IN ICON */
					$options_sql = "SELECT * FROM Options WHERE Options.OPTION='Check In'";
					$options_sth = $db_conn->prepare($options_sql);
					$options_sth->execute();
					$options = $options_sth->fetchAll();
					
					$option = $options[0]['OPTION_FK'];
					$path = $options[0]['PATH'];
					$image = $options[0]['IMAGE'];
					$imageHover = $options[0]['IMAGEHOVER'];
					
					/* GET LIST OF ITEMS */
					$items_sql = "SELECT Items.CATEGORY_FK,Items.ITEM,Items.DESCRIPTION FROM Items WHERE Items.USER_EMAIL_FK='$_COOKIE[kcs_user_email]'";
					$items_sth = $db_conn->prepare($items_sql);
					$items_sth->execute();
					$items = $items_sth->fetchAll();
					
					if($items) {	
						$i = 0;
						foreach($items as $item_row) {
							$category = $item_row['CATEGORY_FK'];
							$item = $item_row['ITEM'];
							$description = $item_row['DESCRIPTION'];
							
							$item_tags_sql = "SELECT TAG_FK,TAG_OPTION FROM Items_Tags WHERE CATEGORY_FK='$category' AND ITEM_FK='$item'";
							$item_tags_sth = $db_conn->prepare($item_tags_sql);
							$item_tags_sth->execute();
							$item_tags = $item_tags_sth->fetchAll();
							
							print("	<form id='form$i' action='checkin-confirmation.php' method='post' autocomplete='off'>
									<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>
										<div class='panel panel-info'>											
											<div class='panel-heading' id='hover' onclick='selectItem(\"$category\",\"$item\"); submitForm(\"form$i\")'>
												<img class='button_chkinot header' src='$image'>
												<h3 class='panel-title' style='display:inline;'><div class='wrap'>$item</div></h3>
											</div>											
											<div class='panel-body'>
												Hint: Adjust any details if needed. Tap orange above to check in.<br>
												$description<br><br>");
												
							foreach($item_tags as $item_tag) {
								$tag = $item_tag['TAG_FK'];
								$tag_repl = str_replace(' ', '##', $tag);
								$tag_option = $item_tag['TAG_OPTION'];
								
								/* GET LIST OF TAGS */
								$tags_options_sql = "SELECT TAG_OPTION FROM Tags_Options WHERE TAG_FK='$tag'";
								$tags_options_sth = $db_conn->prepare($tags_options_sql);
								$tags_options_sth->execute();
								$tags_options = $tags_options_sth->fetchAll();		
								
								print("	<div class='form-group'><label class='control-label' for='$tag_repl'>$tag</label>
												<select class='form-control' name='$tag_repl'>");
								foreach ($tags_options as $tag_options) {
									if($tag_options[TAG_OPTION] === $tag_option)
										print("		<option value='$tag_options[TAG_OPTION]' selected='selected'>$tag_options[TAG_OPTION]</option>");
									else
										print("		<option value='$tag_options[TAG_OPTION]'>$tag_options[TAG_OPTION]</option>");
								}
								print("			</select>												
											</div>");
								
							}
												
							print("			</div>
										</div>
									</div>
									</form>");
							$i++;
							if($i % 3 == 0) { 
								print("<div class='clearfix visible-sm-block visible-md-block visible-lg-block'></div>"); 								
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
	function selectItem(category,item) {
		document.cookie="kcs_category=" + category;
		document.cookie="kcs_item=" + item;
	}	
</script>

<?php require("snippets/return-timer-activator.php"); ?>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>