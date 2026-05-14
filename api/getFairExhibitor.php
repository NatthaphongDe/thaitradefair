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



  $alldata = 0;

  $sqlc = "select * from  tt_exhibitor_list where fair_id = ? order by com_name ASC ";
  $stmtc = $mysqli->prepare($sqlc);
  $stmtc->bind_param('i',$data["fair_id"]);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;
  if($numrowc>0) {
    while($datac = $resultc->fetch_assoc()) {

      $pushitem = 1;

      if($pushitem==1) {
        $array_item = array();
        $array_item["Modified"] = $datac["update_date"];
        $array_item["Corporate_Name"] = $datac["com_name"];
        $array_item["TaxNo"] = $datac["com_taxno"];
        $array_item["Address"] = $datac["com_address"];
        $array_item["Telephone"] = $datac["com_tel"];
        $array_item["Fax"] = $datac["com_fax"];
        $array_item["Email"] = $datac["com_email"];
        $array_item["Website"] = $datac["com_web"];
        $array_item["Contact_Name"] = $datac["contact_name"];
        $array_item["Contact_Position"] = $datac["contact_position"];
        $array_item["Product_group"] = $datac["product_group"];
        $array_item["Product_cat"] = $datac["product_cat"];
        $array_item["Product_brand"] = $datac["product_brand"];
        $array_item["Product_des"] = $datac["product_brand_desc"];

        $array_item["ListProductImageUrl"] = array();
        $sqltf = "select * from tt_exhibitor_product where exl_id = ? order by pro_id ASC ";
        $stmttf = $mysqli->prepare($sqltf);
        $stmttf->bind_param('i',$datac["exl_id"]);
        $stmttf->execute();
        $resulttf = $stmttf->get_result();
        $numrowtf = $resulttf->num_rows;
        if($numrowtf>0) {
          while($datatf = $resulttf->fetch_assoc()) {
            $array_itemtf = array();
            $array_itemtf["ImageTitle"] = $datatf["ImageTitle"];
            $array_itemtf["ImageUrl"] = $datatf["ImageUrl"];
            array_push($array_item["ListProductImageUrl"],$array_itemtf);
          }
        }


        $array_item["ListBoothAllocate"] = array();
        $sqltf = "select * from tt_exhibitor_booth where exl_id = ? order by booth_id ASC ";
        $stmttf = $mysqli->prepare($sqltf);
        $stmttf->bind_param('i',$datac["exl_id"]);
        $stmttf->execute();
        $resulttf = $stmttf->get_result();
        $numrowtf = $resulttf->num_rows;
        if($numrowtf>0) {
          while($datatf = $resulttf->fetch_assoc()) {
            $array_itemtf = array();
            $array_itemtf["Booth_Amount"] = $datatf["Booth_Amount"];
            $array_itemtf["Hall_Id"] = $datatf["Hall_Id"];
            $array_itemtf["Hall_Name"] = $datatf["Hall_Name"];
            $array_itemtf["Block_Code"] = $datatf["Block_Code"];
            $array_itemtf["Booth_no"] = $datatf["Booth_no"];
            array_push($array_item["ListBoothAllocate"],$array_itemtf);
          }
        }


        array_push($array_data["ItemData"],$array_item);
        $alldata++;
      }
    }
  }

  $array_data["ConntItem"] = $alldata;

} else {
  $array_data["res_code"] = "01";
  $array_data["res_status"] = "error";
  $array_data["res_text"] = "Token mismatch.";
  $array_data["ConntItem"] = 0;
}
echo $json = json_encode($array_data);

?>
