<?php

function check_login_user_universal($link)
{
    if (isset($_SESSION['userid'])) {
        // Sanitize the session value
        $fetched_uid = mysqli_real_escape_string($link, $_SESSION['userid']);

        // Prepare and execute the SQL query
        $checkQuery = "SELECT userid FROM users WHERE userid = ?";
        $stmt = mysqli_prepare($link, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $fetched_uid);
        mysqli_stmt_execute($stmt);
    
        $result = mysqli_stmt_get_result($stmt);
    
        // Check if exactly one row is returned
        if (mysqli_num_rows($result) == 1) {
            return true; // User is logged in and verified
        } else {
            return false; // User is not found or multiple rows are returned
        }
    } else {
        return false; // $_SESSION['userid'] is not set
    }
}

?>