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
	<?php //require("snippets/scriptfiles.php"); ?>
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" class="init">
		$(document).ready(function() {
			$('#itemTagAssocationsTable').DataTable({
				"order": [[ 0, "desc" ],[ 1, "desc" ]],
				"lengthMenu": [[10,25,50,-1],[10,25,50,"All"]],
				"pageLength": 25
			});
		});
	</script>
	<!-- Customizations to the options footer -->
	<script>
		$(document).ready(function() {
			$("#options-content").html("<a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");			
			$("#deleteAll").click(function() {				
				$("input[id^='delete']").prop('checked',$("#deleteAll").prop('checked'))
			});
			if($("#noAssociations").length) {
				$("#processBtn").hide();
			}
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Tags > Manage associations");
			</script>	
		</h3>
	</div>
	<div class='panel-body'>
	
		<!-- CREATE SECTION -------------------------------------------------------------------------->
	
		<form class='form-horizontal' id='createAssociationsFrm' action='admin-tags-createassociation-process.php' method='post' autocomplete='off'>
		
			<div class='panel panel-info'>
				<div class='panel-heading'>
					<h3 class='panel-title'>Create</h3>
				</div>					
				<div class='panel-body'>
				<?php
					/* CONNECT TO DATABASE */	
					require("snippets/database.php");
					
					/* AUTHENTICATE */
					require("snippets/admin-auth-test.php");
					
					/* GET LIST OF CATEGORIES AND THEIR ITEMS */
					try {							
						$sql = "SELECT CATEGORY_FK,ITEM FROM Items ORDER BY CATEGORY_FK ASC, ITEM ASC";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
						$result = $sth->fetchAll();							
						print("<datalist id='categoriesAndItems'>");
						foreach ($result as $item)
							print("<option value='" . $item['CATEGORY_FK'] . "-" . $item['ITEM'] . "'>");
						print("</datalist>");
					} catch(PDOException $e) {
						die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
					}

					/* GET LIST OF TAGS */					
					try {							
						$sql = "SELECT TAG FROM Tags";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
						$result = $sth->fetchAll();							
						print("<datalist id='tags'>");
						foreach ($result as $tags)
							print("<option value='" . $tags['TAG'] . "'>");
						print("</datalist>");
					} catch(PDOException $e) {
						die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
					}
							
					print("	
								<div class='form-group'>
									<label for='categoriesAndItems' class='col-sm-2 control-label'>Category-Item Sets</label>
									<div class='col-sm-10'>
										<input class='form-control' type='select' list='categoriesAndItems' name='category_item_set' id='category_item_set' placeholder='Choose an existing category-item set; required' onclick='document.getElementById(\"category_item_set\").value=\"\"'>
									</div>
								</div>
								<div class='form-group'>
									<label for='tag' class='col-sm-2 control-label'>Tag</label>
									<div class='col-sm-10'>
										<input class='form-control' type='select' list='tags' name='tag' id='tag' placeholder='Choose an existing tag; required' onclick='document.getElementById(\"tag\").value=\"\"'>
									</div>
								</div>
								<div class='form-group'>
									<div class='col-sm-offset-2 col-sm-10'>
										<div class='checkbox'>
											<label>
												<input type='checkbox' name='apply_to_category' id='apply_to_category'>Apply to the entire category, not just this item <p style='color:grey'>(This will not over-ride existing item settings.)</p>
											</label>
										</div>
									</div>
								</div>								
					");
				?>		
				</div>
			</div>
			<div style='width:100%;height:44px;margin: 20px 0px 20px 0px'>
			<button type='button' class='btn btn-lg btn-default' onclick='createAssociation()'>Create association</button>
			</div>
			
		</form>
		
		<!-- UPDATE-DELETE SECTION -------------------------------------------------------------------------->
		
		<form id="associationsDeleteFrm" action="admin-tags-associationdelete-process.php" method="post">
			<div class='panel panel-info'>
				<div class='panel-heading'>
					<h3 class='panel-title'>Delete</h3>
				</div>
				<div class='panel-body'>
				<?php
							
					/* GET LIST OF ITEMS AND ASSOCIATED TAGS */				
					try {
						$sql = "SELECT CATEGORY_FK,ITEM_FK,TAG_FK FROM Items_Tags ORDER BY CATEGORY_FK ASC,ITEM_FK ASC,TAG_FK ASC";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
						$result = $sth->fetchAll();
						
						if($result) {
							print("	<div class='page-header'>
										<h1>Item-Tag Associations</h1>
									</div>										
									<div style='padding-left:10px;padding-right:10px;'>
										<table id='itemTagAssocationsTable' class='table table-striped' width='100%'>
											<thead>
												<tr>
													<th>CATEGORY</th>
													<th>ITEM</th>
													<th>TAG</th>
													<th>DELETE? (All <input type='checkbox' id='deleteAll'>)</th>
												</tr>
											</thead>
											<tbody>
							");
							$i = 0;
							foreach($result as $itemsTags_row) {
								$category = $itemsTags_row['CATEGORY_FK'];
								$item = $itemsTags_row['ITEM_FK'];
								$tag = $itemsTags_row['TAG_FK'];
								print("	<tr>
											<td>
												<input type='text' name='CATEGORY_FK/$i/INPUT' id='CATEGORY_FK/$i/INPUT' value=\"$category\" style='display:none' readonly>
												$category
											</td>
											<td>
												<input type='text' name='ITEM_FK/$i/INPUT' id='ITEM_FK/$i/INPUT' value=\"$item\" style='display:none' readonly>
												$item
											</td>
											<td>
												<input type='text' name='TAG_FK/$i/INPUT' id='ITEM_FK/$i/INPUT' value=\"$tag\" style='display:none' readonly>
												$tag
											</td>
											<td>
												<input type='checkbox' name='delete/$i/INPUT' id='delete/$i/INPUT'>
											</td>
										</tr>
								");
								$i++;
							}
							
							print("				</tbody>
											</table>
										</div>
							");
						} else {
							print("<p id='noAssociations'>There are no associations to display. Please create some first.</p>");
						}
					} catch(PDOException $e) {
						die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
					}
				?>
				</div>
			</div>
			<button type='button' class='btn btn-lg btn-default' onclick='deleteAssociations()' id='processBtn'>Process</button>		
		</form>
	</div>
		<?php
			/* CLOSE DATABASE CONNECTION */
			$db_conn = null;
		?>	
</div>

<?php require("snippets/options.php"); ?>

<script>	
	function validateAssociation() {
		var category_item_set = document.getElementById("category_item_set");
		var tag = document.getElementById("tag");		
		return (category_item_set.value.length !=0  && tag.value.length != 0);
	}
	
	function createAssociation() {
		if (!validateAssociation())
			alert("Both fields are required values!");
		else {
			var form = document.getElementById("createAssociationsFrm");
			form.submit();
		}
	}
		
	function validateDeletedAssociations() {
		var form = document.getElementById("associationsDeleteFrm");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.type.indexOf("checkbox") > -1 && item.name.indexOf("delete") > -1 && item.checked) {
				return confirm("One or more associations are selected for permanent deletion!\n\nPress CANCEL to make sure this is correct.");					
			}
		}		
		return true;
	}
	
	function deleteAssociations() {
		if (validateDeletedAssociations()) {
			var form = document.getElementById("associationsDeleteFrm");
			form.submit();
		}
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>