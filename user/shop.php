<?php
session_start();
require_once '../database/connection.php';
require("function/check-login.php");

$isLoggedIn = 0;
if (!check_login_user_universal($link)) {

	$isLoggedIn = 0;
} else {
	$verifiedUID = $_SESSION['userid'];
	$isLoggedIn = 1;
}

$query_categories = "SELECT CategoryID, CategoryName FROM category";
$result_categories = mysqli_query($link, $query_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);


$query_products = "SELECT p.product_id, p.prod_name, p.Description, p.Price, p.ImageURL, c.CategoryName, pd.CategoryID, pd.isNew, pd.onDiscount, pd.Discount
                   FROM products p
                   INNER JOIN product_data pd ON p.product_id = pd.product_id
                   INNER JOIN category c ON pd.CategoryID = c.CategoryID";

$result_products = mysqli_query($link, $query_products);

// Fetch products and format the price
$products = [];
while ($row = mysqli_fetch_assoc($result_products)) {
	// Format price with comma
	$row['FormattedPrice'] = '₱' . number_format($row['Price'], 2); // 2 decimal places

	



	// Calculate discounted price
	if ($row['onDiscount']) {
		$discountedPrice = $row['Price'] - ($row['Price'] * ($row['Discount'] / 100));
		$row['DiscountedPrice'] = '₱' . number_format($discountedPrice, 2);
	}

	$products[] = $row;
}


$secret_key = 'vS8/yDzI70nsY0kOCcHxew==';

function generateToken($product_id, $secret_key)
{
	return hash_hmac('sha256', $product_id, $secret_key);
}

?>




<!DOCTYPE html>
<html lang="en">

<?php include("head.html"); ?>

<style>
	.discount,
	.new {
		position: absolute;
		top: 15px;
		left: 20px;
		color: #ffffff;
		background-color: #fe302f;
		padding: 2px 8px;
		text-transform: uppercase;
		font-size: 0.85rem;
	}

	.new {
		left: 0;
		background-color: #444444;
	}

	.old-price {
		text-decoration: line-through;
	}
</style>

<body>



	<?php

	include 'html/pre-loader.html';
	include("navbar.php");

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
	<!-- end search arewa -->

	<!-- breadcrumb-section -->
	<div class="breadcrumb-section breadcrumb-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="breadcrumb-text">
						<p>Step Into Style</p>
						<h1>Shop</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- products -->
	<div class="product-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="product-filters">
						<ul>
							<li class="active" data-filter="*">All</li>
							<?php foreach ($categories as $category) : ?>
								<li data-filter=".<?= $category['CategoryID'] ?>"><?= $category['CategoryName'] ?></li>
							<?php endforeach; ?>
						</ul>
					</div>

				</div>
			</div>

			<div class="row product-lists">
				<?php foreach ($products as $product) : ?>
					<?php
					$discount = $product['Discount'];
					$formatted_discount = rtrim(rtrim(number_format($discount, 2), '0'), '.');
					
					if (strpos($formatted_discount, '.') === false) {
						$formatted_discount = rtrim($formatted_discount, '.');
					}
					$product_id = $product['product_id'];
					$token = generateToken($product_id, $secret_key);
					?>
					<div class="col-lg-4 col-md-6 text-center <?= htmlspecialchars($product['CategoryID']) ?>">
						<div class="single-product-item">
							<?php if ($product['isNew']) : ?>
								<span class="new" style="margin-left: 5px">new</span>
							<?php endif; ?>
							<?php if ($product['onDiscount']) : ?>
								<span class="discount" style="margin-left: 25px"><?= htmlspecialchars($formatted_discount) ?>% Off</span>
							<?php endif; ?>
							<div class="product-image">
								<a href="single-product.php?product_id=<?= urlencode($product_id) ?>&token=<?= urlencode($token) ?>"><img src="<?= htmlspecialchars($product['ImageURL']) ?>" alt=""></a>
							</div>
							<h3><?= htmlspecialchars($product['prod_name']) ?></h3>
							<p class="product-price">
								<span><?= htmlspecialchars($product['CategoryName']) ?></span>
								<?php if ($product['onDiscount']) : ?>
									<span class="old-price"><?= htmlspecialchars($product['FormattedPrice']) ?></span>
									<?= htmlspecialchars($product['DiscountedPrice']) ?>
								<?php else : ?>
									<span>&nbsp;</span>
									<?= htmlspecialchars($product['FormattedPrice']) ?>
								<?php endif; ?>
							</p>
							<a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>




			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="pagination-wrap">
						<ul>
							<!-- Pagination links will be added dynamically -->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end products -->




	<?php
	include 'html/logo-brand.html';
	include 'html/footer.php';
	include 'html/copyright.html';


	include 'injectables.html';
	?>

	<script>
		window.onload = function() {
			var shopItem = document.getElementById("shop");
			if (shopItem) {
				shopItem.classList.add("current-list-item");
			}
		};
	</script>

</body>

</html>