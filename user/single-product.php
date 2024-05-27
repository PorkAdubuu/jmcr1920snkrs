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




$secret_key = 'vS8/yDzI70nsY0kOCcHxew==';

function generateToken($product_id, $secret_key)
{
	return hash_hmac('sha256', $product_id, $secret_key);
}

if (isset($_GET['product_id']) && isset($_GET['token'])) {
	$product_id = $_GET['product_id'];
	$token = $_GET['token'];

	// Validate and sanitize the product_id
	if (filter_var($product_id, FILTER_VALIDATE_INT)) {
		$product_id = intval($product_id);
		$expected_token = generateToken($product_id, $secret_key);


		// singe-product details
		// Initialize an array to store product data
		$productData = [];

		// SQL query to fetch single product data
		$query_product = "SELECT p.product_id, p.prod_name, p.Description, p.Sizes, p.Price, p.ImageURL, c.CategoryName, pd.CategoryID, pd.isNew, pd.onDiscount, pd.Discount
                  FROM products p
                  INNER JOIN product_data pd ON p.product_id = pd.product_id
                  INNER JOIN category c ON pd.CategoryID = c.CategoryID
                  WHERE p.product_id = $product_id";

		$result_product = mysqli_query($link, $query_product);

		if ($result_product) {
			if (mysqli_num_rows($result_product) > 0) {
				// Fetch the result row
				$productData = mysqli_fetch_assoc($result_product);

				// Format the price with comma
				$productData['FormattedPrice'] = '₱' . number_format($productData['Price'], 2); // 2 decimal places

				// Calculate discounted price if applicable
				if ($productData['onDiscount']) {
					$discountedPrice = $productData['Price'] - ($productData['Price'] * ($productData['Discount'] / 100));
					$productData['DiscountedPrice'] = '₱' . number_format($discountedPrice, 2);
				}
			} else {
				// Handle case where no product is found
				$productData['error'] = "No product found for the given product ID.";
			}
		} else {
			// Handle query error
			$productData['error'] = "Error executing query: " . mysqli_error($link);
		}

		$productName = $productData['prod_name'];
		$productDescription = $productData['Description'];
		$productPrice = $productData['Price'];
		$formattedOrigPrice = '₱' . number_format($productPrice, 2);
		$productImageURL = $productData['ImageURL'];
		$productDiscountedPrice = isset($productData['DiscountedPrice']) ? $productData['DiscountedPrice'] : null;




		if (hash_equals($expected_token, $token)) {



			$queryGetProdCategory = "SELECT CategoryID FROM product_data WHERE product_id = $product_id";
			$result_queryGetProdCategory = mysqli_query($link, $queryGetProdCategory);

			if ($result_queryGetProdCategory) {
				if (mysqli_num_rows($result_queryGetProdCategory) > 0) {
					// Fetch the result row
					$row = mysqli_fetch_assoc($result_queryGetProdCategory);
					$category_id = $row['CategoryID'];
				}
			}



			// Fetch the CategoryID for the given product_id
			$queryGetProdCategory = "SELECT CategoryID FROM product_data WHERE product_id = $product_id";
			$result_queryGetProdCategory = mysqli_query($link, $queryGetProdCategory);

			if ($result_queryGetProdCategory) {
				if (mysqli_num_rows($result_queryGetProdCategory) > 0) {
					// Fetch the result row
					$row = mysqli_fetch_assoc($result_queryGetProdCategory);
					$category_id = $row['CategoryID'];

					// Fetch products that match the fetched CategoryID and exclude the current product_id
					$query_products = "SELECT p.product_id, p.prod_name, p.Description, p.Price, p.ImageURL, c.CategoryName, pd.CategoryID, pd.isNew, pd.onDiscount, pd.Discount
                           FROM products p
                           INNER JOIN product_data pd ON p.product_id = pd.product_id
                           INNER JOIN category c ON pd.CategoryID = c.CategoryID
                           WHERE pd.CategoryID = $category_id AND p.product_id != $product_id";

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

						$category_name = $row['CategoryName'];

						$products[] = $row;
					}


					// Query to fetch category name for single product
					$query_category = "SELECT CategoryName FROM category WHERE CategoryID = $category_id";
					$result_category = mysqli_query($link, $query_category);

					if ($result_category && mysqli_num_rows($result_category) > 0) {
						// Fetch the category name
						$row_category = mysqli_fetch_assoc($result_category);
						$category_name_single = $row_category['CategoryName'];
					} else {
						// Handle case where category name is not found
						$category_name_single = "Unknown Category";
					}
				} else {
					// Handle case where no category is found for the given product ID
					echo "No category found for the given product ID.";
					exit;
				}
			} else {
				// Handle query error
				echo "Error executing query: " . mysqli_error($link);
				exit;
			}
		} else {
			// Handle invalid token
			echo "Invalid token.";
			exit;
		}
	} else {
		// Handle invalid product_id
		echo "Invalid product ID.";
		exit;
	}
} else {
	// Handle missing product_id or token
	echo "Product ID or token is missing.";
	exit;
}



?>
<!DOCTYPE html>
<html lang="en">

<?php require 'head.html'; ?>


<style>
	.size-box {
		display: inline-block;
		width: 40px;
		height: 40px;
		margin: 5px;
		border: 2px solid #ddd;
		cursor: pointer;
		text-align: center;
		line-height: 40px;
		font-weight: bold;
	}


	.size-box.selected {
		border-color: #000;
	}

	.sizes {
		margin-top: 10px;
	}



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
	<script>
		function selectBox(element) {
			let siblings = element.parentNode.children;
			for (let i = 0; i < siblings.length; i++) {
				siblings[i].classList.remove('selected');
			}
			element.classList.add('selected');
		}
	</script>

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
						<p>See more Details</p>
						<h1>Single Product</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end breadcrumb section -->

	<!-- single product -->
	<div class="single-product mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<div class="single-product-img">
						<img src="<?= $productImageURL ?>" alt="">
					</div>
				</div>
				<div class="col-md-7">
					<div class="single-product-content">
						<h3><?= $productName ?></h3>

						<p class="single-product-pricing"><span>Size: </span></p>
						<div class="sizes">
							<?php
							// Check if SizesArray exists and is an array
							if (isset($productData['Sizes']) && is_string($productData['Sizes'])) {
								// Split the string into an array of sizes
								$sizesArray = explode(',', $productData['Sizes']);

								// Loop through each size and create a size box
								foreach ($sizesArray as $size) {
									echo "<div class='size-box' onclick='selectBox(this)'>$size</div>";
								}
							} else {
								// Handle case where Sizes column is not set or not a string
								echo "<div>No sizes available</div>";
							}
							?>
						</div>



						<?php if ($productDiscountedPrice !== null) : ?>
							<p class="single-product-pricing">
								<span style="text-decoration: line-through;"><?= $formattedOrigPrice ?></span>
								<?= $productDiscountedPrice ?>
							</p>
						<?php else : ?>
							<p class="single-product-pricing"><?= $formattedOrigPrice ?></p>
						<?php endif; ?>
						<p><?= $productDescription ?></p>
						<div class="single-product-form">
							<form action="index.html">
								<input type="number" placeholder="0">
							</form>
							<a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
							<p><strong>Categories: </strong><?= $category_name_single ?></p>
						</div>
						<h4>Share:</h4>
						<ul class="product-share">
							<li><a href=""><i class="fab fa-facebook-f"></i></a></li>
							<li><a href=""><i class="fab fa-twitter"></i></a></li>
							<li><a href=""><i class="fab fa-google-plus-g"></i></a></li>
							<li><a href=""><i class="fab fa-linkedin"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- end single product -->


	<!-- more products -->
	<!-- products -->
	<div class="product-section mt-150 mb-150">
		<div class="container">
			<div class="row">

			</div>

			<div class="row product-lists">
				<?php foreach ($products as $product) : ?>
					<?php
					$product_id = $product['product_id'];
					$token = generateToken($product_id, $secret_key);
					?>
					<div class="col-lg-4 col-md-6 text-center <?= htmlspecialchars($product['CategoryID']) ?>">
						<div class="single-product-item">
							<?php if ($product['isNew']) : ?>
								<span class="new" style="margin-left: 5px">new</span>
							<?php endif; ?>
							<?php if ($product['onDiscount']) : ?>
								<span class="discount" style="margin-left: 25px"><?= htmlspecialchars($product['Discount']) ?>% Off</span>
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
	<!-- end more products -->




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