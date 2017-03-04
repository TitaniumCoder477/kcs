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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='createItem()'>Create item</button><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Cancel</button></a>");
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Items > Create");
			</script>
		</h3>
	</div>
	<div class="panel-body">
		<div class='panel panel-info'>
			<div class='panel-heading'>
				<h3 class='panel-title'>Details</h3>
			</div>
			<div class='panel-body'>
			<?php
				/* CONNECT TO DATABASE */	
				require("snippets/database.php");
				
				/* AUTHENTICATE */
				require("snippets/admin-auth-test.php");
								
				/* GET LIST OF CATEGORIES */
				try {							
					$sql = "SELECT CATEGORY FROM Categories";
					$sth = $db_conn->prepare($sql);
					$sth->execute();
					$result = $sth->fetchAll();							
					print("<datalist id='categories'>");
					foreach ($result as $category)
						print("<option value='$category[0]'>");
					print("</datalist>");							
				} catch(PDOException $e) {
					die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
				}
				
				print("	<form class='form-horizontal' id='newItemFrm' action='admin-items-create-process.php' method='post' autocomplete='off'>
							<div class='form-group'>
								<label for='category' class='col-sm-2 control-label'>Category</label>
								<div class='col-sm-10'>
									<input class='form-control' type='select' list='categories' name='category' id='category' placeholder='Category is required' onclick='document.getElementById(\"category\").value=\"\"'>
								</div>
							</div>
							<div class='form-group'>
								<label for='item' class='col-sm-2 control-label'>Item</label>
								<div class='col-sm-10'>
									<input class='form-control' type='text' name='item' id='item' maxlength='45' placeholder='Item is required'>
								</div>
							</div>
							<div class='form-group'>
								<label for='description' class='col-sm-2 control-label'>Description</label>
								<div class='col-sm-10'>
									<input class='form-control' type='text' name='description' maxlength='255' placeholder='Description is optional'>
								</div>
							</div>
						</form>");
				
				/* CLOSE DATABASE CONNECTION */
				$db_conn = null;
			?>						
			</div>
		</div>
	</div>
</div>

<?php require("snippets/options.php"); ?>

<script>	
	function selectItem(category,item,description) {
		document.cookie="kcs_item_category=" + category;
		document.cookie="kcs_item_item=" + item;
		document.cookie="kcs_item_description=" + description;
	}
	
	function validateCategory(id) {		
		var category = document.getElementById(id).value;
		return (category.length != 0);
	}
	
	function validateItem(id) {		
		var item = document.getElementById(id).value;
		return (item.length != 0);
	}
	
	function createItem() {
		if (!validateCategory("category"))
			alert("The category is a required value! Please choose one from the list.");
		else if(!validateItem("item"))
			alert("The item is a required value and must be one or more alpha-numeric characters!");
		else {
			var form = document.getElementById("newItemFrm");
			form.submit();
		}
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>