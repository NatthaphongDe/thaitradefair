<?php
ini_set('display_errors', '0');
session_start();
$serversiteshow = "";
date_default_timezone_set("Asia/Bangkok");
define("ROOTPATH","/home/thaitradefair2022/www");
define("ROOTPATHDOMAIN","https://www.thaitradefair.com/");
define("MAXFILESIZECHK",1024*1024*50);

define ("__DB_CHARSET__",	'UTF-8');
define ("__DB_TYPE__",		'mysqli');
define ("__DB_PORT__",		'');
define ("__DB_HOSTNAME__", "localhost");
define ("__DB_USERNAME__", "ibusiness_trade");
define ("__DB_PASSWORD__", "!F*MLhKz8V34A.yo");
define ("__DB_NAME__", "ibusiness_trade");

/*
define ("_ENABLE_SEND_MAIL_", true);
define ("_DEFAULE_SUBJECT_MAIL_", "Mail alert : ");
define ("_DEFAULE_SMTPAUTH_", true);
define ('_DEFAULE_ADMIN_NAME_','MFEC E-bill system');
define ('_DEFAULE_ADMIN_EMAIL_','UAT_BillingOnline@mfec.co.th');
define ("_DEFAULE_SMTPENABLE_", true);
define ("_DEFAULE_SMTP_", "totmail.debutmail.com");//totmail.totbb.com
define ("_DEFAULE_SMTP_PORT_", "25");//465  587
define ("_DEFAULE_SMTPAUTH_USER_", "order@order.bendix.co.th");//testcode@totmail.totbb.com
define ("_DEFAULE_SMTPAUTH_PASS_", 'F9#dA3klP@ssw0rd');//4#ght5$L1
define ("_DEFAULE_SMTPAUTH_SECURE_", "");//ssl tls
*/


$DOMAIN="thaitradefair.com";
$exire_cookie = time()+60*60*24;

// Create connection
$mysqli = mysqli_connect(__DB_HOSTNAME__,__DB_USERNAME__,__DB_PASSWORD__,__DB_NAME__);
mysqli_set_charset($mysqli,"utf8");


$site_name="Thai Trade Fair 2022";
$site_version="(version 1.0.0)";
include_once(ROOTPATH."/backoffice/function.php");
?>
