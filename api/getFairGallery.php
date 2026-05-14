<?
include_once ("../backoffice/connect.php");

$array_data = array();
$token = $_POST["token"];
$tagFairId = (int)$_POST["tagFairId"];
$tagMasterId = (int)$_POST["tagMasterId"];
$keyword = trim($_POST["keyword"]);

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

  $fcat_id = 6;
  $fct_id = 0;
  $sqlf = "select * from tt_fair_list_cat where fair_id = ? and fcat_id = ? limit 1 ";
  $stmtf = $mysqli->prepare($sqlf);
  $stmtf->bind_param('ii',$data["fair_id"],$fcat_id);
  $stmtf->execute();
  $resultf = $stmtf->get_result();
  $numrowf = $resultf->num_rows;
  if($numrowf>0) {
    $dataf = $resultf->fetch_assoc();
    $fct_id = $dataf["fct_id"];
  }

  $alldata = 0;

  $kw_search = " ";
  if($keyword!="") {
    $kw_search = " and (
                        a.fcg_title_th like '%".$keyword."%' or
                        a.fcg_title_en like '%".$keyword."%' or
                        a.fcg_detail_th like '%".$keyword."%' or
                        a.fcg_detail_en like '%".$keyword."%'
                      )
                ";
  }

  $sqlc = "select * from  tt_fair_content_gallery a left join tt_fair_list b on a.fair_id=b.fair_id where a.fct_id = ? and a.fcg_status = 1 and a.fcg_pubish = 1 $kw_search order by a.fcg_create_date DESC ";
  $stmtc = $mysqli->prepare($sqlc);
  $stmtc->bind_param('i',$fct_id);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;

  if($numrowc>0) {
    while($datac = $resultc->fetch_assoc()) {

      $pushitem = 1;



      if($tagFairId>0) {
        if($datac["fcg_tag_fair_id"]!=$tagFairId) {
          $pushitem = 0;
        }
      }

      if($tagMasterId>0) {
        $pushitem = 0;
        if($datac["fcg_tag_master_id"]!="") {
          $tagmasterdata = explode('|',$datac["fcg_tag_master_id"]);
          if(count($tagmasterdata)>0) {
            for($tm=0;$tm<count($tagmasterdata);$tm++) {
              $tmid = (int)$tagmasterdata[$tm];
              if($tagMasterId==$tmid) {
                $pushitem = 1;
                break;
              }
            }
          }
        }
      }

      if($pushitem==1) {
        $array_item = array();
        $array_item["gellery_id"] = $datac["fcg_id"];
        $array_item["gellery_title_th"] = $datac["fcg_title_th"];
        $array_item["gellery_title_en"] = $datac["fcg_title_en"];
        $array_item["gellery_detail_th"] = $datac["fcg_detail_th"];
        $array_item["gellery_detail_en"] = $datac["fcg_detail_en"];
        $array_item["gellery_create_date"] = $datac["fcg_create_date"];

        $array_item["TagFair"] = array();
        if($datac["fcg_tag_fair_id"]!="") {
          $sqltf = "select * from tt_fair_group where fair_group_id = ?  ";
          $stmttf = $mysqli->prepare($sqltf);
          $stmttf->bind_param('i',$datac["fcg_tag_fair_id"]);
          $stmttf->execute();
          $resulttf = $stmttf->get_result();
          $numrowtf = $resulttf->num_rows;
          if($numrowtf>0) {
            while($datatf = $resulttf->fetch_assoc()) {
              $array_itemtf = array();
              $array_itemtf["tag_id"] = $datatf["fair_group_id"];
              $array_itemtf["tag_name"] = $datatf["fair_group_abb"];
              $array_itemtf["tag_description"] = $datatf["fair_group_name_th"];
              array_push($array_item["TagFair"],$array_itemtf);
            }
          }
        }

        $array_item["TagMaster"] = array();
        if($datac["fcg_tag_master_id"]!="") {
          $tagmaster = explode('|',$datac["fcg_tag_master_id"]);
          if(count($tagmaster)>0) {
            for($tm=0;$tm<count($tagmaster);$tm++) {
              $tmid = (int)$tagmaster[$tm];
              $sqltm = "select * from tt_tag_master where tag_id = ?  ";
              $stmttm = $mysqli->prepare($sqltm);
              $stmttm->bind_param('i',$tmid);
              $stmttm->execute();
              $resulttm = $stmttm->get_result();
              $numrowtm = $resulttm->num_rows;
              if($numrowtm>0) {
                while($datatm = $resulttm->fetch_assoc()) {
                  $array_itemtm = array();
                  $array_itemtm["tag_id"] = $datatm["tag_id"];
                  $array_itemtm["tag_name"] = $datatm["tag_name"];
                  array_push($array_item["TagMaster"],$array_itemtm);
                }
              }
            }
          }
        }

        $array_item["GalleryPhoto"] = array();
        $sqlc2 = "select * from  tt_fair_content_gallery_file where fcg_id = ? order by gall_file_pos ASC ";
        $stmtc2 = $mysqli->prepare($sqlc2);
        $stmtc2->bind_param('i',$datac["fcg_id"],);
        $stmtc2->execute();
        $resultc2 = $stmtc2->get_result();
        $numrowc2 = $resultc2->num_rows;
        if($numrowc2>0) {
          while($datac2 = $resultc2->fetch_assoc()) {

            $urlfile = "";
            $urlfile = str_replace('/data','data',$datac2["gall_file_path"]);
            $urlfile = ROOTPATHDOMAIN.$urlfile;

            $array_item2 = array();
            $array_item2["photo_id"] = $datac2["gall_file_id"];
            $array_item2["photo_url"] = $urlfile;
            $array_item2["photo_position"] = $datac2["gall_file_pos"];
            array_push($array_item["GalleryPhoto"],$array_item2);
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
