<?php

if (isset($_COOKIE['jwt'])) {

    echo "<script>window.location='dashboard.php'</script>";
}

include("conn.php");
require 'vendor/autoload.php';

use Firebase\JWT\JWT;

$username_err = $password_err = $email_err = "";

$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // if (empty($_POST["email"])) {
    //     $email_err = "Email is required";
    // } else {
    //     if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    //         $email_err = "Email is not valid";
    //     }
    // }

    // if (empty($_POST["password"])) {
    //     $password_err = "Please fill password";
    // }



    if (empty($password_err) && empty($email_err)) {

        $password = $_POST['password'];

        // $email = $_POST['email'];

        // $sql = "SELECT * FROM admin_details WHERE password = '$password' AND email = '$email'";

        $usernameOrEmail = $_POST['username_or_email'];
        $email = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL) ? $usernameOrEmail : '';
        $username = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL) ? '' : $usernameOrEmail;

        $sql = "SELECT * FROM admin_details WHERE (email = '$email' OR username = '$username') AND password = '$password'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {

            $row = mysqli_fetch_assoc($result);
            $user_id = $row['id'];

            $secret_key = base64_encode($username . $user_id);

            $issuer_claim = "localhost";
            $issued_at_claim = time();
            $not_before_claim = $issued_at_claim + 10;
            $expire_claim = strtotime('+10 years');

            $token = array(
                "iss" => $issuer_claim,
                "iat" => $issued_at_claim,
                "nbf" => $not_before_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "user_id" => $user_id,
                    "username" => $username,
                )
            );

            $jwt = JWT::encode($token, $secret_key, 'HS256');

            $_SESSION['jwt'] = $jwt;
            $_SESSION['user_id'] =  $user_id;

            setcookie("jwt", $jwt, $expire_claim, "/");
            echo "<script>window.location='dashboard.php'</script>";
        } else {
            echo "<script>alert ('Login failed : Wrong username or password')</script>";
        }
    }
}



?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>

<style>


</style>

<body>
    <section>
        <div class="container">
            <!-- <div class="abc"> -->
            <div class="imgBx">
                <img src="https://images.pexels.com/photos/302769/pexels-photo-302769.jpeg?auto=compress&cs=tinysrgb&w=600">
            </div>
            <div class="contentBx">
                <div class="formBx">
                    <h2>LOGIN</h2>
                    <form method="post">
                        <div class="inputBx">
                            <span>Username or Email</span>
                            <input type="text" name="username_or_email" id="usernameOrEmail" autocomplete="off" required>
                            <i class='bx bxs-user-circle'></i>
                        </div>
                        <span style="color: red; font-size:12px;" class="error"><?php echo "$username_err" ?></span><br>
                        <!-- <div class="inputBx">
                            <span>Email</span>
                            <input type="email" name="email" id="emailAddress" autocomplete="off" required>
                            <i class='bx bxs-envelope'></i>
                        </div>
                        <span style="color: red; font-size:12px;" class="error"><?php echo "$email_err" ?></span><br> -->
                        <div class="inputBx">
                            <span>Password</span>
                            <input type="Password" name="password" id="password" autocomplete="off" required>
                            <i class='fa-solid fa-eye' onclick="myFunction()"></i>
                        </div><br>
                        <span style="color: red; font-size:12px;" class="error"><?php echo "$password_err" ?></span><br>
                        <div class="inputBx">
                            <input type="submit" value="Sign In" name="" id="" style="font-weight:bold;">
                        </div><br>
                    </form>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </section>
    <script>

        function myFunction() {

            var x = document.getElementById("password");
            var icon = document.querySelector('.fa-solid');

            if (x.type === "password") {
                x.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                x.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
            
        }
        
    </script>
</body>

</html>