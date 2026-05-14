<?php
include_once ("backoffice/connect.php");

function generatetoken() {
    return md5(uniqid(rand(), true));
}

function isValidEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

if(isset($_POST['wscall']) && $_POST['wscall'] == "save-meeting"){
  $response = array();
  $ssoid = $_COOKIE["ssoid"];
  $datetime_list = $_POST['datetime_list'];
  $timezone_list = $_POST['timezone_list'];
  $gmt_list = $_POST['gmt_list'];
  $exp_cm = $_POST['exp_cm'];
  $exl_cm = $_POST['exl_cm'];
  $message_mit = htmlspecialchars($_POST['message_mit']);
  $meeting_token = generatetoken();
  if($ssoid != ""){

    $sql_chk = "SELECT * FROM `tt_sso_login` WHERE sso_id = '$ssoid' ";
    $stmt_chk = $mysqli->prepare($sql_chk);
    $stmt_chk->execute();
    $result = $stmt_chk->get_result();
    if($result->num_rows > 0){
      $res = $result->fetch_assoc();
      if(isValidEmail($res['sso_email'])){
        $sql = "INSERT INTO `tt_meeting` (meeting_ssoid,meeting_exp_id,meeting_exl_id,meeting_message,meeting_token) VALUES ('$ssoid','$exp_cm','$exl_cm','$message_mit','$meeting_token')";
        $stmt = $mysqli->prepare($sql);
        if($stmt->execute()){
          $last_id = $mysqli->insert_id;
          $index = 1;
          foreach ($datetime_list as $key => $value) {
            $meeting_list_title = 'Online Meeting Option '.$index;
            $sql_list = "INSERT INTO `tt_meeting_list` (meeting_list_mid,meeting_list_title,meeting_list_datetime,meeting_list_timezone,meeting_list_gmt) VALUES ('$last_id','$meeting_list_title','$value','$timezone_list[$key]','$gmt_list[$key]')";
            $stmt_list = $mysqli->prepare($sql_list);
            $stmt_list->execute();
            $index++;
          }
            $meeting_id = $last_id;
            include 'meeting_call_mail.php';

          $response['res_code'] = "00";
        }else {
          $response['res_code'] = "01";
        }
      }else {
        $response['res_code'] = "02";
      }

    }else {
      $response['res_code'] = "01";
    }


  }else {
    $response['res_code'] = "01";
  }
  echo json_encode($response);
  exit();
}

?>
