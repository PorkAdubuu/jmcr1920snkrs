	<!-- header -->
	<div class="top-header-area" id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-sm-12 text-center">
					<div class="main-menu-wrap">
						<!-- logo -->
						<div class="site-logo">
							<a href="index">
								<img src="assets/img/logo.png" alt="">
							</a>
						</div>
						<!-- logo -->

						<!-- menu start -->
						<nav class="main-menu">
							<ul>
								<li class="current-list-item"><a href="#">Home</a>

								</li>
								<li><a href="#">About</a></li>


								<li><a href="#">Contact</a></li>
								<li id="shop"><a href="user/shop">Shop</a>
								</li>
								<li>
									<div class="header-icons">
										<?php if ($isLoggedIn === 1) {
										?>
											<a class="login-icon">
												<i class="fas fa-sign-out-alt" onclick="logoutConfirmation()">
													<form id="logoutForm" method="POST" action="user/function/logout.php">
													</form>
												</i>
												<a class="mobile-hide shopping-cart" href="user/503-cart"><i id="cart" class="fas fa-shopping-cart"></i></a>
											</a>
										<?php
										} else { ?>
											<a class="mobile-hide login-icon" data-toggle="modal" data-target="#loginModal"><i class="fas fa-user"></i></a>
											<!-- <a class="shopping-cart" href="cart"><i id="cart" class="fas fa-shopping-cart"></i></a> -->
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
							<a class="mobile-show shopping-cart" href="user/503-cart"><i id="cart" class="fas fa-shopping-cart"></i></a>
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


	<script>
		function logoutConfirmation() {
			var confirmLogout = confirm("Are you sure? You are about to logout.");

			if (confirmLogout) {
				document.getElementById("logoutForm").submit();
			}
		}
	</script>