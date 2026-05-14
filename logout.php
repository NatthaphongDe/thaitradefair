<?
include_once ("backoffice/connect.php");

$userid = $_COOKIE["ssoid"];
$ip = get_real_ip();
$sqlgl = "insert into tt_activity_sso (log_type,sso_id,sso_date,sso_ip,log_action) values ('1',?,now(),?,'Sign Out') ";
$stmtgl = $mysqli->prepare($sqlgl);
if($stmtgl) {
  $stmtgl->bind_param('ss',$userid,$ip);
  $stmtgl->execute();
}

setcookie("ssoid","",-$exire_cookie,'/',$DOMAIN,1);
header('Location: https://sso.ditp.go.th/auth/clientLogout?callback='.ROOTPATHDOMAIN);
exit();
?>
