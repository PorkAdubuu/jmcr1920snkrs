<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../database/connection.php");
require("function/admin-function.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = random_num(5);
    $email = htmlspecialchars($_POST["email"]);
    $username = htmlspecialchars($_POST["username"]);
    $password = password_hash(htmlspecialchars($_POST["adminpass"]), PASSWORD_BCRYPT);

    if (empty($username) || empty($email) || empty($password)) {
        $errorMessage = "Error. Please complete the form ";

        if (empty($username)) {
            $errorMessage .= "Username ";
        }
        if (empty($email)) {
            $errorMessage .= "Email ";
        }
        if (empty($password)) {
            $errorMessage .= "Password ";
        }

     

        handleValidationError($errorMessage);
    } else {

        if (isUsernameExists($link, $username)) {
            handleValidationError("Username is taken");
        }

        if (isEmailExists($link, $email)) {
            handleValidationError("Email already exist");
        }


        //save to database

        $queryUsers = "INSERT INTO admin (admin_id, username, email, password) VALUES (?, ?, ?, ?)";
        $stmtUsers = mysqli_prepare($link, $queryUsers);

        $isUnique = false;
        while (!$isUnique) {
            $userid = random_num(5);
            mysqli_stmt_bind_param($stmtUsers, "ssss", $userid, $username, $email, $password);

            try {
                $isUnique = mysqli_stmt_execute($stmtUsers);
                if ($isUnique) {
                    
                        handleValidationSuccess("Registered successfully!");
                
                }
            } catch (mysqli_sql_exception $e) {
                // Handle any SQL exception
                echo "An error occurred while registering an admin account. Please try again later.";
                break;
            }
        }
    }
}
?>

<style>
    .sepa {
        margin-bottom: 20px;
    }
</style>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

    <div style="text-align: center;">
        <div class="sepa">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="sepa">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="sepa">
            <label>Password</label>
            <input type="text" name="adminpass" required>
        </div>

        <button type="submit">Submit</button>
    </div>

</form>