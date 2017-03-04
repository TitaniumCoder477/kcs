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
			$("#options-content").html("<a href='admin-logoff.php'><button type='button' class='btn btn-lg btn-default'>Log Off</button></a>");
		});
	</script>		
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"> 
			<a href='admin-logoff.php'>Log Off</a> > Admin
		</h3>
	</div>
	<div class='panel-body'>
	<?php
		
		/* CONNECT TO DATABASE */	
		require("snippets/database.php");
				
		/* AUTHENTICATE */
		require("snippets/admin-auth-test.php");
		
		if($result) {
						
			print("<div class='row'>");
	
			/* LIST ADMIN CATEGORIES */

			try {
				$sql = "SELECT * FROM Admin_Categories ORDER BY RANKING ASC";
				$sth = $db_conn->prepare($sql);
				$sth->execute();
				$result = $sth->fetchAll();
				
				if($result) {							
					$i = 0;
					foreach($result as $admin_categories_row) {
						$category = $admin_categories_row['CATEGORY'];
						
						try { 
							//$sql2 = "SELECT Admin_Categories.CATEGORY,Admin_Category_Tasks.TASK,Admin_Category_Tasks.PATH FROM Admin_Category_Tasks LEFT JOIN Admin_Categories ON Admin_Category_Tasks.CATEGORY_FK=Admin_Categories.CATEGORY WHERE Admin_Categories.CATEGORY='$category'";
							$sql2 = "SELECT * FROM Admin_Category_Tasks WHERE CATEGORY_FK='$category' ORDER BY RANKING ASC";
							$sth2 = $db_conn->prepare($sql2);
							$sth2->execute();
							$result2 = $sth2->fetchAll();

							if($result2) {							
								print("	<div class='col-xs-6 col-sm-3 col-md-2 col-lg-2'>
											<div class='panel panel-info'>
												<div class='panel-heading'>
													<h3 class='panel-title'>$category</h3>
												</div>
												<div class='panel-body'>");
								
													foreach($result2 as $admin_category_tasks_row) {
														$task = $admin_category_tasks_row['TASK'];
														$path = $admin_category_tasks_row['PATH'];
														print("		<a href='$path'>$task</a><br>");
													}
								
								print("			</div>
											</div>
										</div>");
							}
						}							
						catch(PDOException $e) {
							die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
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
				}
			}
			catch(PDOException $e) {
				die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
			}		
			print("</div>");
		}
		/* CLOSE DATABASE CONNECTION */
		$db_conn = null;
	?>
	</div>
</div>

<?php require("snippets/options.php"); ?>

<script>
	function selectCategory(category,description) {
		document.cookie="kcs_category=" + category;
		document.cookie="kcs_category_description=" + description;
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>