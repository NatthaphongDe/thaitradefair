<?
include_once ("backoffice/connect.php");


$posttype = $_POST["method"];

if($posttype=="saveform") {

  if ($_SESSION['csrf_token'] != $_POST["csrf_token"]) { ?>
    <script type="text/javascript">
      setTimeout(function () {top.pc_overlay(2);top.alertToken();},2000);
    </script>
  <?exit();}
  $cont_create_ip = get_real_ip();
  $cont_name = $_POST["cont_name"];
  $cont_subject = $_POST["cont_subject"];
  $cont_email = $_POST["cont_email"];
  $cont_tel = $_POST["cont_tel"];
  $cont_message = $_POST["cont_message"];

  $sql = "insert into tt_contact_list (fair_id,cont_status,cont_name,cont_subject,cont_email,cont_tel,cont_message,cont_create_date,cont_create_ip) values ('0','0',?,?,?,?,?,now(),?) ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('ssssss',$cont_name,$cont_subject,$cont_email,$cont_tel,$cont_message,$cont_create_ip);
    $stmt->execute();
  }
  ?>
  <script type="text/javascript">
    setTimeout(function () {top.pc_overlay(2);top.alertSuccess();},2000);
    setTimeout(function () {top.window.location=top.window.location;},3000);
  </script>
  <?
  exit();
}


if($posttype=="saveformfair") {
  if ($_SESSION['csrf_token'] != $_POST["csrf_token"]) { ?>
    <script type="text/javascript">
      setTimeout(function () {top.pc_overlay(2);top.alertToken();},2000);
    </script>
  <?exit();}
  $fair_id = $_POST["fair_id"];
  $cont_create_ip = get_real_ip();
  $cont_name = $_POST["cont_name"];
  $cont_subject = $_POST["cont_subject"];
  $cont_email = $_POST["cont_email"];
  $cont_tel = $_POST["cont_tel"];
  $cont_message = $_POST["cont_message"];

  $sql = "insert into tt_contact_list (fair_id,cont_status,cont_name,cont_subject,cont_email,cont_tel,cont_message,cont_create_date,cont_create_ip) values (?,'0',?,?,?,?,?,now(),?) ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('issssss',$fair_id,$cont_name,$cont_subject,$cont_email,$cont_tel,$cont_message,$cont_create_ip);
    $stmt->execute();
  }
  ?>
  <script type="text/javascript">
    setTimeout(function () {top.pc_overlay(2);top.alertSuccess();},2000);
    setTimeout(function () {top.window.location=top.window.location;},3000);
  </script>
  <?
  exit();
}

?>
