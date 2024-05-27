<?php

function check_login_user_universal($link)
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
            return true; 
        } else {
            return false; // User is not found or multiple rows are returned
        }
    } else {
        return false; // $_SESSION['userid'] is not set
    }
}

?>