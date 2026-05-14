<?php
include_once("../connect.php");


header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=online-meeting-log.xls");
header("Pragma: no-cache");
header("Expires: 0");


$array_alldata = array();

$array_head = array("No.","Sender Email","Sender Name","Receiver Email","Receiver Name","Request Date/Time 1","Request Date/Time 2","Request Date/Time 3","Request Status","Receiver Select");

array_push($array_alldata,$array_head);


$sql = "select * from tt_meeting a left join tt_sso_login b on a.meeting_ssoid=b.sso_id order by a.meeting_create DESC limit 3000 ";
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

      if($data["meeting_exl_id"]>0) {
        $sqllog = "select * from tt_exhibitor_list where exl_id = ? ";
        $stmtlog = $mysqli->prepare($sqllog);
        $stmtlog->bind_param('i',$data["meeting_exl_id"]);
        $stmtlog->execute();
        $resultlog = $stmtlog->get_result();
        $numrowlog = $resultlog->num_rows;
        if($numrowlog>0) {
          $datalog = $resultlog->fetch_assoc();
          $ReceiveEmail = $datalog["com_email"];
          $ReceiveName = $datalog["com_name"];
        }
      }

      if($data["meeting_exp_id"]>0) {
        $sqllog = "select * from tt_exportor_list where exp_id = ? ";
        $stmtlog = $mysqli->prepare($sqllog);
        $stmtlog->bind_param('i',$data["meeting_exp_id"]);
        $stmtlog->execute();
        $resultlog = $stmtlog->get_result();
        $numrowlog = $resultlog->num_rows;
        if($numrowlog>0) {
          $datalog = $resultlog->fetch_assoc();
          $ReceiveEmail = $datalog["Mail"];
          $ReceiveName = $datalog["Corporate_Name_EN"];
        }
      }

      $status = "";
      if($data["meeting_status"]==0) {
        $status = 'Pending';
      }
      if($data["meeting_status"]==1) {
        $status = 'Confirm';
      }
      if($data["meeting_status"]==2) {
        $status = 'Reject';
      }

      $dateslot1 = "";
      $dateslot2 = "";
      $dateslot3 = "";
      $confirmslot = "";
      $sqllog = "select * from  tt_meeting_list where meeting_list_mid = ? order by meeting_list_datetime ASC ";
      $stmtlog = $mysqli->prepare($sqllog);
      $stmtlog->bind_param('i',$data["meeting_id"]);
      $stmtlog->execute();
      $resultlog = $stmtlog->get_result();
      $numrowlog = $resultlog->num_rows;
      if($numrowlog>0) {
        $dates = 1;
        while($datalog = $resultlog->fetch_assoc()) {

          if($dates==1) {
            $dateslot1 = $datalog["meeting_list_datetime"];
          }
          if($dates==2) {
            $dateslot2 = $datalog["meeting_list_datetime"];
          }
          if($dates==3) {
            $dateslot3 = $datalog["meeting_list_datetime"];
          }


          if($datalog["meeting_list_status"]==1) {
            $confirmslot = $datalog["meeting_list_datetime"];
          }

          $dates++;

        }
      }

      $array_inner = array();
      array_push($array_inner,number_format($runno));
      array_push($array_inner,htmlspecialchars_decode($SenderEmail));
      array_push($array_inner,htmlspecialchars_decode($SenderName));
      array_push($array_inner,htmlspecialchars_decode($ReceiveEmail));
      array_push($array_inner,htmlspecialchars_decode($ReceiveName));
      array_push($array_inner,htmlspecialchars_decode($dateslot1));
      array_push($array_inner,htmlspecialchars_decode($dateslot2));
      array_push($array_inner,htmlspecialchars_decode($dateslot3));
      array_push($array_inner,htmlspecialchars_decode($status));
      array_push($array_inner,htmlspecialchars_decode($confirmslot));
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
