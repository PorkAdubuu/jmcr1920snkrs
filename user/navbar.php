<!-- header -->
<div class="top-header-area" id="sticker">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-sm-12 text-center">
				<div class="main-menu-wrap">
					<!-- logo -->
					<div class="site-logo">
						<a href="../index">
							<img src="../assets/img/logo.png" alt="">
						</a>
					</div>
					<!-- logo -->

					<!-- menu start -->
					<nav class="main-menu">
						<ul>
							<li><a href="../index">Home</a>

							</li>
							<li><a href="about.html">About</a></li>


							<li><a href="contact.html">Contact</a></li>
							<li id="shop"><a href="shop">Shop</a>
							</li>
							<li>
								<div class="header-icons">
									<?php if ($isLoggedIn === 1) {
									?>
										<a class="login-icon">
											<i class="fas fa-sign-out-alt" onclick="logoutConfirmation()">
												<form id="logoutForm" method="POST" action="function/logout.php">
												</form>
											</i>
											<a class="mobile-hide shopping-cart" href="503-cart"><i id="cart" class="fas fa-shopping-cart"></i></a>
										</a>
									<?php
									} else { ?>
										<a class="mobile-hide login-icon" data-toggle="modal" data-target="#loginModal"><i class="fas fa-user"></i></a>
										
									<?php
									}
									?>

									<a class="mobile-hide search-bar-icon" href="#"><i class="fas fa-search"></i></a>

								</div>
							</li>
						</ul>
					</nav>
					<?php if ($isLoggedIn === 1) {
					?>
						<a class="mobile-show shopping-cart" href="503-cart"><i id="cart" class="fas fa-shopping-cart"></i></a>
					<?php
					} else { ?>
						<a class="mobile-show login-icon" data-toggle="modal" data-target="#loginModal"><i class="fas fa-user"></i></a>
					<?php
					}
					?>
					<a class="mobile-show search-bar-icon" href="#"><i class="fas fa-search"></i></a>
					<div class="mobile-menu"></div>
					<!-- menu end -->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end header -->


<!-- modal -->
<?php

if ($isLoggedIn === 1) {
	// Code to execute if user is logged in
} else {
	include("html/modal/signup-modal.html");
	include("html/modal/user-login-modal.html");
	include("html/modal/admin-login-modal.html");
}
?>

<!-- modal end -->

<script>
  function logoutConfirmation() {
    var confirmLogout = confirm("Are you sure? You are about to logout.");

    if (confirmLogout) {
      document.getElementById("logoutForm").submit();
    }
  }
</script>