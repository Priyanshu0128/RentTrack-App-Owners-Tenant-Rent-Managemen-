<?php
include("conn.php");

// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
//     $userId = $_POST['id'];

//     $deleteUserSql = "DELETE FROM users WHERE id = $userId";
//     $result = mysqli_query($conn, $deleteUserSql);

//     if ($result) {
//         $deleteAllotedRoomSql = "DELETE FROM alloted_room WHERE u_id = $userId";
//         $resultAllotedRoom = mysqli_query($conn, $deleteAllotedRoomSql);

//         if ($resultAllotedRoom) {
//             echo "success";
//         } else {
//             echo "failed";
//         }
//     } else {
//         echo "failed";
//     }
// } else {
//     echo "failed";
// }


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['disable_date'])) {


    // echo "<pre>";
    // print_r ($_POST);
    // echo "</pre>";
    // die();

    $userId = $_POST['id'];
    $disableDate = $_POST['disable_date'];

    $disableUserSql = "UPDATE users SET disable_date = '$disableDate' WHERE id = $userId";
    $result = mysqli_query($conn, $disableUserSql);

    if ($result) {
        $deleteAllotedRoomSql = "DELETE FROM alloted_room WHERE u_id = $userId";
        $resultAllotedRoom = mysqli_query($conn, $deleteAllotedRoomSql);

        if ($resultAllotedRoom) {
            echo "success";
        } else {
            echo "failed to delete allotted room";
        }
    } else {
        echo "failed to disable user";
    }
} else {
    echo "invalid request";
}

?>
