<html lang="en">
<?php
session_start();
require_once("../database/connection.php");
require_once("function/admin-function.php");
require("function/check-login.php");
// include("head.html");


check_login_user_universal($link);
if (!check_login_user_universal($link)) {
    
} else {
    $verifiedUID = $_SESSION['admin_id'];
    header("Location: home");
    exit;
}


?>

<link rel="stylesheet" href="assets/css/login.css">
<div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Tabs Titles -->
        <h2 class="active"> Admin </h2>

        <!-- Icon -->


        <!-- Login Form -->
        <form method="POST" action="function/login-process.php">
          
            <input type="text" id="login" class="fadeIn second" name="adminUsername" placeholder="Username">
            <input type="text" id="password" class="fadeIn third" name="adminpass" placeholder="Password">
            <input type="submit" class="fadeIn fourth" value="Log In">
        </form>

        <!-- Remind Passowrd -->
        <div id="formFooter">
            <a class="underlineHover" href="#">Forgot Password?</a>
        </div>

    </div>
</div>

</html>