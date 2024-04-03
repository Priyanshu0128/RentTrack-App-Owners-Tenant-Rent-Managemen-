<?php

include("conn.php");

if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    $getUserSql = "SELECT u.*, r.`room_no`, r.`floor` 
    FROM users u 
    JOIN `alloted_room` ar ON u.`id` = ar.`u_id`
    JOIN `rooms` r ON ar.`room_no` = r.`id` 
    WHERE u.`id` = $userId;";

    $getUserResult = mysqli_query($conn, $getUserSql);

    // echo json_encode($getUserResult);  

    if ($getUserResult && $userData = mysqli_fetch_assoc($getUserResult)) {
        echo json_encode($userData);
    } else {
        echo json_encode(["error" => "User not found"]);
    }
} 
// else {
//     echo json_encode(["error" => "Invalid request"]);
// }
?>
