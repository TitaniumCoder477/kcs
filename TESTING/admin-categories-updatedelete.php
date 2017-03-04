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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='updateCategories()'>Process</button><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Cancel</button></a>");
		});
	</script>		
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<form id="processCategoriesUpdate" action="admin-categories-updatedelete-process.php" method="post">
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Categories > Update/Delete
		</h3>
	</div>
	<div class="panel-body">
		<!-- # 11/24/2016 <div class="row"> -->
		<?php
			/* CONNECT TO DATABASE */	
			require("snippets/database.php");
			
			/* AUTHENTICATE */
			require("snippets/admin-auth-test.php");
			
			if($result) {
			
				/* GET LIST OF CATEGORIES */
				try {
					$sql = "SELECT * FROM Categories ORDER BY RANKING ASC";
					$sth = $db_conn->prepare($sql);
					$sth->execute();
					$result = $sth->fetchAll();
					
					/* EXPECTED DATA FORMAT
					*
					*	KEY					VALUE
					*	0/_key				Computers
					*	0/CATEGORY			Computers2
					*	0/DESCRIPTION		
					*	0/_delete			on
					*	1/_key				Flash drives
					*	1/CATEGORY			Flash drives
					*	1/DESCRIPTION		
					*/
					
					if($result) {
						print("	<div class='page-header'>
									<h1>Categories</h1>
								</div>
										<table class='table table-striped' id='categoryTable'>
											<thead>
												<tr>
													<th>CATEGORY</th>
													<th>DESCRIPTION</th>
													<th>RANKING</th>
													<th>EXPANDED</th>
													<th>DELETE?</th>
												</tr>
											</thead>
											<tbody id='categoriesTBody'>
						");
						$i = 0;
						foreach($result as $categories_row) {
							$category = $categories_row['CATEGORY'];
							$description = $categories_row['DESCRIPTION'];
							$ranking = $categories_row['RANKING'];
							$expanded = $categories_row['EXPANDED'];
							$expanded = ($expanded == 1 ? "checked" : "");
							print("	<tr>
										<td style='display:none' id='key/$i/TD'><input type='text' name='key/$i/INPUT' value='$category' maxlength='45' size='45'></td>
										<td id='CATEGORY/$i/TD'><input type='text' name='CATEGORY/$i/INPUT' id='CATEGORY/$i/INPUT' value='$category' maxlength='45' size='45' placeholder='Category is required' onchange=\"validateCategory('CATEGORY/$i')\"></td>
										<td id='DESCRIPTION/$i/TD'><input type='text' name='DESCRIPTION/$i/INPUT' id='DESCRIPTION/$i/INPUT' value='$description' maxlength='150' size='80' placeholder='Description is optional'></td>
							");
							
							print("		<td id='RANKING/$i/TD'><input type='number' name='RANKING/$i/INPUT' id='RANKING/$i/INPUT' value='$ranking' min='0' max='100000' style='text-align: right;' \"></td>");
							print("		<td id='EXPANDED/$i/TD'><input type='checkbox' name='EXPANDED/$i/INPUT' id='EXPANDED/$i/INPUT' $expanded \"></td>");
							
							print("		<td><input type='checkbox' name='delete/$i/INPUT'></td>
									</tr>
							");
							$i++;
						}
						
						print("				</tbody>
										</table>
									
						");
					} else {
						print("
							<div class='panel panel-info'>
								<div class='panel-heading'>
									<h3 class='panel-title'>Categories</h3>
								</div>
								<div class='panel-body'>
									<p id='noCategories'>There are no categories to display. Please create some first.</p>
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
		<!-- </div> -->
	</div>
</div>
</form>

<?php require("snippets/options.php"); ?>

<script>
	function selectCategory(category,description) {
		document.cookie="kcs_category=" + category;
		document.cookie="kcs_category_description=" + description;
	}
		
	function validateCategory(id) {
		//console.log("ID = " + id);
		var input = document.getElementById(id+"/INPUT");
		var td = document.getElementById(id+"/TD");
		
		//console.log("input = " + input);
		//console.log("td = " + td);
		
		var result = (input.value.length != 0);
		td.style.backgroundColor = (result ? "white" : "red");
		
		return result;
	}
	
	function validateCategories() {	
		var form = document.getElementById("processCategoriesUpdate");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.name.indexOf("CATEGORY") > -1) {
				var name = item.name;
				var id = name.substr(0,name.indexOf("/INPUT"));
				if (!validateCategory(id))
					return false;
			}
		}		
		return true;
	}
	
	function deleteCategories() {
		var form = document.getElementById("processCategoriesUpdate");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.type.indexOf("checkbox") > -1 && item.name.indexOf("delete") > -1 && item.checked) {
				return confirm("One or more categories are selected for permanent deletion!\n\nPress CANCEL to make sure this is correct.");					
			}
		}		
		return true;
	}
	
	function updateCategories() {
		if (validateCategories()) {
			if (deleteCategories()) {
				var form = document.getElementById("processCategoriesUpdate");
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