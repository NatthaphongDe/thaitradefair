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

  $sqlc = "select * from tt_contact_list where fair_id = ? order by cont_create_date DESC ";
  $stmtc = $mysqli->prepare($sqlc);
  $stmtc->bind_param('i',$data["fair_id"]);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;
  $array_data["ConntItem"] = $numrowc;
  if($numrowc>0) {
    while($datac = $resultc->fetch_assoc()) {
      $array_item = array();
      $array_item["contact_id"] = $datac["cont_id"];
      $array_item["contact_name"] = $datac["cont_name"];
      $array_item["contact_email"] = $datac["cont_email"];
      $array_item["contact_phone"] = $datac["cont_tel"];
      $array_item["contact_subject"] = $datac["cont_subject"];
      $array_item["contact_message"] = $datac["cont_message"];
      $array_item["contact_create_date"] = $datac["cont_create_date"];
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
