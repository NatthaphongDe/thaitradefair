<?
include_once ("../backoffice/connect.php");

$array_data = array();
$token = $_POST["token"];

$sql = "select * from tt_fair_list where fair_token = ? ";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s',$token);
$stmt->execute();
$result = $stmt->get_result();
$numrow = $result->num_rows;
if($numrow>0) {
  $data = $result->fetch_assoc();

  $array_data["res_code"] = "00";
  $array_data["res_status"] = "success";
  $array_data["res_text"] = "success";
  $array_data["ConntItem"] = 0;

  $array_data["ItemData"] = array();

  $sqlc = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_type = '1' and a.fct_status = 1 and b.fcat_status = 1 order by a.fct_pos ASC ";
  $stmtc = $mysqli->prepare($sqlc);
  $stmtc->bind_param('i',$data["fair_id"]);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;
  $array_data["ConntItem"] = $numrowc;
  if($numrowc>0) {
    while($datac = $resultc->fetch_assoc()) {
      $array_item = array();
      $array_item["category_id"] = $datac["fct_id"];
      $array_item["category_name"] = trim($datac["fcat_name"]);
      $array_item["SubCategory"] = array();

      $sqlc2 = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_master = ? and b.fcat_type = '2' and a.fct_status = 1 and b.fcat_status = 1 order by a.fct_pos ASC ";
      $stmtc2 = $mysqli->prepare($sqlc2);
      $stmtc2->bind_param('ii',$data["fair_id"],$datac["fcat_id"]);
      $stmtc2->execute();
      $resultc2 = $stmtc2->get_result();
      $numrowc2 = $resultc2->num_rows;
      if($numrowc2>0) {
        while($datac2 = $resultc2->fetch_assoc()) {
          $array_item2 = array();
          $array_item2["category_id"] = $datac2["fct_id"];
          $array_item2["category_name"] = trim($datac2["fcat_name"]);
          array_push($array_item["SubCategory"],$array_item2);
        }
      }
      array_push($array_data["ItemData"],$array_item);
    }
  }

} else {
  $array_data["res_code"] = "01";
  $array_data["res_status"] = "error";
  $array_data["res_text"] = "Token mismatch.";
  $array_data["ConntItem"] = 0;
}
echo $json = json_encode($array_data);

?>
