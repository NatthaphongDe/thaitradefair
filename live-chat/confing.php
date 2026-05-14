<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");

global $conn, $image_not_available;


$db_host = 'localhost';
$db_name = 'ibusiness_trade';
$db_username = 'ibusiness_trade';
$db_password = '!F*MLhKz8V34A.yo';

$conn = mysqli_init();
// $conn->real_connect($db_host,$db_username,$db_password,$db_name);
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// $conn = new mysqli($db_host,$db_username,$db_password,$db_name);
$conn->set_charset('utf8');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else{
  // dd($conn->connect_error);
}



$image_not_available = '/assest/img/image-not-available.jpg';
$image_not_available = '/assest/img/image-not-available-ex.png';



 ?>
