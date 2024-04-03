<?php
include('conn.php');
if (isset($_POST['floor']) && empty($_POST['type']))
 {
    $selectRoom = mysqli_real_escape_string($conn, $_POST['floor']);

    if ($selectRoom == 'all_room') {
        // If 'all_room' is selected, remove the WHERE condition
        $res = mysqli_query($conn, "SELECT r.id, r.room_no, r.floor, r.capacity, COUNT(ar.room_no) AS room_allocation_count
        FROM rooms r
        LEFT JOIN alloted_room ar ON r.id = ar.room_no
        GROUP BY r.id, r.room_no, r.floor, r.capacity");
    } else {
        // If a specific floor is selected, include the WHERE condition
        $res = mysqli_query($conn, "SELECT r.id, r.room_no, r.floor, r.capacity, COUNT(ar.room_no) AS room_allocation_count
        FROM rooms r
        LEFT JOIN alloted_room ar ON r.id = ar.room_no
        WHERE r.floor = '$selectRoom'
        GROUP BY r.id, r.room_no, r.floor, r.capacity");
    }

    $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
    echo json_encode($row);
}

?>