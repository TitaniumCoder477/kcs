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
			$('#checkedOutTable').DataTable({
				"order": [[ 3, "desc" ],[ 0, "desc" ]],
				"lengthMenu": [[10,25,50,-1],[10,25,50,"All"]],
				"pageLength": 25
			});
		});
	</script>
	<!-- Customizations to the options footer -->
	<script>
		$(document).ready(function() {
			$("#options-content").html("<a href='index.php'><button type='button' class='btn btn-lg btn-default'>Back</button></a>");
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
				document.write("<a href='index.php'>Users</a> > View checked out");
			</script>	
			<?php require("snippets/return-timer.php"); ?>
		</h3>
	</div>
	<div class='panel-body'>
	<?php
		/* CONNECT TO DATABASE */	
		require("snippets/database.php");
					
		/* GET HISTORY */
		try {
			$sql = "SELECT * FROM Items WHERE NOT USER_EMAIL_FK='admin@kioskcheckoutsystem.com' ORDER BY DATE_OUT DESC,CATEGORY_FK DESC";
			$sth = $db_conn->prepare($sql);
			$sth->execute();
			$result = $sth->fetchAll();

			if($result) {
				print("	<div class='page-header'>
							<h1>Checked out items</h1>
						</div>
						<div style='padding-left:10px;padding-right:10px;'>
							<table id='checkedOutTable' class='table table-striped' width='100%'>
								<thead>
									<tr>
										<th>CATEGORY</th>
										<th>ITEM</th>
										<th>USER</th>
										<th>DATE OUT</th>
										<th>DAYS OUT</th>
									</tr>
								</thead>
								<tbody>"
				);
						foreach($result as $checked_out_item_row) {
							$category = $checked_out_item_row['CATEGORY_FK'];
							$item = $checked_out_item_row['ITEM'];
							$user_email = $checked_out_item_row['USER_EMAIL_FK'];
							$date_out = $checked_out_item_row['DATE_OUT'];
							
							$dtout = date_create_from_format('Y-m-d',$date_out);
							$dttoday = date_create();								
							$dysoutinterval = date_diff($dtout,$dttoday);
							
							$days_out = $dysoutinterval->format('%a');
							
							print("	<tr>
										<td>$category</td>
										<td>$item</td>
										<td>$user_email</td>
										<td>$date_out</td>
										<td style='text-align:right'>$days_out</td>
									</tr>"
							);
						}
				print("			</tbody>
							</table>
						</div>"
				);
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
		
		/* CLOSE DATABASE CONNECTION */
		$db_conn = null;
	?>		
	</div>
</div>

<?php require("snippets/options.php"); ?>
<?php require("snippets/return-timer-activator.php"); ?>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>