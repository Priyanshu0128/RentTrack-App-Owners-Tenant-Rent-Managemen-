<?php
include("conn.php");

// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['room_id'])) {
//     $roomId = mysqli_real_escape_string($conn, $_POST['room_id']);

//     $query = "SELECT * FROM electricity_bill WHERE id = '$roomId'";
//     $result = mysqli_query($conn, $query);

//     if ($result) {
//         $data = mysqli_fetch_assoc($result);
//         echo json_encode($data);
//     } else {
//         echo json_encode(["error" => "Error fetching data from the database"]);
//     }
// } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_bill'])) {
//     $roomId = mysqli_real_escape_string($conn, $_POST['room_id']);
//     $units = mysqli_real_escape_string($conn, $_POST['units']);
//     $bill = mysqli_real_escape_string($conn, $_POST['bill']);

//     $updateQuery = "UPDATE electricity_bill SET units='$units', bill='$bill' WHERE id='$roomId'";
//     $updateResult = mysqli_query($conn, $updateQuery);

//     if (isset($_POST['incoming_expenses'])) {
//         $incomingExpenses = $_POST['incoming_expenses'];

//         foreach ($incomingExpenses as $roomToUpdate => $incomingExpense) {
//             $roomIdToUpdate = mysqli_real_escape_string($conn, $roomToUpdate);
//             $incomingExpenseToUpdate = mysqli_real_escape_string($conn, $incomingExpense);

//             $updateIncomingQuery = "UPDATE incoming_expenses SET incoming_expense='$incomingExpenseToUpdate' WHERE room_id='$roomIdToUpdate'";
//             $updateIncomingResult = mysqli_query($conn, $updateIncomingQuery);

//             // Handle the result as needed
//         }
//     }

//     if ($updateResult) {
//         echo json_encode(["success" => "Data updated successfully"]);
//     } else {
//         echo json_encode(["error" => "Error updating data in the database"]);
//     }
// } 
// else {
//     echo json_encode(["error" => "Invalid request"]);
// }
