<?php

include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['room_no'])) {
  $roomNo = $_POST['room_no'];

  
  $query = "SELECT * FROM users u
            INNER JOIN alloted_room ar ON u.id = ar.u_id
            INNER JOIN rooms r ON ar.room_no = r.id
            WHERE r.room_no = '$roomNo'";

  $result = mysqli_query($conn, $query);

  if ($result && $userDetails = mysqli_fetch_assoc($result)) {
   
    $htmlContent  = "<h2>Person Details</h2>"; 
    $htmlContent .= "<p><strong>Name:</strong> {$userDetails['name']}</p>";
    $htmlContent .= "<p><strong>Mobile:</strong> {$userDetails['mobile']}</p>";
    $htmlContent .= "<p><strong>Gender:</strong> {$userDetails['gender']}</p>";
    $htmlContent .= "<p><strong>Address:</strong> {$userDetails['permanentAddress']}</p>";


    echo $htmlContent;
  } else {
    echo "Error fetching user details.";
  }
} else {
  echo "Invalid request.";
}
?>
