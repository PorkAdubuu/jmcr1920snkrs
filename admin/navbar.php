<?php

$getUser = "SELECT username FROM admin WHERE admin_id = ?";
$stmt = mysqli_prepare($link, $getUser);

// Bind the user ID parameter
mysqli_stmt_bind_param($stmt, "s", $verifiedUID);

// Execute the statement
mysqli_stmt_execute($stmt);

// Bind the result variable
mysqli_stmt_bind_result($stmt, $username);

// Fetch the result
mysqli_stmt_fetch($stmt);

// Free the result set
mysqli_stmt_free_result($stmt);

?>

<nav class="navbar p-0 fixed-top d-flex flex-row">
  <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
    <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../assets/images/logo-mini.svg" alt="logo" /></a>
  </div>
  <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
      <span class="mdi mdi-menu"></span>
    </button>
    <ul class="navbar-nav w-100">
      <li class="nav-item w-100">
        <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
          <input type="text" class="form-control" placeholder="Search">
        </form>
      </li>
    </ul>
    <ul class="navbar-nav navbar-nav-right">

      <li class="nav-item dropdown">
        <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
          <div class="navbar-profile">
            <img class="img-xs rounded-circle" src="../assets/images/faces/face15.jpg" alt="">
            <p class="mb-0 d-none d-sm-block navbar-profile-name"><span style="color: green;">ADMIN: </span> <?php echo $username ?></p>
            <i class="mdi mdi-menu-down d-none d-sm-block"></i>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
          <h6 class="p-3 mb-0">Profile</h6>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item preview-item">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-settings text-success"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <p class="preview-subject mb-1">Settings</p>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item preview-item" onclick="logoutConfirmation()">
            <div class="preview-thumbnail">
              <div class="preview-icon bg-dark rounded-circle">
                <i class="mdi mdi-logout text-danger"></i>
              </div>
            </div>
            <div class="preview-item-content">
              <form id="logoutForm" method="POST" action="function/logout.php">
                <p class="preview-subject mb-1">Log out</p>
              </form>
            </div>

          </a>
          <div class="dropdown-divider"></div>
          <p class="p-3 mb-0 text-center">Leviathan</p>
        </div>
      </li>
    </ul>
    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
      <span class="mdi mdi-format-line-spacing"></span>
    </button>
  </div>
</nav>

<script>
  function logoutConfirmation() {
    var confirmLogout = confirm("Are you sure? You are about to logout.");

    if (confirmLogout) {
      document.getElementById("logoutForm").submit();
    }
  }
</script>