<?php

include("conn.php");

if (!isset($_COOKIE['jwt'])) {
    echo "<script>window.location='index.php'</script>";
}

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    $u_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM admin_details WHERE id = $u_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $user_data = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Unauthorized Login.'); window.location='index.php'</script>";
        die();
    }
} else {
    echo "<script>window.location='logout.php'</script>";
    die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="navbar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mob">
  <div class="container-fluid">
  <img src="logo.jpeg" alt="" class="img-fluid rounded-circle" width="40">
    <button id="navbarToggler" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add_user.php">Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="electricity_bill.php">Bill</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="other_expenses.php">Other Expenses</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="month_data.php">Monthly Incoming</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="addrooms.php">Rooms</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="profile.php">Settings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- <nav class="navbar navbar-light bg-light mob">
  <div class="container-fluid">
  <img src="logo.jpeg" alt="" class="img-fluid rounded-circle" width="40">
  <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add_user.php">Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="electricity_bill.php">Bill</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="other_expenses.php">Other Expenses</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="month_data.php">Monthly Incoming</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="addrooms.php">Rooms</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="profile.php">Settings</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav> -->

<nav class="sidebar close ">
        <header class=" text-light">
            <div class="d-flex align-items-center">
                <span class="image">
                    <img src="logo.jpeg" alt="" class="img-fluid rounded-circle" width="40">
                </span>
                <div class="text header-text mx-3">
                    <span class="name">Hello</span>
                    <span class="profession">World</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle text-light'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="dashboard.php" class="text-decoration-none">
                        <i class='bx bxs-dashboard icon' ></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="add_user.php" class="text-decoration-none">
                            <i class='bx bx-user-circle icon'></i>
                            <span class="text nav-text">User</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="electricity_bill.php" class="text-decoration-none">
                            <i class='bx bx-calculator icon'></i>
                            <span class="text nav-text">Bill</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="other_expenses.php" class="text-decoration-none">
                            <i class='bx bxl-redbubble icon'></i>
                            <span class="text nav-text">Other Expenses</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="month_data.php" class="text-decoration-none">
                        <i class='bx bxs-data icon'></i>
                            <span class="text nav-text">Monthly Incoming</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="addrooms.php" class="text-decoration-none">
                        <i class='bx bx-home icon' ></i>
                            <span class="text nav-text">Rooms</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="profile.php" class="text-decoration-none">
                        <i class='bx bx-cog icon'></i>
                        <span class="text nav-text">Setting</span>
                    </a>
                </li>

                <li class="">
                    <a href="logout.php" class="text-decoration-none">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>
            </div>
        </div>
    </nav> 
    <!-- <section class="home">
      <div class="text">Dashboard</div>
    </section> -->
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function() {
        history.pushState(null, null, document.URL);
    });

    const body = document.querySelector("body"),
    sidebar = document.querySelector(".sidebar"),
    toggle = document.querySelector(".toggle");

    toggle.addEventListener("click" , () =>{
        sidebar.classList.toggle("close");
    });

</script>

</html>
