<?php
include("navbar.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <link rel="stylesheet" href="dash_style.css">
    <title>Document</title>
    <style>
        
    </style>
</head>

<body>
    <section class="home">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class='bx bxs-home-smile'></i>
                    </span> Dashboard
                </h3>
            </div>
            <div class="row">
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white">
                        <div class="card-body">
                            <img src="images/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="">Total Incoming <i class='bx bxs-down-arrow-circle fs-4 fw-bold float-right'></i>
                            </h4>
                            <?php
                            $roomBillQuery = "SELECT SUM(`room_bill`) AS total_room_bill FROM `electricity_bill`";
                            $roomBillResult = mysqli_query($conn, $roomBillQuery);
                            $rowRoomBill = mysqli_fetch_assoc($roomBillResult);
                            $totalRoomBill = $rowRoomBill['total_room_bill'];

                            // SQL query to calculate total room rent
                            $roomRentQuery = "SELECT SUM(`room_rent`) AS total_room_rent FROM `month_rent`";
                            $roomRentResult = mysqli_query($conn, $roomRentQuery);
                            $rowRoomRent = mysqli_fetch_assoc($roomRentResult);
                            $totalRoomRent = $rowRoomRent['total_room_rent'];

                            $totalInc = $totalRoomBill +  $totalRoomRent


                            ?>
                            <h2 class="">
                                Rs <?php echo $totalInc; ?>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white">
                        <div class="card-body">
                            <img src="images/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="">Total Expenses <i class='bx bxs-up-arrow-circle fs-4 fw-bold float-right'></i>
                            </h4>
                            <?php
                            $meterBillQuery = "SELECT SUM(`meter_bill`) AS total_meter_bill FROM `electricity_meter`";
                            $meterBillResult = mysqli_query($conn, $meterBillQuery);
                            $rowMeterBill = mysqli_fetch_assoc($meterBillResult);
                            $totalMeterBill = $rowMeterBill['total_meter_bill'];

                            // SQL query to calculate total expenses
                            $expensesQuery = "SELECT SUM(`exp_value`) AS total_expenses FROM `expenses_data`";
                            $expensesResult = mysqli_query($conn, $expensesQuery);
                            $rowExpenses = mysqli_fetch_assoc($expensesResult);
                            $totalExpenses = $rowExpenses['total_expenses'];

                            $totalExp = $totalMeterBill + $totalExpenses;

                            ?>
                            <h2 class="">
                                Rs <?php echo $totalExp; ?>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-success card-img-holder text-white">
                        <div class="card-body">
                            <img src="images/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="">Total Active Tenant <i class='bx bx-user-pin fs-4 fw-bold float-right'></i>
                            </h4>
                            <?php
                            $userCountQuery = "SELECT COUNT(*) AS total_users FROM `users` WHERE disable_date IS NULL";
                            $userCountResult = mysqli_query($conn, $userCountQuery);
                            $rowUserCount = mysqli_fetch_assoc($userCountResult);
                            $totalUsers = $rowUserCount['total_users'];
                            ?>
                            <h2 class=""><?php echo $totalUsers; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 grid-margin stretch-card">
                    <div class="roomDisplay">
                        <div class="clearfix">
                            <h3 class="room-title float-left">Rooms</h3>
                            <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right">
                                <ul>
                                    <li><span class="legend-dots" style="background:linear-gradient(to right,#EF2A14,#F9736A)"></span>Filled Rooms </li>
                                    <li><span class="legend-dots" style="background:linear-gradient(to right, #FEC91D, #F1B323)"></span class="fw-bold">Half-Filled Rooms</li>
                                    <li><span class="legend-dots" style="background:linear-gradient(to right, #2EDF3F,#21AF59)"></span class="fw-bold">Vacant Rooms</li>
                                </ul>
                            </div>
                        </div>
                        <div class="cardContainer">
                            <div class="groundFloor">
                                <div class="flrHead fw-bold">
                                    <p>Ground floor</p>
                                    <a href="addrooms.php">View All</a>
                                </div>

                                <div class="cardCont">
                                    <?php
                                    $query = "SELECT * FROM rooms WHERE floor = 'ground_floor'";
                                    $res = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $roomId = $row['id'];
                                        $query1 = "SELECT COUNT(*) as userCount FROM alloted_room WHERE room_no = '$roomId'";
                                        $res1 = mysqli_query($conn, $query1);
                                        $userCount = mysqli_fetch_assoc($res1)['userCount'];
                                    ?>
                                        <div class="floor-card <?= $row['floor'] ?>">
                                            <div class="card card-img-holder text-dark" style="background: <?= ($userCount == 0) ? 'linear-gradient(to right, #2EDF3F,#21AF59)' : (($userCount == $row['capacity']) ? 'linear-gradient(to right,#ea6c5e,#fe4f44)' : 'linear-gradient(to right, #FEC91D, #F1B323)') ?>">
                                                <div class="card-body pt-4 px-3">
                                                    <img src="images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                                    <h5 class="card-title fs-6 fw-bold">Room Name: <?= $row["room_no"]; ?></h5>
                                                    <p class="card-text" style="font-weight:bold; font-size:12px;">Capacity: <?= $row["capacity"]; ?></p>
                                                    <p style="font-weight:bold; font-size:12px;">Candidate:</p>

                                                    <?php
                                                    $roomId = $row['id'];
                                                    $query1 = "SELECT u.name,u.id,u.mobile FROM alloted_room ar
                                              JOIN users u ON ar.u_id = u.id
                                              WHERE ar.room_no = '$roomId'";
                                                    $res1 = mysqli_query($conn, $query1);
                                                    if (mysqli_num_rows($res1) > 0) {
                                                        while ($allocatedRow = mysqli_fetch_assoc($res1)) {
                                                            $userId = $allocatedRow['id'];
                                                            $userName = $allocatedRow['name'];
                                                            $userNo = $allocatedRow['mobile'];
                                                    ?>

                                                            <p class="user-disp" style="text-transform:capitalize; font-size:12px; font-weight:700;"><?= $userName . ': ' . $userNo ?></p>
                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <p style="font-weight:700; font-size:12px;">No candidate in this room</p>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>

                            </div>

                            <div class="firstFloor mt-4">
                                <div class="flrHead fw-bolder">
                                    <p>First floor</p>
                                    <a href="addrooms.php">View All</a>
                                </div>
                                <div class="cardCont">
                                    <?php
                                    $query = "SELECT * FROM rooms WHERE floor = 'first_floor'";
                                    $res = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $roomId = $row['id'];
                                        $query1 = "SELECT COUNT(*) as userCount FROM alloted_room WHERE room_no = '$roomId'";
                                        $res1 = mysqli_query($conn, $query1);
                                        $userCount = mysqli_fetch_assoc($res1)['userCount'];
                                    ?>
                                        <div class="floor-card <?= $row['floor'] ?>">
                                            <div class="card card-img-holder text-dark" style="background: <?= ($userCount == 0) ? 'linear-gradient(to right, #2EDF3F,#21AF59)' : (($userCount == $row['capacity']) ? 'linear-gradient(to right,#EF2A14,#F9736A)' : 'linear-gradient(to right, #FEC91D, #F1B323)') ?>">
                                                <div class="card-body pt-4 px-3">
                                                    <img src="images/circle.svg" class="card-img-absolute" alt="circle-image" />
                                                    <h5 class="card-title fs-6 fw-bold">Room Name: <?= $row["room_no"]; ?></h5>
                                                    <p class="card-text" style="font-weight:bold; font-size:12px;">Capacity: <?= $row["capacity"]; ?></p>
                                                    <p style="font-weight:bold;  font-size:12px;">Candidate:</p>

                                                    <?php
                                                    $roomId = $row['id'];
                                                    $query1 = "SELECT u.name,u.id,u.mobile FROM alloted_room ar
                                              JOIN users u ON ar.u_id = u.id
                                              WHERE ar.room_no = '$roomId'";
                                                    $res1 = mysqli_query($conn, $query1);
                                                    if (mysqli_num_rows($res1) > 0) {
                                                        while ($allocatedRow = mysqli_fetch_assoc($res1)) {
                                                            $userId = $allocatedRow['id'];
                                                            $userName = $allocatedRow['name'];
                                                            $userNo = $allocatedRow['mobile'];
                                                    ?>

                                                            <p class="user-disp" style="text-transform:capitalize; font-size:12px; font-weight:700;"><?= $userName . ': ' . $userNo ?></p>
                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <p style="font-weight:700; font-size:12px;">No candidate in this room</p>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-5 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="title-button">
                                <h4 class="card-title float-left">Incoming/Expenses</h4>
                                <div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right">
                                    <div class="btn-group" >
                                        <button type="button" class="btn" onclick="updateChart('all')">
                                            All Data
                                        </button>
                                        <button type="button" class="btn" onclick="updateChart('Rooms')">
                                            Rooms
                                        </button>
                                        <button type="button" class="btn" onclick="updateChart('Electricity')">
                                            Electricity
                                        </button>
                                        <button type="button" class="btn" onclick="updateChart('Other')">
                                            Other
                                        </button>
                                        <!-- <select id="filterMonth" onchange="applyMonthFilter(this)">
                                            <?php
                                            $monthsQuery = "SELECT DISTINCT `month` FROM electricity_bill ORDER BY `month`";
                                            $monthsResult = mysqli_query($conn, $monthsQuery);
                                            echo "<option value='' selected disabled>Select Month</option>";
                                            echo "<option value='all'>All</option>";
                                            while ($monthRow = mysqli_fetch_assoc($monthsResult)) {
                                                $monthValue = $monthRow['month'];
                                                $monthText = date('F Y', strtotime($monthValue));
                                                echo "<option value='$monthValue'>$monthText</option>";
                                            }
                                            ?>
                                        </select> -->
                                    </div>
                                </div>


                            </div>

                            <canvas id="barchart" style="height:10px ; width:10px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8+Zl1CAw1jZXpPhkD4dgc/5AwiF3TRvQrL5fXBRbEV8DeYO6H2fwYFjowL+x" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.cardCont').slick({
                infinite: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                prevArrow: '<button type="button" class="slick-prev">&#8592;</button>',
                nextArrow: '<button type="button" class="slick-next">&#8594;</button>',
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    }
                }]
            });
        });

        const ctx = document.getElementById('barchart').getContext('2d');

        let initialData = {
            labels: ['Total Incoming', 'Total Expenses'],
            datasets: [{
                label: 'Amount',
                data: [<?= $totalInc ?>, <?= $totalExp ?>],
                backgroundColor: [
                    '#ff4c4c',
                    'rgba(13, 110, 253, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1,
                barPercentage: 0.3
            }]
        };

        let myChart = new Chart(ctx, {
            type: 'bar',
            data: initialData,
            options: {
                scales: {
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                indexAxis: 'x',
            }
        });

        function updateChart(category) {
            let newData;
            switch (category) {
                case 'Rooms':
                    newData = {
                        labels: ['Total Incoming'],
                        datasets: [{
                            label: 'Amount',
                            data: [<?= $totalRoomRent ?>],
                            backgroundColor: [
                                '#ff4c4c',
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                            ],
                            borderWidth: 1,
                            barPercentage: 0.2
                        }]
                    };
                    break;
                case 'Electricity':
                    newData = {
                        labels: ['Total Incoming', 'Total Expenses'],
                        datasets: [{
                            label: 'Amount',
                            data: [<?= $totalRoomBill ?>, <?= $totalMeterBill  ?>],
                            backgroundColor: [
                                '#ff4c4c',
                                '#0d6efdde'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)'
                            ],
                            borderWidth: 1,
                            barPercentage: 0.3
                        }]
                    };
                    break;
                case 'Other':
                    newData = {
                        labels: ['Total Expenses'],
                        datasets: [{
                            label: 'Amount',
                            data: [<?= $totalExpenses  ?>],
                            backgroundColor: [
                                '#0d6efdde'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)'
                            ],
                            borderWidth: 1,
                            barPercentage: 0.2
                        }]
                    };
                    break;
                case 'all':
                    newData = initialData;
                    break;
                default:
                    break;
            }
            myChart.data = newData;
            myChart.update();
        }
    </script>
</body>

</html>