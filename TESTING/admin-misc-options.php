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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='updateOptions()'>Process</button><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
		});
	</script>
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<form id="processOptionsUpdate" action="admin-misc-options-process.php" method="post">
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<script>
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Misc > Options");
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
			
			/* GET LIST OF OPTIONS */				
			try {
				$sql = "SELECT * FROM Admin_Misc_Options ORDER BY RANKING ASC";
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
				*	RANKING/{i}/INPUT	{$category} = Computers									# New values
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
								<h1>Options</h1>
							</div>								
							<table class='table table-striped' id='optionTable'>
								<thead>
									<tr>
										<th>DESCRIPTION</th>
										<th>SETTING</th>
										<th>VALUE</th>
									</tr>
								</thead>
								<tbody id='optionsTBody'>
					");
					$i = 0;
					foreach($result as $options_row) {
						
						$description = $options_row['DESCRIPTION'];
						$setting = $options_row['SETTING'];
						$value = $options_row['VALUE'];
						$type = $options_row['TYPE'];
						$required = $options_row['REQUIRED'];
						$min_bound = $options_row['MIN_BOUND'];
						$max_bound = $options_row['MAX_BOUND'];
						
						print("		<tr>
										<td><label>$description</label></td>
										<td><label for='VALUE/$setting/INPUT'>$setting</label></td>
										<td id='VALUE/$setting/TD'>
						");
						
						if($required == '1') {
							if(strcmp($type,"integer") === 0) {
								if(!is_null($min_bound) && !is_null($max_bound))
									print("<input type='number' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" min='$min_bound' max='$max_bound' required>");
								else if(!is_null($min_bound))
									print("<input type='number' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" min='$min_bound' required>");
								else if(!is_null($max_bound))
									print("<input type='number' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" max='$max_bound' required>");
								else
									print("<input type='number' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" required>");
							} else if(strcmp($type,"string") === 0) {
								print("<input type='text' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" maxlength='512' size='90' required>");
							} else if(strcmp($type,"email") === 0) {
								print("<input type='email' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" maxlength='512' size='90' required>");
							} else if(strcmp($type,"boolean") === 0) {
								if($value == '1')
									print("<input type='checkbox' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' checked required>");
								else
									print("<input type='checkbox' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' required>");
							}
						} else {
							if(strcmp($type,"integer") === 0) {
								if(!is_null($min_bound) && !is_null($max_bound))
									print("<input type='number' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" min='$min_bound' max='$max_bound'>");
								else if(!is_null($min_bound))
									print("<input type='number' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" min='$min_bound'>");
								else if(!is_null($max_bound))
									print("<input type='number' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" max='$max_bound'>");
								else
									print("<input type='number' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\">");
							} else if(strcmp($type,"string") === 0) {
								print("<input type='text' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" maxlength='512' size='90'>");
							} else if(strcmp($type,"email") === 0) {
								print("<input type='email' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' value=\"$value\" maxlength='512' size='90'>");
							} else if(strcmp($type,"boolean") === 0) {
								if($value == '1')
									print("<input type='checkbox' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT' checked>");
								else
									print("<input type='checkbox' name='VALUE/$setting/INPUT' id='VALUE/$setting/INPUT'>");
							}
						}
						
						print("			</id>
									</tr>
						");
						
						$i++;
					}					
					print("		</tbody>
							</table>								
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
		var form = document.getElementById("processOptionsUpdate");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.name.indexOf("VALUE") > -1) {
				var name = item.name;
				var id = name.substr(0,name.indexOf("/INPUT"));
				if (!validateInput(id))
					return false;
			}
		}		
		return true;
	}
	
	function updateOptions() {
		if (validateFormValues()) {
			var form = document.getElementById("processOptionsUpdate");
			form.submit();
		} else alert("Error validating data!\n\nPlease correct the items in red.");
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>