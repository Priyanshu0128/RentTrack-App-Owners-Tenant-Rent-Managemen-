<?php
include("conn.php");

$floor = $_POST['floor'];

$sql = "INSERT INTO rooms (floor, room_no, capacity) VALUES ('$floor', 'New Room', 2)";

if ($conn->query($sql) === TRUE) {
    echo "Vacant room added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
