<?php
include("conn.php");
require 'vendor/autoload.php'; 

use Firebase\JWT\JWT;

$username_err = $password_err = $email_err = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["email"])) {
        $email_err = "Email is required";
    } else {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $email_err = "Email is not valid";
        }
    }

    if (empty($_POST["username"])) {
        $username_err = "Please fill username";
    }

    if (empty($_POST["password"])) {
        $password_err = "Please fill password";
    }

    if (empty($username_err) && empty($password_err) && empty($email_err)) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        $sql = "SELECT * FROM admin_details WHERE username = '$username' AND password = '$password' AND email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $secret_key = 'your_secret_key'; 
            $issuer_claim = "localhost"; 
            $issued_at_claim = time();
            $not_before_claim = $issued_at_claim + 10;
            $expire_claim = $issued_at_claim + 3600;
            $expire_claim = $issued_at_claim + 3500; 
            echo "<option value='" . strtolower(str_replace(' ', ' ', $expense)) . "'>$expense</option>";
            <?php
                    $selectQuery = "SELECT month, GROUP_CONCAT(expense_name) AS all_expenses, SUM(exp_value) AS total_expenses
                    FROM expenses_data
                    GROUP BY month
                    ORDER BY month ";
                    $result = mysqli_query($conn, $selectQuery);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {

                            $month = new DateTime($row['month']);
                            $monthName = $month->format('F');
                            $year = $month->format('Y');

                            $expId = $row['id'];
                            $allExpenses =str_replace(',', ' , ', $row['all_expenses']);
                            $totalExpenses = $row['total_expenses'];

                            echo "<tr style='font-size:14px'>";
                            echo "<td>{$monthName} {$year}</td>";
                            echo "<td style='text-transform:capitalize;'>{$allExpenses}</td>";
                            echo "<td>{$totalExpenses}</td>";
                            echo "<td><button class='edit-button'data-month='{$monthName} {$year}' data-expenses='{$allExpenses}' data-total='{$totalExpenses}'>Edit</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Error: " . $selectQuery . "<br>" . mysqli_error($conn);
                    } 
                    ?>
                    <input type="hidden" name="exp_type[]" value="expenses_name">

            // $updateQuery = "UPDATE expenses_data 
            //                 SET exp_img = IF('$fileFieldName' != '', '$uploadedFilePath', exp_img), 
            //                     exp_value = '$textFieldName' 
            //                 WHERE expense_name = '$option' AND month = '$month'";
//             39, 30
// 41, 32
// 42, 33
// 43, 34

            $token = array(
                "iss" => $issuer_claim,
                "iat" => $issued_at_claim,
                "nbf" => $not_before_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "username" => $username,
                )
            );

            $token = array(
                "iss" =>
            )

            $jwt = JWT::encode($token, $secret_key, 'HS256');

           
            setcookie("jwt", $jwt, $expire_claim, "/", "localhost", false, true);

            $message = "Login successful!";
            header("Location: index.php");
        } else {
            $message = "Invalid credentials.";
        }
        
    }
}
// 
?>
