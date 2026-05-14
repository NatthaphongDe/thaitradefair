<?php
ini_set('display_errors', '0');
session_start();
date_default_timezone_set("Asia/Bangkok");

define ("__DB_CHARSET__",	'UTF-8');
define ("__DB_TYPE__",		'mysqli');
define ("__DB_PORT__",		'');
define ("__DB_HOSTNAME__", "localhost");
define ("__DB_USERNAME__", "ibusiness_trade");
define ("__DB_PASSWORD__", "LnZPGsh*U3PTU!li");
define ("__DB_NAME__", "ibusiness_trade");

$mysqli = mysqli_connect(__DB_HOSTNAME__,__DB_USERNAME__,__DB_PASSWORD__,__DB_NAME__);
mysqli_set_charset($mysqli,"utf8");


?>
