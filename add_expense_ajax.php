<?php


include("conn.php");
 
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['type']) && $_POST['type'] == "add_exp") {
//     $adminId = $_POST['user_id'];
//     $selectedOptions = $_POST['select_bill'];
//     $month = $_POST['month'];

//     $checkingMonth = mysqli_query($conn, "SELECT `month` FROM `expenses_data` WHERE `month`='$month'");
//     if (mysqli_num_rows($checkingMonth) > 0) {
//         echo json_encode(["error" => "Selected Month Expenses Already Inserted"]);
//         die();
//     }

//     if (empty($selectedOptions) || empty($month)) {
//         echo json_encode(["error" => "Some required fields are empty. Please fill them out."]);
//         exit();
//     }

//     $successFlag = false;

//     if (!empty($selectedOptions)) {
//         foreach ($selectedOptions as $key => $option) {

//             // $fileFieldName = 'file_' . $option;
//             // $textFieldName = 'text_' . $option;

//             // $fileData = $_FILES[$fileFieldName];
//             // $fileName = $fileData['name'];
//             // $fileTmpName = $fileData['tmp_name'];

//             $textFieldName = $_POST['values'][$key];
//             $fileFieldName = $_FILES['files']['name'][$key];
//             $fileFieldName_tmp = $_FILES['files']['tmp_name'][$key];

//             // $textData = $_POST[$textFieldName];

//             if (empty($textFieldName) || empty($fileFieldName) || empty($fileFieldName_tmp)) {
//                 echo json_encode(["error" => "Some required fields are empty for option $option. Please fill them out."]);
//                 continue;
//             }

//             $uploadDirectory = 'uploads/';
//             $uploadedFilePath = $uploadDirectory . $fileFieldName;
//             move_uploaded_file($fileFieldName_tmp, $uploadedFilePath);

//             $sql = "INSERT INTO expenses_data (admin_id, expense_name, exp_img, exp_value, month) 
//                     VALUES ('$adminId', '$option', '$uploadedFilePath', '$textFieldName', '$month')";

//             if (mysqli_query($conn, $sql)) {
//                 $successFlag = true;
//             } else {
//                 echo json_encode(["error" => "Error: " . $sql . "<br>" . mysqli_error($conn)]);
//             }
//         }

//         if ($successFlag) {
//             echo json_encode(["success" => "Record inserted successfully"]);
//         }
//     }
// }


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['type']) && $_POST['type'] == "room_rnt") {

    $selectedRoom = $_POST['select_room'];
    $keptBy = $_POST['select_admin'];

    if (empty($selectedRoom) || empty($keptBy)) {
        echo json_encode(["error" => "Some required fields are empty. Please fill them out."]);
        exit();
    }

    $successFlag = false;

    // Extracting individual values for the single selected room
    $rentDate = $_POST['rent_dates'];
    $rentMonth = $_POST['rent_months'];
    $rentValue = $_POST['values'];

    if (empty($rentDate) || empty($rentMonth) || empty($rentValue)) {
        echo json_encode(["error" => "Some required fields are empty. Please fill them out."]);
        exit();
    }

    // Processing data for the single selected room
    $sql = "INSERT INTO month_rent (room_name, kept_by, date, month , room_rent) 
            VALUES ('$selectedRoom', '$keptBy', '$rentDate','$rentMonth','$rentValue')";

    if (mysqli_query($conn, $sql)) {
        $successFlag = true;
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . mysqli_error($conn)]);
    }

    if ($successFlag) {
        echo json_encode(["success" => "Record inserted successfully"]);
    }
}



if ($_POST['type'] == "edit_rent") {

    $r_id = $_POST['id'];

    $query = "SELECT `room_name`, `kept_by`, `date`, `month`, `room_rent` FROM `month_rent` WHERE `id` =' $r_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $rentDataArray = array();
        $room_name = [];
        while ($row =  mysqli_fetch_assoc($result)) {
            $roomData = array(
                'room_name' => $row['room_name'],
                'kept_by' => $row['kept_by'],
                'date' => $row['date'],
                'month' => $row['month'],
                'room_rent' => $row['room_rent']
            );
            $rentDataArray[] = $roomData;
            $room_name[] = $row['room_name'];
            $kept_by[] = $row['kept_by'];
        }

        echo json_encode(array(
            'rent' => $rentDataArray,
            'id' => $r_id
        ),true);

        mysqli_free_result($result);
        // die();
    } else {
        error_log("Error: " . $query . "\n" . mysqli_error($conn));
        echo json_encode(array('error' => 'Error fetching records'));
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "search_month") {

    // echo "hello";
    try {
        $month = $_POST['month'];
        $selecetd_Room = $_POST['selected_room'];
        if ($month == "all") {
            $sql = "SELECT * FROM month_rent";
            if(!empty($selecetd_Room)){
                $sql .= " WHERE `room_name` = '$selecetd_Room'";
            }
        } else {
            $sql = "SELECT * FROM month_rent WHERE `month` = '$month'";
            if(!empty($selecetd_Room)){
                $sql .= " AND `room_name` = '$selecetd_Room'";
            }
        }


        $select_month = mysqli_query($conn,$sql);
        $selectMonth = mysqli_fetch_all($select_month, MYSQLI_ASSOC);

        $arr = [];
        foreach ($selectMonth as $monthData) {
            $month1 = new DateTime($monthData["month"]);
            $monthName = $month1->format('F');
            $year = $month1->format('Y');

            $data = [
                "id" => $monthData['id'],
                "date" => $monthData['date'],
                "month" => $monthName . " " . $year,
                "kept_by" => $monthData['kept_by'],
                "room_name" => $monthData['room_name'],
                "room_rent" => $monthData['room_rent'],
            ];
            array_push($arr, $data);
        }
        echo json_encode($arr);
    } catch (Exception $e) {
        echo json_encode($e->getMessage());
    }
}

if($_POST['type'] == "search_room"){
    try {
        $room = $_POST['room_name'];
        $selecetdMonth = $_POST['month_name'];
        if ($room == "all") {
            $sql =  "SELECT * FROM month_rent";
            if(!empty($selecetdMonth)){
                $sql .= " WHERE `month` = '$selecetdMonth'";
            }
        } else {
            $sql = "SELECT * FROM month_rent WHERE `room_name` = '$room'";
            if(!empty($selecetdMonth)){
                $sql .= " AND `month` = '$selecetdMonth'";
            }
        }


        $select_month = mysqli_query($conn,$sql);
        $selectRoom = mysqli_fetch_all($select_month, MYSQLI_ASSOC);

        $arr = [];
        foreach ($selectRoom as $roomData) {
            $month1 = new DateTime($roomData["month"]);
            $monthName = $month1->format('F');
            $year = $month1->format('Y');

            $data = [
                "id" => $roomData['id'],
                "date" => $roomData['date'],
                "month" => $monthName . " " . $year,
                "kept_by" => $roomData['kept_by'],
                "room_name" => $roomData['room_name'],
                "room_rent" => $roomData['room_rent'],
            ];
            array_push($arr, $data);
        }
        echo json_encode($arr);
    } catch (Exception $e) {
        echo json_encode($e->getMessage());
    }
}



// if ($_POST['type'] == "month_anyl") {

//     $month = $_POST['month'];
// //    $selectR = $POST['room_sel'];
//     $totalIncoming = 0;

//     if($month == "all" || empty($month)){
//         $fetchDataQuery = "SELECT SUM(`room_rent`) as totalIncoming FROM month_rent" ;


//         $result = mysqli_query($conn, $fetchDataQuery);
//         $totalIncomingRow = mysqli_fetch_assoc($result);
//         $totalIncoming += $totalIncomingRow['totalIncoming'];
//     }else{
//         $fetchDataQuery = "SELECT * FROM month_rent WHERE `month`='$month'";
//         $result = mysqli_query($conn, $fetchDataQuery);
//         $row = mysqli_fetch_assoc($result);
        
//             $monthYear = $row['month'];
//             $fetchIncomingQuery = "SELECT `month`, SUM(`room_rent`) AS totalIncoming
//     FROM `month_rent` WHERE `month`='$monthYear'
//     GROUP BY `month`";
    
//             $resultIncoming = mysqli_query($conn, $fetchIncomingQuery);
//             $totalIncomingRow = mysqli_fetch_assoc($resultIncoming);
//             $totalIncoming += $totalIncomingRow['totalIncoming'];

//     }
//     $resultArray = array(
//         'total Incoming' => $totalIncoming,
//     );

//     $jsonResult = json_encode($resultArray);
//     echo $jsonResult;
// }

if ($_POST['type'] == "month_anyl") 
{

    try {
        $month = $_POST['month'];
        $selectedRoom = $_POST['room_sel'];
        $totalIncoming = 0;

        if ($month == "all" || empty($month)) {
            $fetchDataQuery = "SELECT `room_name`, SUM(`room_rent`) as totalIncoming FROM month_rent";
            if (!empty($selectedRoom)) {
                $fetchDataQuery .= " WHERE `room_name`='$selectedRoom'";
            }
            $fetchDataQuery .= " GROUP BY `room_name`";
        } else {
            $fetchDataQuery = "SELECT `room_name`, SUM(`room_rent`) as totalIncoming FROM month_rent WHERE `month`='$month'";
            if (!empty($selectedRoom)) {
                $fetchDataQuery .= " AND `room_name`='$selectedRoom'";
            }
            $fetchDataQuery .= " GROUP BY `room_name`";
        }

        $result = mysqli_query($conn, $fetchDataQuery);

        while ($row = mysqli_fetch_assoc($result)) {
            $totalIncoming += $row['totalIncoming'];
        }

        $resultArray = array(
            'total Incoming' => $totalIncoming,
        );

        echo json_encode($resultArray);
    } catch (Exception $e) {
        echo json_encode($e->getMessage());
    }
}


// if ($_POST['type'] == "room_anyl") {

//     $room = $_POST['room_name'];
//     $totalRoomIncoming = 0;

//     if($room == "all" || empty($room)){
//         $fetchDataQuery = "SELECT SUM(`room_rent`) as totalRoomIncoming FROM month_rent" ;
//         $result = mysqli_query($conn, $fetchDataQuery);
//         $totalIncomingRow = mysqli_fetch_assoc($result);
//         $totalRoomIncoming += $totalIncomingRow['totalRoomIncoming'];
//     }else{
//         $fetchDataQuery = "SELECT * FROM month_rent WHERE `room_name`= '$room'";
//         $result = mysqli_query($conn, $fetchDataQuery);
//         $row = mysqli_fetch_assoc($result);
        
//             $roomsInc = $row['room_name'];
//             $fetchIncomingQuery = "SELECT `room_name`, SUM(`room_rent`) AS totalRoomIncoming
//             FROM `month_rent` WHERE `room_name`='$roomsInc' GROUP BY `room_name`";
            

    
//             $resultIncoming = mysqli_query($conn, $fetchIncomingQuery);
//             $totalIncomingRow = mysqli_fetch_assoc($resultIncoming);
//             $totalRoomIncoming += $totalIncomingRow['totalRoomIncoming'];

//     }
//     $resultRoomArray = array(
//         'total room Incoming' => $totalRoomIncoming,
//     );

//     $jsonResult = json_encode($resultRoomArray);
//     echo $jsonResult;
// }
