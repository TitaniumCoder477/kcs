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
			$("#options-content").html("<button type='button' class='btn btn-lg btn-default' onclick='updateUsers()'>Process</button><a href='admin.php'><button type='button' class='btn btn-lg btn-default'>Cancel</button></a>");
			$("#admin@kioskcheckoutsystem.com/PIN/TD").tooltip();
			$("#admin@kioskcheckoutsystem.com/NAME/INPUT").tooltip();
			$("#admin@kioskcheckoutsystem.com/EMAIL/INPUT").tooltip();
			$("#admin@kioskcheckoutsystem.com/RANKING/INPUT").tooltip();
		});
	</script>
</head>

<body role="document">

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<form id="processUsersUpdate" action="admin-users-updatedelete-process.php" method="post">
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">
			<script>
				document.write("<a href='admin-logoff.php'>Log Off</a> > <a href='admin.php'>Admin</a> > Users > Update/Delete");
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
			
			/* GET LIST OF USERS */
			try {
				$sql = "SELECT * FROM Users ORDER BY NAME ASC";
				$sth = $db_conn->prepare($sql);
				$sth->execute();
				$result = $sth->fetchAll();
				
				if($result) {
					print("	<div class='page-header'>
								<h1>Users</h1>
							</div>
							<table class='table table-striped' id='userTable'>
								<thead>
									<tr>
										<th>NAME</th>
										<th>EMAIL</th>
										<th>PIN</th>
										<th>ADMIN</th>
										<th>HIDE</th>
										<th>RANKING</th>
										<th>DELETE?</th>
									</tr>
								</thead>
								<tbody id='usersTBody'>
					");
					$i = 0;
					foreach($result as $users_row) {
						$name = $users_row['NAME'];
						$email = $users_row['EMAIL'];
						$pin = $users_row['PIN'];
						$admin = $users_row['ADMIN'];
						$hide = $users_row['HIDE'];
						$ranking = $users_row['RANKING'];
						
						print("	<tr>
									<td style='display:none' id='$email/KEY/TD'><input type='text' name='" . str_replace(".","\\",$email) . "/KEY' value='$email' maxlength='45' size='45'></td>
						");						
						
						//Disable changing of Default Admin details (except email and PIN)
						if(strcmp($email,"admin@kioskcheckoutsystem.com") === 0) {
							print("		
										<td id='$email/NAME/TD'><input data-toggle='tooltip' title='Only the PIN can be changed for the default admin account.' type='text' name='" . str_replace(".","\\",$email) . "/NAME' id='$email/NAME/INPUT' value='$name' maxlength='25' size='25' placeholder='Name is required' onchange=\"validateName('$email')\" disabled></td>
										<td id='$email/EMAIL/TD'><input data-toggle='tooltip' title='Only the PIN can be changed for the default admin account.' type='email' name='" . str_replace(".","\\",$email) . "/EMAIL' id='$email/EMAIL/INPUT' value='$email' maxlength='45' size='45' placeholder='Email address is required' onchange=\"validateEmail('$email')\" disabled></td>
							");
							if($pin === "2007") {
								print("		
										<td id='$email/PIN/TD' data-toggle='tooltip' title='Default admin password! Please change!!' style='background-color:red;'><input type='password' name='" . str_replace(".","\\",$email) . "/PIN' id='$email/PIN/INPUT' value='$pin' maxlength='4' size='4' onchange=\"validatePIN('$email')\"></td>
								");
							} else {
								print("		
										<td id='$email/PIN/TD'><input type='password' name='" . str_replace(".","\\",$email) . "/PIN' id='$email/PIN/INPUT' value='$pin' maxlength='4' size='4' onchange=\"validatePIN('$email')\"></td>
								");
							}
							
							if($admin == '1')
								print("	<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/ADMIN' checked disabled></td>");
							else
								print("	<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/ADMIN' disabled></td>");						
						
							if($hide == '1')
								print("	<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/HIDE' checked disabled></td>");
							else
								print("	<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/HIDE' disabled></td>");
							
							print("		
										<td id='$email/RANKING/TD'><input data-toggle='tooltip' title='Only the PIN can be changed for the default admin account.' type='number' name='" . str_replace(".","\\",$email) . "/RANKING' id='$email/RANKING/INPUT' value='$ranking' min='0' max='100000' style='text-align: right;' \" disabled></td>
										<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/delete' disabled></td>
									</tr>
							");
						} else {
							print("		
										<td id='$email/NAME/TD'><input type='text' name='" . str_replace(".","\\",$email) . "/NAME' id='$email/NAME/INPUT' value='$name' maxlength='25' size='25' placeholder='Name is required' onchange=\"validateName('$email')\"></td>
										<td id='$email/EMAIL/TD'><input type='email' name='" . str_replace(".","\\",$email) . "/EMAIL' id='$email/EMAIL/INPUT' value='$email' maxlength='45' size='45' placeholder='Email address is required' onchange=\"validateEmail('$email')\"></td>
										<td id='$email/PIN/TD'><input type='password' name='" . str_replace(".","\\",$email) . "/PIN' id='$email/PIN/INPUT' value='****' maxlength='4' size='4' onchange=\"validatePIN('$email')\"></td>
							");
							
							if($admin == '1')
								print("	<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/ADMIN' checked></td>");
							else
								print("	<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/ADMIN'></td>");
							
							if($hide == '1')
								print("	<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/HIDE' checked></td>");
							else
								print("	<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/HIDE'></td>");
							
							print("		
										<td id='$email/RANKING/TD'><input type='number' name='" . str_replace(".","\\",$email) . "/RANKING' id='$email/RANKING/INPUT' value='$ranking' min='0' max='100000' style='text-align: right;' \"></td>
										<td><input type='checkbox' name='" . str_replace(".","\\",$email) . "/delete'></td>
									</tr>
							");
						}
						$i++;
					}
					
					print("		</tbody>
							</table>
								
					");
				}
			}
			catch(PDOException $e) {
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
		
	function validateName(id) {
		//console.log("ID = " + id);
		str = id + "/NAME/INPUT";
		var input = document.getElementById(str);
		str = id + "/NAME/TD";
		var td = document.getElementById(str);
		
		//console.log("input = " + input);
		//console.log("td = " + td);
		
		var result = (input.value.length != 0);
		td.style.backgroundColor = (result ? "white" : "red");
		
		return result;
	}
	
	function validateEmail(id) {
		str = id + "/EMAIL/INPUT";
		var input = document.getElementById(str);
		str = id + "/EMAIL/TD";
		var td = document.getElementById(str);
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;    
		
		var result = (re.test(input.value) && input.value.length != 0);
		td.style.backgroundColor = (result ? "white" : "red");
		
		return result;
	}
	
	function validatePIN(id) {
		str = id + "/PIN/INPUT";
		var input = document.getElementById(str);
		str = id + "/PIN/TD";
		var td = document.getElementById(str);		
		var pin = input.value;
		
		var isNum = /^\d+$/.test(pin);
		var result = (pin.length == 4 && isNum) || pin =='****';		
		td.style.backgroundColor = (result ? "white" : "red");
		
		return result;
	}
	
	function validateUsers() {	
		var form = document.getElementById("processUsersUpdate");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.type.indexOf("email") > -1) {
				var name = item.name;
				var email = name.substr(0,name.indexOf("/EMAIL")).replace("\\com",".com");
				if (!validateName(email) || !validateEmail(email) || !validatePIN(email))
					return false;
			}
		}		
		return true;
	}
	
	function deleteUsers() {
		var form = document.getElementById("processUsersUpdate");
		for (var i=0; i<form.length; i++) {
			var item = form.elements.item(i);
			if (item.type.indexOf("checkbox") > -1 && item.name.indexOf("/delete") > -1 && item.checked) {
				return confirm("One or more users are selected for permanent deletion!\n\nPress CANCEL to make sure this is correct.");					
			}
		}		
		return true;
	}
	
	function updateUsers() {
		if (validateUsers()) {
			if (deleteUsers()) {
				var form = document.getElementById("processUsersUpdate");
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