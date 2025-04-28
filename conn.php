<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "12345";
$dbname  = "apartment_for_rent";

define('base_url', 'http://localhost/Project/Login/');

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?>