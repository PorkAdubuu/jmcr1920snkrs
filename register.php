<?php
session_start();
require_once "database/connection.php";
require("user/function/check-login.php");

$isLoggedIn = 0;
if (!check_login_user_universal($link)) {
  
  $isLoggedIn = 0;
} else {
  $verifiedUID = $_SESSION['userid'];
  $isLoggedIn = 1;
  header("Location: user/shop");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

 


  $username = $_POST["signupusername"];
  $email = $_POST["email"];
  $password = $_POST["userpass"];


  $hashed_password = password_hash($password, PASSWORD_BCRYPT);


  $stmt = $link->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, $hashed_password);
  $stmt->execute();

  // Check if the registration was successful
  if ($stmt->affected_rows > 0) {
    // Registration success
    $new_user_id = $stmt->insert_id; // Get the ID of the newly inserted user
    $_SESSION['userid'] = $new_user_id; // Store the user ID in the session
    echo "<script>alert('Registration successful!');</script>";
    echo "<script>window.location.href = 'user/shop';</script>";
    exit;
  } else {
    // Registration failed
    echo "<script>alert('Registration failed. Please try again later.');</script>";
  }


  $stmt->close();

  $link->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Corona Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="admin/assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="admin/assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="admin/assets/css/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="admin/assets/images/favicon.png" />
</head>

<body>
  <?php
  include 'index-resources/pre-loader.html';
  ?>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="row w-100 m-0">
        <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
          <div class="card col-lg-4 mx-auto">
            <div class="card-body px-5 py-5">
              <h3 class="card-title text-left mb-3">Register</h3>
              <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control p_input" name="signupusername">
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control p_input" name="email">
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control p_input" id="passwordField" name="userpass">

                </div>
                <div class="form-group d-flex align-items-center justify-content-between">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" class="form-check-input"> Remember me </label>
                  </div>
                  <a href="#" class="forgot-pass">Forgot password</a>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary btn-block enter-btn">Register</button>
                </div>
                <div class="d-flex">
                  <button class="btn btn-facebook col mr-2">
                    <i class="mdi mdi-facebook"></i> Facebook </button>
                  <button class="btn btn-google col">
                    <i class="mdi mdi-google-plus"></i> Google plus </button>
                </div>
                <p class="sign-up text-center">Already have an Account?<a href="index"> Sign In</a></p>
                <p class="terms">By creating an account you are accepting our<a href="#"> Terms & Conditions</a></p>
              </form>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- row ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="admin/assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="admin/assets/js/off-canvas.js"></script>
  <script src="admin/assets/js/hoverable-collapse.js"></script>
  <script src="admin/assets/js/misc.js"></script>
  <script src="admin/assets/js/settings.js"></script>
  <script src="admin/assets/js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>