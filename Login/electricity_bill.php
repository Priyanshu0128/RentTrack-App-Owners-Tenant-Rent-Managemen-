<?php

include("navbar.php");
$sql = "SELECT username FROM admin_details";
$result = $conn->query($sql);

$adminList = array();
while ($row = $result->fetch_assoc()) {
    $adminList[] = $row["username"];
}
?>
<!-- For Update -->
<?php
if (isset($_POST['update_bill'])) {

    try {
        $monthEx = mysqli_real_escape_string($conn, $_POST['month']);
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);


        $selectRoom = $_POST['select_room'];

        $roomUnits = $_POST['room_units'];
        $roomBill = $_POST['room_bill'];
        $billDates = $_POST['room_date'];
        $keptby = $_POST['bill_kept_by'];

        foreach ($selectRoom as $room) {
            $units = mysqli_real_escape_string($conn, $roomUnits[$room]);
            $bill = mysqli_real_escape_string($conn, $roomBill[$room]);
            $dates = mysqli_real_escape_string($conn, $billDates[$room]);
            $admin = mysqli_real_escape_string($conn, $keptby[$room]);

            // Check if the room already exists in the database
            $checkRoomQuery = "SELECT * FROM electricity_bill WHERE room_name = '$room' AND month = '$monthEx'";
            $result = mysqli_query($conn, $checkRoomQuery);

            if (mysqli_num_rows($result) > 0) {
                // Room exists, update the existing record
                $updateRoomQuery = "UPDATE electricity_bill SET room_units = '$units', room_bill = '$bill', room_date = '$dates', bill_kept_by = '$admin' WHERE room_name = '$room' AND month = '$monthEx'";
                mysqli_query($conn, $updateRoomQuery);
            } else {
                // Room does not exist, insert a new record
                $insertRoomQuery = "INSERT INTO electricity_bill (room_name, room_units, room_bill, room_date, bill_kept_by, month) VALUES ('$room', '$units', '$bill', '$dates', '$admin', '$monthEx')";
                mysqli_query($conn, $insertRoomQuery);
            }
        }


        foreach ($_POST['meter_name'] as $key => $meterName) {

            $meterUnits = mysqli_real_escape_string($conn, $_POST['meter_units'][$key]);
            $meterBill = mysqli_real_escape_string($conn, $_POST['meter_bill'][$key]);
            $meterDate = mysqli_real_escape_string($conn, $_POST['meter_date'][$key]);
            $meterStatus = mysqli_real_escape_string($conn, $_POST['meter_status'][$key]);

            $meterImgName = $_FILES['meter_img']['name'][$key];
            $meterImgName_tmp = $_FILES['meter_img']['tmp_name'][$key];

            $meterFilePath = '';
            if (!empty($meterImgName) && !empty($meterImgName_tmp)) {
                $meterDirectory = 'uploads/';
                $meterFilePath = $meterDirectory . $meterImgName;
                if (move_uploaded_file($meterImgName_tmp, $meterFilePath)) {
                } else {
                    echo "Error uploading file for option $meterName.";
                    continue;
                }
            }

            $meterUserId = $meterStatus == 'Paid' ? $user_id : NULL;

            $updateMeterQuery = "UPDATE electricity_meter SET 
                meter_units = '$meterUnits', 
                meter_bill = '$meterBill', 
                meter_date = '$meterDate',";

            if (!empty($meterFilePath)) {
                $updateMeterQuery .= " meter_img = '$meterFilePath',";
            }

            $updateMeterQuery .= " meter_status = '$meterStatus', 
                meter_paidby = '$meterUserId' 
                WHERE meter_name = '$meterName' AND month = '$monthEx'";


            mysqli_query($conn, $updateMeterQuery);
        }

        // die();

        // echo "<script>window.location='electricity_bill.php';</script>";
        // exit();
    } catch (Exception $e) {
        echo $e->getMessage();
        die();
    }
}
?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="user_details.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
    <style>
        .meterCont {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .button-1 {
            background-color: #0081ff;
            border-radius: 8px;
            border-style: none;
            box-sizing: border-box;
            color: #FFFFFF;
            cursor: pointer;
            display: inline-block;
            font-family: "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            font-weight: 500;
            height: 40px;
            line-height: 20px;
            list-style: none;
            margin: 0;
            outline: none;
            padding: 10px 16px;
            text-align: center;
            text-decoration: none;
            transition: color 100ms;
            vertical-align: baseline;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .button-2 {
            background-color: #0081ff;
            border-radius: 8px;
            border-style: none;
            box-sizing: border-box;
            color: #FFFFFF;
            cursor: pointer;
            display: inline-block;
            font-family: "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            font-weight: 500;
            height: 40px;
            line-height: 20px;
            list-style: none;
            margin: 0;
            outline: none;
            padding: 10px 16px;
            text-align: center;
            text-decoration: none;
            transition: color 100ms;
            vertical-align: baseline;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            float: right;
            margin: 10px;
        }

        .head {
            display: flex;
            justify-content: space-between;
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

        .inputBx input[type="submit"] {
            margin-top: 20px;
        }

        .head p {
            color: #707070;
            font-weight: 600;
            font-size: 20px;
            /* margin-bottom: 20px; */
            border-bottom: 4px solid var(--sub_btn);
            display: inline-block;
            letter-spacing: 1px
        }

        .edit-button {
            padding: 5px 10px;
            margin-right: 20px;
            border-radius: 5px;
            font-size: 10px;
            border: none;
        }

        .view-button {
            padding: 5px 10px;
            margin-right: 20px;
            border-radius: 5px;
            font-size: 10px;
            border: none;
        }

        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
        }

        .dataTables_filter {
            display: none;
        }

        .dataTables_paginate {
            margin: 20px
        }

        .dataTables_filter {
            margin-bottom: 25px;
        }

        #t_data {
            font-size: 12px;
            color: grey;
            background-color: #fff;
            text-align: left;
        }

        tbody td {
            border: 1px solid #8080802b;
        }

        #displayAnalytics {
            border: 1px solid #000000ad;
            background-color: #f9f9f9;
            width: 100%;
            /* height: 40px; */
            /* overflow-y: auto; */
            display: flex;
            justify-content: space-between;
            padding: 8px 16px;
            border-radius: 4px;
        }

        /* .input-wrapper select {
            width: 100%;
            padding: 13px;
            box-sizing: border-box;
            margin-bottom: 10px;
        } */

        select {
            width: 100%;
            padding: 9px;
            box-sizing: border-box;
            margin-bottom: 10px;
            font-size: 14px;
        }

        /* .summary-heading {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        } */

        .summary-data {
            display: flex;
            /* justify-content: space-between; */
            /* margin-bottom: 10px; */
        }

        .summary-label {
            font-weight: bold;
            text-transform: capitalize;
            font-size: 14px;
        }

        .analy {
            font-weight: bold;
            font-size: 14px;
            margin-left: 5px;
            margin-bottom: 0;
        }

        #tableContainer td {
            padding: 20px;
        }

        .boxrooms td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        /* tr:nth-child(even) {
            background-color: #dddddd;
        } */

        .bootstrap-select .dropdown-menu {
            min-width: 50%;
        }

        #roomtableContainer td {
            padding: 20px;
        }

        #meterForm {
            display: block;
            /* max-width: 800px; */
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border: 2px solid #ccc;
            border-radius: 5px;
            /* margin-top: 10px; */
        }

        #billForm {
            display: block;
            /* max-width: 800px; */
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border: 2px solid #ccc;
            border-radius: 5px;
            /* margin-top: 10px; */
        }

        /* .row {
            margin-bottom: 20px;
        } */

        p {
            font-size: 18px;
            font-weight: bold;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .bootstrap-select button {
            border: 1px solid #000;
            padding: 8px 10px;
        }

        #click_tabs li {
            flex: 50%;
        }

        #click_tabs .nav-link.active {
            background: #0081ff;
            color: #fff;
        }

        input[type="file"] {
            padding: 8px;
            background-color: #ffffff;
        }

        /* select {
            padding: 13px;
            background-color: #ffffff;
        } */

        #click_tabs .nav-link {
            background-color: rgb(192 181 181 / 19%);
            border: 1px solid #f8f9fa;
            color: #000;
            font-weight: bold;
        }

        #elect_Btn {
            background-color: #0081ff;
            color: #ffffff;
            font-weight: bold;
        }

        #imageModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        @media screen and (max-width: 760px) {
            nav.sidebar.close {
                display: none;
            }

            .sidebar.close~.home {
                width: 100%;
                left: 0;
                padding: 15px 20px 0px;
            }

            .navbar.mob {
                display: block;
            }

            .head p {
                font-size: 12px;
            }

            .button-1 {
                font-size: 12px;
                height: 30px;
                line-height: 10px;
                font-weight: bold;
            }

            .mainDash {
                overflow-x: scroll;
            }

            .modal-dialog {
                display: flex;
                justify-content: center;
            }

            .modal-content {
                width: 90%;
                top: 100px
            }

            .inputBx input {
                font-size: 12px;
            }

            .inputBx label {
                font-size: 12px;
            }

            #aadhar_image {
                top: 25px;
                right: 70px;
                width: 80px
            }

            .inputBx select {
                font-size: 10px;
            }

            .inputBx input[type="submit"] {
                font-weight: bold;
            }

            .modal-title {
                font-size: 16px;
            }

            .summary-label {
                font-size: 12px;
            }

            .analy {
                font-size: 12px;
            }

            #displayAnalytics {
                padding: 2px 8px;
            }

            .summary-data {
                display: inline;
                margin-bottom: 10px;
            }

            .edit-button,
            .view-button {
                margin-top: 5px;
            }

            table.dataTable tbody td {
                padding: 0px 5px;
                font-size: 12px;
            }

            select {
                width: 100%;
                padding: 10px;
                box-sizing: border-box;
                margin-bottom: 0;
                font-size: 14px;
            }

            #click_tabs .nav-link {
                font-size: 12px;
            }

            .addData .nav-item {
                padding-left: 0;
            }

            #month {
                width: 100%;
                /* padding: 5px; */
            }

            .col-md-4 {
                padding: 0px 10px;
                font-size: 12px;
            }

            .col-md-6 {
                padding: 0px 10px;
                font-size: 12px;
            }

            input {
                margin-bottom: 0;
            }

            .row p {
                font-size: 12px;
            }

            button.btn.dropdown-toggle.btn-light.bs-placeholder {
                padding: 4px 8px;
                font-size: 13px;
            }

            .col-md-3 {
                padding: 0px 10px;
                font-size: 12px;
            }

            #tableContainer td {
                padding: 8px;
            }

            .boxrooms td,
            th {
                font-size: 12px;
            }

            #tableContainer tr {
                font-size: 12px;
            }

            #tableContainer {
                overflow-y: auto;
            }

            #roomtableContainer tr {
                font-size: 12px;
            }

            #roomtableContainer {
                overflow-y: auto;
            }

            #roomtableContainer td {
                padding: 8px;
            }


            .viewData .nav-item {
                padding-left: 0;
            }

        }
    </style>
</head>

<body>
    <section class="home">

        <div class="head">
            <p>Electricity Bill</p>
            <a class="button-1" role="button" id="openbillModel">Bill Form</a>
        </div>
        <br>
        <div class="row justify-content-between gx-md-0 gy-2">
            <div class="col-md-3">
                <select id="filterMonth" onchange="applyMonthFilter(this)">
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
                </select>
            </div>
            <div class="col-md-6">
                <div id="displayAnalytics"></div>
            </div>
        </div>
        <br>
        <div class="mainDash">

            <table id="example" style="box-sizing: border-box; box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.1);background: #fff;">
                <thead class="tablerow" id="t_data">
                    <tr>
                        <!-- <th>Bill Image</th> -->
                        <th>Month</th>
                        <th>Rooms</th>
                        <th>Expenses</th>
                        <th>Incoming</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="month_data">
                    <?php
                    $sql = "SELECT 
                    month, 
                    SUM(DISTINCT meter_bill) AS total_meter_bill,
                    GROUP_CONCAT(DISTINCT room_name) AS rooms, 
                    SUM(DISTINCT room_bill) AS total_room_bill
                FROM 
                    electricity_bill 
                JOIN 
                    electricity_meter USING (month)
                GROUP BY 
                    month
                ORDER BY 
                    month ASC";
                    $result = mysqli_query($conn, $sql);

                    if ($result && mysqli_num_rows($result) > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {

                            $month = new DateTime($row['month']);
                            $monthName = $month->format('F');
                            $year = $month->format('Y');
                            echo "<tr style='font-size:14px;'>";
                            echo "<td>{$monthName} {$year}</td>";
                            echo "<td>" . $row['rooms'] . "</td>";
                            echo "<td>" . $row['total_meter_bill'] . "</td>";
                            echo "<td>" . $row['total_room_bill'] . "</td>";
                            echo "<td><button class='edit-button' data-month='{$monthName} {$year}'>Edit</button>
                            <button class='view-button' data-month='{$monthName} {$year}'>View</button></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="modal" tabindex="-1" role="dialog" id="billModel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tle">Bill Incoming/Expenses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="combinedForm" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                        <div class="col-md-4">
                            <label for="month" class="mb-1">Month</label>
                            <input type="month" id="month" name="month">
                        </div>
                        <br>

                        <div class="addData">
                            <ul class="nav nav-tabs row justify-content-between gx-md-0 gy-2" id="click_tabs">
                                <!-- <div class=""> -->
                                <div class="col-md-6">
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#home" id="expensesButton">Electricity Expenses</a>
                                    </li>
                                </div>
                                <div class="col-md-6">
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#menu1" id="incomingButton">Electricity Incoming</a>
                                    </li>
                                </div>
                                <!-- </div> -->
                            </ul>

                        </div>

                        <div class="viewData">
                            <ul class="nav nav-tabs row justify-content-between gx-md-0 gy-2" id="click_tabs">
                                <div class="col-md-6">
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#meterData" id="displayMeterdata">Electricity Expenses</a>
                                    </li>
                                </div>
                                <div class="col-md-6">
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#roomData" id="displayRoomdata">Electricity Incoming</a>
                                    </li>
                                </div>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane container" id="meterData" style="padding:0;margin-top: 10px">
                                <div id="tableContainer"></div>
                            </div>
                            <div class="tab-pane container fade" id="roomData" style="padding:0;margin-top: 10px">
                                <div id="roomtableContainer"></div>
                            </div>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane container" id="home" style="padding:0;margin-top: 10px">
                                <div id="dynamicFieldsContainer"></div>
                                <button type="button" class="button-2" id="addFieldButton" onclick="addDynamicField()">Add Meter+</button>
                            </div>
                            <div class="tab-pane container fade" id="menu1" style="padding:0;margin-top: 10px">
                                <div class="row">
                                    <p for="select_room" class="mb-1">Add Room</p>
                                    <select id="select_room" name="select_room[]" class="selectpicker w-100" aria-label="Default select example" data-live-search="true" onchange="showRooms()" multiple>
                                        <option value="" disabled>Select</option>
                                        <?php

                                        $sql = "SELECT room_no FROM rooms";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            $rooms_list = array();

                                            while ($row = $result->fetch_assoc()) {
                                                $rooms_list[] = $row["room_no"];
                                            }
                                        }
                                        foreach ($rooms_list as $room_s) {
                                            echo "<option class='dropdown-item ' value='" . strtoupper(str_replace(' ', '', $room_s)) . "'>$room_s</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='inputBx' style='margin-top: 40px;'>
                            <div><input type='button' name='submit' id='elect_Btn' value='Submit'></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this room?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div id="imageModal" class="modal">

        <span class="close-cross" onclick="closeModal()" style="    position: absolute;top: 10px;right: 10px;font-size: 40px;cursor: pointer;color: #fff;">&times;</span>
        <img class="modal-content" id="img01" style="max-width:800px;width:100%;">

    </div>


</body>

<script src="https://code.jquery.com/jquery-3.6.4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var expensesButton = document.getElementById('expensesButton');
        var incomingButton = document.getElementById('incomingButton');
        var meterFields = document.getElementById('home');
        var addRoomFields = document.getElementById('menu1');

        expensesButton.addEventListener('click', function() {
            meterFields.style.display = 'block';
            addRoomFields.style.display = 'none';
        });

        incomingButton.addEventListener('click', function() {
            meterFields.style.display = 'none';
            addRoomFields.style.display = 'block';
        });
    });


    document.addEventListener('DOMContentLoaded', function() {
        var displayMeterdata = document.getElementById('displayMeterdata');
        var displayRoomdata = document.getElementById('displayRoomdata');
        var meterData = document.getElementById('meterData');
        var roomData = document.getElementById('roomData');

        displayMeterdata.addEventListener('click', function() {
            meterData.style.display = 'block';
            roomData.style.display = 'none';
        });

        displayRoomdata.addEventListener('click', function() {
            meterData.style.display = 'none';
            roomData.style.display = 'block';
        });
    });

    new DataTable('#example', {
        ordering: false
    })

    function openbillModel() {
        $('#tle').html(`Bill Incoming/Expenses`)
        // $('#elect_Btn').attr("name", `submit`)
        $('#update_bill_data').attr("type", 'button')
        $('#update_bill_data').attr("name", `submit`)
        $('#update_bill_data').attr("id", `elect_Btn`)
        $('#elect_Btn').val('Submit')
        $('#combinedForm')[0].reset()
        $('#month').val('')
        $('#click_tabs .nav-link.active').removeClass("active");
        document.getElementById("menu1").style.display = "none";
        document.getElementById("home").style.display = "none";
        var meterContElements = document.getElementsByClassName("meterCont");
        while (meterContElements.length > 0) {
            meterContElements[0].remove();
        }
        document.querySelector(".viewData").style.display = "none";
        document.querySelector(".addData").style.display = "block";
        document.getElementById("tableContainer").style.display = "none";
        document.getElementById("roomtableContainer").style.display = "none";
        document.querySelector(".inputBx").style.display = "block";
        document.getElementById("addFieldButton").style.display = "block";
        // document.querySelector(".meterCont").style.display="none";



        $('.selectpicker').selectpicker('deselectAll');
        $('#billModel').modal('show');
    }

    // Function to close the registration modal
    function closebillModel() {
        $('#billModel').modal('hide');
    }

    $('#openbillModel').on('click', openbillModel);
    // function hideDropDown() {
    //     //  $('.dropdown-menu').hide();
    //     $('.dropdown-menu ').removeClass('show');

    // }
    function showRooms() {
        var selectedOptions = $("#select_room").val();
        var existingFields = $(".room-fields");
        // $('.dropdown-menu').hide();

        // document.querySelector('.dropdown-menu').style.display ="none";
        existingFields.each(function() {
            var optionName = $(this).data("option-name");

            if (optionName && (!selectedOptions || selectedOptions.indexOf(optionName) === -1)) {
                $(this).remove();
            }
        });

        if (selectedOptions && selectedOptions.length > 0) {
            selectedOptions.forEach(function(option) {
                var existingField = existingFields.filter("[data-option-name='" + option + "']");
                if (existingField.length === 0) {
                    var inputWrapper =
                        "<div class='input-wrapper room-fields mt-3' data-option-name='" + option + "'>" +
                        "<div class='row'>" +
                        "<p>" + option + " " + "Bill" + "</p>" +
                        "<div class='col-md-3'><label>Units</label><input type='text' name='room_units[" + option + "]' class='room-fields' data-option-name='" + option + "'></div>" +
                        "<div class='col-md-3'><label>Incoming</label><input type='text' name='room_bill[" + option + "]' class='room-fields' data-option-name='" + option + "'></div>" +
                        "<div class='col-md-3'><label>Date</label><input type='date' style='' name='room_date[" + option + "]' class='room-fields' autocomplete='off' data-option-name='" + option + "'></div>" +
                        "<div class='col-md-3'><label>Kept By</label><select name='bill_kept_by[" + option + "]' style='padding:12px;background:#fff;'>" +
                        "<option selected disabled>Select</option>";

                    <?php
                    foreach ($adminList as $admin) {
                        echo "inputWrapper += \"<option value='" . strtolower(str_replace(' ', '', $admin)) . "'>$admin</option>\";";
                    }
                    ?>

                    inputWrapper += "</select></div>" +
                        "</div>" +
                        "</div>";

                    $("#combinedForm #menu1").append(inputWrapper);

                }
            });
        }
        // $('.dropdown-menu').hide();
    }

    // Add Meters 
    function createDynamicField(index) {
        var dynamicFieldDiv = document.createElement('div');
        dynamicFieldDiv.className = 'meterCont';
        dynamicFieldDiv.innerHTML = `
        <div class="row">
        <div class="col-md-4">
                <label for="meter_name_${index}" class="mb-1">Name</label>
                <input type="text" name="meter_name[${index}]">
            </div>
            <div class="col-md-4">
        <label for="meter_img_${index}" class="mb-1">Document</label>
        <input type="file" name="meter_img[${index}]" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps">
    </div>
            <div class="col-md-4">
                <label for="meter_units_${index}" class="mb-1">Units</label>
                <input type="text" name="meter_units[${index}]">
            </div>
            <div class="col-md-4">
                <label for="meter_bill_${index}" class="mb-1">Bill</label>
                <input type="text" name="meter_bill[${index}]">
            </div>
            <div class="col-md-4">
                <label for="meter_date_${index}" class="mb-1"> Date</label>
                <input type="date" name="meter_date[${index}]">
            </div>
            <div class="col-md-4">
                <label for="meter_status_${index}" class="mb-1">Status</label>
                <select name="meter_status[${index}]" class="w-100" style="padding:12px;background:#fff;">
                    <option selected>Select</option>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
           </div>`;
        return dynamicFieldDiv;
    }

    function addDynamicField() {
        var container = document.getElementById('dynamicFieldsContainer');
        var index = container.children.length;
        var newDynamicField = createDynamicField(index);
        container.appendChild(newDynamicField);
    }

    //     function removeDynamicField(button) {
    //     var container = document.getElementById('dynamicFieldsContainer');
    //     var fieldToRemove = button.parentNode.parentNode; // Navigate up to the parent div containing the field
    //     container.removeChild(fieldToRemove);
    // }



    $(document).ready(function() {
        $(document).on('click', '#elect_Btn', function() {
            var formData = new FormData($("#combinedForm")[0]);
            // console.log(...formData);

            $.ajax({
                url: 'ajax_call.php?type=add_bill',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    console.log(response)
                    // Display response in a temporary modal
                    var temporaryModal = $('<div class="modal fade" id="temporaryModal" tabindex="-1" role="dialog" aria-labelledby="temporaryModalLabel" aria-hidden="true">' +
                        '<div class="modal-dialog" role="document">' +
                        '<div class="modal-content">' +
                        '<div class="modal-body">' +
                        '<p>' + response + '</p>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');

                    $('body').append(temporaryModal);

                    temporaryModal.modal('show');

                    setTimeout(function() {
                        temporaryModal.modal('hide');
                        temporaryModal.remove(); // Remove the modal from DOM after hiding
                        window.location.reload();
                    }, 1000);
                },
            });
        });
    });


    function activateExpensesTab() {
        $('#click_tabs a[href="#home"]').tab('show');
        // activateIncomingTab()
    }

    function activateIncomingTab() {
        $('#click_tabs a[href="#menu1"]').tab('show');
    }
    $(document).ready(function() {
        $('.dropdown-item').on('click', function() {
            $(this).remove(); // Remove the selected month from the dropdown menu
        });
    });

    // For Editing
    $(document).on('click', '.edit-button', function() {
        let monthExp = $(this).data('month');
        // console.log(monthExp)
        editopenbillModel(monthExp);
        // activateExpensesTab();
    });

    function editopenbillModel(monthExp) {
        // $('#expensesButton').addClass('active');
        $('.modal-title').html('Update Bill')
        $('#elect_Btn').attr("name", 'update_bill')
        $('#elect_Btn').attr("id", 'update_bill_data')
        $('#update_bill_data').attr("type", 'submit')
        $('#update_bill_data').val('Update')
        document.getElementById("addFieldButton").style.display = "none";

        $('#billModel').modal('show');
        document.querySelector(".viewData").style.display = "none";
        document.querySelector(".addData").style.display = "block";
        document.querySelector(".inputBx").style.display = "block";
        document.getElementById("roomtableContainer").style.display = "none";
        document.getElementById("tableContainer").style.display = "none";
        // document.querySelector("meterCont").style.display = "block";

        if (monthExp) {
            // $("#combinedForm #menu1").empty();
            $.ajax({
                type: "POST",
                url: "ajax_call.php",
                data: {
                    "month": monthExp,
                    "type": "edit_bill"
                },
                success: function(response) {

                    console.log(response)
                    // return false;
                    let data = JSON.parse(response);
                    console.log(data)


                    $('#month').val(data.month);

                    console.log(data.meterData.length, "Meter Data")
                    if (data.meterData && data.meterData.length > 0) {
                        $('.meterCont').remove();
                        data.meterData.forEach((meterData, index) => {

                            var meterCont = `<div class="meterCont"><div class="row">
                            <div class="col-md-4">
                              <label for="meter_name_${index}" class="mb-1">Name</label>
                              <input type="text" name="meter_name[${index}]" value="${meterData.meter_name}">
                            </div>
                            <div class="col-md-4">
    <label for="meter_img_${index}" class="mb-1">Document</label>
    <div class='image-preview'>
        <!-- Check if the file is an image -->
        <img src="./${meterData.meter_img}" alt='Expense Image' onclick="showImage('${meterData.meter_img}')"  style='height:50px; display: ${meterData.meter_img.endsWith('.pdf') ? 'none' : 'inline-block'}'>
        <!-- Check if the file is a PDF -->
        <span class="pdf-icon" style="display: ${meterData.meter_img.endsWith('.pdf') ? 'inline-block' : 'none'}">
            <!-- Provide a link to download the PDF -->
            <a href="./${meterData.meter_img}" download>
                <!-- Insert PDF icon here -->
                <img src="pdf-icon.png" alt="PDF Icon" style="height:50px;">
            </a>
        </span>
        <!-- Input file for image upload -->
        <input type='file' name="meter_img[${index}]" style='display: none;'>
        <!-- Button to trigger file upload -->
        <button type='button' class='btn btn-sm btn-primary btn-upload'>Upload</button>
    </div>
</div>

                            <div class="col-md-4">
                                <label for="meter_units_${index}" class="mb-1">Units</label>
                                <input type="text" name="meter_units[${index}]" value="${meterData.meter_units}">
                            </div>
                            <div class="col-md-4">
                                <label for="meter_bill_${index}" class="mb-1">Bill</label>
                                <input type="text" name="meter_bill[${index}]" value="${meterData.meter_bill}">
                            </div>
                            <div class="col-md-4">
                                <label for="meter_date_${index}" class="mb-1"> Date</label>
                                <input type="date" name="meter_date[${index}]" value="${meterData.meter_date}">
                            </div>
                            <div class="col-md-4">
                                <label for="meter_status_${index}" class="mb-1">Status</label>
                                <select name="meter_status[${index}]" class="w-100" style="padding:12px;background:#fff;">
                                    <option selected>Select</option>
                                    <option value="Paid"${meterData.meter_status === 'Paid' ? 'selected' : ''}>Paid</option>
                                    <option value="Pending"${meterData.meter_status === 'Pending' ? 'selected' : ''}>Pending</option>
                                </select>
                            </div>
                            </div></div>`

                            $("#combinedForm #home").append(meterCont);
                        });

                        $(".btn-upload").click(function() {
                            $(this).prev("input[type=file]").click();
                        });

                    }

                    if (data.billData && data.billData.length > 0) {
                        $('.input-wrapper').remove();
                        // $("#combinedForm #menu1").empty();

                        data.billData.forEach(billData => {

                            var inputWrapper =
                                "<div class='input-wrapper room-fields mt-3' data-option-name='" + billData.room_name + "'>" +
                                "<div class='row'>" +
                                "<div class='d-flex justify-content-between align-items-center '>" +
                                "<p>" + billData.room_name + " Bill</p>" +
                                "<i class='bx bxs-trash-alt text-danger remove-room' data-room-name='" + billData.room_name + "'></i>" +
                                "</div>" +
                                "<div class='col-md-3'>" +
                                "<label>Units</label>" +
                                "<input type='text' name='room_units[" + billData.room_name + "]' class='room-fields' value='" + billData.room_units + "'>" +
                                "</div>" +
                                "<div class='col-md-3'>" +
                                "<label>Bill</label>" +
                                "<input type='text' name='room_bill[" + billData.room_name + "]' class='room-fields' value='" + billData.room_bill + "'>" +
                                "</div>" +
                                "<div class='col-md-3'>" +
                                "<label>Date</label>" +
                                "<input type='date' name='room_date[" + billData.room_name + "]' class='room-fields' value='" + billData.room_date + "' autocomplete='off'>" +
                                "</div>" +
                                "<div class='col-md-3'>" +
                                "<label>Kept By</label>" +
                                "<select name='bill_kept_by[" + billData.room_name + "]' id='select_admin_" + billData.room_name + "' style='padding:12px;background:#fff;'>" +
                                "<option selected disabled>Select</option>";

                            // Use PHP to dynamically generate the options
                            <?php
                            $sql = "SELECT username FROM admin_details";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $admin = $row["username"];

                                    echo "inputWrapper += \"<option value='" . strtolower(str_replace(' ', '', $admin)) . "'>$admin</option>\";";
                                }
                            }
                            ?>

                            inputWrapper += "</select>" +
                                "</div>" +
                                "</div>" +
                                "</div>";

                            $("#combinedForm #menu1").append(inputWrapper);
                            $("#select_admin_" + billData.room_name).val(billData.bill_kept_by)
                        });


                        $('#select_room').selectpicker('val', data.rooms);


                    } else {
                        console.log("error")
                    }

                }
            });
        }




        $("#combinedForm #menu1").on("click", ".remove-room", function() {
            var deleteButton = $(this);
            $('#confirmationModal').modal('show');

            $('#confirmDeleteBtn').on('click', function() {
                $('#confirmationModal').modal('hide');

                // console.log("clicked");
                var optionNameToRemove = deleteButton.data("room-name");
                var monthExp = $('#month').val();
                console.log(optionNameToRemove);
                console.log(monthExp);

                $.ajax({
                    type: "POST",
                    url: "ajax_call.php",
                    data: {
                        "optionNameToRemove": optionNameToRemove,
                        "month": monthExp,
                        "type": "delete_room_data"
                    },
                    success: function(response) {
                        $("[data-option-name='" + optionNameToRemove + "']").remove();
                        $('#billModel').modal('hide');
                        window.location.reload();
                    },
                    error: function() {
                        console.log("Error deleting room data.");
                    }
                });
            });
        });
    }


    $(document).on('click', '.view-button', function() {
        let monthExp = $(this).data('month');
        // console.log(monthExp)
        viewopenbillModel(monthExp);
        // activateExpensesTab();
    });


    //View Only Modal

    function viewopenbillModel(monthExp) {

        $('.modal-title').html('View Bill');
        // $('#home').attr("id", 'meterData')
        // $('#menu1').attr("id", 'roomData')
        // $('.nav-link').removeClass('active');
        $('#billModel').modal('show');
        document.querySelector(".inputBx").style.display = "none";
        document.querySelector(".addData").style.display = "none";
        document.querySelector(".viewData").style.display = "block";
        document.getElementById("home").style.display = "none";
        document.getElementById("menu1").style.display = "none";
        document.getElementById("tableContainer").style.display = "block";
        document.getElementById("roomtableContainer").style.display = "block";



        if (monthExp) {
            $.ajax({
                type: "POST",
                url: "ajax_call.php",
                data: {
                    "month": monthExp,
                    "type": "edit_bill"
                },
                success: function(response) {
                    let data = JSON.parse(response);

                    $('#month').val(data.month);
                    let tableHtml = "<table border='1' style='width:100%'>" +
                        "<tr>" +
                        "<th>Meter Name</th>" +
                        "<th>Document</th>" +
                        "<th>Units</th>" +
                        "<th>Bill</th>" +
                        "<th>Date</th>" +
                        "</tr>";

                    // Add meter data to the table

                    if (data.meterData && data.meterData.length > 0) {
                        data.meterData.forEach(meterData => {
                            tableHtml += "<tr>" +
                                "<td>" + meterData.meter_name + "</td>" +
                                "<td>";

                            // Check if the file is a PDF
                            if (meterData.meter_img.endsWith('.pdf')) {
                                tableHtml += "<a href='" + meterData.meter_img + "' download>" +
                                    "<img src='pdf-icon.png' alt='PDF Icon' style='height:50px;'>" +
                                    "</a>";
                            } else {
                                tableHtml += "<img src='" + meterData.meter_img + "' alt='Meter Image' style='height:50px;'>";
                            }

                            tableHtml += "</td>" +
                                "<td>" + meterData.meter_units + "</td>" +
                                "<td>" + meterData.meter_bill + "</td>" +
                                "<td>" + meterData.meter_date + "</td>" +
                                "</tr>";
                        });
                    }


                    tableHtml += "</table>";

                    // Append the table to a container div
                    $("#tableContainer").html(tableHtml);


                    let roomTableHtml = "<table border='1' style='width:100%'>" +
                        "<tr>" +
                        "<th>Room Name</th>" +
                        "<th>Units</th>" +
                        "<th>Bill</th>" +
                        "<th>Date</th>" +
                        "<th>Kept</th>" +
                        "</tr>";


                    // Add bill data to the table
                    if (data.billData && data.billData.length > 0) {
                        data.billData.forEach(billData => {
                            roomTableHtml += "<tr>" +
                                "<td>" + billData.room_name + "</td>" +
                                "<td>" + billData.room_units + "</td>" +
                                "<td>" + billData.room_bill + "</td>" +
                                "<td>" + billData.room_date + "</td>" +
                                "<td style='text-transform:capitalize'>" + billData.bill_kept_by + "</td>" +
                                "</tr>";
                        });
                    }
                    roomTableHtml += "</table>";


                    $("#roomtableContainer").html(roomTableHtml);
                }
            });
        }
    }


    // //Month Filtering


    function applyMonthFilter(e) {
        let month = $(e).val()
        if (month === 'all') {
            month = '';
        }

        // console.log(month)
        $.ajax({
            type: "POST",
            url: "ajax_call.php",
            data: {
                "month": month,
                "type": "search"
            },
            success: function(response) {
                console.log(response)
                let tbody = $('#month_data')
                tbody.empty()
                let month_data = JSON.parse(response);
                month_data.forEach(function(data) {
                    // <td><img src='./uploads/${data.bill_image}' alt='Bill Image' style='width: 50px;'></td>
                    console.log(data)
                    let content = `<tr style='font-size:14px;'>
                                     <td>${data.month}</td>
                                     <td>${data.rooms}</td>
                                     <td>${data.totalMeterBill}</td>
                                     <td>${data.totalRoomBill}</td>
                                     <td><button class='edit-button' onclick='editopenbillModel("${data.month}")'>Edit</button>
                                     <button class='view-button' onclick='viewopenbillModel("${data.month}")'>View</button></td>
                                   </tr>`;
                    tbody.append(content);
                });
                updateSummary(month)

            }
        });
    }



    // //For Displaying Analytics

    function updateSummary(month = null) {
        // console.log(month)
        $.ajax({
            url: 'ajax_call.php',
            type: 'POST',
            data: {
                "type": "analytics",
                "month": month
            },
            success: function(response) {
                console.log(response)
                var data = JSON.parse(response);
                $('#displayAnalytics').empty();
                // $('#displayAnalytics').append('<div class="summary-heading">Summary Analytics</div>');
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        var value = data[key];
                        $('#displayAnalytics').append('<div class="summary-data"><span class="summary-label">' + key + '  ' + ':</span>' + '  ' + '  ' + '<p class="analy">' + value + '</p>' + '</div>');
                    }
                }
            },
            error: function() {
                alert('Error fetching summary data.');
            }
        });
    }
    $(document).ready(function() {
        updateSummary(null);
    });


    function showImage(imageSrc) {
        var modal = document.getElementById("imageModal");
        var modalImg = document.getElementById("img01");
        modal.style.display = "flex";
        modalImg.src = imageSrc;
    }

    function closeModal() {
        var modal = document.getElementById("imageModal");
        modal.style.display = "none";
    }
</script>

</html>