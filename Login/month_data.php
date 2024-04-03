<?php
include("navbar.php");

if (isset($_POST['update_rent'])) {
    $r_id = $_POST['id'];
    $selectedRoom = $_POST['select_room'];
    $keptBy = $_POST['select_admin'];

    // Retrieve values for the selected room
    $rentDate = $_POST['dates'];
    $rentMonth = $_POST['months'];
    $rentValue = $_POST['values'];

    // Check if any of the required fields are empty
    if (empty($selectedRoom) || empty($keptBy) || empty($rentDate) || empty($rentMonth) || empty($rentValue)) {
        echo json_encode(["error" => "Some required fields are empty. Please fill them out."]);
        exit();
    }

    // Update the rent information for the selected room
    $sql = "UPDATE month_rent 
            SET date = '$rentDate', 
                month = '$rentMonth',
                room_rent = '$rentValue',
                kept_by = '$keptBy' 
            WHERE id = '$r_id'";

    mysqli_query($conn, $sql);
    // {
        // echo "<script>window.location='month_data.php';</script>";
        // echo json_encode(["success" => "Record updated successfully"]);
    // } else {
        // echo json_encode(["error" => "Error updating record: " . mysqli_error($conn)]);
    // }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="user_details.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
    <title>Document</title>
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

        .head p {
            color: #707070;
            font-weight: 600;
            font-size: 20px;
            /* margin-bottom: 20px; */
            border-bottom: 4px solid var(--sub_btn);
            display: inline-block;
            letter-spacing: 1px
        }

        .bootstrap-select button {
            border: 1px solid #000;
            padding: 8px 10px;
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

        .edit-button {
            padding: 5px 10px;
            margin-right: 20px;
            border-radius: 5px;
            font-size: 10px;
            border: none;
        }

        #select_admin {
            padding: 10px;
        }

        .filterData {
            display: flex;
            justify-content: space-between
        }

        #displayAnalytics {
            border: 1px solid #000000ad;
            background-color: #f9f9f9;
            /* width: 30%; */
            height: 40px;
            /* overflow-y: auto; */
            display: flex;
            justify-content: space-between;
            padding: 8px 20px;
            border-radius: 4px;
            width: 100%;
        }

        #filterMonth {

            width: 100%;
        }

        
        .analy {
            font-weight: bold;
            font-size: 14px;
            margin-left: 5px;
            margin-bottom: 0;
        }
        .summary-data {
            display: flex;
            /* justify-content: space-between; */
            margin-bottom: 10px;
        }

        .summary-label {
            font-weight: bold;
            text-transform: capitalize;
            font-size: 14px;
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

            .row .mb-1 {
                font-size: 12px;
                font-weight: bold;
            }

            .bootstrap-select button {
                padding: 5px 8px;
                font-size: 14px;
            }

            .col-md-4 label {
                font-size: 12px;
                font-weight: bold;
            }

            .rent-fields{
                padding: 5px;
            }

            /* .col-md-4{
                padding:0px 5px;
            }

            .inputBx input[type="submit"] {
                padding: 0;
                margin-top: 10px;
            } */

            table.dataTable tbody tr{
                font-size: 12px;
            }

        }
    </style>
</head>

<body>
    <section class="home">
        <div class="head">
            <p>Monthly Rent</p>
            <a class="button-1" role="button" id="monthdataModel">Add Room Rent</a>
        </div>
        <br>

        <div class="row justify-content-between gx-md-0 gy-2">

            <!-- <label for="filterMonth">Select Month:</label> -->
            <div class="col-md-3">
                <select id="filterMonth" style='font-size:14px'>
                    <?php
                    $monthsQuery = "SELECT DISTINCT `month` FROM month_rent ORDER BY `month`";
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
            <!-- <select id="filterRoom" style="width:30%"> -->
            <!-- onchange="roomFilter(this)" -->
            <?php
            // $roomsQuery = "SELECT DISTINCT `room_name` FROM month_rent";
            // $roomsResult = mysqli_query($conn, $roomsQuery);
            // echo "<option value='' selected disabled>Select Room</option>";
            // // echo "<option value='all'>All</option>";
            // while ($roomRow = mysqli_fetch_assoc($roomsResult)) {
            //     $roomValue = $roomRow['room_name'];
            //     echo "<option value='$roomValue' style='text-transform:uppercase;'>$roomValue</option>";
            // }
            ?>
            <!-- </select> -->
            <div class="col-md-3">
                <div id="displayAnalytics"></div>
            </div>
        </div>

        <br>
        <div class="mainDash">
            <table id="example" style="box-sizing: border-box; box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.1);background: #fff;">
                <thead class="tablerow" id="t_data">
                    <tr>
                        <th>Room Name</th>
                        <th>Date</th>
                        <th>Month</th>
                        <th>Rent</th>
                        <th>Kept By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="fil">
                    <?php
                    $selectQuery = "SELECT id,room_name , date , month , room_rent , kept_by
                    FROM month_rent
                    ORDER BY month ";
                    $result = mysqli_query($conn, $selectQuery);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {

                            $month = new DateTime($row['month']);
                            $monthName = $month->format('F');
                            $year = $month->format('Y');

                            $r_id = $row['id'];
                            echo "<tr style='font-size:14px'>";
                            echo "<td style='text-transform:UPPERCASE;'>{$row['room_name']}</td>";
                            echo "<td>{$row['date']}</td>";
                            echo "<td>{$monthName} {$year}</td>";
                            echo "<td>{$row['room_rent']}</td>";
                            echo "<td style='text-transform:capitalize;'>{$row['kept_by']}</td>";
                            echo "<td><button class='edit-button' data-r-id='$r_id'>Edit</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Error: " . $selectQuery . "<br>" . mysqli_error($conn);
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="modal" tabindex="-1" role="dialog" id="monthData">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tle">Monthly Rent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rentForm" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="select_room" class="mb-1">Select Room</label>
                                <select id="select_room" name="select_room" class="selectpicker w-100" aria-label="Default select example" data-live-search="true" onchange="showFields()">
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
                                    foreach ($room_list as $rml) {
                                        echo "<option value='" . strtolower(str_replace(' ', '', $rml)) . "'>$rml</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="select_admin" class="mb-1">Kept By</label>
                                <select id="select_admin" name="select_admin" class="w-100">
                                    <option value="" selected>Select</option>
                                    <?php

                                    $sql = "SELECT username FROM admin_details";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        $expenses_list = array();

                                        while ($row = $result->fetch_assoc()) {
                                            $admin_list[] = $row["username"];
                                        }
                                    }
                                    foreach ($admin_list as $admin) {
                                        echo "<option value='" . strtolower(str_replace(' ', '', $admin)) . "'>$admin</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
<script>
    new DataTable('#example', {
        ordering: false
    })


    function monthdataModel() {
        $('#tle').html(`Monthly Rent`)
        $('#rentSub').attr("name", `submit`)
        $('#update_r').attr("name", `submit`)
        $('#update_r').attr("id", `rentSub`)
        $('#rentSub').val('Submit')
        $('.input-wrapper').remove();
        $('#rentForm')[0].reset();
        $('#select_room').selectpicker('val', null);
        $('#select_admin').selectpicker('val', null);
        $('#rentForm').attr('action', 'javascript:void(0)');
        $('#monthData').modal('show');
    }

    function closebillModel() {
        $('#monthData').modal('hide');
    }

    $('#monthdataModel').on('click', monthdataModel);

    function showFields() {
    var selectedOption = $("#select_room").val();
    var existingFields = $(".rent-fields");

    existingFields.each(function() {
        var optionName = $(this).data("option-name");

        if (optionName && optionName !== selectedOption) {
            $(this).remove();
        }

    });

    if (selectedOption) {
        var existingField = existingFields.filter("[data-option-name='" + selectedOption + "']");
        if (existingField.length === 0) {
            var inputWrapper =
                "<div class='input-wrapper rent-fields mt-3' data-option-name='" + selectedOption + "'>" +
                "<div class='row'>" +
                "<div class='col-md-4'><label>Date</label><input type='date' name='rent_dates' class='rent-fields' data-option-name='" + selectedOption + "'></div>" +
                "<div class='col-md-4'><label>Month</label><input type='month' name='rent_months' class='rent-fields' data-option-name='" + selectedOption + "'></div>" +
                "<div class='col-md-4'><label style='text-transform:capitalize;'>" + selectedOption + " " + "Rent" + "</label><input type='text' style='' name='values' class='rent-fields' autocomplete='off' data-option-name='" + selectedOption + "'></div>" +
                "</div>" +
                "</div>";

            $("#rentForm").append(inputWrapper);
        }
        $("#submitContainer").appendTo("#rentForm").show();
    } else {
        $("#submitContainer").hide();
    }
    if ($("#submitContainer").length === 0) {
        $("#rentForm").append("<div class='inputBx' id='submitContainer'><div><input type='submit' name='submit' id='rentSub' value='Submit'></div></div>");
    }
}






    $(document).ready(function() {
        $(document).on('click', '#rentSub', function() {
            // console.log("clicked")
            addRoomrent();

        });

        function addRoomrent() {

            var formData = new FormData($('#rentForm')[0]);

            formData.append("type", "room_rnt");
            $(".rent-fields").each(function(index, element) {
                var fieldName = $(element).find("input").attr("name");
                var fieldValue = $(element).find("input").val();

                formData.append(fieldName, fieldValue);
            });

            // for (var pair of formData.entries()) {
            //     console.log(pair[0] + ': ' + pair[1]);
            // }

            $.ajax({
                url: "add_expense_ajax.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    let data = JSON.parse(response)
                    if (data.success) {
                        // console.log(data)
                        var modal = $('<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-body">' + data.success + '</div></div></div></div>');

                        $('body').append(modal);
                        modal.modal('show');

                        setTimeout(function() {
                            modal.modal('hide');
                            modal.remove();
                            location.reload();
                        }, 1000);
                        $('#billmodal').modal('hide');
                    } else {
                        console.log(response)
                    }
                }
            });
        }

    });



    //For Editing


    $(document).on('click', '.edit-button', function() {
        let r_id = $(this).attr('data-r-id');
        editopenbillModel(r_id);
    });



    function editopenbillModel(r_id) {
        // console.log(r_id);
        $('.modal-title').html('Update');

        $('#rentForm').attr('action', '')
        $('#monthData').modal('show');
        if (r_id != undefined) {
            $.ajax({
                url: 'add_expense_ajax.php',
                method: 'POST',
                data: {
                    id: r_id,
                    "type": "edit_rent"
                },
                success: function(response) {
                    let data = JSON.parse(response);
                    // console.log(data);

                    if (data.rent && data.rent.length > 0) {
                        // // Remove existing input fields
                        $('.input-wrapper').remove();
                        $('#submitContainer').remove();


                        data.rent.forEach(rent => {
                            var inputWrapper =
                                "<div class='input-wrapper dynamic-fields mt-3'>" +
                                "<div class='row'>" +
                                "<div class='col-md-4'>" +
                                "<label>Date</label>" +
                                "<input type='date' name='dates' class='dynamic-fields' value='" + rent.date + "'>" +
                                "</div>" +
                                "<div class='col-md-4'>" +
                                "<label>Month</label>" +
                                "<input type='month' name='months' class='dynamic-fields' value='" + rent.month + "'>" +
                                "</div>" +
                                "<div class='col-md-4'>" +
                                "<label style='text-transform:capitalize;'>" + rent.room_name + " " + "Rent" + "</label>" +
                                "<input type='text' name='values' class='dynamic-fields' value='" + rent.room_rent + "'>" +
                                "</div>" +
                                "</div>" +
                                "</div>";
                            $("#rentForm").append(inputWrapper);
                        });

                        $("#rentForm").append("<div class='inputBx' id='submitContainer'><div><input type='submit' name='submit' id='rentSub' value='Submit'></div></div>");
                        $('#select_room').selectpicker('val', data.rent[0].room_name);
                        $('#select_admin').selectpicker('val', data.rent[0].kept_by);

                        $('#id').val(data.id);
                        $('#rentSub').attr("name", "update_rent");
                        $('#rentSub').attr("id", "update_r");
                        $('#update_r').val("Update");

                        $('#monthData').modal('show');
                    } else {
                        console.log("error")
                    }
                },
                error: function(error) {
                    console.log('Error fetching record details:', error);
                }
            });
        }
    }


    //For Month Filtering

    $(document).on('change', '#filterMonth', function() {
        let month = $(this).val()
        let room = $('#filterRoom :selected').val()
        room = room != "" ? room : null
        // console.log(room, month)
        monthFilter(month, room)
    })

    function monthFilter(month, room = null) {
        // let month = $(e).val();
        // console.log(month)
        $.ajax({
            type: "POST",
            url: "add_expense_ajax.php",
            data: {
                "month": month,
                "selected_room": room,
                "type": "search_month"
            },
            success: function(response) {
                let tbody = $('#fil')
                tbody.empty()
                let month_data = JSON.parse(response);
                month_data.forEach(function(data) {
                    let content = `<tr style='font-size:14px'>
                         <td style='text-transform:capitalize;'>${data.room_name}</td>
                         <td>${data.date}</td>
                         <td>${data.month}</td>
                         <td>${data.room_rent}</td>
                         <td style='text-transform:capitalize;'>${data.kept_by}</td>
                         <td><button class='edit-button' data-r-id='${data.id}'>Edit</button></td>
                         </tr>`
                    tbody.append(content);
                });
                updateMonthInc(month, room)
            }
        });
    }


    // Month Analysis

    function updateMonthInc(month = null, room = null) {
        // console.log(month)
        $.ajax({
            url: 'add_expense_ajax.php',
            type: 'POST',
            data: {
                "type": "month_anyl",
                "room_sel": room,
                "month": month
            },
            success: function(response) {
                console.log(response)
                var data = JSON.parse(response);
                $('#displayAnalytics').empty();
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

    // $(document).ready(function() {
    //     updateMonthInc(null);
    // });

    // function updateMonthInc(month = null, room = null) {
    //     $.ajax({
    //         url: 'add_expense_ajax.php',
    //         type: 'POST',
    //         data: {
    //             "type": "month_anyl",
    //             "month": month,
    //             "selected_room": room
    //         },
    //         success: function(response) {
    //             console.log(response);
    //             var data = JSON.parse(response);
    //             $('#displayAnalytics').empty();
    //             for (var key in data) {
    //                 if (data.hasOwnProperty(key)) {
    //                     var value = data[key];
    //                     $('#displayAnalytics').append('<div class="summary-data"><span class="summary-label">' + key + '  ' + ':</span>' + '  ' + '  ' + value + '</div>');
    //                 }
    //             }

    //             // Call the monthFilter function with the updated month and room
    //             monthFilter(month, room);
    //         },
    //         error: function() {
    //             alert('Error fetching summary data.');
    //         }
    //     });
    // }

    $(document).ready(function() {
        updateMonthInc(null, null);
        $(document).on('change', '#filterMonth, #filterRoom', function() {
            let month = $('#filterMonth').val();
            let room = $('#filterRoom :selected').val();
            console.log(month, room)
            updateMonthInc(month, room);
        });
    });



    //For Room Filtering

    $(document).on('change', '#filterRoom', function() {
        let room = $(this).val()
        let month = $('#filterMonth :selected').val()
        month = month != "" ? month : null
        // console.log(room, month)
        roomFilter(room, month)
    })

    function roomFilter(room, month = null) {
        // let room = $(e).val();
        // console.log(room);
        $.ajax({
            type: "POST",
            url: "add_expense_ajax.php",
            data: {
                "room_name": room,
                "month_name": month,
                "type": "search_room"
            },
            success: function(response) {
                // console.log(response)
                let tbody = $('#fil')
                tbody.empty()
                let room_data = JSON.parse(response);
                room_data.forEach(function(data) {
                    // <td><img src='./uploads/${data.bill_image}' alt='Bill Image' style='width: 50px;'></td>
                    // console.log(data)
                    let content = `<tr>
                 <td style='text-transform:capitalize;'>${data.room_name}</td>
                 <td>${data.date}</td>
                 <td>${data.month}</td>
                 <td>${data.room_rent}</td>
                 <td style='text-transform:capitalize;'>${data.kept_by}</td>
                 <td><button class='edit-button' data-r-id='${data.id}'>Edit</button></td>
                 </tr>`
                    tbody.append(content);
                });
                // updateRoomsInc(room)
            }
        });
    }

    // Room Analysis

    // function updateRoomsInc(room = null) {
    //     // console.log(month)
    //     $.ajax({
    //         url: 'add_expense_ajax.php',
    //         type: 'POST',
    //         data: {
    //             "type": "room_anyl",
    //             "room_name": room
    //         },
    //         success: function(response) {
    //             console.log(response)
    //             var data = JSON.parse(response);
    //             $('#displayAnalytics').empty();
    //             for (var key in data) {
    //                 if (data.hasOwnProperty(key)) {
    //                     var value = data[key];
    //                     $('#displayAnalytics').append('<div class="summary-data"><span class="summary-label">' + key + '  ' + ':</span>' + '  ' + '  ' + value + '</div>');
    //                 }
    //             }
    //         },
    //         error: function() {
    //             alert('Error fetching summary data.');
    //         }
    //     });
    // }
    // $(document).ready(function() {
    //     updateRoomsInc(null);
    // });
</script>

</html>