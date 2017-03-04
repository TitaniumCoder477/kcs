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
		<h3 class="panel-title">Initialization</h3>
	</div>
	<div class="panel-body">
		<script type='text/javascript'>clearCookies();</script>
		<div class='panel panel-info'>
			<div class='panel-heading'>
				<h3 class='panel-title'><div class='wrap'>Welcome</div></h3>
			</div>
			<div class='panel-body'>
				Welcome to Kiosk Checkout System! The database is basically empty, so this must be the first time you've used KCS. You need to follow some steps to get up and running.
				<br><br><ol>
					<li>Create some categories</li>
					<li>Create some items</li>
					<li>Create some users</li>
					<li>Start using!</li>
				</ol><br>
				To complete these steps, please click the Admin button in the top-right corner and sign in with these default credentials.<br><br>
				<em>*** For security, you should immediately change the default PIN. ***</em>
				<br><br>				
				Admin username: <b>admin@kioskcheckoutsystem.com</b><br>
				Admin PIN code: <b>2007</b>
				<br><br>				
				I hope you find this software very useful!
				<br><br>				
				James Wilmoth<br><br>
				<i>"Behold, I am the Lord, the God of all flesh. Is anything too hard for me?" - Jeremiah 32:27 ESV</i>
				<hr>
				Additional resources:
				<br><br><ul>
					<li>Main site (<a href="http://kioskcheckoutsystem.com">http://kioskcheckoutsystem.com</a>)</li>
					<li>FAQ (<a href="http://kioskcheckoutsystem.com/#faq">http://kioskcheckoutsystem.com/#faq</a>)</li>
					<!-- THESE SITES WERE TAKEN DOWN WHEN I DECIDED TO RELEASE KCS AS OSS
					<li>Support (<a href="http://support.kioskcheckoutsystem.com">http://support.kioskcheckoutsystem.com</a>)</li>
					<li>KCS Overview (<a href="http://support.kioskcheckoutsystem.com/t/welcome-to-kcs-support/">http://support.kioskcheckoutsystem.com/t/welcome-to-kcs-support/</a>)</li>
					<li>KCS Getting Started (<a href="http://support.kioskcheckoutsystem.com/t/getting-started/19">http://support.kioskcheckoutsystem.com/t/getting-started/19</a>)</li>
					<li>KCS Administrative Features (<a href="http://support.kioskcheckoutsystem.com/t/administrative-features/20">http://support.kioskcheckoutsystem.com/t/administrative-features/20</a>)</li>
					-->
				</ul>
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
</script>

</body>

<footer>
<?php require("snippets/footer.php"); ?>
</footer>

</html>