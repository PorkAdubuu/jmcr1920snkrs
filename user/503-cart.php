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
                        <h1>503 - Unavailable</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- error section -->
    <div class="full-height-section error-section">
        <div class="full-height-tablecell">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 text-center">
                        <div class="error-text">
                            <i class="far fa-sad-cry"></i>
                            <h1>Oops! Not Found.</h1>
                            <p>The page you requested for is under construction.</p>
                            <a href="../index" class="boxed-btn">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end error section -->



    <?php
    include 'html/logo-brand.html';
    include 'html/footer.php';
    include 'html/copyright.html';


    include 'injectables.html';
    ?>

<script>
		window.onload = function() {
			var cartIcon = document.getElementById("cart");
			if (cartIcon) {
				cartIcon.style.color = "#F28123";
			}
		};
	</script>

</body>

</html>