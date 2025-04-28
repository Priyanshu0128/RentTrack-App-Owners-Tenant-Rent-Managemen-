<?php

include("navbar.php");

?>
<?php
// if (isset($_POST['update_expenses'])) {
//     $month = $_POST['month_exp'];
//     $adminId = $_POST['user_id'];
//     $selectedOptions = $_POST['select_bill'];


//     $folder = './uploads';

//     if (!empty($selectedOptions)) {
//         $successFlag = false;

//         foreach ($selectedOptions as $key => $option) {

//             $textFieldName = $_POST['values'][$key];
//             $fileFieldName = $_FILES['files']['name'][$key];
//             $fileFieldName_tmp = $_FILES['files']['tmp_name'][$key];

//             $uploadedFilePath = '';
//             if (!empty($fileFieldName) && !empty($fileFieldName_tmp)) {
//                 $uploadDirectory = 'uploads/';
//                 $uploadedFilePath = $uploadDirectory . $fileFieldName;

//                 // Move uploaded file to destination
//                 if (move_uploaded_file($fileFieldName_tmp, $uploadedFilePath)) {
//                     // echo "File Uploaded";
//                 } else {
//                     echo "Error uploading file for option $option.";
//                     continue;
//                 }
//             }


//             $sql = "UPDATE expenses_data 
//                     SET exp_img = IF('$fileFieldName' != '', '$uploadedFilePath', exp_img), 
//                         exp_value = '$textFieldName' 
//                     WHERE admin_id = '$adminId' AND expense_name = '$option' AND month = '$month'";

//             // $sql = "INSERT INTO expenses_data (admin_id, expense_name, exp_img, exp_value, month) 
//             //         VALUES ('$adminId', '$option', '$uploadedFilePath', '$textData', '$month')";


//             if (mysqli_query($conn, $sql)) {
//                 $successFlag = true;
//             } else {
//                 echo "Error updating record for option $option: " . mysqli_error($conn);
//             }
//         }

//         if ($successFlag) {
//             echo "<script>alert('Records updated successfully'); window.location='other_expenses.php';</script>";
//         }
//     }
// }


// if (isset($_POST['update_expenses'])) {
//     $month = $_POST['month_exp'];
//     $adminId = $_POST['user_id'];
//     $selectedOptions = $_POST['select_bill'];

//     $folder = './uploads';

//     // $fetchStoredOptionsQuery = "SELECT expense_name FROM expenses_data WHERE admin_id = '$adminId' AND month = '$month'";
//     // $storedOptionsResult = mysqli_query($conn, $fetchStoredOptionsQuery);
//     // $storedOptions = mysqli_fetch_all($storedOptionsResult, MYSQLI_ASSOC);
//     // $storedOptions = array_column($storedOptions, 'expense_name');

//     if (!empty($selectedOptions)) {
//         $successFlag = false;

//         foreach ($selectedOptions as $key => $option) {

//             $textFieldName = $_POST['values'][$key];
//             $fileFieldName = $_FILES['files']['name'][$key];
//             //    die();
//             $fileFieldName_tmp = $_FILES['files']['tmp_name'][$key];

//             $uploadedFilePath = '';
//             if (!empty($fileFieldName) && !empty($fileFieldName_tmp)) {
//                 $uploadDirectory = 'uploads/';
//                 $uploadedFilePath = $uploadDirectory . $fileFieldName;

//                 if (move_uploaded_file($fileFieldName_tmp, $uploadedFilePath)) {
//                 } else {
//                     echo "Error uploading file for option $option.";
//                     continue;
//                 }
//             }

//             $fetchQuery = "SELECT * FROM expenses_data WHERE admin_id = '$adminId' AND expense_name = '$option' AND month = '$month'";
//             $fetchResult = mysqli_query($conn, $fetchQuery);

//             if (mysqli_num_rows($fetchResult) > 0) {

//                 $updateQuery = "UPDATE expenses_data 
//                                 SET exp_img = IF('$fileFieldName' != '', '$uploadedFilePath', exp_img), 
//                                     exp_value = '$textFieldName' 
//                                 WHERE admin_id = '$adminId' AND expense_name = '$option' AND month = '$month'";

//                 if (mysqli_query($conn, $updateQuery)) {
//                     $successFlag = true;
//                 } else {
//                     echo "Error updating record for option $option: " . mysqli_error($conn);
//                 }
//             } else {

//                 $insertQuery = "INSERT INTO expenses_data (admin_id, expense_name, exp_img, exp_value, month) 
//                 VALUES ('$adminId', '$option', '$uploadedFilePath', '$textFieldName', '$month')";


//                 if (mysqli_query($conn, $insertQuery)) {
//                     $successFlag = true;
//                 } else {
//                     echo "Error inserting record for option $option: " . mysqli_error($conn);
//                 }
//             }
//         }

//         // $optionsToDelete = array_diff($storedOptions, $selectedOptions);
//         // foreach ($optionsToDelete as $optionToDelete) {
//         //     $deleteQuery = "DELETE FROM expenses_data WHERE admin_id = '$adminId' AND expense_name = '$optionToDelete' AND month = '$month'";
//         //     if (mysqli_query($conn, $deleteQuery)) {
//         //         $successFlag = true;
//         //     } else {
//         //         echo "Error deleting record for option $optionToDelete: " . mysqli_error($conn);
//         //     }
//         // }

//         if ($successFlag) {
//             echo "<script>alert('Records updated successfully'); window.location='other_expenses.php';</script>";
//         }
//     }
// }




if (isset($_POST['update_expenses'])) {




    $month = $_POST['month_exp'];
    $adminId = $_POST['user_id'];
    $selectedOptions = $_POST['select_bill'];


    $folder = './uploads';

    // if (!empty($selectedOptions)) {
    $successFlag = false;



    foreach ($selectedOptions as $key => $option) {
        // print_r($_POST['values']);
        // die();

        $textFieldName = $_POST['values'][$option];

        $fileFieldName = $_FILES['files']['name'][$option];
        $fileFieldName_tmp = $_FILES['files']['tmp_name'][$option];


        $uploadedFilePath = '';
        if (!empty($fileFieldName) && !empty($fileFieldName_tmp)) {
            $uploadDirectory = 'uploads/';
            $uploadedFilePath = $uploadDirectory . $fileFieldName;

            if (move_uploaded_file($fileFieldName_tmp, $uploadedFilePath)) {
            } else {
                echo "Error uploading file for option $option.";
                continue;
            }
        }

        $fetchQuery = "SELECT * FROM expenses_data WHERE expense_name = '$option' AND month = '$month'";
        $fetchResult = mysqli_query($conn, $fetchQuery);

        if (mysqli_num_rows($fetchResult) > 0) {
            $updateQuery = "UPDATE expenses_data SET";
            if (!empty($uploadedFilePath)) {
                $updateQuery .= " exp_img = '$uploadedFilePath',";
            }
            $updateQuery .= " exp_value = '$textFieldName' WHERE expense_name = '$option' AND month = '$month'";

            if (mysqli_query($conn, $updateQuery)) {
                $successFlag = true;
            } else {
                echo "Error updating record for option $option: " . mysqli_error($conn);
            }
        } else {
            $insertQuery = "INSERT INTO expenses_data (admin_id, expense_name, exp_img, exp_value, month) 
                            VALUES ('$adminId', '$option', '$uploadedFilePath', '$textFieldName', '$month')";

            if (mysqli_query($conn, $insertQuery)) {
                $successFlag = true;
            } else {
                echo "Error inserting record for option $option: " . mysqli_error($conn);
            }
        }
    }

    if ($successFlag) {
        // echo "<script>window.location='other_expenses.php';</script>";
    }
}
// }






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

        .tag {
            background-color: #e0e2df;
            color: #707070;
            padding: 5px;
            margin: 5px;
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
            border-bottom: 4px solid var(--sub_btn);
            display: inline-block;
            letter-spacing: 1px
        }

        .addBtn {
            background-color: #707070;
            color: #ffffff;
            /* pointer-events:none; */
        }

        .inputContainer {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            border: 2px solid #3498db;
            padding: 10px 20px;
            margin-top: 20px;
            column-gap: 10px;
        }

        .edit-button {
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


        .bootstrap-select button {
            border: 1px solid #000;
            padding: 8px 10px;
        }

        .input-wrapper {
            /* display: flex; */
            padding: 20px;
            box-sizing: border-box;
            border: 2px solid #000;
            background: #dadee0;

        }

        .input-wrapper label {
            font-weight: bold;
            color: #000;
            margin: 5px;
            text-transform: capitalize;
        }

        input.dynamic-fields {
            background-color: #ffffff;
        }

        .delete-button {
            padding: 5px 10px;
            margin-right: 20px;
            border-radius: 5px;
            font-size: 12px;
            border: none;
        }

        .image-preview {
            display: flex;
            justify-content: space-between;
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
                font-weight: 500;
                padding: 10px
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

            .inputBx select {
                font-size: 10px;
            }

            .inputBx input[type="submit"] {
                font-weight: bold;
            }

            .modal-title {
                font-size: 16px;
            }

            table.dataTable tbody th,
            table.dataTable tbody td {
                padding: 8px 10px;
                font-size: 12px;
            }

            #filterMonth {
                width: 100%;
            }

            .addBtn {
                font-size: 12px;
            }

            input.dynamic-fields {
                background-color: #ffffff;
                font-size: 12px;
                padding: 5px;
            }

            .input-wrapper label {

                font-size: 11px;

            }

            .row .mb-1 {
                font-size: 12px;
                font-weight: bold;
            }

            .btn .dropdown-toggle .btn-light {
                padding: 8px 10px;
                font-size: 12px;
            }

            #month {
                padding: 5px 10px;
            }

            .summary-label {
                font-weight: bold;
                text-transform: capitalize;
                font-size: 13px;
            }

            .analy {
                font-weight: bold;
                font-size: 13px;
                margin-left: 5px;
            }

            #filterMonth {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <section class="home">
        <div class="head">
            <div>
                <p>Other Expenses</p>
            </div>
            <div>
                <a class="btn addBtn" data-bs-toggle="modal" data-bs-target="#myModal" role="button">Add More</a>
                <a class="button-1" role="button" id="openbillModel">Expenses</a>
            </div>
        </div>
        <br>
        <div>
            <!-- <label for="filterMonth">Select Month:</label> -->
            <div class="row justify-content-between gx-md-0 gy-2">
                <div class="col-md-3">
                    <select id="filterMonth" onchange="applyMonthFilter(this)" style="font-size: 14px;">
                        <?php
                        $monthsQuery = "SELECT DISTINCT `month` FROM expenses_data ORDER BY `month`";
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
                <div class="col-md-3">
                    <div id="displayAnalytics"></div>
                </div>
            </div>
        </div>
        <br>
        <div class="mainDash">
            <table id="example" style="box-sizing: border-box; box-shadow: 0px 0px 5px 2px rgba(0, 0, 0, 0.1);background: #fff;">
                <thead class="tablerow" id="t_data">
                    <tr>
                        <th>Month</th>
                        <th>Expenses Name</th>
                        <th>Expenses</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="month_data">
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
                            $allExpenses = str_replace(',', ' , ', $row['all_expenses']);
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
                </tbody>
            </table>
        </div>

    </section>
    <!-- Add Bill Modal -->
    <div class="modal" tabindex="-1" role="dialog" id="billModel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tle">Expenses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="billForm" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="month_exp" id="month_exp" value="month">
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="select_bill" class="mb-1">Add Bill</label>
                                <select id="select_bill" name="select_bill[]" class="selectpicker w-100" aria-label="Default select example" data-live-search="true" onchange="showFields()" multiple>
                                    <option value="" disabled>Select</option>
                                    <?php

                                    $sql = "SELECT expenses_name FROM expenses_list";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        $expenses_list = array();

                                        while ($row = $result->fetch_assoc()) {
                                            $expenses_list[] = $row["expenses_name"];
                                        }
                                    }
                                    foreach ($expenses_list as $expense) {
                                        echo "<option value='" . strtolower(str_replace(' ', ' ', $expense)) . "'>$expense</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="month" class="mb-1">Month</label>
                                <input type="month" id="month" name="month" placeholder="Select Month">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Add Input Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Expenses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-primary" id="showInputBtn" style="float:inline-end">Add</button>
                    <br><br>
                    <?php
                    $sql = "SELECT id, expenses_name FROM expenses_list";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $expenseId = $row['id'];
                            $expenseName = $row['expenses_name'];
                            echo '<span class="badge rounded-pill tag" style="cursor:pointer;" data-id="' . $expenseId . '">' . $expenseName . '<span class="badge text-dark" onclick="hideBadge(' . $expenseId . ', \'newSubmitButtonId\')">&#10060;</span></span>';
                        }
                    } else {
                        echo "No expenses found";
                        echo "<br>";
                    }
                    ?>
                    <div id="inputSection" style="display: none; margin-top:10px;">
                        <label for="inputField" style="font-weight:bold;">Add Exepenses</label>
                        <input type="text" class="form-control" id="inputField" autocomplete="off">
                    </div><br>
                    <button type="button" class="btn btn-success mt-3" id="submitButton">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Image Modal -->
    <!-- <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Expense Image Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img id="modalImage" src="" alt="Expense Image" style="width: 100%;">
      </div>
    </div>
  </div>
</div> -->

    <div id="imageModal" class="modal">
        <span class="close-cross" onclick="closeModal()" style="    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 40px;
    cursor: pointer;
    color: #fff;">&times;</span>
        <img class="modal-content" id="img01" style="max-width:800px;width:100%;                                ">
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

    function openbillModel() {

        $('#tle').html(`Expenses`)
        $('#subBtn').attr("name", `submit`)


        $('#update_submit').attr("name", `submit`);
        $('#update_submit').attr("id", `subBtn`);
        $('#subBtn').val('Submit');

        $('.input-wrapper').remove();
        $('#billForm')[0].reset();
        $('.selectpicker').selectpicker('deselectAll');
        $('#billModel').modal('show');
        $('#billForm').attr('action', 'javascript:void(0)')
    }

    function closebillModel() {
        $('#billModel').modal('hide');
    }

    $('#openbillModel').on('click', openbillModel);

    var selectedOptionsOrder = [];

    function showFields() {

        var selectedOptions = $("#select_bill").val();
        var existingFields = $(".dynamic-fields");

        existingFields.each(function() {
            var fileInput = $(this).find("input[type=file]");
            var optionName = fileInput.length > 0 ? fileInput.attr("name").split("_")[1] : null;

            if (optionName && (!selectedOptions || selectedOptions.indexOf(optionName) === -1)) {
                $(this).remove();
            }
        });

        if (selectedOptions && selectedOptions.length > 0) {
            selectedOptions.forEach(function(option) {
                var existingField = existingFields.filter("[data-option-name='" + option + "']");
                if (existingField.length === 0) {
                    var inputWrapper =
                        "<div class='input-wrapper dynamic-fields mt-3' data-option-name='" + option + "'>" +
                        "<div class='row'>" +
                        "<div class='col-md-6'><label>" + option + " " + "" + "</label><input type='file' name='files[" + option + "]' class='dynamic-fields'></div>" +
                        "<div class='col-md-6'><label>" + option + " " + "RS" + "</label><input type='text' style='padding:11px 10px' name='values[" + option + "]' class='dynamic-fields' autocomplete='off'></div>" +
                        "</div>" +
                        "</div>";

                    $("#billForm").append(inputWrapper);
                }
            });
            $("#submitContainer").appendTo("#billForm").show();
        } else {
            $("#submitContainer").hide();
        }
        if ($("#submitContainer").length === 0) {
            $("#billForm").append("<div class='inputBx' id='submitContainer'><div><input type='submit' name='submit' id='subBtn' value='Submit'></div></div>");
        }
    }






    $(document).ready(function() {
        $(document).on('click', '#subBtn', function() {
            // console.log("clicked")
            addExp();

        });

        function addExp() {

            var formData = new FormData($('#billForm')[0]);

            formData.append("type", "add_exp");
            $(".dynamic-fields").each(function(index, element) {
                var fieldName = $(element).find("input").attr("name");
                var fieldValue = $(element).find("input").val();

                formData.append(fieldName, fieldValue);
            });

            // for (var pair of formData.entries()) {
            //     console.log(pair[0] + ': ' + pair[1]);
            // }

            $.ajax({
                url: "other_ajax_details.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    let data = JSON.parse(response)
                    if (data.success) {
                        var modalSuccess = $('<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-body">' + data.success + '</div></div></div></div>');

                        $('body').append(modalSuccess);
                        modalSuccess.modal('show');

                        setTimeout(function() {
                            modalSuccess.modal('hide');
                            modalSuccess.remove();
                            location.reload();
                        }, 1000);
                        $('#billmodal').modal('hide');
                    } else if (data.error) {
                        var modalError = $('<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-body">' + data.error + '</div></div></div></div>');

                        $('body').append(modalError);
                        modalError.modal('show');

                        setTimeout(function() {
                            modalError.modal('hide');
                            modalError.remove();
                        }, 1000);
                    } else {
                        console.log(response);
                    }
                }
            });
        }

    });


    $(document).on('click', '.edit-button', function() {
        let monthExp = $(this).data('month');
        editopenbillModel(monthExp);
    });


    //For Editing
    function editopenbillModel(monthExp) {
        $('.modal-title').html(`Update Expenses`);

        // $('#subBtn').val('Update')
        $('#billForm').attr('action', '')

        $('#billModel').modal('show');
        // console.log(monthExp)
        $.ajax({
            url: 'other_ajax_details.php',
            method: 'POST',
            data: {
                month: monthExp,
                "type": "editExp"
            },
            success: function(response) {
                let data = JSON.parse(response);
                console.log(data);

                $('.input-wrapper').remove()
                $('#submitContainer').remove()
                data.expenses.forEach(expense => {

                    var inputWrapper =
                        "<div class='input-wrapper dynamic-fields mt-3' data-option-name='" + expense.expense_name + "'>" +
                        "<div class='row'>" +
                        "<div class='col-md-6'>" +
                        "<label>" + expense.expense_name + "</label>" +
                        "<div class='image-preview'>" +
                        (expense.exp_img.endsWith('.pdf') ?
                            "<span class='pdf-icon'>" +
                            "<a href='" + expense.exp_img + "' download>" +
                            "<img src='pdf-icon.png' alt='PDF Icon' style='height:50px;'>" +
                            "</a>" +
                            "</span>" :
                            "<img src='" + expense.exp_img + "' alt='Expense Image' onclick='showImage(\"" + expense.exp_img + "\")' style='height:50px;'>") +
                        "<input type='file' name='files[" + expense.expense_name + "]' class='dynamic-fields' style='display: none;'>" +
                        "<button type='button' class='btn btn-sm btn-primary btn-upload'>Upload</button>" +
                        "</div>" +
                        "</div>" +
                        "<div class='col-md-6'>" +
                        "<label>" + expense.expense_name + " " + "RS" + "</label>" +
                        "<input type='text' style='padding:11px 10px' name='values[" + expense.expense_name + "]' class='dynamic-fields' value='" + expense.exp_value + "'>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                    $("#billForm").append(inputWrapper);

                });

                $(".btn-upload").click(function() {
                    $(this).prev("input[type=file]").click();
                });

                // $(".image-preview img").click(function() {
                //     var imageSrc = $(this).attr("src");

                //     if (typeof imageSrc !== "undefined") {
                //         $("#modalImage").attr("src", imageSrc);
                //         $("#imageModal").modal("show");
                //     } else {
                //         console.log("Image source is undefined.");
                //     }
                // });

                $("#billForm").append("<div class='inputBx' id='submitContainer'><div><input type='submit' name='submit' id='subBtn' value='Submit'></div></div>");
                $('#select_bill').selectpicker('val', data.exp_name);
                $('#month').val(data.month);
                $('#month_exp').val(data.month);
                $('#subBtn').attr("name", `update_expenses`);
                $('#subBtn').attr("id", `update_submit`);
                $('#update_submit').val('Update');

                // $('#submitContainer').attr("id", `update_cont`);
                // $('#billForm').attr("id", `updateForm`);

                $('#billModel').modal('show');
            },
            error: function(error) {
                console.log('Error fetching record details:', error);
            }
        });
    }


    //For Month Filtering  
    function applyMonthFilter(e) {
        let month = $(e).val();
        $.ajax({
            type: "POST",
            url: "other_ajax_details.php",
            data: {
                "month": month,
                "type": "search"
            },
            success: function(response) {
                let tbody = $('#month_data');
                tbody.empty();
                let month_data = JSON.parse(response);
                console.log(month_data);

                if (month_data.tableData && month_data.tableData.length > 0) {
                    let content = `<tr data-month="${month_data.month}" style="font-size: 14px;">
                    <td >${month_data.month}</td>
                    <td class="expense-name" style='text-transform:capitalize'>${month_data.tableData.map(data => data['Expense Name']).join(', ')}</td>
                    <td>${month_data.totalExpenses}</td>
                    <td><button class='edit-button' data-month="${month_data.month}">Edit</button></td>
                </tr>`;
                    tbody.append(content);
                } else {
                    month_data.forEach(function(monthDetails) {
                        let content = `<tr data-month="${monthDetails.month}" style="font-size: 14px;">
                        <td>${monthDetails.month}</td>
                        <td class="expense-name" style='text-transform:capitalize'>${monthDetails.tableData.map(data => data['Expense Name']).join(', ')}</td>
                        <td>${monthDetails.totalExpenses}</td>
                        <td><button class='edit-button' data-month="${monthDetails.month}">Edit</button></td>
                    </tr>`;
                        tbody.append(content);
                    });
                }
                updateSummary(month)
            }
        });


    }


    function updateSummary(month = null) {
        // console.log(month)
        $.ajax({
            url: 'other_ajax_details.php',
            type: 'POST',
            data: {
                "type": "month_analytics",
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
                        $('#displayAnalytics').append('<div class="summary-data"><span class="summary-label">' + key + ':</span><span class="analy">' + value + '</span></div>');
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


    //For Displaying Tag
    document.getElementById('showInputBtn').addEventListener('click', function() {
        document.getElementById('inputSection').style.display = 'block';
    });

    $(document).ready(function() {
        $('#showInputBtn').on('click', function() {
            $('#inputSection').show();
        });

        $('#submitButton').on('click', function() {
            var inputValue = $('#inputField').val();

            $.ajax({
                type: 'POST',
                url: 'other_ajax_details.php',
                data: {
                    expenses_name: inputValue,
                    "type": "add"
                },
                success: function(response) {
                    var data = JSON.parse(response);
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


                        $('#myModal').modal('hide');
                        $('#inputField').val('');
                        $('#inputSection').hide();
                    }
                }
            });

        });

    });




    //For Deleting 
    function hideBadge(expenseId, newSubmitButtonId) {
        var badge = $('.tag[data-id="' + expenseId + '"]');
        badge.hide();
        $('#submitButton').attr('id', newSubmitButtonId);
        $('#newSubmitButtonId').attr('data-tagid', expenseId);
    }

    $(document).on('click', '#newSubmitButtonId', function() {
        var expense_id = $(this).attr('data-tagid');


        $.ajax({
            type: 'POST',
            url: 'other_ajax_details.php',
            data: {
                id: expense_id,
                "type": "delete"
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // console.log(data)
                    var delmodal = $('<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-body">' + data.success + '</div></div></div></div>');

                    $('body').append(delmodal);
                    delmodal.modal('show');

                    setTimeout(function() {
                        delmodal.modal('hide');
                        delmodal.remove();
                        location.reload();
                    }, 1000);

                    $('#myModal').modal('hide');
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });


    //For Expenses Data Deleting

    $(document).ready(function() {
        $('.delete-button').on('click', function() {
            var month = $(this).data('month');
            if (confirm('Are you sure you want to delete data for ' + month + '?')) {
                $.ajax({
                    type: 'POST',
                    url: 'other_ajax_details.php',
                    data: {
                        month: month,
                        "type": "delete_data"
                    },
                    success: function(response) {
                        alert(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            }
        });
    });


    // $(document).ready(function(e) {
    //     $('#update_submit').on('click', function() {
    //         $.ajax({
    //             type: "POST",
    //             url: "add_expenses_ajax.php",
    // data: new FormData(this),
    //             success: function(response) {
    //                 response = JSON.parse(response);
    //                 if (response.success) {
    //                     alert(response.message);
    //                     window.location = 'other_expenses.php';
    //                 } else {
    //                     alert(response.message);
    //                 }
    //             },
    //             error: function(error) {
    //                 console.log('Error submitting form:', error);
    //             }
    //         });

    //     });
    // });

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