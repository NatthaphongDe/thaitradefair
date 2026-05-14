<?php

function DuplicateCheck($table, $col, $value) {
	echo $sql = "SELECT * FROM ".$table." Where ".$col." = '".$value."'";
	$result = mysqli_query($conn, $sql);
	$z = mysqli_num_rows($result);
	echo ">>>>>".$z;
}
?>