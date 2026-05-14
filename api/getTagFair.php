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

  $sqlc = "select * from tt_fair_group where fair_group_status != '9' order by fair_group_abb ASC ";
  $stmtc = $mysqli->prepare($sqlc);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;
  $array_data["ConntItem"] = $numrowc;
  if($numrowc>0) {
    while($datac = $resultc->fetch_assoc()) {
      $array_item = array();
      $array_item["tag_id"] = $datac["fair_group_id"];
      $array_item["tag_name"] = $datac["fair_group_abb"];
      $array_item["tag_description"] = $datac["fair_group_name_th"];
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
