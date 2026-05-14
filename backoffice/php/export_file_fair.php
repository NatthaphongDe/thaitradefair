<?php
include_once("../connect.php");


header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=Email.xls");
header("Pragma: no-cache");
header("Expires: 0");


$array_alldata = array();

$array_head = array("Number","Date/Time user","Title","Sender name","Sender email","Telephone","Fair name","Status","Massage user","Date/Time admin","Name admin","Email admin","Massage admin");

array_push($array_alldata,$array_head);

$rowexcel = 2;
$grand_amt = 0;
$sql_export = "select * from tt_contact_list as a left join tt_contact_list_reply as b on a.cont_id = b.cont_id where a.cont_id IN (".$imp.") ORDER BY a.cont_id DESC";
  $stmt_export = $mysqli->prepare($sql_export);
  // $stmt_export->bind_param('i',$imp);
  $stmt_export->execute();
  $result_export = $stmt_export->get_result();
  $numrow_export = $result_export->num_rows;
  if($numrow_export > 0) {
    $runrun = 1;
    while($data = $result_export->fetch_assoc()) {
      
      // if($data["cont_status"] == 1){
      //   $status = "";
      // }
      $array_inner = array();
      array_push($array_inner,number_format($runrun)); //ลำดับ
      array_push($array_inner,htmlspecialchars_decode($data["cont_create_date"])); //วัน และเวลา ของผู้ส่ง
      array_push($array_inner,htmlspecialchars_decode($data["cont_subject"])); //วัน และเวลา ของผู้รับ
      array_push($array_inner,htmlspecialchars_decode($data["cont_name"])); //ผู้ส่งจดหมาย
      array_push($array_inner,htmlspecialchars_decode($data["cont_email"])); //Email ผู้ส่ง
      array_push($array_inner,htmlspecialchars_decode($data["cont_tel"])); //เบอร์มือถือผู้ส่ง
      array_push($array_inner,htmlspecialchars_decode($data["fair_id"])); //งานที่ส่งมา
      array_push($array_inner,htmlspecialchars_decode($data["cont_status"])); //สถาน่ะ
      array_push($array_inner,htmlspecialchars_decode($data["cont_message"])); //ข้อความที่ส่งมา
      array_push($array_inner,htmlspecialchars_decode($data["reply_date"])); //ข้อความที่ส่งมา
      array_push($array_inner,htmlspecialchars_decode($data["reply_name"])); //ชื่อผู้ตอบกลับ
      array_push($array_inner,htmlspecialchars_decode($data["reply_email"])); //Email ผู้ตอบกลับ
      array_push($array_inner,htmlspecialchars_decode($data["reply_mesage"])); //ข้อความของผู้ตอบกลับ
      array_push($array_alldata,$array_inner);

      $runrun++;

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
