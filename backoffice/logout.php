<?php
	include("connect.php");

	saveLogActivity(1,$_SESSION["id"],0,"Sign Out");

	$_SESSION["id"] = "";
	$_SESSION["name"] =  "";
	$_SESSION["user_type"] = "";
	$_SESSION["admin_name"] = "";
	$_SESSION["fgid"] = "";
	$_SESSION["frgid"] = "";
	$_SESSION["frlid"] = "";
	session_destroy();
	header('Location: login.php');
?>
