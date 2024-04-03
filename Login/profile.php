<?php

include("navbar.php");

if (isset($_POST['update']) && $_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $query = "UPDATE admin_details SET username='$username',password='$password',email = '$email' WHERE id = '$u_id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Success'); window.location='dashboard.php'</script>";
    } else {
        echo "Error:" . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title></title>
</head>
<style>
    :root {
        --black: #000;
        --pink: #e82968;
        --deep_pink: #e91e63;
        --white: #fff;
        --light_blue: #03a9f4;
        --light_green: #067d8b;
    }


    /* .navbar img {
        display: block;
        margin-left: 20px;
        margin-right: auto;
        max-height: 45px;
        width: 75%;
        padding: 5px;
    } */

    body {
        /* font-family: 'Arial', sans-serif; */
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        width: 400px;
        max-width: 100%;
    }

    form input {
        position: relative;
    }

    form i {
        position: absolute;
        top: 45%;
        right: 20px;
        font-size: 16px;
    }


    label {
        display: block;
        margin-bottom: 8px;
        color: #333;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    /* select {
        cursor: pointer;
    }

    textarea {
        resize: vertical;
    } */

    /* input[type="submit"] {
        background-color: #4caf50;
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    } */

    .formBx {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        top: 100px;
        left: 400px;
        max-width: 450px;
        width: 100%;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .formBx h2 {
        color: #707070;
        font-weight: 600;
        font-size: 25px;
        margin-bottom: 20px;
        border-bottom: 4px solid #0081ff;
        display: inline-block;
        letter-spacing: 1px;
    }

    .formBx .inputBx {
        position: relative;
    }

    .formBx .inputBx label {
        font-size: 16px;
        margin-bottom: 5px;
        display: inline-block;
        color: var(--light_blue);
        font-weight: 300;
        letter-spacing: 1px;
    }

    .formBx .inputBx input {
        width: 100%;
        padding: 10px 45px 10px 15px;
        outline: none;
        font-weight: 400;
        border: 1px solid #607d8b;
        font-size: 16px;
        letter-spacing: 1px;
        color: var(--light_green);
        background: transparent;
        border-radius: 30px;
    }

    .formBx .inputBx input[type="submit"] {
        background: #0081ff;
        color: var(--white);
        outline: none;
        border: none;
        font-weight: 500;
        cursor: pointer;
        padding: 15px;
    }

    .home {
        height: 100vh;
        width: calc(100% - 88px);
        background: #fff;
        transition: var(--tran-05);
        padding: 50px 80px 50px 80px;
    }

    .home .text {
        font-size: 30px;
    }

    .sidebar.close~.home {
        left: 88px;
        width: calc(100% - 88px);
    }

    @media screen and (max-width: 760px) {
        .sidebar.close~.home {
            left: 0;
            width: 100%;
        }

        .formBx {
            top: 20px;
            left: 0;
        }
    }
</style>

<body>

    <section class="home">
        <div class="formBx">

            <div>
                <h2>Admin Details</h2>
            </div>

            <form action="#" method="post">

                <div class="inputBx">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $user_data['username']; ?>">
                </div>

                <div class="inputBx">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" value="<?php echo $user_data['email']; ?>"><br>
                </div>

                <div class="inputBx">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" value="<?php echo $user_data['password']; ?>"><br>
                    <i class='fa-solid fa-eye' onclick="eyeFunction()"></i>
                </div>

                <div class="inputBx">
                    <input type="submit" name="update" value="Update">
                </div>

            </form>

        </div>
    </section>
</body>
<script>
    function eyeFunction() {

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

</html>