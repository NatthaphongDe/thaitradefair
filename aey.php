<?php
$mysqli = new mysqli("localhost","ibusiness_trade","LnZPGsh*U3PTU!li","ibusiness_trade");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}else{
	echo "connectd";

}
?>
