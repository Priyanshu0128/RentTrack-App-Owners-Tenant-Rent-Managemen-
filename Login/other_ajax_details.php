<?php

include("conn.php"); 
//For Inserting Expenses Data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['type']) && $_POST['type'] == "add_exp") {
    $adminId = $_POST['user_id'];
    $selectedOptions = $_POST['select_bill'];
    $month = $_POST['month'];

    // Check if the month expenses already exist
    $checkingMonth = mysqli_query($conn, "SELECT `month` FROM `expenses_data` WHERE `month`='$month'");
    if (mysqli_num_rows($checkingMonth) > 0) {
        echo json_encode(["error" => "Selected Month Expenses Already Inserted And Same bill cannot be added multiple times"]);
        die();
    }

    // Check for empty fields
    if (empty($selectedOptions) || empty($_FILES['files']['name']) || empty($_POST['values'])) {
        echo json_encode(["error" => "Some required fields are empty. Please fill them out."]);
        exit();
    }

    $successFlag = false;
    $errorMessages = [];

    foreach ($selectedOptions as $option) {
       
        $textFieldName = isset($_POST['values'][$option]) ? $_POST['values'][$option] : '';
        $fileFieldName = isset($_FILES['files']['name'][$option]) ? $_FILES['files']['name'][$option] : '';
        $fileFieldName_tmp = isset($_FILES['files']['tmp_name'][$option]) ? $_FILES['files']['tmp_name'][$option] : '';

        // Validate required fields
        if (empty($textFieldName) || empty($fileFieldName) || empty($fileFieldName_tmp)) {
            $errorMessages[] = "Some required fields are empty for option $option. Please fill them out.";
            continue;
        }

        // Upload file
        $uploadDirectory = 'uploads/';
        $uploadedFilePath = $uploadDirectory . $fileFieldName;
        if (!move_uploaded_file($fileFieldName_tmp, $uploadedFilePath)) {
            $errorMessages[] = "Failed to upload file for option $option.";
            continue;
        }

        // Insert into database
        $sql = "INSERT INTO expenses_data (admin_id, expense_name, exp_img, exp_value, month) 
                VALUES ('$adminId', '$option', '$uploadedFilePath', '$textFieldName', '$month')";

        if (mysqli_query($conn, $sql)) {
            $successFlag = true;
        } else {
            $errorMessages[] = "Error: " . mysqli_error($conn);
        }
    }

    // Return appropriate response
    if ($successFlag) {
        echo json_encode(["success" => "Record inserted successfully"]);
    } else {
        echo json_encode(["error" => implode("<br>", $errorMessages)]);
    }
}



//For Deleting Expenses
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == "delete") {
//     if (isset($_POST['id'])) {
//         $expense_id = $_POST['id'];
//         $deleteSql = "DELETE FROM expenses_list WHERE id = $expense_id";
//         if (mysqli_query($conn, $deleteSql)) {
//             echo "Expense deleted successfully";
//             die();
//         } else {
//             echo 'Error deleting expense: ' . mysqli_error($conn);
//             die();
//         }
//     } else {
//         echo 'ExpenseId not provided';
//         die();
//     }
// }

// //Inserting the
// elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['type'] == "add") {
//     if (isset($_POST['expenses_name']) && !empty($_POST['expenses_name'])) {
//         $expense_name = $_POST['expenses_name'];

//     $sql = "INSERT INTO expenses_list (expenses_name) VALUES ('$expense_name')";

//     if (mysqli_query($conn, $sql)) {
//         echo "Added successfully";
//         die();
//     } else {
//         echo "Error to add expense";
//         die();
//     }
// }else {
//     echo "Please Add expense";
//     die();
// }
// } 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['type'] == "delete") {
        if (isset($_POST['id'])) {
            $expense_id = $_POST['id'];
            $deleteSql = "DELETE FROM expenses_list WHERE id = $expense_id";
            if (mysqli_query($conn, $deleteSql)) {
                echo json_encode(["success" => "Expense deleted successfully"]);
            } else {
                echo json_encode(["error" => 'Error deleting expense: ' . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(["error" => 'ExpenseId not provided']);
        }
    } elseif ($_POST['type'] == "add") {
        if (isset($_POST['expenses_name']) && !empty($_POST['expenses_name'])) {
            $expense_name = $_POST['expenses_name'];
            $sql = "INSERT INTO expenses_list (expenses_name) VALUES ('$expense_name')";
            if (mysqli_query($conn, $sql)) {
                echo json_encode(["success" => "Added successfully"]);
            } else {
                echo json_encode(["error" => "Error adding expense: " . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(["error" => "Please Add expense"]);
        }
    }
   

}


// Assuming you have a database connection in $conn variable

// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['month']) &&$_POST['type'] == "delete_data") {
//     $month = $_POST['month'];

//     $deleteQuery = "DELETE FROM expenses_data WHERE month = '$month'";
//     $deleteResult = mysqli_query($conn, $deleteQuery);

//     if ($deleteResult) {
//         echo "Data for $month deleted successfully.";
//     } else {
//         echo "Error deleting data: " . mysqli_error($conn);
//     }
// } else {
//     echo "Invalid request.";
// }




if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "editExp") {

    $monthExp = $_POST['month'];
   
    $formattedMonth = date("Y-m", strtotime($monthExp));
    $query = "SELECT expense_name, exp_value, exp_img FROM expenses_data WHERE month = '$formattedMonth'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $expenseDataArray = array();
 $exp_name =[];
        while ($row =  mysqli_fetch_assoc($result)) {
            $expenseData = array(
                'expense_name' => $row['expense_name'],
                'exp_value' => $row['exp_value'],
                'exp_img' => $row['exp_img']
                // 'month' => $formattedMonth,
            );
            $expenseDataArray[] = $expenseData;
            $exp_name[] =$row['expense_name'];
        }

        // echo json_encode($expenseDataArray);

        $sumQuery = "SELECT SUM(exp_value) AS total_expenses FROM expenses_data WHERE month = '$formattedMonth'";
        $sumResult = mysqli_query($conn, $sumQuery);
        $sumRow = mysqli_fetch_assoc($sumResult);

        echo json_encode(array(
            'expenses' => $expenseDataArray,
            'month' => $formattedMonth,
            'total_expenses' => $sumRow['total_expenses'],
            'exp_name' => $exp_name
        ));

        mysqli_free_result($result);
    } else {
        error_log("Error: " . $query . "\n" . mysqli_error($conn));
        echo json_encode(array('error' => 'Error fetching records'));
    }
}


    // Check if type is "search"
   
   
    if (!empty($_POST['type']) && $_POST['type'] == "search") {
        try {
            $month = $_POST['month'];
    
            if ($month == "all") {
                $select_months = mysqli_query($conn, "SELECT DISTINCT `month` FROM expenses_data");
                $allMonthsData = array();
    
                while ($row = mysqli_fetch_assoc($select_months)) {
                    $currentMonth = $row['month'];
    
                    $select_month = mysqli_query($conn, "SELECT * FROM expenses_data WHERE `month` = '$currentMonth'");
                    $selectMonth = mysqli_fetch_all($select_month, MYSQLI_ASSOC);
    
                    $tableData = array();
                    $totalExpenses = 0;
    
                    foreach ($selectMonth as $expense) {
                        $rowData = array(
                            'Expense Name' => $expense['expense_name'],
                            'Expense Value' => $expense['exp_value'],
                        );
                        array_push($tableData, $rowData);
    
                        $totalExpenses += $expense['exp_value'];
                    }
    
                    $monthDate = new DateTime($currentMonth);
                    $monthName = $monthDate->format('F');
                    $year = $monthDate->format('Y');
    
                    $allMonthsData[] = array(
                        'month' => $monthName . " " . $year,
                        'tableData' => $tableData,
                        'totalExpenses' => $totalExpenses
                    );
                }
    
                echo json_encode($allMonthsData);
            } else {
                $select_month = mysqli_query($conn, "SELECT * FROM expenses_data WHERE `month` = '$month'");
                $selectMonth = mysqli_fetch_all($select_month, MYSQLI_ASSOC);
    
                $tableData = array();
                $totalExpenses = 0;
    
                foreach ($selectMonth as $expense) {
                    $rowData = array(
                        'Expense Name' => $expense['expense_name'],
                        'Expense Value' => $expense['exp_value'],
                    );
                    array_push($tableData, $rowData);
    
                    $totalExpenses += $expense['exp_value'];
                }
    
                $month1 = new DateTime($month);
                $monthName = $month1->format('F');
                $year = $month1->format('Y');
    
                $response = array(
                    'month' =>  $monthName . " " . $year,
                    'tableData' => $tableData,
                    'totalExpenses' => $totalExpenses
                );
    
                echo json_encode($response);
            }
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
        }
    }



    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "month_analytics") {
        $month = $_POST['month'];
        $totalExpenses = 0;
    
        if ($month == "all" || empty($month)) {
            $fetchDataQuery = "SELECT SUM(`exp_value`) AS totalExpenses FROM `expenses_data`";
            $result = mysqli_query($conn, $fetchDataQuery);
            $row = mysqli_fetch_assoc($result);
            $totalExpenses = $row['totalExpenses'];
        } else {
            $fetchDataQuery = "SELECT SUM(`exp_value`) AS totalExpenses FROM `expenses_data` WHERE `month`='$month'";
            $result = mysqli_query($conn, $fetchDataQuery);
            $row = mysqli_fetch_assoc($result);
            $totalExpenses = $row['totalExpenses'];
        }
    
        $resultArray = array(
            'total Expenses' => $totalExpenses
        );
    
        $jsonResult = json_encode($resultArray);
        echo $jsonResult;
    }
    
    





