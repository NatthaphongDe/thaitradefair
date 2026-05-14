<?
include_once ("../backoffice/connect.php");
ini_set('display_errors', '1');

$array_data = array();
$token = $_POST["token"];
$tagFairId = (int)$_POST["tagFairId"];
$tagMasterId = (int)$_POST["tagMasterId"];
$keyword = trim($_POST["keyword"]);
$categoryId = (int)$_POST["categoryId"];

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
  $array_data["ItemDataArticle"] = array();
  $array_data["ItemDataCMS"] = array();

  $alldata = 0;

  $cat_serch = " ";
  if($categoryId>0) {
    $cat_serch = " and a.fct_id = '".$categoryId."' ";
  }

  $kw_search = " ";
  if($keyword!="") {
    $kw_search = " and (
                        a.fca_title_th like '%".$keyword."%' or
                        a.fca_title_en like '%".$keyword."%' or
                        a.fca_detail_th like '%".$keyword."%' or
                        a.fca_detail_en like '%".$keyword."%'
                      )
                ";
  }



  $sqlc = "select * from  tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_category c on a.fcat_id=c.fcat_id where a.fair_id = ? and a.fca_status = 1 and fca_pubish = 1 $cat_serch $kw_search order by a.fca_create_date DESC ";
  $stmtc = $mysqli->prepare($sqlc);
  $stmtc->bind_param('i',$data["fair_id"]);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;

  if($numrowc>0) {
    while($datac = $resultc->fetch_assoc()) {

      $pushitem = 1;


      if($tagFairId>0) {
        if($datac["fca_tag_fair_id"]!=$tagFairId) {
          $pushitem = 0;
        }
      }

      if($tagMasterId>0) {
        $pushitem = 0;
        if($datac["fca_tag_master_id"]!="") {
          $tagmasterdata = explode('|',$datac["fca_tag_master_id"]);
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

        $datac["fca_detail_th"] = str_replace('..//data','../data',$datac["fca_detail_th"]);
        $datac["fca_detail_th"] = str_replace('../data',ROOTPATHDOMAIN.'data',$datac["fca_detail_th"]);

        $datac["fca_detail_en"] = str_replace('..//data','../data',$datac["fca_detail_en"]);
        $datac["fca_detail_en"] = str_replace('../data',ROOTPATHDOMAIN.'data',$datac["fca_detail_en"]);

        $bannerpath = "";
        if($datac["fca_banner_path"]!="") {
          $bannerpath = str_replace('/data','data',$datac["fca_banner_path"]);
          $bannerpath = ROOTPATHDOMAIN.$bannerpath;
        }

        $array_item = array();
        $array_item["content_id"] = $datac["fca_id"];
        $array_item["category_id"] = $datac["fct_id"];
        $array_item["category_name"] = $datac["fcat_name"];
        $array_item["content_title_th"] = $datac["fca_title_th"];
        $array_item["content_title_en"] = $datac["fca_title_en"];
        $array_item["content_detail_th"] = $datac["fca_detail_th"];
        $array_item["content_detail_en"] = $datac["fca_detail_en"];
        $array_item["content_banner_url"] = $bannerpath;
        $array_item["content_map_title"] = $datac["fca_map_title"];
        $array_item["content_map_address"] = $datac["fca_address"];
        $array_item["content_map_lat"] = $datac["fca_lat"];
        $array_item["content_map_lng"] = $datac["fca_lng"];


        $array_item["RelateUrl"] = array();
        if($datac["fca_url"]!="") {
          $url = explode('|',$datac["fca_url"]);
          if(count($url)>0) {
            for($tm=0;$tm<count($url);$tm++) {
              if(trim($url[$tm])!="") {
                $array_itemurl = array();
                $array_itemurl["url_link"] = trim($url[$tm]);
                array_push($array_item["RelateUrl"],$array_itemurl);
              }
            }
          }
        }

        $array_item["RelateYoutube"] = array();
        if($datac["fca_youtube"]!="") {
          $url = explode('|',$datac["fca_youtube"]);
          if(count($url)>0) {
            for($tm=0;$tm<count($url);$tm++) {
              if(trim($url[$tm])!="") {
                $array_itemurl = array();
                $array_itemurl["url_link"] = trim($url[$tm]);
                array_push($array_item["RelateYoutube"],$array_itemurl);
              }
            }
          }
        }


        $array_item["AttatchFile"] = array();
        $sqlc2 = "select * from  tt_fair_content_file where file_type = 2 and content_id = ? order by file_update_date ASC ";
        $stmtc2 = $mysqli->prepare($sqlc2);
        $stmtc2->bind_param('i',$datac["fca_id"],);
        $stmtc2->execute();
        $resultc2 = $stmtc2->get_result();
        $numrowc2 = $resultc2->num_rows;
        if($numrowc2>0) {
          while($datac2 = $resultc2->fetch_assoc()) {

            $urlfile = "";
            $urlfile = str_replace('/data','data',$datac2["file_path"]);
            $urlfile = ROOTPATHDOMAIN.$urlfile;

            $array_item2 = array();
            $array_item2["file_id"] = $datac2["file_id"];
            $array_item2["file_url"] = $urlfile;
            array_push($array_item["AttatchFile"],$array_item2);
          }
        }


        $array_item["TagFair"] = array();
        if($datac["fca_tag_fair_id"]!="") {
          $sqltf = "select * from tt_fair_group where fair_group_id = ?  ";
          $stmttf = $mysqli->prepare($sqltf);
          $stmttf->bind_param('i',$datac["fca_tag_fair_id"]);
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
        if($datac["fca_tag_master_id"]!="") {
          $tagmaster = explode('|',$datac["fca_tag_master_id"]);
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

        array_push($array_data["ItemDataArticle"],$array_item);
        $alldata++;
      }
    }
  }





  $cat_serch = " ";
  if($categoryId>0) {
    $cat_serch = " and a.fct_id = '".$categoryId."' ";
  }

  $kw_search = " ";
  if($keyword!="") {
    $kw_search = " and (
                        a.fc_detail_th like '%".$keyword."%' or
                        a.fc_detail_en like '%".$keyword."%'
                      )
                ";
  }



  $sqlc = "select * from  tt_fair_content_onepage a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_category c on a.fcat_id=c.fcat_id where a.fair_id = ? and a.fc_status = 1 and fc_pubish = 1 $cat_serch $kw_search order by a.fc_create_date DESC ";
  $stmtc = $mysqli->prepare($sqlc);
  $stmtc->bind_param('i',$data["fair_id"]);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;

  if($numrowc>0) {
    while($datac = $resultc->fetch_assoc()) {

      $pushitem = 1;


      if($tagFairId>0) {
        if($datac["fc_tag_fair_id"]!=$tagFairId) {
          $pushitem = 0;
        }
      }

      if($tagMasterId>0) {
        $pushitem = 0;
        if($datac["fc_tag_master_id"]!="") {
          $tagmasterdata = explode('|',$datac["fc_tag_master_id"]);
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

        $datac["fc_detail_th"] = str_replace('..//data','../data',$datac["fc_detail_th"]);
        $datac["fc_detail_th"] = str_replace('../data',ROOTPATHDOMAIN.'data',$datac["fc_detail_th"]);

        $datac["fc_detail_en"] = str_replace('..//data','../data',$datac["fc_detail_en"]);
        $datac["fc_detail_en"] = str_replace('../data',ROOTPATHDOMAIN.'data',$datac["fc_detail_en"]);

        $bannerpath = "";
        if($datac["fc_banner_path"]!="") {
          $bannerpath = str_replace('/data','data',$datac["fc_banner_path"]);
          $bannerpath = ROOTPATHDOMAIN.$bannerpath;
        }

        $array_item = array();
        $array_item["content_id"] = $datac["fc_id"];
        $array_item["category_id"] = $datac["fct_id"];
        $array_item["category_name"] = $datac["fcat_name"];
        $array_item["content_detail_th"] = $datac["fc_detail_th"];
        $array_item["content_detail_en"] = $datac["fc_detail_en"];
        $array_item["content_banner_url"] = $bannerpath;
        $array_item["content_map_title"] = $datac["fc_map_title"];
        $array_item["content_map_address"] = $datac["fc_address"];
        $array_item["content_map_lat"] = $datac["fc_lat"];
        $array_item["content_map_lng"] = $datac["fc_lng"];


        $array_item["RelateUrl"] = array();
        if($datac["fc_url"]!="") {
          $url = explode('|',$datac["fc_url"]);
          if(count($url)>0) {
            for($tm=0;$tm<count($url);$tm++) {
              if(trim($url[$tm])!="") {
                $array_itemurl = array();
                $array_itemurl["url_link"] = trim($url[$tm]);
                array_push($array_item["RelateUrl"],$array_itemurl);
              }
            }
          }
        }

        $array_item["RelateYoutube"] = array();
        if($datac["fc_youtube"]!="") {
          $url = explode('|',$datac["fc_youtube"]);
          if(count($url)>0) {
            for($tm=0;$tm<count($url);$tm++) {
              if(trim($url[$tm])!="") {
                $array_itemurl = array();
                $array_itemurl["url_link"] = trim($url[$tm]);
                array_push($array_item["RelateYoutube"],$array_itemurl);
              }
            }
          }
        }


        $array_item["AttatchFile"] = array();
        $sqlc2 = "select * from  tt_fair_content_file where file_type = 2 and content_id = ? order by file_update_date ASC ";
        $stmtc2 = $mysqli->prepare($sqlc2);
        $stmtc2->bind_param('i',$datac["fc_id"],);
        $stmtc2->execute();
        $resultc2 = $stmtc2->get_result();
        $numrowc2 = $resultc2->num_rows;
        if($numrowc2>0) {
          while($datac2 = $resultc2->fetch_assoc()) {

            $urlfile = "";
            $urlfile = str_replace('/data','data',$datac2["file_path"]);
            $urlfile = ROOTPATHDOMAIN.$urlfile;

            $array_item2 = array();
            $array_item2["file_id"] = $datac2["file_id"];
            $array_item2["file_url"] = $urlfile;
            array_push($array_item["AttatchFile"],$array_item2);
          }
        }


        $array_item["TagFair"] = array();
        if($datac["fc_tag_fair_id"]!="") {
          $sqltf = "select * from tt_fair_group where fair_group_id = ?  ";
          $stmttf = $mysqli->prepare($sqltf);
          $stmttf->bind_param('i',$datac["fc_tag_fair_id"]);
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
        if($datac["fc_tag_master_id"]!="") {
          $tagmaster = explode('|',$datac["fc_tag_master_id"]);
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

        array_push($array_data["ItemDataCMS"],$array_item);
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
