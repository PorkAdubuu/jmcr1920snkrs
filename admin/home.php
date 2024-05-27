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


?>

<body>



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
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Status<span class="mdi mdi-information-outline" title="Overall Sale Performance Summary" style="font-size: small;"></span></h4>
                  <canvas id="transaction-history" class="transaction-chart"></canvas>
                  <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                    <div class="text-md-center text-xl-left">
                      <h6 class="mb-1">Orders</h6>
                      <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                    </div>
                    <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                      <h6 class="font-weight-bold mb-0">32</h6>
                    </div>
                  </div>
                  <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                    <div class="text-md-center text-xl-left">
                      <h6 class="mb-1">Pending Shipment</h6>
                      <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
                    </div>
                    <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                      <h6 class="font-weight-bold mb-0">12</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-row justify-content-between">
                    <h4 class="card-title mb-1">Sale Performance Summary</h4>

                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="preview-list">
                        <div class="preview-item border-bottom">
                          <div class="preview-thumbnail">
                            <div class="preview-icon bg-primary">
                              <i class="mdi mdi-book-open-page-variant"></i>
                            </div>
                          </div>
                          <div class="preview-item-content d-sm-flex flex-grow">
                            <div class="flex-grow">
                              <h6 class="preview-subject">Performace Chart</h6>
                              <!-- message -->
                              <p class="text-muted mb-0">As of 05/14/2024, 03:00 PM</p>
                              <!-- end -->
                            </div>
                            <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                              <p class="text-muted mb-0">Category </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

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
</body>

</html>