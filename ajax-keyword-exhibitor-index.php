<?
//exit();
include_once ("backoffice/connect.php");

function array_sort($array, $on, $order=SORT_ASC){
  $new_array = array();
  $sortable_array = array();

  if (count($array) > 0) {
      foreach ($array as $k => $v) {
          if (is_array($v)) {
              foreach ($v as $k2 => $v2) {
                  if ($k2 == $on) {
                      $sortable_array[$k] = $v2;
                  }
              }
          } else {
              $sortable_array[$k] = $v;
          }
      }

      switch ($order) {
          case SORT_ASC:
              asort($sortable_array);
              break;
          case SORT_DESC:
              arsort($sortable_array);
              break;
      }

      foreach ($sortable_array as $k => $v) {
          $new_array[$k] = $array[$k];
      }
  }

  return $new_array;
}

$typesearch = (int)$_GET["typesearch"];
$gid = (int)$_GET["gid"];
$y = (int)$_GET["y"];
$keyword = $_GET['name_startsWith'];

$array_data = array();
$params = [];
$types = "";

$params2 = [];
$types2 = "";

$params3 = [];
$types3 = "";

$g_search = " ";
$g_search2 = " ";

if($gid>0) {
  $g_search = " and fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and c.fair_group_id = ? and b.fair_flag = '1' and c.fair_group_status = '1' ) ";

  $g_search2 = " and b.fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and c.fair_group_id = ? and b.fair_flag = '1' and c.fair_group_status = '1' ) ";
  
  $params[] = $gid;
  $types .= "s";
  
  $params2[] = $gid;
  $types2 .= "s";

  $params3[] = $gid;
  $types3 .= "s";
}

$y_search = " ";
$y_search2 = " ";
if($y>0) {
  $y_search = " and fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' ) ";

  $y_search2 = " and b.fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' ) ";
  
  $params[] = $y;
  $types .= "s";
  
  $params2[] = $y;
  $types2 .= "s";

  $params3[] = $y;
  $types3 .= "s";
}

$keyword = trim($keyword ?? '');
$like_keyword = '%' . $keyword . '%';
$params3[] = $like_keyword;
$types3 .= "s";

if($typesearch==0 or $typesearch==1 or $typesearch==2) {
  $sqlelc = " select * from tt_exhibitor_list where exl_id > 0 $g_search $y_search and
            (com_name like ?  or
            com_taxno like ?) group by com_taxno limit 100 ";
  $params[] = $like_keyword;
  $params[] = $like_keyword;
  $types .= "ss";
  $stmtelc = $mysqli->prepare($sqlelc);
  $stmtelc->bind_param($types, ...$params);
  $stmtelc->execute();
  $resultelc = $stmtelc->get_result();
  $numrowelc = $resultelc->num_rows;

  if($numrowelc>0) {
    while($dataelc = $resultelc->fetch_assoc()) {


      if (strpos(mb_strtoupper($dataelc["com_name"]),mb_strtoupper($keyword)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["com_name"]);
          $array_item["type"] = "COMPANY";
          $array_item["valdata"] = $dataelc["exl_id"];
          $array_item["typedata"] = 1;
          $array_item["com_name"] = $dataelc["com_name"];
          array_push($array_data, $array_item);
      }


      if (strpos(mb_strtoupper($dataelc["com_taxno"]),mb_strtoupper($keyword)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["com_taxno"]);
          $array_item["type"] = "BUSNESS RERISTER NO.";
          $array_item["valdata"] =  $dataelc["exl_id"];
          $array_item["typedata"] = 1;
          $array_item["com_name"] = $dataelc["com_name"];
          array_push($array_data, $array_item);
      }
    }
  }

}
  
if($typesearch==0 or $typesearch==1 or $typesearch==3) {
  $sqlelc = " select product_group from tt_exhibitor_list where exl_id > 0 $g_search $y_search and product_group like ? group by product_group order by product_group ASC limit 100 ";
  $stmtelc = $mysqli->prepare($sqlelc);
  $stmtelc->bind_param($types3, ...$params3);
  $stmtelc->execute();
  $resultelc = $stmtelc->get_result();
  $numrowelc = $resultelc->num_rows;
  
  if($numrowelc>0) {
    while($dataelc = $resultelc->fetch_assoc()) {
      if (strpos(mb_strtoupper($dataelc["product_group"]),mb_strtoupper($keyword)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["product_group"]);
          $array_item["type"] = "GROUP";
          $array_item["valdata"] =  mb_strtoupper($dataelc["product_group"]);
          $array_item["typedata"] = 2;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }
    }
  }
}

if($typesearch==0 or $typesearch==1 or $typesearch==3) {
  $sqlelc = " select product_brand from tt_exhibitor_list where exl_id > 0 $g_search $y_search and product_brand like ? group by product_brand order by product_brand ASC limit 100 ";
  $stmtelc = $mysqli->prepare($sqlelc);
  $stmtelc->bind_param($types3, ...$params3);
  $stmtelc->execute();
  $resultelc = $stmtelc->get_result();
  $numrowelc = $resultelc->num_rows;
  if($numrowelc>0) {
    while($dataelc = $resultelc->fetch_assoc()) {
      if (strpos(mb_strtoupper($dataelc["product_brand"]),mb_strtoupper($keyword)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["product_brand"]);
          $array_item["type"] = "BRAND";
          $array_item["valdata"] =  mb_strtoupper($dataelc["product_brand"]);
          $array_item["typedata"] = 3;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }
    }
  }
}

if($typesearch==0 or $typesearch==1 or $typesearch==3) {
  $sqlelc = " select * from tt_exhibitor_list where exl_id > 0 $g_search $y_search and product_brand_desc like ? group by com_taxno order by product_brand_desc limit 100 ";
  $stmtelc = $mysqli->prepare($sqlelc);
  $stmtelc->bind_param($types3, ...$params3);
  $stmtelc->execute();
  $resultelc = $stmtelc->get_result();
  $numrowelc = $resultelc->num_rows;
  if($numrowelc>0) {
    while($dataelc = $resultelc->fetch_assoc()) {
      if (strpos(mb_strtoupper($dataelc["product_brand_desc"]),mb_strtoupper($keyword)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["product_brand_desc"]);
          $array_item["type"] = "PRODUCT";
          $array_item["valdata"] = mb_strtoupper($dataelc["product_brand_desc"]);
          $array_item["typedata"] = 4;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }
    }
  }
}

if($typesearch==0 or $typesearch==1) {
  $sqlelc = " select a.*,CONCAT(a.Block_Code,'-',a.Booth_no) as block_item  from tt_exhibitor_booth a left join tt_exhibitor_list b on a.exl_id=b.exl_id where a.exl_id > 0 $g_search2 $y_search2 and (CONCAT(a.Block_Code,'-',a.Booth_no) like ? or Hall_Name like ? ) group by a.exl_id, a.Hall_Name, a.Block_Code, a.Booth_no limit 100 ";
  $params2[] = $like_keyword;
  $params2[] = $like_keyword;
  $types2 .= "ss";
  $stmtelc = $mysqli->prepare($sqlelc);
  $stmtelc->bind_param($types2, ...$params2);
  $stmtelc->execute();
  $resultelc = $stmtelc->get_result();
  $numrowelc = $resultelc->num_rows;
  if($numrowelc>0) {
    while($dataelc = $resultelc->fetch_assoc()) {

      $itemdata = $dataelc["Hall_Name"]." : ".$dataelc["block_item"];

      if (strpos(mb_strtoupper($itemdata),mb_strtoupper($keyword)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($itemdata);
          $array_item["type"] = "BOOTH NO.";
          $array_item["valdata"] = $keyword;
          $array_item["typedata"] = 5;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }
    }
  }
}


if(count($array_data)>0) {
  $array_data = array_sort($array_data, 'text', SORT_ASC);
  $data = array();

  for($i=0;$i<count($array_data);$i++) {
    $name = $array_data[$i]['text'].'|'.$array_data[$i]['type'].'|'.$array_data[$i]['valdata'].'|'.$array_data[$i]['typedata'].'|'.$array_data[$i]['com_name'];
    array_push($data, $name);
  }
  echo $json = json_encode($data);
}

?>
