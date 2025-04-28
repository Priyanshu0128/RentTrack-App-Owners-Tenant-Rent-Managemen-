<?php
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $room_id = $_POST['room_id'];

    // $room_no = $_POST['room_no'];
    // $selectFloor = $_POST['select_floor'];
    
    // $room_capacity = $_POST['capacity'];
    $sql = "SELECT * FROM `rooms` WHERE `id`= $room_id ";
    $result = mysqli_query($conn, $sql);
   $room_result = mysqli_fetch_assoc($result);
    if ($result) {
        echo json_encode($room_result);
        // echo "<script>alert('Room updated successfully'); window.location='addrooms.php'</script>";
    } else {
        echo "Failed to update room";
    }
    
}
?>