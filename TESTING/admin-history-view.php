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
			$('#historyTable').DataTable({
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
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > History > View");
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
				$sql = "SELECT * FROM History ORDER BY DATE DESC,TIME DESC";
				$sth = $db_conn->prepare($sql);
				$sth->execute();
				$result = $sth->fetchAll();

				if($result) {
					print("	<div class='page-header'>
								<h1>History</h1>
							</div>
							<div style='padding-left:10px;padding-right:10px;'>
								<table id='historyTable' class='table table-striped' width='100%'>
									<thead>
										<tr>
											<th>DATE</th>
											<th>TIME</th>
											<th>NAME</th>
											<th>EMAIL</th>
											<th>OPTION</th>
											<th>CATEGORY</th>
											<th>ITEM</th>
											<th>COMMENT</th>
										</tr>
									</thead>
									<tbody>"
					);
							foreach($result as $history_row) {
								$date = $history_row['DATE'];
								$time = $history_row['TIME'];
								$name = $history_row['NAME'];
								$email = $history_row['EMAIL'];
								$option = $history_row['OPTION'];
								$category = $history_row['CATEGORY'];
								$item = $history_row['ITEM'];
								$comment = $history_row['COMMENT'];
								print("	<tr>
											<td>$date</td>
											<td>$time</td>
											<td>$name</td>
											<td>$email</td>
											<td>$option</td>
											<td>$category</td>
											<td>$item</td>
											<td>$comment</td>
										</tr>"
								);
							}
					print("			</tbody>
								</table>
							</div>
					");
				} else {
					print("
						<div class='panel panel-info'>
							<div class='panel-heading'>
								<h3 class='panel-title'>History</h3>
							</div>
							<div class='panel-body'>
								<p id='noItems'>There is no history to display.</p>
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

<br><br>

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