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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='updateItems()'>Process</button><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
			$("#selectAll").click(function() {
				$(".deleteCheckboxes").prop("checked", $("#selectAll").prop("checked"));
			});
		});
	</script>
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<form id="processItemsUpdate" action="admin-items-updatedelete-process.php" method="post">
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<script>
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Items > Update/Delete");
			</script>	
		</h3>
	</div>
	<div class='panel-body'>
	<?php
		/* CONNECT TO DATABASE */	
		require("snippets/database.php");
		
		/* AUTHENTICATE */
		require("snippets/admin-auth-test.php");
		
		if($result) {				
			
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

			/* GET LIST OF ITEMS */				
			try {
				$sql = "SELECT * FROM Items ORDER BY CATEGORY_FK ASC";
				$sth = $db_conn->prepare($sql);
				$sth->execute();
				$result = $sth->fetchAll();
				
				/* EXPECTED DATA FORMAT
				*
				*   EACH ROW HAS THE FOLLOWING:
				*
				*	INPUT NAME				INPUT VALUE
				*	category_key/{i}/INPUT	{$category} = Computers									# Original value
				*	item_key/{i}/INPUT		{$item} = Laptop01										# Original value
				*	category_fk/{i}/INPUT	{$category} = Computers									# New values
				*	item/{i}/INPUT			{$item} = Laptop01										# New values
				*	description/{i}/INPUT	{$description} = 										# New values
				*	user_email_fk/{i}/INPUT	{$user_email} = jwilmoth@biztechnologysolutions.com		# New values
				*	delete/{i}/INPUT		{} = on													# Action value
				*
				*   - EACH ROW IS UNIQUE BECAUSE OF THE INDEX VALUE.
				*	- IF DELETE IS NOT CHECKED FOR A ROW, THEN IT DOES NOT HAVE A DELETE INPUT VALUE.
				*/
				
				if($result) {
					print("	<div class='page-header'>
								<h1>Items</h1>
							</div>								
							<table class='table table-striped' id='itemTable'>
								<thead>
									<tr>
										<th>CATEGORY</th>
										<th>ITEM</th>
										<th>DESCRIPTION</th>
										<th>USER EMAIL</th>
										<th>
											DELETE?<br>
											<input type='checkbox' name='selectAll' id='selectAll' data-toggle='tooltip' title='Select all the check boxes'> Select
										</th>
									</tr>
								</thead>
								<tbody id='itemsTBody'>
					");
					$i = 0;
					foreach($result as $items_row) {
						$category = $items_row['CATEGORY_FK'];
						$item = $items_row['ITEM'];
						$description = $items_row['DESCRIPTION'];
						$user_email = $items_row['USER_EMAIL_FK'];
						print("		<tr>
										<td style='display:none' id='category_key/$i/TD'><input type='text' name='category_key/$i/INPUT' id='category_key/$i/INPUT' value=\"$category\" maxlength='45' size='45'></td>
										<td style='display:none' id='item_key/$i/TD'><input type='text' name='item_key/$i/INPUT' id='item_key/$i/INPUT' value=\"$item\" maxlength='45' size='45'></td>
										<td id='CATEGORY_FK/$i/TD'><input type='select' list='categories' name='CATEGORY_FK/$i/INPUT' id='CATEGORY_FK/$i/INPUT' value=\"$category\" placeholder='Category is required' onclick=\"onClickInput('CATEGORY_FK/$i')\" onfocusout=\"onLeaveInput('CATEGORY_FK/$i','category_key/$i')\" onchange=\"validateInput('CATEGORY_FK/$i')\"></td>
										<td id='ITEM/$i/TD'><input type='text' name='ITEM/$i/INPUT' id='ITEM/$i/INPUT' value=\"$item\" maxlength='45' placeholder='Item is required' onchange=\"validateInput('ITEM/$i')\"></td>
										<td id='DESCRIPTION/$i/TD'><input type='text' name='DESCRIPTION/$i/INPUT' id='DESCRIPTION/$i/INPUT' value=\"$description\" maxlength='150' size='70' placeholder='Description is optional'></td>
										<td id='USER_EMAIL_FK/$i/TD'><input type='text' name='USER_EMAIL_FK/$i/INPUT' id='USER_EMAIL_FK/$i/INPUT' value=\"$user_email\" maxlength='45' placeholder='Not checked out' readonly style='color:grey;'></td>
										<td><input class='deleteCheckboxes' type='checkbox' name='delete/$i/INPUT'></td>
									</tr>
						");
						$i++;
					}					
					print("		</tbody>
							</table>								
					");
				} else {
					print("
						<div class='panel panel-info'>
							<div class='panel-heading'>
								<h3 class='panel-title'>Items</h3>
							</div>
							<div class='panel-body'>
								<p id='noItems'>There are no items to display. Please create some first.</p>
							</div>
						</div>
					");						
				}
			} catch(PDOException $e) {
				die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
			}
		}
			
		/* CLOSE DATABASE CONNECTION */
		$db_conn = null;
	?>	
	</div>
</div>
</form>

<?php require("snippets/options.php"); ?>

<script>	
	function validateFormValues() {	
		var form = document.getElementById("processItemsUpdate");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.name.indexOf("CATEGORY_FK") > -1 || item.name.indexOf("ITEM") > -1) {
				var name = item.name;
				var id = name.substr(0,name.indexOf("/INPUT"));
				if (!validateInput(id))
					return false;
			}
		}		
		return true;
	}
		
	function validateDeletedItems() {
		var form = document.getElementById("processItemsUpdate");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.type.indexOf("checkbox") > -1 && item.name.indexOf("delete") > -1 && item.checked) {
				return confirm("One or more items are selected for permanent deletion!\n\nPress CANCEL to make sure this is correct.");					
			}
		}		
		return true;
	}
	
	function updateItems() {
		if (validateFormValues()) {
			if (validateDeletedItems()) {
				var form = document.getElementById("processItemsUpdate");
				form.submit();
			}
		} else alert("Error validating data!\n\nPlease correct the items in red.");
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>