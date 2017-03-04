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
			$("#options-content").html("<a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Tags > Create/Update/Delete");
			</script>	
		</h3>
	</div>
	<div class='panel-body'>
	
		<!-- CREATE SECTION -------------------------------------------------------------------------->
	
		<form class='form-horizontal' id='createTagFrm' action='admin-tags-create-process.php' method='post' autocomplete='off'>
		
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
					
					/* GET LIST OF TAGS */
						try {							
							$sql = "SELECT TAG FROM Tags";
							$sth = $db_conn->prepare($sql);
							$sth->execute();
							$result = $sth->fetchAll();							
							print("<datalist id='tags'>");
							foreach ($result as $tag)
								print("<option value='$tag[0]'>");
							print("</datalist>");							
						} catch(PDOException $e) {
							die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
						}
							
						print("	
									<div class='form-group'>
										<label for='tag' class='col-sm-2 control-label'>Tag</label>
										<div class='col-sm-10'>
											<input class='form-control' type='select' list='tags' name='tag' id='tag' maxlength='35' placeholder='Choose an existing tag or type a new one; required' onclick='document.getElementById(\"tag\").value=\"\"'>
										</div>
									</div>
									<div class='form-group'>
										<label for='tag_option' class='col-sm-2 control-label'>Tag Option</label>
										<div class='col-sm-10'>
											<input class='form-control' type='text' name='tag_option' id='tag_option' maxlength='35' placeholder='Type a new tag option; required'>
											<br><p style='color:grey'>To set a default, prepend the Tag Option with 1-. For example, if Tag was 'Needs maintenance?' then the default Tag Option could be '1-No need'. Then when you associate a Tag with an Item or entire Category, the default Tag Option of your choice will be chosen.</p>
										</div>
									</div>
								
						");
				?>		
				</div>
			</div>
			<div style='width:100%;height:44px;margin: 20px 0px 20px 0px'>
			<button type='button' class='btn btn-lg btn-default' onclick='createTag()'>Create tag</button>
			</div>
			
		</form>
		
		<!-- UPDATE-DELETE SECTION -------------------------------------------------------------------------->
		
		<form id="tagsUpdateDeleteFrm" action="admin-tags-updatedelete-process.php" method="post">
			<div class='panel panel-info'>
				<div class='panel-heading'>
					<h3 class='panel-title'>Update/Delete</h3>
				</div>
				<div class='panel-body'>
				<?php
							
					/* GET LIST OF TAGS */
					try {							
						$sql = "SELECT TAG FROM Tags";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
						$result = $sth->fetchAll();							
						print("<datalist id='tags'>");
						foreach ($result as $tag)
							print("<option value='$tag[0]'>");
						print("</datalist>");							
					} catch(PDOException $e) {
						die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
					}

					/* GET LIST OF TAGS OPTIONS */				
					try {
						$sql = "SELECT * FROM Tags_Options ORDER BY TAG_FK ASC";
						$sth = $db_conn->prepare($sql);
						$sth->execute();
						$result = $sth->fetchAll();
						
						if($result) {
							print("	<div class='page-header'>
										<h1>Tag-Option Sets</h1>
									</div>
										
											<table class='table table-striped' id='tagsOptionsTable'>
												<thead>
													<tr>
														<th>TAG</th>
														<th>OPTION</th>
														<th>DELETE?</th>
													</tr>
												</thead>
												<tbody id='tagsOptionsTBody'>
							");
							$i = 0;
							foreach($result as $tagsOptions_row) {
								$tag = $tagsOptions_row['TAG_FK'];
								$tag_option = $tagsOptions_row['TAG_OPTION'];
								print("	<tr>
											<td style='display:none' id='tag_key/$i/TD'><input type='text' name='tag_key/$i/INPUT' id='tag_key/$i/INPUT' value=\"$tag\" maxlength='35' size='35'></td>
											<td style='display:none' id='tag_option_key/$i/TD'><input type='text' name='tag_option_key/$i/INPUT' id='tag_option_key/$i/INPUT' value=\"$tag_option\" maxlength='35' size='35'></td>
											<td id='TAG_FK/$i/TD'><input type='select' list='tags' name='TAG_FK/$i/INPUT' id='TAG_FK/$i/INPUT' value=\"$tag\" placeholder='Tag is required' maxlength='35' size='35' readonly style='color:grey'></td>
											<!-- <td id='TAG_FK/$i/TD'><input type='select' list='tags' name='TAG_FK/$i/INPUT' id='TAG_FK/$i/INPUT' value=\"$tag\" placeholder='Tag is required' onclick=\"onClickInput('TAG_FK/$i')\" onfocusout=\"onLeaveInput('TAG_FK/$i','tag_key/$i')\" onchange=\"validateInput('TAG_FK/$i')\" maxlength='35' size='35'></td> -->
											<td id='TAG_OPTION/$i/TD'><input type='text' name='TAG_OPTION/$i/INPUT' id='TAG_OPTION/$i/INPUT' value=\"$tag_option\" placeholder='Tag option is required' onchange=\"validateInput('TAG_OPTION/$i')\" maxlength='35' size='35'></td>
								");
								print("	<td><input type='checkbox' name='delete/$i/INPUT'></td>
										</tr>
								");
								$i++;
							}
							
							print("				</tbody>
											</table>
										
							");
						} else {
							print("<p id='noTags'>There are no tags to display. Create some tags above.</p>");
						}
					} catch(PDOException $e) {
						die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
					}
				?>
				</div>
			</div>
			<button type='button' class='btn btn-lg btn-default' onclick='updateDeleteTags()'>Process</button>		
		</form>
	</div>
		<?php
			/* CLOSE DATABASE CONNECTION */
			$db_conn = null;
		?>	
</div>

<?php require("snippets/options.php"); ?>

<script>	
	function validateTag() {
		var tag = document.getElementById("tag");
		var tag_option = document.getElementById("tag_option");
		return (tag.value.length != 0 && tag_option.value.length != 0);
	}
	
	function createTag() {
		if (!validateTag())
			alert("Both fields are required values!");
		else {
			var form = document.getElementById("createTagFrm");
			form.submit();
		}
	}
	
	function validateFormValues() {	
		var form = document.getElementById("tagsUpdateDeleteFrm");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.name.indexOf("TAG_FK") > -1 || item.name.indexOf("TAG_OPTION") > -1) {
				var name = item.name;
				var id = name.substr(0,name.indexOf("/INPUT"));
				if (!validateInput(id))
					return false;
			}
		}		
		return true;
	}
		
	function validateDeletedItems() {
		var form = document.getElementById("tagsUpdateDeleteFrm");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.type.indexOf("checkbox") > -1 && item.name.indexOf("delete") > -1 && item.checked) {
				return confirm("One or more tags are selected for permanent deletion!\n\nPress CANCEL to make sure this is correct.");					
			}
		}		
		return true;
	}
	
	function updateDeleteTags() {
		if (validateFormValues()) {
			if (validateDeletedItems()) {
				var form = document.getElementById("tagsUpdateDeleteFrm");
				form.submit();
			}
		} else alert("Error validating data!\n\nPlease correct the fields in red.");
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>