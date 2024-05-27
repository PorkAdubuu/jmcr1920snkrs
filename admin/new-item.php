<html lang="en">
<?php
session_start();
require_once("../database/connection.php");
require_once("function/admin-function.php");
require("function/check-login.php");
include("head.html");


check_login_user_universal($link);
if (!check_login_user_universal($link)) {
    header("Location: index");
    exit;
} else {
    $verifiedUID = $_SESSION['admin_id'];
}

$query_categories = "SELECT CategoryID, CategoryName FROM category";
$result_categories = mysqli_query($link, $query_categories);
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);


$query_size = "SELECT size_id, sizes_all FROM sizes";
$result_size = mysqli_query($link, $query_size);
$sizes = mysqli_fetch_all($result_size, MYSQLI_ASSOC);

?>

<body>

    <style>
        .discount-input {
            display: none;
            margin-top: 10px;
            margin-bottom: 10px;
            width: 50%;
        }
    </style>

    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        <?php include("sidebar.php"); ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            <?php include("navbar.php") ?>
            <!-- partial -->
            <div class="main-panel">

                <div class="content-wrapper">



                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">new product</h4>
                                    <p class="card-description"> *Use 500x500px image size only! </p>
                                    <form id="newItemForm" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="itemName">Item Name</label>
                                            <input type="text" class="form-control" id="itemName" name="itemName" placeholder="Name" style="color: white;">
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4" style="color: white;"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Available Size</label>
                                            <select class="js-example-basic-multiple" multiple="multiple" name="sizes[]" style="width:100%">
                                                <?php foreach ($sizes as $sizes) : ?>
                                                    <option value="<?= $sizes['sizes_all'] ?>"><?= $sizes['sizes_all'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="number" min="0" class="form-control" id="price" name="price" placeholder="Price" style="color: white;">
                                        </div>


                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select class="form-control" id="category" name="category" style="color: white;">
                                                <?php foreach ($categories as $category) : ?>
                                                    <option value="<?= $category['CategoryID'] ?>"><?= $category['CategoryName'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>File upload</label>
                                            <input type="file" name="img[]" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image" name="img">
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-check mx-sm-2">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" id="sale-checkbox" name="sale-checkbox"> On Sale
                                            </label>
                                        </div>
                                        <div class="discount-input" id="discount-input">
                                            <label for="discount-percentage">Discount Percentage:</label>
                                            <input type="number" id="discount-percentage" class="form-control" name="discount-percentage" min="0" max="100" step="1">
                                        </div>
                                        <div id="formMessage"></div>
                                        <button type="submit" id="submitBtn" class="btn btn-primary mr-2">Submit</button>
                                        <button class="btn btn-dark" onclick="windows.location.relaod();">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function() {
                                $('#sale-checkbox').change(function() {
                                    if ($(this).is(':checked')) {
                                        $('#discount-input').slideDown();
                                    } else {
                                        $('#discount-input').slideUp();
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <?php include("footer.html") ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->


    <?php include("assets/injectables.html"); ?>


    <script>
        $(document).ready(function() {
            $('#submitBtn').click(function() {
                // Disable the submit button to prevent multiple submissions
                $(this).prop('disabled', true);

                // Serialize the form data
                var formData = new FormData($('#newItemForm')[0]);

                // Send AJAX request
                $.ajax({
                    url: 'function/process-new-item.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Display success message as a pop-up
                        alert('New product has been added successfully!');

                        // Reset the form instantly
                        $('#newItemForm')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        // Display error message as a pop-up
                        alert('Error: ' + error);
                    },
                    complete: function() {
                        // Enable the submit button after request completes
                        $('#submitBtn').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>

</html>