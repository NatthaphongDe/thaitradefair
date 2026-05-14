<?
include_once("backoffice/connect.php");

function array_sort($array, $on, $order = SORT_ASC)
{
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
$keyw = $_GET['name_startsWith'];

$array_data = array();


if ($typesearch == 0 or $typesearch == 1) {
  /*$sqlelc = " select * from tt_exportor_list where exp_id > 0 and
            (
              DBD_Register_No like '%".$keyw."%'  or
              Corporate_Name_TH like '%".$keyw."%' or
              Corporate_Name_EN like '%".$keyw."%'
            )
            group by User_ID order by Corporate_Name_EN ASC limit 100 ";
            */
  /* $sqlelc = " select * from tt_exportor_list where exp_id > 0 and
                      (
                        DBD_Register_No like '%".$keyw."%'  or
                        Corporate_Name_EN like '%".$keyw."%'
                      )
                      group by User_ID order by Corporate_Name_EN ASC limit 100 "; */
  $sqlelc = "SELECT * FROM tt_exportor_list 
           WHERE exp_id > 0 AND
           (
               DBD_Register_No LIKE CONCAT('%', ?, '%') OR
               Corporate_Name_EN LIKE CONCAT('%', ?, '%')
           )
           GROUP BY User_ID 
           ORDER BY Corporate_Name_EN ASC 
           LIMIT 100";
  $stmtelc = $mysqli->prepare($sqlelc);
  $stmtelc->bind_param('ss', $keyw, $keyw);
  $stmtelc->execute();
  $resultelc = $stmtelc->get_result();
  $numrowelc = $resultelc->num_rows;
  if ($numrowelc > 0) {
    while ($dataelc = $resultelc->fetch_assoc()) {

      if ($dataelc["Corporate_Name_EN"] != "") {
        if (strpos(mb_strtoupper($dataelc["Corporate_Name_EN"]), mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Corporate_Name_EN"]);
          $array_item["type"] = "COMPANY";
          $array_item["valdata"] = $dataelc["exp_id"];
          $array_item["typedata"] = 1;
          $array_item["com_name"] = $dataelc["Corporate_Name_EN"];
          array_push($array_data, $array_item);
        }
      } else {
        /*
        if (strpos(mb_strtoupper($dataelc["Corporate_Name_TH"]),mb_strtoupper($keyw)) !== false) {
            $array_item = array();
            $array_item["text"] = mb_strtoupper($dataelc["Corporate_Name_TH"]);
            $array_item["type"] = "COMPANY";
            $array_item["valdata"] = $dataelc["exp_id"];
            $array_item["typedata"] = 1;
            $array_item["com_name"] = $dataelc["Corporate_Name_TH"];
            array_push($array_data, $array_item);
        }
        */
      }


      if (strpos(mb_strtoupper($dataelc["DBD_Register_No"]), mb_strtoupper($keyw)) !== false) {
        $array_item = array();
        $array_item["text"] = mb_strtoupper($dataelc["DBD_Register_No"]);
        $array_item["type"] = "BUSNESS RERISTER NO.";
        $array_item["valdata"] =  $dataelc["exp_id"];
        $array_item["typedata"] = 1;
        $array_item["com_name"] = $dataelc["Corporate_Name_EN"];
        array_push($array_data, $array_item);
      }
    }
  }
}

if ($typesearch == 0 or $typesearch == 2) {
  /*$sqlelc = " select * from tt_exportor_product where Product_Name_EN != '' and
              (
                  Product_Name_EN like '%".$keyw."%' or
                  Product_Brand_EN like '%".$keyw."%' or
                  Product_Description_EN like '%".$keyw."%' or
                  Product_Name_TH like '%".$keyw."%' or
                  Product_Brand_TH like '%".$keyw."%' or
                  Product_Description_TH like '%".$keyw."%' or
                  Product_Group_Name_TH like '%".$keyw."%' or
                  Product_Group_Name_EN like '%".$keyw."%'
              )
              group by Product_Name_EN, Product_Brand_EN, Product_Description_EN, Product_Name_TH, Product_Brand_TH, Product_Description_TH, Product_Group_Name_TH, Product_Group_Name_EN order by Product_Name_EN ASC limit 100 ";*/

  /*$sqlelc = " select * from tt_exportor_product where Product_Name_EN != '' and
                          (
                              Product_Name_EN like '%".$keyw."%' or
                              Product_Brand_EN like '%".$keyw."%' or
                              Product_Description_EN like '%".$keyw."%' or
                              Product_Group_Name_EN like '%".$keyw."%'
                          )
                          group by Product_Name_EN, Product_Brand_EN, Product_Description_EN, Product_Group_Name_EN order by Product_Name_EN ASC limit 100 ";
                          */

  /* $sqlelc = " select * from tt_exportor_product where Product_Name_EN != '' and
                                      (
                                          Product_Name_EN like '%".$keyw."%' or
                                          Product_Group_Name_EN like '%".$keyw."%'
                                      )
                                      group by Product_Name_EN, Product_Group_Name_EN order by Product_Name_EN ASC limit 100 "; */
  $sqlelc = "SELECT * FROM tt_exportor_product 
                                      WHERE Product_Name_EN != '' AND
                                      (
                                          Product_Name_EN LIKE CONCAT('%', ?, '%') OR
                                          Product_Group_Name_EN LIKE CONCAT('%', ?, '%')
                                      )
                                      GROUP BY Product_Name_EN, Product_Group_Name_EN 
                                      ORDER BY Product_Name_EN ASC 
                                      LIMIT 100";
  $stmtelc = $mysqli->prepare($sqlelc);
  $stmtelc->bind_param('ss', $keyw, $keyw);
  $stmtelc->execute();
  $resultelc = $stmtelc->get_result();
  $numrowelc = $resultelc->num_rows;
  if ($numrowelc > 0) {
    while ($dataelc = $resultelc->fetch_assoc()) {
      if (strpos(mb_strtoupper($dataelc["Product_Name_EN"]), mb_strtoupper($keyw)) !== false) {
        $array_item = array();
        $array_item["text"] = mb_strtoupper($dataelc["Product_Name_EN"]);
        $array_item["type"] = "PRODUCT";
        $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Name_EN"]);
        $array_item["typedata"] = 2;
        $array_item["com_name"] = "";
        array_push($array_data, $array_item);
      }
      /*
      if (strpos(mb_strtoupper($dataelc["Product_Brand_EN"]),mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Product_Brand_EN"]);
          $array_item["type"] = "BRAND";
          $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Brand_EN"]);
          $array_item["typedata"] = 2;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }

      if (strpos(mb_strtoupper($dataelc["Product_Description_EN"]),mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Product_Description_EN"]);
          $array_item["type"] = "PRODUCT-DETAIL";
          $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Description_EN"]);
          $array_item["typedata"] = 2;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }
      /*
      if (strpos(mb_strtoupper($dataelc["Product_Name_TH"]),mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Product_Name_TH"]);
          $array_item["type"] = "PRODUCT";
          $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Name_TH"]);
          $array_item["typedata"] = 2;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }


      if (strpos(mb_strtoupper($dataelc["Product_Brand_TH"]),mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Product_Brand_TH"]);
          $array_item["type"] = "BRAND";
          $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Brand_TH"]);
          $array_item["typedata"] = 2;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }

      if (strpos(mb_strtoupper($dataelc["Product_Description_TH"]),mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Product_Description_TH"]);
          $array_item["type"] = "PRODUCT-DETAIL";
          $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Description_TH"]);
          $array_item["typedata"] = 2;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }

      if (strpos(mb_strtoupper($dataelc["Product_Group_Name_EN"]),mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Product_Group_Name_EN"]);
          $array_item["type"] = "GROUP";
          $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Group_Name_EN"]);
          $array_item["typedata"] = 2;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }

      if (strpos(mb_strtoupper($dataelc["Product_Group_Name_TH"]),mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Product_Group_Name_TH"]);
          $array_item["type"] = "GROUP";
          $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Group_Name_TH"]);
          $array_item["typedata"] = 2;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }
      */
    }
  }
}

if ($typesearch == 0 or $typesearch == 3) {
  /*  $sqlelc = " select * from tt_exportor_product where Product_Cat_Name_EN != '' and
              (
                  Product_Cat_Name_TH like '%".$keyw."%' or
                  Product_Cat_Name_EN like '%".$keyw."%' or
                  Product_Sub_Cat_Name_TH like '%".$keyw."%' or
                  Product_Sub_Cat_Name_EN like '%".$keyw."%'
              )
              group by Product_Cat_Name_EN, Product_Sub_Cat_Name_EN, Product_Group_Name_EN, Product_Cat_Name_TH order by Product_Cat_Name_EN ASC limit 100 ";
              */

  /* $sqlelc = " select * from tt_exportor_product where Product_Cat_Name_EN != '' and
                          (
                              Product_Cat_Name_EN like '%".$keyw."%' or
                              Product_Sub_Cat_Name_EN like '%".$keyw."%'
                          )
                          group by Product_Cat_Name_EN, Product_Sub_Cat_Name_EN, Product_Group_Name_EN order by Product_Cat_Name_EN ASC limit 100 ";
  $stmtelc = $mysqli->prepare($sqlelc); */
  $sqlelc = "SELECT * FROM tt_exportor_product 
           WHERE Product_Cat_Name_EN != '' AND
           (
               Product_Cat_Name_EN LIKE CONCAT('%', ?, '%') OR
               Product_Sub_Cat_Name_EN LIKE CONCAT('%', ?, '%')
           )
           GROUP BY Product_Cat_Name_EN, Product_Sub_Cat_Name_EN, Product_Group_Name_EN 
           ORDER BY Product_Cat_Name_EN ASC 
           LIMIT 100";

  $stmtelc = $mysqli->prepare($sqlelc);

  $stmtelc->bind_param('ss', $keyw, $keyw);
  $stmtelc->execute();
  $resultelc = $stmtelc->get_result();
  $numrowelc = $resultelc->num_rows;
  if ($numrowelc > 0) {
    while ($dataelc = $resultelc->fetch_assoc()) {
      if (strpos(mb_strtoupper($dataelc["Product_Cat_Name_EN"]), mb_strtoupper($keyw)) !== false) {
        $array_item = array();
        $array_item["text"] = mb_strtoupper($dataelc["Product_Cat_Name_EN"]);
        $array_item["type"] = "CATEGORY";
        $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Cat_Name_EN"]);
        $array_item["typedata"] = 3;
        $array_item["com_name"] = "";
        array_push($array_data, $array_item);
      }

      /*
      if (strpos(mb_strtoupper($dataelc["Product_Cat_Name_TH"]),mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Product_Cat_Name_TH"]);
          $array_item["type"] = "CATEGORY";
          $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Cat_Name_TH"]);
          $array_item["typedata"] = 3;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }
      */

      if (strpos(mb_strtoupper($dataelc["Product_Sub_Cat_Name_EN"]), mb_strtoupper($keyw)) !== false) {
        $array_item = array();
        $array_item["text"] = mb_strtoupper($dataelc["Product_Sub_Cat_Name_EN"]);
        $array_item["type"] = "SUBCATEGORY";
        $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Sub_Cat_Name_EN"]);
        $array_item["typedata"] = 3;
        $array_item["com_name"] = "";
        array_push($array_data, $array_item);
      }

      /*
      if (strpos(mb_strtoupper($dataelc["Product_Sub_Cat_Name_TH"]),mb_strtoupper($keyw)) !== false) {
          $array_item = array();
          $array_item["text"] = mb_strtoupper($dataelc["Product_Sub_Cat_Name_TH"]);
          $array_item["type"] = "SUBCATEGORY";
          $array_item["valdata"] =  mb_strtoupper($dataelc["Product_Sub_Cat_Name_TH"]);
          $array_item["typedata"] = 3;
          $array_item["com_name"] = "";
          array_push($array_data, $array_item);
      }
      */
    }
  }
}


if (count($array_data) > 0) {
  $array_data = array_map("unserialize", array_unique(array_map("serialize", $array_data)));
  $array_data = array_sort($array_data, 'text', SORT_ASC);
  $data = array();

  for ($i = 0; $i < count($array_data); $i++) {
    if ($array_data[$i]['text'] != "") {
      $name = $array_data[$i]['text'] . '|' . $array_data[$i]['type'] . '|' . $array_data[$i]['valdata'] . '|' . $array_data[$i]['typedata'] . '|' . $array_data[$i]['com_name'];
      array_push($data, $name);
    }
  }
  echo $json = json_encode($data);
}
