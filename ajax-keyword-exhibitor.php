<?
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

/* $fair_id = (int)$_GET["fair_id"];
$keyword = $_GET['name_startsWith']; */
$fair_id = (int)($_GET['fair_id'] ?? 0);
$keyword = trim($_GET['name_startsWith'] ?? '');
$array_data = array();

// $sqlelc = " select * from tt_exhibitor_list where fair_id = ? and
//           (com_name like '%".$keyword."%'  or
//           com_taxno like '%".$keyword."%'  or
//           product_group like '%".$keyword."%'  or
//           product_brand_desc like '%".$keyword."%'  or
//           product_brand like '%".$keyword."%' ) group by com_taxno limit 100 ";

$sqlelc = " select * from tt_exhibitor_list where fair_id = ? and
          (com_name like ?  or
          com_taxno like ? ) group by com_taxno limit 100 ";
$like_keyword = '%' . $keyword . '%';
$stmtelc = $mysqli->prepare($sqlelc);
$stmtelc->bind_param('iss',$fair_id,$like_keyword,$like_keyword);
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

$sqlelc = " select product_group from tt_exhibitor_list where fair_id = ? and product_group like ? group by product_group order by product_group ASC limit 100 ";
$stmtelc = $mysqli->prepare($sqlelc);
$stmtelc->bind_param('is',$fair_id,$like_keyword );
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

$sqlelc = " select product_brand from tt_exhibitor_list where fair_id = ? and  product_brand like ? group by product_brand order by product_brand ASC limit 100 ";
$stmtelc = $mysqli->prepare($sqlelc);
$stmtelc->bind_param('is',$fair_id,$like_keyword );
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


$sqlelc = " select * from tt_exhibitor_list where fair_id = ? and product_brand_desc like ? group by com_taxno order by product_brand_desc limit 100 ";
$stmtelc = $mysqli->prepare($sqlelc);
$stmtelc->bind_param('is',$fair_id,$like_keyword );
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

$sqlelc = " select a.*,CONCAT(a.Block_Code,'-',a.Booth_no) as block_item  from tt_exhibitor_booth a left join tt_exhibitor_list b on a.exl_id=b.exl_id where b.fair_id = ? and (CONCAT(a.Block_Code,'-',a.Booth_no) like ? or Hall_Name like ? ) group by a.exl_id, a.Hall_Name, a.Block_Code, a.Booth_no limit 100 ";
$stmtelc = $mysqli->prepare($sqlelc);
$stmtelc->bind_param('iss',$fair_id,$like_keyword ,$like_keyword );
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
