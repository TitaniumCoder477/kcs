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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='processChanges()' id='processBtn'>Process</button><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Return to admin</button></a>");
			if($("#noItems").length) {
				$("#processBtn").hide();
			}
		});
	</script>
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<form id="processChanges" action="admin-items-viewcheckedout-process.php" method="post">
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<script>
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Items > View checked out");
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
			
			/* GET HISTORY */
			try {
				$items_sql = "SELECT * FROM Items WHERE NOT USER_EMAIL_FK='admin@kioskcheckoutsystem.com' ORDER BY DATE_OUT DESC,CATEGORY_FK DESC";
				$items_sth = $db_conn->prepare($items_sql);
				$items_sth->execute();
				$items_result = $items_sth->fetchAll();
				
				/* EXPECTED DATA FORMAT
				*
				*   EACH ROW HAS THE FOLLOWING:
				*
				*	INPUT NAME					INPUT VALUE
				*	category_key/{i}/INPUT		{$category} = Computers									# Original value
				*	item_key/{i}/INPUT			{$item} = Laptop01										# Original value
				*	user_key/{i}/INPUT			{$user_email} = jwilmoth@biztechnologysolutions			# Original value
				*	email_action/{i}/INPUT		{} = on													# Action value
				*	hold/{i}/INPUT				{$hold} = on											# New values
				*	checkin_action/{i}/INPUT	{} = on													# Action values
				*
				*   - EACH ROW IS UNIQUE BECAUSE OF THE INDEX VALUE.
				*	- IF DELETE IS NOT CHECKED FOR A ROW, THEN IT DOES NOT HAVE A DELETE INPUT VALUE.
				*/

				if($items_result) {
					print("	<div class='page-header'>
								<h1>Checked out items</h1>
							</div>
								<table id='checkedOutTable' class='table table-striped' width='100%'>
									<thead>
										<tr>
											<th>CATEGORY</th>
											<th>ITEM</th>
											<th>USER</th>
											<th>DATE OUT</th>
											<th>DAYS OUT</th>
											<th>EMAIL?</th>											
											<th>HOLD?</th>
											<th>CHECK IN?</th>
										</tr>
									</thead>
									<tbody>"
					);
					
					$i = 0;
					foreach($items_result as $checked_out_item_row) {
						$category = $checked_out_item_row['CATEGORY_FK'];
						$item = $checked_out_item_row['ITEM'];
						$user_email = $checked_out_item_row['USER_EMAIL_FK'];
						$date_out = $checked_out_item_row['DATE_OUT'];
						
						$dtout = date_create_from_format('Y-m-d',$date_out);
						$dttoday = date_create();								
						$dysoutinterval = date_diff($dtout,$dttoday);
						
						$days_out = $dysoutinterval->format('%a');
						
						print("			<tr>
											<td style='display:none' id='category_key/$i/TD'>			<input type='text' name='category_key/$i/INPUT' id='category_key/$i/INPUT' value=\"$category\" maxlength='45' size='45'></td>
											<td style='display:none' id='item_key/$i/TD'>				<input type='text' name='item_key/$i/INPUT' id='item_key/$i/INPUT' value=\"$item\" maxlength='45' size='45'></td>
											<td style='display:none' id='user_key/$i/TD'>				<input type='email' name='user_key/$i/INPUT' id='user_key/$i/INPUT' value=\"$user_email\" maxlength='45' size='45'></td>
											<td>$category</td>
											<td>$item</td>
											<td>$user_email</td>
											<td style='text-align:center'>$date_out</td>
											<td style='text-align:center'>$days_out</td>
											<td style='text-align:center' id='email_action/$i/TD'>		<input type='checkbox' name= 'email_action/$i/INPUT' id='email_action/$i/INPUT'></td>
											<td style='text-align:center' id='hold/$i/TD'>				<input type='checkbox' name= 'hold/$i/INPUT' id='hold/$i/INPUT' disabled></td>
											<td style='text-align:center' id='checkin_action/$i/TD'>	<input type='checkbox' name= 'checkin_action/$i/INPUT' id='checkin_action/$i/INPUT' disabled></td>
										</tr>"
						);								
						$i++;
					}
					print("			</tbody>
								</table>
					");
				} else {
					print("
						<div class='panel panel-info'>
							<div class='panel-heading'>
								<h3 class='panel-title'>Items</h3>
							</div>
							<div class='panel-body'>
								<p id='noItems'>No items are checked out.</p>
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
	function selectCategory(category,description) {
		document.cookie="kcs_category=" + category;
		document.cookie="kcs_category_description=" + description;
	}
	
	function processChanges() {
		var form = document.getElementById("processChanges");
		form.submit();
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>