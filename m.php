<?php

include("navbar.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['submit']) {

    $bill_imagetmp = $_FILES['bill_image']['tmp_name'];
    $bill_imageName = $_FILES['bill_image']['name'];
    $units = $_POST['units'];
    $bill = $_POST['bill'];
    $month = $_POST['month'];
    $folder = './uploads';
    $checkingMonth = mysqli_query($conn, "SELECT `month` FROM `electricity_bill` WHERE `month`='$month'");
    if (mysqli_num_rows($checkingMonth) > 0) {
        echo "<script>alert('Selected Month Expenses Already Inserted.'); window.location='electricity_bill.php'</script>";
        die();
    }

    move_uploaded_file($bill_imagetmp, $folder . '/' .  $bill_imageName);

    $sql = "INSERT INTO electricity_bill (bill_image,units,bill,month) 
       VALUES ('$bill_imageName','$units','$bill','$month')";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error inserting electricity bill data: " . mysqli_error($conn);
    } else {
        echo "Electricity bill data inserted successfully.<br>";
    }


    $incomingExpenses = $_POST['incoming_expenses'];
    $roomNo = $_POST['room_no'];

    foreach ($incomingExpenses as $roomId => $incomingExpense) {

        $incomingExpense = empty($incomingExpense) ? 0 : mysqli_real_escape_string($conn, $incomingExpense);
        $roomId = mysqli_real_escape_string($conn, $roomNo[$roomId]);

        $stmtIncomingExpense = mysqli_prepare($conn, "INSERT INTO incoming_expenses (room_id, incoming_expense,month) VALUES ('$roomId', '$incomingExpense' ,'$month')");


        mysqli_stmt_bind_param($stmtIncomingExpense, "ss", $roomId, $incomingExpense);
        $resultIncomingExpense = mysqli_stmt_execute($stmtIncomingExpense);

        if (!$resultIncomingExpense) {
            echo "Error inserting incoming expense for room $roomId: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmtIncomingExpense);
    }
    if ($result) {
        echo "<script>alert('Data Inserted'); window.location='electricity_bill.php'</script>";
    }
}

?>
<!-- For Update -->
<?php
if (isset($_POST['update_bill'])) {

    $roomId = mysqli_real_escape_string($conn, $_POST['room_id']);
    $units = mysqli_real_escape_string($conn, $_POST['units']);
    $bill = mysqli_real_escape_string($conn, $_POST['bill']);
    $month = mysqli_real_escape_string($conn, $_POST['month']);

    $folder = './uploads';
    $bill_imageName = $_FILES['bill_image']['name'];
    $bill_imagetmp = $_FILES['bill_image']['tmp_name'];

    if (!empty($bill_imageName)) {
        move_uploaded_file($bill_imagetmp, $folder . '/' .  $bill_imageName);
    } else {
        $selectExistingImageQuery = "SELECT `bill_image` FROM `electricity_bill` WHERE `id`='$roomId'";
        $resultExistingImage = mysqli_query($conn, $selectExistingImageQuery);
        $rowExistingImage = mysqli_fetch_assoc($resultExistingImage);
        $bill_imageName = $rowExistingImage['bill_image'];
    }

    $updateQuery = "UPDATE electricity_bill SET bill_image = '$bill_imageName', units='$units', bill='$bill' WHERE month='$month'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {

        $incomingExpenses = $_POST['incoming_expenses'];
        $roomNo = $_POST['room_no'];
        foreach ($incomingExpenses as $roomToUpdate => $incomingExpense) {
            $roomIdToUpdate = mysqli_real_escape_string($conn, $roomNo[$roomToUpdate]);;
            $incomingExpenseToUpdate = mysqli_real_escape_string($conn, $incomingExpense);

            $updateIncomingQuery = "UPDATE incoming_expenses SET incoming_expense='$incomingExpenseToUpdate' WHERE room_id='$roomIdToUpdate' AND month='$month'";
            $updateIncomingResult = mysqli_query($conn, $updateIncomingQuery);

            if (!$updateIncomingResult) {
                echo json_encode(["error" => "Error updating incoming expenses for room $roomIdToUpdate"]);
                exit;
            }
        }

        // echo json_encode(["success" => "Data updated successfully"]);
    } else {
        echo json_encode(["error" => "Error updating data in the database"]);
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
    <style>
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
            font-size: 12px;
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
            width: 65%;
            height: 40px;
            overflow-y: auto;
            display: flex;
            justify-content: space-between;
            padding: 8px 20px;
            border-radius: 4px;
        }

        /* .summary-heading {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        } */

        .summary-data {
            display: flex;
            /* justify-content: space-between; */
            margin-bottom: 10px;
        }

        .summary-label {
            font-weight: bold;
            text-transform: capitalize;
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

        }

        .boxrooms td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
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
        <div style="display: flex;justify-content: space-between;">
            <select id="filterMonth" style="width:30%" onchange="applyMonthFilter(this)">
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

            <div id="displayAnalytics"></div>
        </div>
        <br>
        <div class="mainDash">

            <table id="example" style="box-sizing: border-box; box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.1);background: #fff;">
                <thead class="tablerow" id="t_data">
                    <tr>
                        <!-- <th>Bill Image</th> -->
                        <th>Month</th>
                        <th>Units</th>
                        <th>Expenses</th>
                        <th>Incoming</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="month_data">
                    <?php
                    $fetchDataQuery = "SELECT * FROM electricity_bill";
                    $result = mysqli_query($conn, $fetchDataQuery);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $billImage = $row['bill_image'];
                        $units = $row['units'];
                        $bill = $row['bill'];
                        $monthYear = $row['month'];

                        $month = new DateTime($row['month']);
                        $monthName = $month->format('F');
                        $year = $month->format('Y');

                        $roomId = $row['id'];
                        $fetchIncomingQuery = "SELECT `month`, SUM(`incoming_expense`) AS totalIncoming
                        FROM `incoming_expenses` WHERE `month`='$monthYear'
                        GROUP BY `month`";

                        $resultIncoming = mysqli_query($conn, $fetchIncomingQuery);
                        $totalIncomingRow = mysqli_fetch_assoc($resultIncoming);
                        $totalIncoming = $totalIncomingRow['totalIncoming'];

                        echo "<tr>";
                        // echo "<td><img src='./uploads/$billImage' alt='Bill Image' style='width: 50px;'></td>";
                        echo " <td>$monthName $year</td>";
                        echo "<td>$units</td>";
                        echo "<td>$bill</td>";
                        echo "<td>$totalIncoming</td>";
                        echo "<td><button class='edit-button' data-room-id='$roomId'>Edit</button></td>";
                        echo "</tr>";
                    }
                    // onclick='editRow($roomId)'
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="modal" tabindex="-1" role="dialog" id="billModel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tle">Admin Expenses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="billForm" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <!-- <input type="hidden" name="user_id" id="userId"> -->
                        <div class="row">
                            <div class="inputBx">
                                <div><label for="bill_image">Upload Bill Image:</label></div>
                                <div>
                                    <input type="file" name="bill_image" id="bill_image" class="pad" style="position:relative">
                                    <div id="bii_image" style="position: absolute; top:31px; right:25px; cursor: pointer;">
                                    </div>
                                </div>

                                <div class="inputBx">
                                    <label for="units">Units:</label>
                                    <input type="text" id="units" name="units" autocomplete="off"><br>
                                </div>

                                <div class="inputBx">
                                    <label for="bill">Price:</label>
                                    <input type="text" id="bill" name="bill" autocomplete="off"><br>
                                </div>

                                <div class="inputBx">
                                    <label for="select_floor">Rooms:</label>
                                    <select id="select_floor" name="select_floor" class="w-100">
                                    <option value="" selected>Select</option>
                                    <?php

                                    $sql = "SELECT room_no FROM rooms";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        $expenses_list = array();

                                        while ($row = $result->fetch_assoc()) {
                                            $room_list[] = $row["room_no"];
                                        }
                                    }
                                    foreach ($room_list as $room) {
                                        echo "<option value='" . strtoupper(str_replace(' ', '', $room)) . "'>$room</option>";
                                    }

                                    ?>
                                </select>
                                    <!-- <select id="select_floor" name="select_floor"> -->
                                        <!-- <option value="" selected disabled>Select Room</option> -->
                                        <!-- <option value="all_room" selected>Show All Rooms</option> -->
                                        <!-- <option value="ground_floor">Ground Floor</option>
                                    <option value="first_floor">First Floor</option> -->
                                    </select>
                                </div>

                                <div class="ground_flr">
                                    <input type="hidden" name="selected_room" class="selected_room">
                                    <div class="box_container boxrooms"></div>
                                </div>

                                <div class="inputBx">
                                    <label for="month">Month:</label>
                                    <input type="month" id="month" name="month" autocomplete="off"><br>
                                </div>

                                <br>
                                <div class="inputBx">
                                    <input type="submit" name="submit" id="subBtn" value="Submit">
                                </div>

                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script>
    new DataTable('#example', {
    ordering: false
})

    function openbillModel() {  
        $('#tle').html(`Admin Expenses`)
        $('#subBtn').attr("name", `submit`)
        $('#billForm')[0].reset()
        $('.ground_flr').hide()
        $('#billModel').modal('show');
    }

    // Function to close the registration modal
    function closebillModel() {
        $('#billModel').modal('hide');
    }


    $('#openbillModel').on('click', openbillModel);


    function showRooms() {
        let allotroom = document.getElementById("select_floor").value;
        let groundFlr = document.querySelector(".ground_flr");

        if (allotroom == "all_room") {
            groundFlr.style.display = "block";
            handel_ajax("all_room");
        } else {
            groundFlr.style.display = "block";
            handel_ajax(allotroom);
        }
    }




    function handel_ajax(allotroom, month = null, type = null) {    
        // console.log(month,type)
        let selectedRoom
        $.ajax({
            type: "POST",
            url: "ajax_call.php",
            data: {
                "floor": allotroom,
                "month": month,
                "type": type
            },
            success: function(response) {
                let groundFlr = document.querySelector(".ground_flr");
                groundFlr.style.display = "block";

                let res = JSON.parse(response);
                $('.boxrooms').empty();

                let tableHTML = `
                        <table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
                            <tr>
                                <th>Rooms</th>
                                <th>Incoming Expenses</th>
                            </tr>
                               `;
                let roomData = {};
                $.each(res, (i, val) => {
                    let isSelected = selectedRoom && val.room_no === selectedRoom ? 'selected' : '';

                    let occupancyStatus = determineOccupancyStatus(val.capacity, val.room_allocation_count);

                    function determineOccupancyStatus(roomCapacity, currentOccupancy) {
                        if (currentOccupancy == 0) {
                            return 'vacant';
                        } else if (currentOccupancy < roomCapacity) {
                            return 'half-filled';
                        } else {
                            return 'full';
                        }
                    }

                    let isDisabled = occupancyStatus === 'full' ? 'disabled' : '';

                    let expense = (val.incoming_expense !== undefined && val.incoming_expense !== null) ? val.incoming_expense : "";
                    tableHTML += `
                      <tr>
                          <td>
                              <div class="box ${isSelected}" onmousedown="startLongPress('${val.room_no}')" onmouseup="cancelLongPress()"               onclick="showUserDetails('${val.room_no}')">
                                  <div><input type="text" name="room_name[]" id="${val.id}" style="height:35px;" value="${val.room_no}" data-capacity="${val.room_allocation_count}" ${isDisabled} readonly>
                                  <input type ="hidden" name = "room_no[]" value="${val.id}"  ></div>
                              </div>
                          </td>
                          <td>
                          <input type="text" name="incoming_expenses[]" id="incoming_expenses_${val.id}" style="height:35px;" placeholder="Enter Value Of ${val.room_no}" value="${expense}">
                          </td>
                      </tr>
                  `;
                });
                tableHTML += `</table>`;
                $('.boxrooms').append(tableHTML);

            }
        });
    }

    // For Editing
    $('.edit-button').on('click', function() {
        // console.log("clicked")
        let roomId = $(this).data('room-id');
        editopenbillModel(roomId);
    });

    function editopenbillModel(roomId) {

        $('.modal-title').html(`Update Expenses`)
        $('#subBtn').attr("name", `update_bill`)
        $('#billModel').modal('show');

        if (roomId) {
            $.ajax({
                type: "POST",
                url: "ajax_bill_data.php",
                data: {
                    "room_id": roomId
                },
                success: function(response) {

                    // console.log(response)
                    let data = JSON.parse(response);

                    function loadURLToInputFiled(url, inputId) {
                        getImgURL(url, (imgBlob) => {
                            // Load img blob to input
                            // WIP: UTF8 character error
                            let fileName = url;
                            let file = new File([imgBlob], fileName, {
                                type: "image/jpeg",
                                lastModified: new Date().getTime()
                            }, 'utf-8');
                            let container = new DataTransfer();
                            container.items.add(file);
                            document.querySelector(`#${inputId}`).files = container.files;

                        })
                    }

                    function getImgURL(url, callback) {
                        var xhr = new XMLHttpRequest();
                        xhr.onload = function() {
                            callback(xhr.response);
                        };
                        xhr.open('GET', url);
                        xhr.responseType = 'blob';
                        xhr.send();
                    }

                    $('#bill_image').html(`<img onclick="showImage(this)" src="./uploads/${data.bill_image}" id="bill_image" width="40" height="40" >`);
                    loadURLToInputFiled(`./uploads/${data.bill_image}`, "bill_image")
                    $('#units').val(data.units);
                    $('#bill').val(data.bill);  
                    $('#month').val(data.month);
                    if (data.floor != "") {
                        // handel_ajax(data.floor);
                        // $('.all_room').show();
                        $('#select_floor').val('all_room');
                        handel_ajax("all_room", data.month, "edit_expense");
                    }

                }
            });
        }
    }

    //Month Filtering
    function applyMonthFilter(e) {
        let month = $(e).val()
        $.ajax({
            type: "POST",
            url: "ajax_call.php",
            data: {
                "month": month,
                "type": "search"
            },
            success: function(response) {
                let tbody = $('#month_data')
                tbody.empty()
                let month_data = JSON.parse(response);
                month_data.forEach(function(data) {
                    // <td><img src='./uploads/${data.bill_image}' alt='Bill Image' style='width: 50px;'></td>
                    // console.log(data)
                    let content = `<tr>
                        
                         <td>${data.month}</td>
                         <td>${data.units}</td>
                         <td>${data.bill}</td>
                         <td>${data.totalIncoming}</td>
                         <td><button class='edit-button' onclick="editopenbillModel(${data.id})">Edit</button></td>
                         </tr>`
                    tbody.append(content);
                });
                updateSummary(month)

            }
        });
    }

    //For Displaying Analytics
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
                // console.log(response)
                var data = JSON.parse(response);
                $('#displayAnalytics').empty();
                // $('#displayAnalytics').append('<div class="summary-heading">Summary Analytics</div>');
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        var value = data[key];
                        $('#displayAnalytics').append('<div class="summary-data"><span class="summary-label">' + key + '  ' + ':</span>'+'  ' + '  ' + value + '</div>');
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
</script>

</html>

