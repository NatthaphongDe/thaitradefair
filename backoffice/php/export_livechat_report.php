<?php
include_once("../connect.php");


header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=liveschat-log.xls");
header("Pragma: no-cache");
header("Expires: 0");


$array_alldata = array();

$array_head = array("No.","Sender Email","Sender Name","Receiver Email","Receiver Name","Chat Date","Time Start","Time End");

array_push($array_alldata,$array_head);


$sql = "select * from tt_chat_log a left join tt_sso_login b on a.log_user=b.sso_id order by a.log_start DESC limit 3000 ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $runno = 1;
    while($data = $result->fetch_assoc()) {
      $SenderEmail = $data["sso_email"];
      $SenderName = "";
      if($data["sso_name_en"]!="") {
        $SenderName = $data["sso_name_en"];
      } else {
        $SenderName = $data["sso_name_th"];
      }

      $ReceiveEmail = "";
      $ReceiveName = "";

      if($data["log_type"]==1) {
        $sqllog = "select * from tt_exhibitor_list where com_taxno = ? ";
        $stmtlog = $mysqli->prepare($sqllog);
        $stmtlog->bind_param('s',$data["log_company"]);
        $stmtlog->execute();
        $resultlog = $stmtlog->get_result();
        $numrowlog = $resultlog->num_rows;
        if($numrowlog>0) {
          $datalog = $resultlog->fetch_assoc();
          $ReceiveEmail = $datalog["com_email"];
          $ReceiveName = $datalog["com_name"];
        }
      }

      if($data["log_type"]==2) {
        $sqllog = "select * from tt_exportor_list where User_ID = ? ";
        $stmtlog = $mysqli->prepare($sqllog);
        $stmtlog->bind_param('s',$data["log_company"]);
        $stmtlog->execute();
        $resultlog = $stmtlog->get_result();
        $numrowlog = $resultlog->num_rows;
        if($numrowlog>0) {
          $datalog = $resultlog->fetch_assoc();
          $ReceiveEmail = $datalog["Mail"];
          $ReceiveName = $datalog["Corporate_Name_EN"];
        }
      }

      $start_chat_date = "";
      $start_chat_start = "";
      $start_chat_end = "";
      if($data["log_start"]!="0000-00-00 00:00:00") {
        $start_chat_date = date("Y-m-d",strtotime($data["log_start"]));
      }

      if($data["log_start"]!="0000-00-00 00:00:00") {
        $start_chat_start = date("H:i:s",strtotime($data["log_start"]));
      }

      if($data["log_end"]!="0000-00-00 00:00:00") {
        $start_chat_end = date("H:i:s",strtotime($data["log_end"]));
      }

      $array_inner = array();
      array_push($array_inner,number_format($runno));
      array_push($array_inner,htmlspecialchars_decode($SenderEmail));
      array_push($array_inner,htmlspecialchars_decode($SenderName));
      array_push($array_inner,htmlspecialchars_decode($ReceiveEmail));
      array_push($array_inner,htmlspecialchars_decode($ReceiveName));
      array_push($array_inner,htmlspecialchars_decode($start_chat_date));
      array_push($array_inner,htmlspecialchars_decode($start_chat_start));
      array_push($array_inner,htmlspecialchars_decode($start_chat_end));
      array_push($array_alldata,$array_inner);

      $runno++;

    }
  }
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>MFEC</title>
	</head>
  <body>
    <table border="1">
      <tbody>
        <? if(count($array_alldata)>0) { ?>

            <? for($i=0;$i<count($array_alldata);$i++) { ?>
              <tr>
                <? for($j=0;$j<count($array_alldata[$i]);$j++) { ?>
                  <td><?=$array_alldata[$i][$j]?></td>
                <? } ?>
                </tr>
            <? } ?>

        <? } ?>
      </tbody>
    </table>
  </body>
</html>
