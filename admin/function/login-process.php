<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../../database/connection.php");
require("admin-function.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['adminUsername'];
    $password = $_POST['adminpass'];
    if (!empty($username) && !empty($password)) {
        // Prepare the statement
        $query = "SELECT admin_id, password FROM admin WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($link, $query);

        if ($stmt) {
            // Bind the parameter
            mysqli_stmt_bind_param($stmt, "s", $username);

            // Execute the statement
            mysqli_stmt_execute($stmt);

            // Get the result
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                // Fetch the row
                $user_data = mysqli_fetch_assoc($result);

                // Use password_verify to check the hashed password
                if (password_verify($password, $user_data['password'])) {
                    $_SESSION['admin_id'] = $user_data['admin_id']; // Store admin_id in session

                    // Show success alert
                    show_generic_message("Login successful. Redirecting... ", "success");


                    // Redirect to the main webpage after a delay
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function() { window.location.href = "../home"; }, 2000);';
                    echo '</script>';
                    die;
                } else {
                    // Incorrect password
                    show_generic_message("Login failed. Incorrect password.", "error");
                    echo '<script type="text/javascript">';
                    echo 'history.back();';
                    echo '</script>';
                }
            } else {
                // No user found
                show_generic_message("Login failed. Please check your credentials.", "error");
                echo '<script type="text/javascript">';
                echo 'history.back();';
                echo '</script>';
            }
        } else {
            // Error preparing the statement
            show_generic_message("An error occurred. Please try again later.", "error");
            echo '<script type="text/javascript">';
            echo 'history.back();';
            echo '</script>';
        }
    }
}
