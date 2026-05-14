<?php
include_once("../connect.php");


header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=sso-report.xls");
header("Pragma: no-cache");
header("Expires: 0");


$array_alldata = array();

$array_head = array("No.","SSO ID","Name","Company","Action","Date","IP Address");

array_push($array_alldata,$array_head);


$sql = "select * from tt_activity_sso a left join tt_sso_login b on a.sso_id=b.sso_id order by a.sso_date DESC limit 3000 ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $runno = 1;
    while($data = $result->fetch_assoc()) {
      $name = "";
      $company = "";
      if($data["sso_name_en"]!="") {
        $name = $data["sso_name_en"];
      } else {
        $name = $data["sso_name_th"];
      }

      if($data["sso_company_en"]!="") {
        $company = $data["sso_company_en"];
      } else {
        $company = $data["sso_company_th"];
      }

      $array_inner = array();
      array_push($array_inner,number_format($runno));
      array_push($array_inner,htmlspecialchars_decode($data["sso_id"]));
      array_push($array_inner,htmlspecialchars_decode($name));
      array_push($array_inner,htmlspecialchars_decode($company));
      array_push($array_inner,htmlspecialchars_decode($data["log_action"]));
      array_push($array_inner,htmlspecialchars_decode($data["sso_date"]));
      array_push($array_inner,htmlspecialchars_decode($data["sso_ip"]));
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
