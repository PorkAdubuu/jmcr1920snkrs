<?php

function check_login($link)
{
    if (isset($_SESSION['userid'])) {
        // Sanitize the session value
        $fetched_uid = mysqli_real_escape_string($link, $_SESSION['userid']);

        // Prepare and execute the SQL query to check if the user exists
        $checkQuery = "SELECT userid FROM users WHERE userid = ?";
        $stmt = mysqli_prepare($link, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $fetched_uid);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        // Check if exactly one row is returned
        if (mysqli_num_rows($result) == 1) {
            // User is verified
            $isLoggedIn = 1;

            // Assign the fetched user ID to verifiedUID
            $verifiedUID = 901;
        } else {
            // Handle the case where user is not found or multiple rows are returned
            header("Location: ../index");
        }
    } else {
        // Handle the case where $_SESSION['userid'] is not set
        header("Location: ../index");
    }
}

function generateOTP($length = 4)
{
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}







// form validation function
function isUsernameExists($link, $username)
{

    $checkQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($link, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return (mysqli_num_rows($result) > 0);
}


function isEmailExists($link, $email)
{

    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($link, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return (mysqli_num_rows($result) > 0);
}

function isKeypassExist($link, $keyPass)
{
    // Prepare the query to check if the keypass exists
    $checkQuery = "SELECT * FROM keypass WHERE keypass = ?";
    $stmt = mysqli_prepare($link, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $keyPass);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if no rows are returned (keypass does not exist)
    return (mysqli_num_rows($result) == 0);
}


function isKeypassExpired($link, $keyPass)
{
    // Prepare the query to check if the keypass is expired
    $checkQuery = "SELECT * FROM keypass WHERE keypass = ? AND isExpired = 1";
    $stmt = mysqli_prepare($link, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $keyPass);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return (mysqli_num_rows($result) > 0);
}

function isKeypassUsed($link, $keyPass)
{
    // Prepare the query to check if the keypass is expired
    $checkQuery = "SELECT * FROM keypass WHERE keypass = ? AND isUsed = 1";
    $stmt = mysqli_prepare($link, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $keyPass);
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
function show_generic_message($message, $icon, $timer_duration = 2000)
{

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