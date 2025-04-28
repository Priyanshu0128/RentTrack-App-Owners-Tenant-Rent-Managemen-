<?php
include('conn.php');

// if (isset($_POST['floor']) && empty($_POST['type']))
//  {
//     $selectRoom = mysqli_real_escape_string($conn, $_POST['floor']);

//     if ($selectRoom == 'all_room') {
//         // If 'all_room' is selected, remove the WHERE condition
//         $res = mysqli_query($conn, "SELECT r.id, r.room_no, r.floor, r.capacity, COUNT(ar.room_no) AS room_allocation_count
//         FROM rooms r
//         LEFT JOIN alloted_room ar ON r.id = ar.room_no
//         GROUP BY r.id, r.room_no, r.floor, r.capacity");
//     } else {
//         // If a specific floor is selected, include the WHERE condition
//         $res = mysqli_query($conn, "SELECT r.id, r.room_no, r.floor, r.capacity, COUNT(ar.room_no) AS room_allocation_count
//         FROM rooms r
//         LEFT JOIN alloted_room ar ON r.id = ar.room_no
//         WHERE r.floor = '$selectRoom'
//         GROUP BY r.id, r.room_no, r.floor, r.capacity");
//     }

//     $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
//     echo json_encode($row);
// }

// if (!empty($_POST['type']) && $_POST['type'] == "edit_expense") {
//     $month = $_POST['month'];

//     $res = mysqli_query($conn, "SELECT r.id, r.room_no, r.floor, r.capacity, ie.incoming_expense FROM incoming_expenses ie JOIN rooms r ON r.id=ie.room_id WHERE ie.month = '$month' GROUP BY r.id, r.room_no, r.floor, r.capacity, ie.incoming_expense");
//     $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
//     echo json_encode($row);
// }



if ($_GET['type'] == "add_bill") {
    // print_r($_POST);
    // die();

    $month = $_POST['month'];
    $user_id = $_POST['user_id'];

    if (empty($month)) {
        echo json_encode("Month is empty");
        exit();
    }


    $selectRoom = $_POST['select_room'];
    $roomUnits = $_POST['room_units'];
    $roomBill = $_POST['room_bill'];
    $billDates = $_POST['room_date'];
    $keptby = $_POST['bill_kept_by'];


    foreach ($_POST['meter_name'] as $key => $value) {
        $meterName = mysqli_real_escape_string($conn, $_POST['meter_name'][$key]);
        $meterUnits = mysqli_real_escape_string($conn, $_POST['meter_units'][$key]);
        $meterBill = mysqli_real_escape_string($conn, $_POST['meter_bill'][$key]);
        $meterDate = mysqli_real_escape_string($conn, $_POST['meter_date'][$key]);
        $meterStatus = mysqli_real_escape_string($conn, $_POST['meter_status'][$key]);

        $meterImg = null;
        if ($_FILES['meter_img']['error'][$key] == UPLOAD_ERR_OK) {
            $targetDir = 'uploads/';
            $targetFilePath = $targetDir . basename($_FILES['meter_img']['name'][$key]);
            if (move_uploaded_file($_FILES['meter_img']['tmp_name'][$key], $targetFilePath)) {
                $meterImg = mysqli_real_escape_string($conn, $targetFilePath);
            } else {
                echo "File upload failed!";
            }
        }

        $meterUserId = $meterStatus == 'Paid' ? $user_id : NULL;


        $query = "INSERT INTO electricity_meter (meter_name, meter_units, meter_bill, meter_date, meter_img, meter_status, meter_paidby, month) 
                  VALUES ('$meterName', '$meterUnits', '$meterBill', '$meterDate', '$meterImg', '$meterStatus', '$meterUserId', '$month')";

        $result = mysqli_query($conn, $query);
    }


    foreach ($selectRoom as $room) {
        $units = mysqli_real_escape_string($conn, $roomUnits[$room]);
        $bill = mysqli_real_escape_string($conn, $roomBill[$room]);
        $dates = mysqli_real_escape_string($conn, $billDates[$room]);
        $admin = mysqli_real_escape_string($conn, $keptby[$room]);

        // Your SQL query to insert into the electricity_bill table
        $query = "INSERT INTO electricity_bill (room_name, room_units, room_bill, room_date, bill_kept_by, month) 
              VALUES ('$room', '$units', '$bill', '$dates', '$admin', '$month')";
        //   die();

        $insertBill = mysqli_query($conn, $query);
    }
    if ($insertBill && $result) {
        // $response = array('status' => 'success', 'message' => 'Data inserted room vise successfully');
        echo json_encode("Data inserted successfully");
        // echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => mysqli_error($conn));
        echo json_encode($response);
    }
}




if (!empty($_POST['type']) && $_POST['type'] == "search") {
    $monthFilter = isset($_POST['month']) ? mysqli_real_escape_string($conn, $_POST['month']) : null;

    $sql = "SELECT month, SUM(DISTINCT meter_bill) AS total_meter_bill, GROUP_CONCAT(DISTINCT room_name) AS rooms, SUM(DISTINCT room_bill) AS total_room_bill
    FROM electricity_bill JOIN electricity_meter USING (month)";
    if ($monthFilter) {
        $sql .= " WHERE month = '$monthFilter'";
    }

    $sql .= " GROUP BY month
             ORDER BY month ASC";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $month = new DateTime($row['month']);
            $monthName = $month->format('F');
            $year = $month->format('Y');

            $data[] = array(
                'month' => "{$monthName} {$year}",
                'rooms' => $row['rooms'],
                'totalMeterBill' => $row['total_meter_bill'],
                'totalRoomBill' => $row['total_room_bill']
            );
        }
        echo json_encode($data);
        exit;
    } else {
        // Error handling if query fails
        echo "Error: " . mysqli_error($conn);
    }
}





if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['type'] == "analytics") {
    $month = $_POST['month'];
    $totalExpenses = 0;
    $totalIncoming = 0;
    $remaining = 0;

    if ($month == "all" || empty($month)) {
        $fetchDataQuery = "SELECT SUM(meter_bill) AS total_meter_bill FROM electricity_meter";
        $result = mysqli_query($conn, $fetchDataQuery);
        $row1 = mysqli_fetch_assoc($result);

        $totalExpenses = $row1['total_meter_bill'];
        $fetchIncomingQuery = "SELECT SUM(room_bill) AS totalIncoming FROM electricity_bill";
        $resultIncoming = mysqli_query($conn, $fetchIncomingQuery);
        $totalIncomingRow = mysqli_fetch_assoc($resultIncoming);
        $totalIncoming = $totalIncomingRow['totalIncoming'];

        $remaining = $totalIncoming - $totalExpenses;
    } else {
        $fetchDataQuery = "SELECT month, SUM(meter_bill) AS total_meter_bill FROM electricity_meter WHERE month='$month'";
        $result = mysqli_query($conn, $fetchDataQuery);
        $row = mysqli_fetch_assoc($result);
        $totalExpenses += $row['total_meter_bill'];

        $monthYear = $row['month'];
        $fetchIncomingQuery = "SELECT month, SUM(room_bill) AS totalIncoming
            FROM electricity_bill
            WHERE month='$monthYear'
            GROUP BY month";

        $resultIncoming = mysqli_query($conn, $fetchIncomingQuery);
        $totalIncomingRow = mysqli_fetch_assoc($resultIncoming);
        $totalIncoming += $totalIncomingRow['totalIncoming'];

        $remaining = $totalIncoming - $totalExpenses;
    }

    $resultArray = array(
        'total Incoming' => $totalIncoming,
        'total Expenses' => $totalExpenses,
        'remaining' => $remaining
    );

    $jsonResult = json_encode($resultArray);
    echo $jsonResult;
}




if ($_POST['type'] == "edit_bill") {

    try {
        $monthBill = $_POST['month'];
        $convertedMonth = date('Y-m', strtotime($monthBill));
        //    echo $convertedMonth;

        $rooms = [];
        $meter_name = [];


        $fetchMeterDataQuery = "SELECT * FROM electricity_meter WHERE month = '$convertedMonth'";
        $fetchMeterDataResult = mysqli_query($conn, $fetchMeterDataQuery);


        if ($fetchMeterDataResult) {
            $meterData = [];
            // $meterData = mysqli_fetch_array($fetchMeterDataResult,MYSQLI_ASSOC);
            while ($row1 = mysqli_fetch_array($fetchMeterDataResult, MYSQLI_ASSOC)) {
                // $meterData[] = $row;
                array_push($meterData, $row1);
                $meter_name[] = $row1['meter_name'];
            }


            $fetchBillDataQuery = "SELECT * FROM electricity_bill WHERE month = '$convertedMonth'";
            $fetchBillDataResult = mysqli_query($conn, $fetchBillDataQuery);

            if ($fetchBillDataResult) {
                $billData = array();
                while ($row = mysqli_fetch_assoc($fetchBillDataResult)) {
                    $billData[] = $row;
                    $rooms[] = $row['room_name'];
                }

                $response = array(
                    'month' => $convertedMonth,
                    'status' => 'success',
                    'message' => 'Data fetched successfully',
                    'meterData' => $meterData,
                    'billData' => $billData,
                    'rooms' => $rooms
                );
                echo json_encode($response);
                die();
            } else {
                $response = array('status' => 'error', 'message' => mysqli_error($conn));
                echo json_encode($response);
                die();
            }
        } else {
            $response = array('status' => 'error', 'message' => mysqli_error($conn));
            echo json_encode($response);
            die();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        die();
    }
}

// if ($_POST['type'] == "edit_bill") {
//     $monthBill = $_POST['month'];
//     $convertedMonth = date('Y-m', strtotime($monthBill));

//     $rooms = [];


//     // Fetch meter data
//     $fetchMeterDataQuery = "SELECT * FROM electricity_meter WHERE month = '$convertedMonth'";
//     $fetchMeterDataResult = mysqli_query($conn, $fetchMeterDataQuery);

//     if ($fetchMeterDataResult) {
//         $meterData = array();
//         while ($row = mysqli_fetch_assoc($fetchMeterDataResult)) {
//             $meterData[] = $row;
//             $meter_name[] = $row['meter_name'];

//         }

//         // Fetch bill data
//         $fetchBillDataQuery = "SELECT * FROM electricity_bill WHERE month = '$convertedMonth'";
//         $fetchBillDataResult = mysqli_query($conn, $fetchBillDataQuery);

//         if ($fetchBillDataResult) {
//             $billData = array();
//             while ($row = mysqli_fetch_assoc($fetchBillDataResult)) {
//                 $billData[] = $row;
//                 $rooms[] = $row['room_name'];
//             }

//             // Prepare response
//             $response = array(
//                 'month' => $convertedMonth,
//                 'status' => 'success',
//                 'message' => 'Data fetched successfully',
//                 'meterData' => $meterData,
//                 'billData' => $billData,
//                 'rooms' => $rooms
//             );
//             echo json_encode($response);
//         } else {
//             $response = array('status' => 'error', 'message' => mysqli_error($conn));
//             echo json_encode($response);
//         }
//     } else {
//         $response = array('status' => 'error', 'message' => mysqli_error($conn));
//         echo json_encode($response);
//     }
// }



if ($_POST['type'] == 'delete_room_data') {

    if (isset($_POST['optionNameToRemove']) && isset($_POST['month'])) {
        $optionNameToRemove = mysqli_real_escape_string($conn, $_POST['optionNameToRemove']);
        $month = mysqli_real_escape_string($conn, $_POST['month']);

        $sql = "DELETE FROM electricity_bill WHERE room_name = '$optionNameToRemove' AND month = '$month'";
        $result = $conn->query($sql);

        if ($result) {

            echo json_encode(["status" => "success"]);
        } else {

            echo json_encode(["status" => "error", "message" => "Error deleting room data"]);
        }
    }
}
