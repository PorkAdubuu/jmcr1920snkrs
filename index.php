<?php
session_start();
require_once 'database/connection.php';
require("user/function/check-login.php");

$isLoggedIn = 0;
if (!check_login_user_universal($link)) {

	$isLoggedIn = 0;
} else {
	$verifiedUID = $_SESSION['userid'];
	$isLoggedIn = 1;
}

?>
<!DOCTYPE html>
<html lang="en">

<?php 

include 'index-resources/head.html'; 
include 'index-resources/navbar.php'; 

?>
<style>

</style>

<body>

	<?php
	include 'index-resources/pre-loader.html';
	?>



	<!-- search area -->
	<div class="search-area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<span class="close-btn"><i class="fas fa-window-close"></i></span>
					<div class="search-bar">
						<div class="search-bar-tablecell">
							<h3>Search For:</h3>
							<input type="text" placeholder="Keywords">
							<button type="submit">Search <i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end search area -->

	<!-- hero area -->
	<div class="hero-area hero-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 offset-lg-2 text-center">
					<div class="hero-text">
						<div class="hero-text-tablecell">
							<p class="subtitle">TOP SNEAKER SPOT</p>
							<h1>Elevate your sneaker style with our collection</h1>
							<div class="hero-btns">
								<a href="user/shop" class="bordered-btn">Shop Now</a>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end hero area -->

	
	<?php 
	include 'index-resources/feature-list.php'; 
	include 'index-resources/new-arival.php'; 
	include 'index-resources/sale-of-month.php'; 
	include 'index-resources/testimonial.php'; 
	include 'index-resources/special-sale-monthly.php'; 
	include 'index-resources/logo-brand.html'; 
	include 'index-resources/footer.php'; 
	include 'index-resources/copyright.html'; 


	include 'index-resources/injectables.html'; 
	?>


	
	


	

</body>
<?php

if ($isLoggedIn === 1) {
	// Code to execute if user is logged in
} else {
	include("index-resources/modal/signup-modal.html");
	include("index-resources/modal/user-login-modal.html");
	include("index-resources/modal/admin-login-modal.html");
}
?>

</html>