<?php
include_once("../connect.php");


header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=admin-report.xls");
header("Pragma: no-cache");
header("Expires: 0");


$array_alldata = array();

$array_head = array("No.","Username","Role","Type","Fair Group","Fair Name","Caterory Name","Title Name","Action","Date","IP Address");

array_push($array_alldata,$array_head);


$sql = "select * from tt_activity_log a left join tt_admin b on a.admin_id=b.id left join tt_admin_role c on b.user_type=c.role_id where log_action != 'Delete' order by a.admin_date DESC limit 3000 ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $runno = 1;
    while($data = $result->fetch_assoc()) {
  
      $rolelv = "";
      if($data["role_lv"]==1) {
        $rolelv = "Super Admin";
      }
      if($data["role_lv"]==2) {
        $rolelv = "Admin";
      }
      if($data["role_lv"]==3) {
        $rolelv = "Fair Admin";
      }

      $fairgroup = "";
      $fairname = "";
      $catname = "";
      $titlename = "";
      $type = "";
      if($data["log_type"]==1) {
        $type = "Access";
      }
      if($data["log_type"]==2) {
        $type = "Fair Master";

        $sqllog = "select * from tt_fair_group where fair_group_id = ? ";
        $stmtlog = $mysqli->prepare($sqllog);
        $stmtlog->bind_param('i',$data["ref_id"]);
        $stmtlog->execute();
        $resultlog = $stmtlog->get_result();
        $numrowlog = $resultlog->num_rows;
        if($numrowlog>0) {
          $datalog = $resultlog->fetch_assoc();
          $fairgroup = $datalog["fair_group_abb"];
        }


      }
      if($data["log_type"]==3) {
        $type = "Fair List";

        $sqllog = "select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_id = ? and b.fair_flag != '9' limit 1 ";
        $stmtlog = $mysqli->prepare($sqllog);
        $stmtlog->bind_param('i',$data["ref_id"]);
        $stmtlog->execute();
        $resultlog = $stmtlog->get_result();
        $numrowlog = $resultlog->num_rows;
        if($numrowlog>0) {
          $datalog = $resultlog->fetch_assoc();
          $fairgroup = $datalog["fair_group_abb"];
          $fairname = $datalog["fair_name"];
        }

      }
      if($data["log_type"]==4) {
        $type = "Content CMS";

        $sqllog = "select * from tt_fair_content_onepage z left join tt_fair_list a on z.fair_id=a.fair_id left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id left join tt_fair_category d on z.fcat_id=d.fcat_id where z.fc_id = ? and b.fair_flag != '9' limit 1 ";
        $stmtlog = $mysqli->prepare($sqllog);
        $stmtlog->bind_param('i',$data["ref_id"]);
        $stmtlog->execute();
        $resultlog = $stmtlog->get_result();
        $numrowlog = $resultlog->num_rows;
        if($numrowlog>0) {
          $datalog = $resultlog->fetch_assoc();
          $fairgroup = $datalog["fair_group_abb"];
          $fairname = $datalog["fair_name"];
          $catname = $datalog["fcat_name"];
        }

      }
      if($data["log_type"]==5) {
        $type = "Content Article";

        $sqllog = "select * from tt_fair_content_article z left join tt_fair_list a on z.fair_id=a.fair_id left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id left join tt_fair_category d on z.fcat_id=d.fcat_id where z.fca_id = ? and b.fair_flag != '9' limit 1 ";
        $stmtlog = $mysqli->prepare($sqllog);
        $stmtlog->bind_param('i',$data["ref_id"]);
        $stmtlog->execute();
        $resultlog = $stmtlog->get_result();
        $numrowlog = $resultlog->num_rows;
        if($numrowlog>0) {
          $datalog = $resultlog->fetch_assoc();
          $fairgroup = $datalog["fair_group_abb"];
          $fairname = $datalog["fair_name"];
          $catname = $datalog["fcat_name"];
          $titlename = $datalog["fca_title_en"];
        }

      }
      if($data["log_type"]==6) {
        $type = "Gallery";

        $sqllog = "select * from tt_fair_content_gallery z left join tt_fair_list a on z.fair_id=a.fair_id left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id left join tt_fair_category d on z.fcat_id=d.fcat_id where z.fcg_id = ? and b.fair_flag != '9' limit 1 ";
        $stmtlog = $mysqli->prepare($sqllog);
        $stmtlog->bind_param('i',$data["ref_id"]);
        $stmtlog->execute();
        $resultlog = $stmtlog->get_result();
        $numrowlog = $resultlog->num_rows;
        if($numrowlog>0) {
          $datalog = $resultlog->fetch_assoc();
          $fairgroup = $datalog["fair_group_abb"];
          $fairname = $datalog["fair_name"];
          $catname = $datalog["fcat_name"];
          $titlename = $datalog["fcg_title_en"];
        }
      }

      $array_inner = array();
      array_push($array_inner,number_format($runno));
      array_push($array_inner,htmlspecialchars_decode($data["username"]));
      array_push($array_inner,htmlspecialchars_decode($rolelv));
      array_push($array_inner,htmlspecialchars_decode($type));
      array_push($array_inner,htmlspecialchars_decode($fairgroup));
      array_push($array_inner,htmlspecialchars_decode($fairname));
      array_push($array_inner,htmlspecialchars_decode($catname));
      array_push($array_inner,htmlspecialchars_decode($titlename));
      array_push($array_inner,htmlspecialchars_decode($data["log_action"]));
      array_push($array_inner,htmlspecialchars_decode($data["admin_date"]));
      array_push($array_inner,htmlspecialchars_decode($data["admin_ip"]));
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
