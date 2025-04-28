<?php
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['roomId'])) {
    $roomId = $_POST['roomId'];

    $query= "SELECT * FROM alloted_room WHERE room_no = '$roomId'";
    $result = mysqli_query($conn , $query);

    if(mysqli_num_rows($result)>0){
        echo json_encode(['message'=>"User Exist","status"=>'failed']);
    }else{
        $deleteQuery = "DELETE FROM rooms WHERE id = $roomId";
        $deleteResult = mysqli_query($conn, $deleteQuery);
    
        if ($deleteResult) {
            echo json_encode(['message'=>"Room deleted successfully","status"=>'success']);
        } else {
            echo json_encode(['message'=>"Error deleting room","status"=>'failed']);
        }
    }

} else {
    echo json_encode(['message'=>"Invalid request","status"=>'failed']);
}
?>