<!DOCTYPE html>
<!-- 
	Kiosk Checkout System (KCS). A simple web application for checking items in and out.
    Copyright (C) 2017 James Wilmoth

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->
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
	
	<link href="css/fatscrollbars.css" rel="stylesheet">
	
	<!-- Customizations to the options footer -->
	<script>
		$(document).ready(function() {
			$("#options-content").html("<a href='admin-auth.php'><button type='button' class='btn btn-lg btn-default'>Admin</button></a>");
		});
	</script>	
</head>

<body role="document">

<script>
	function clearCookies() {
		setCookie('kcs_user_name','',1);
		setCookie('kcs_user_email','',1);
		setCookie('kcs_option','',1);
		setCookie('kcs_category','',1);
		setCookie('kcs_item','',1);
		console.log("Cleared cookies!");
	}
</script>

<?php require("snippets/fixednavbar.php"); ?>
<br><br><br>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Users</h3>
	</div>
	<div class="panel-body">
		<div class="buttons_wrapper" id="default">
			<div class="row">
			<?php
				/* CONNECT TO DATABASE */	
				require("snippets/database.php");
						
				$result = false;	
				try {
						
					/* GET LIST OF USERS */
					$totalUsers = 0;
					
					$sql = "SELECT * FROM Users";
					$sth = $db_conn->prepare($sql);
					$sth->execute();
					$result = $sth->fetchAll();
					
					$totalUsers = count($result);
					if($totalUsers <= 1)
						print("<script>loadPage('initialization.php');</script>");
				}
				catch(PDOException $e) {
					die("There was a problem communicating with the database. Please contact the Webmaster.<br>" . $e->getMessage());
				}
					
				//$sql = "SELECT NAME,EMAIL,HIDE,ITEM FROM Users LEFT JOIN Items ON Users.EMAIL=Items.USER_EMAIL_FK";
				# ORDER BY RANKING    $users_sql = "SELECT NAME,EMAIL,HIDE FROM Users ORDER BY RANKING DESC";
				$users_sql = "SELECT NAME,EMAIL,HIDE FROM Users ORDER BY NAME ASC";
				$users_sth = $db_conn->prepare($users_sql);
				$users_sth->execute();
				$users = $users_sth->fetchAll();
					
				if($users) {
					foreach($users as $user) {
						$name = $user['NAME'];
						$email = $user['EMAIL'];
						$hide = $user['HIDE'];
						/** If this is not a hidden user... */
						if(!$hide) {
							/** Determine if this user just needs a check out button or both in and out... */
							$buttons_sql = "SELECT DISTINCT OPTION_FK,PATH,IMAGE,IMAGEHOVER,IMAGE_ALT,IMAGEHOVER_ALT FROM User_Options RIGHT JOIN Options ON User_Options.OPTION_FK=Options.OPTION WHERE EMAIL_FK='$email' ORDER BY OPTION_FK ASC";
							$buttons_sth = $db_conn->prepare($buttons_sql);
							$buttons_sth->execute();
							$buttons = $buttons_sth->fetchAll();
							$totalButtons = count($buttons);
							
							/** Two buttons look different than sign button... */
							if($totalButtons > 1) {
																		
								print("	<div class='col-xs-6 col-sm-3 col-md-2 col-lg-2'>
											<div class='panel panel-info'>
												<div class='panel-heading'>
													<h3 class='panel-title'><div class='wrap'>$name</div></h3>
												</div>
												<div class='panel-body'>
													<div style='text-align:center'>");
													foreach($buttons as $button) {
														$option = $button['OPTION_FK'];
														$path = $button['PATH'];
														$image = $button['IMAGE'];
														$imageHover = $button['IMAGEHOVER'];
														print("<img class='button_chkinot image-button' src='$image' onclick=\"selectUserOption('$name','$email','$option','$path'); loadPage('$path')\" onmouseover=\"this.src='$imageHover'\" onmouseout=\"this.src='$image'\">");
													}
								print("						
													</div>
												</div>
											</div>
										</div>");
								
							} else {

								$option = $buttons[0]['OPTION_FK'];
								$path = $buttons[0]['PATH'];
								$image = $buttons[0]['IMAGE_ALT'];
								$imageHover = $buttons[0]['IMAGEHOVER_ALT'];
														
								print("	<div class='col-xs-6 col-sm-3 col-md-2 col-lg-2'>
											<div class='panel panel-info' onclick=\"selectUserOption('$name','$email','$option','$path'); loadPage('$path')\" onmouseover=\"document.getElementById('$email/IMAGE').src='$imageHover'\" onmouseout=\"document.getElementById('$email/IMAGE').src='$image'\">
												<div class='panel-heading' id='light'>
													<h3 class='panel-title'><div class='wrap'>$name</div></h3>
												</div>
												<div class='panel-body'>
													<div style='text-align:center'>
														<img class='button_chkinot image-button2' id='$email/IMAGE' src='$image' >
													</div>
												</div>
											</div>
										</div>");
										
							}									
						}
					}
				}
				
				/* CLOSE DATABASE CONNECTION */
				$db_conn = null;
			?>
			</div>
		</div>
	</div>
</div>

<?php //require("snippets/options.php"); ?>

<script>
	function selectUser(name,email) {
		document.cookie="kcs_user_name=" + name;
		document.cookie="kcs_user_email=" + email;
	}	
	
	function selectOption(option,path) {
		document.cookie="kcs_option=" + option;
		document.cookie="kcs_option_path=" + path;
	}
	
	function selectUserOption(name,email,option,path) {
		document.cookie="kcs_user_name=" + name;
		document.cookie="kcs_user_email=" + email;
		document.cookie="kcs_option=" + option;
		document.cookie="kcs_option_path=" + path;
	}
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>