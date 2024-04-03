<?php

include("navbar.php");

?>
<?php

if (isset($_POST['update_expenses'])) {
    $month = $_POST['month_exp'];
    $adminId = $_POST['user_id'];
    $selectedOptions = $_POST['select_bill'];

    $folder = './uploads';

    if (!empty($selectedOptions)) {
        $successFlag = false;

        foreach ($selectedOptions as $key => $option) {

            $textFieldName = $_POST['values'][$key];
            $fileFieldName = $_FILES['files']['name'][$key];
            $fileFieldName_tmp = $_FILES['files']['tmp_name'][$key];

            $uploadedFilePath = '';
            if (!empty($fileFieldName) && !empty($fileFieldName_tmp)) {
                $uploadDirectory = 'uploads/';
                $uploadedFilePath = $uploadDirectory . $fileFieldName;

                // Move uploaded file to destination
                if (move_uploaded_file($fileFieldName_tmp, $uploadedFilePath)) {
                    // echo "File Uploaded";
                } else {
                    echo "Error uploading file for option $option.";
                    continue;
                }
            }

            // Fetch data from expenses_data to check if the record already exists
            $fetchQuery = "SELECT * FROM expenses_data WHERE admin_id = '$adminId' AND expense_name = '$option' AND month = '$month'";
            $fetchResult = mysqli_query($conn, $fetchQuery);

            if (mysqli_num_rows($fetchResult) > 0) {
                // Record exists, perform update
                $updateQuery = "UPDATE expenses_data 
                                SET exp_img = IF('$fileFieldName' != '', '$uploadedFilePath', exp_img), 
                                    exp_value = '$textFieldName' 
                                WHERE admin_id = '$adminId' AND expense_name = '$option' AND month = '$month'";

                if (mysqli_query($conn, $updateQuery)) {
                    $successFlag = true;
                } else {
                    echo "Error updating record for option $option: " . mysqli_error($conn);
                }
            } else {
                // Record doesn't exist, perform insert
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
            echo "<script>alert('Records updated successfully'); window.location='other_expenses.php';</script>";
        }
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

</head>

<body>
    <section class="home">
        <div class="head">
            <div>
                <p>Other Expenses</p>
            </div>
            <div>
                <a class="btn addBtn" data-bs-toggle="modal" data-bs-target="#myModal" role="button">Add More</a>
                <a class="button-1" role="button" id="openbillModel">Bill</a>
            </div>
        </div>
        <br>
        <div>
            <!-- <label for="filterMonth">Select Month:</label> -->
            <select id="filterMonth" style="width:30%" onchange="applyMonthFilter(this)">
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
                            $allExpenses = $row['all_expenses'];
                            $totalExpenses = $row['total_expenses'];

                            echo "<tr>";
                            echo "<td>{$monthName} {$year}</td>";
                            echo "<td style='text-transform:capitalize'>{$allExpenses}</td>";
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
                                        echo "<option value='" . strtolower(str_replace(' ', '', $expense)) . "'>$expense</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="month" class="mb-1">Month</label>
                                <input type="month" id="month" name="month" placeholder="Select Month">
                            </div>
                            <!-- <div class="inputBx">
                                <input type="submit" name="submit" id="subBtn" value="Submit">
                            </div> -->
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

    $(document).on('click', '.edit-button', function() {
        let monthExp = $(this).data('month');
        editopenbillModel(monthExp);
    });


    //For Editing
    function editopenbillModel(monthExp) {
        $('.modal-title').html(`Update Expenses`);


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
                // console.log(data);

                $('.input-wrapper').remove()
                $('#submitContainer').remove()
                data.expenses.forEach(expense => {

                    var inputWrapper =
                        "<div class='input-wrapper dynamic-fields mt-3'>" +
                        "<div class='row'>" +
                        "<div class='col-md-6'>" +
                        "<label>" + expense.expense_name + " " + "Bill" + "</label>" +
                        "<div class='image-preview'>" +
                        "<img src='" + expense.exp_img + "' alt='Expense Image' style='height:50px;'>" +
                        "<input type='file' name='files[]' class='dynamic-fields' style='display: none;' placeholder='Upload file for " + expense.expense_name + "'>" +
                        "<button type='button' class='btn btn-sm btn-primary btn-upload'>Upload Image</button>" +
                        "</div>" +
                        "</div>" +
                        "<div class='col-md-6'>" +
                        "<label>" + expense.expense_name + " " + "Expenses" + "</label>" +
                        "<input type='text' style='padding:11px 10px' name='values[]' class='dynamic-fields' placeholder='Value for " + expense.expense_name + "' value='" + expense.exp_value + "'>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                    $("#billForm").append(inputWrapper);

                });
                $(".btn-upload").click(function() {
                    $(this).prev("input[type=file]").click();
                });
                $("#billForm").append("<div class='inputBx' id='submitContainer'><div><input type='submit' name='submit' id='subBtn' value='Submit'></div></div>");
                $('#select_bill').selectpicker('val', data.exp_name);
                $('#month').val(data.month);
                $('#month_exp').val(data.month);
                $('#subBtn').attr("name", `update_expenses`);
                $('#subBtn').attr("id", `update_submit`);
                // $('#submitContainer').attr("id", `update_cont`);
                // $('#billForm').attr("id", `updateForm`);

                $('#billModel').modal('show');
            },
            error: function(error) {
                console.log('Error fetching record details:', error);
            }
        });
    }
</script>

</html>

function applyMonthFilter(e) {
let month = $(e).val()
// let type = "search";

if (month === 'all') {
month = '';
}
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
let content = `<tr>
    <td>${data.month}</td>
    <td>${data.rooms}</td>
    <td>${data.totalIncoming}</td>
    <td>${data.totalRoomBill}</td>
    <td><button class='edit-button' onclick="editopenbillModel(${data.id})">Edit</button></td>
</tr>`
tbody.append(content);
});
// updateSummary(month)
}
});
}


<div class="tab-content">
    <div class="tab-pane container" id="meterData" style="padding:0;margin-top: 10px">
        
    </div>
    <div class="tab-pane container fade" id="roomData" style="padding:0;margin-top: 10px">
       
    </div>
</div>

$(".remove-room").on("click", function () {
        console.log("clicked")
        var optionNameToRemove = $(this).data("option-name");
        console.log(optionNameToRemove)
    //     $.ajax({
    //     type: "POST",
    //     url: "ajax_call.php",
    //     data: {
    //         "optionNameToRemove": optionNameToRemove,
    //         "type": "delete_room_data"
    //     },
    //     success: function(response) {
    //         $("[data-option-name='" + optionNameToRemove + "']").remove();
    //     },
    //     error: function() {
    //         console.log("Error deleting room data.");
    //     }
    // });
    });


    if ($_POST['type'] === 'delete_room_data') {

    if (isset($_POST['optionNameToRemove'])) {
        $optionNameToRemove = mysqli_real_escape_string($conn, $_POST['optionNameToRemove']);
                                                                                                                                                                                        
        $sql = "DELETE FROM electricity_bill WHERE room_name = '$optionNameToRemove'";
        $result = $conn->query($sql);

        // Check if the deletion was successful
        if ($result) {
           
            echo json_encode(["status" => "success"]);
        } else {
     
            echo json_encode(["status" => "error", "message" => "Error deleting room data"]);
        }
    } 
}

function viewRecord(userId) {

if (userId == "") {
    alert('User Not valid.')
    return false;
}
$('.modal-title').html(`View User`)

$.ajax({
    type: "POST",
    url: "ajax_get_user.php",
    data: {
        "id": userId
    },
    success: function(response) {

        console.log(response)


        let userData = JSON.parse(response);

        let userDetailsHTML = `
    <strong>User ID:</strong> ${userData.id}<br>
    <strong>Name:</strong> ${userData.name}<br>
    <strong>Father's Name:</strong> ${userData.fatherName}<br>
    <strong>Mother's Name:</strong> ${userData.motherName}<br>
    <strong>Mobile No.:</strong> ${userData.mobile}<br>
    <strong>Aadhar No.:</strong> ${userData.aadhar}<br>
    <strong>Gender:</strong> ${userData.gender}<br>
    <strong>Email :</strong> ${userData.email}<br>
    <strong>Emergency Contact:</strong> ${userData.emergencyContact}<br>
    <strong>Permanent Address:</strong> ${userData.permanentAddress}<br>
    <strong>Occupation:</strong> ${userData.occupation}<br>
    // ... Add other fields as needed

    <strong>Room Details:</strong><br>
    <strong>Floor:</strong> ${userData.floor}<br>
    <strong>Room No.:</strong> ${userData.room_no}<br>
    <strong>Rent:</strong> ${userData.rent}<br>
    <strong>Security Deposit:</strong> ${userData.security_deposite}<br>
    <strong>Date:</strong> ${userData.date}<br>
`;

// Set the HTML content
// $('#userDetailsContent').html(userDetailsHTML);

// Hide the submit button
$('#subBtn').hide();

// Open the modal
openRegistrationModal('view',userDetailsHTML);
       
    }
});


}
