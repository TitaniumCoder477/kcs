<!-- Fixed navbar -->
<nav class='navbar navbar-inverse navbar-fixed-top'>
	<!-- <div class='container'> COMMENTED OUT TO KEEP LEFT AND RIGHT ITEMS IN PLACE-->
		<div class='navbar-header'>
			<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false' aria-controls='navbar'>
				<span class='sr-only'>Toggle navigation</span>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
				<span class='icon-bar'></span>
			</button>
			<a href='index.php'><img src='../images/home.svg' alt='Home' class='home-button'></a>
			<a href='items-viewcheckedout.php'><img src='../images/checkoutlist.svg' alt='Checkout List' class='checkoutlist-button'></a>
			<a class='navbar-brand' href='index.php'>Kiosk Checkout System</a>			
		</div>		
		<div id='navbar' class='navbar-collapse collapse' style='float:right;'>
			<ul class='nav navbar-nav'>
				<li></li>
				<li><a href='index.php'>Users</a></li>
				<li><a href='admin-auth.php'>Admin</a></li>
				<li class='dropdown'>
					<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>Help <span class='caret'></span></a>
					<ul class='dropdown-menu' role='menu'>
						<li><a href='about.php'>About</a></li>
					</ul>
				</li>
			</ul>
		</div><!--/.nav-collapse -->		
	<!-- </div> -->
</nav>