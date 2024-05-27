<?php

function check_login($link)
{
    if (isset($_SESSION['admin_id'])) {
        // Sanitize the session value
        $fetched_uid = mysqli_real_escape_string($link, $_SESSION['admin_id']);

        // Prepare and execute the SQL query
        $checkQuery = "SELECT admin_id FROM admin WHERE admin_id = ?";
        $stmt = mysqli_prepare($link, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $fetched_uid);
        mysqli_stmt_execute($stmt);
    
        $result = mysqli_stmt_get_result($stmt);
    
        // Check if exactly one row is returned
        if (mysqli_num_rows($result) == 1) {
            $isLoggedIn = 1;
        } else {
            // Handle the case where user is not found or multiple rows are returned
             header("Location: ../login");
        }
    } else {
        // Handle the case where $_SESSION['userid'] is not set
        header("Location: ../login");
    }
}

// form validation function
function isUsernameExists($link, $username)
{

    $checkQuery = "SELECT * FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($link, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return (mysqli_num_rows($result) > 0);
}


function isEmailExists($link, $email)
{

    $checkQuery = "SELECT * FROM admin WHERE email = ?";
    $stmt = mysqli_prepare($link, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return (mysqli_num_rows($result) > 0);
}







//error message alert
function handleValidationError($errorMessage)
{
    
    echo "<script>
              var errorMessage = '" . $errorMessage . "';
              if (errorMessage) {
                  if (confirm(errorMessage)) {
                    history.back();
                  }
              }
            </script>";
            exit();
}

//success message alert
function handleValidationSuccess($successMessage)
{
    echo "<script>
          var successMessage = '" . $successMessage . "';
          if (successMessage) {
              if (confirm(successMessage)) {
                window.location.href = '../../index';
              }
          }
        </script>";

}

// generic message
function show_generic_message($message, $icon, $timer_duration = 2000) {
    
    echo '<script type="text/javascript">';
    echo 'document.addEventListener("DOMContentLoaded", function () {';
    echo 'alert("' . $message . '");';
    echo '});</script>';
}


//random number generator
function random_num($length)
{

    $text = "";
    if ($length < 5) {
        $length = 5;
    }

    $len = rand(4, $length);

    for ($i = 0; $i < $len; $i++) {


        $text .= rand(0, 9);
    }

    return $text;
}


?>



<!-- js function -->
<script>
    
</script>