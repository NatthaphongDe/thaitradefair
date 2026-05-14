<?
include_once ("backoffice/connect.php");

$cat = $mysqli->real_escape_string(urldecode($_GET["cat"]));
$keyw = $mysqli->real_escape_string(urldecode($_GET["keyw"]));
$keywtype = $mysqli->real_escape_string((int)$_GET["keywtype"]);
$pagetype = $mysqli->real_escape_string((int)$_GET["pagetype"]);
$csrf_token = $mysqli->real_escape_string($_GET['csrf_token']);

if ($_SESSION['csrf_token'] != $csrf_token) { ?>
  <script type="text/javascript">
    setTimeout(function () {top.alertToken();},2000);
  </script>
<?
exit();
}


if($pagetype==1) {

  /* if(trim($cat)=="") {
    $cat_search = "  ";
  } else {
    $cat_search = " and exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN = '".$cat."' group by exp_id ) ";
  }

  $wordsearch = " ";
  if(trim($keyw)!="") {
    if($keywtype == 0) {

      $wordsearch = " and (
        DBD_Register_No like '%".$keyw."%'  or
        Corporate_Name_TH like '%".$keyw."%' or
        Corporate_Name_EN like '%".$keyw."%' or
        Telephone like '%".$keyw."%' or
        Mail like '%".$keyw."%' or
        exp_id in (
            select exp_id from tt_exportor_product where
            Product_Cat_Name_EN like '%".$keyw."%' or
            Product_Sub_Cat_Name_EN like '%".$keyw."%' or
            Product_Group_Name_EN like '%".$keyw."%' or
            Product_Name_EN like '%".$keyw."%' or
            Product_Brand_EN like '%".$keyw."%' or
            Product_Description_EN like '%".$keyw."%'
            group by exp_id
        )
      ) ";
    } else if($keywtype == 1) {
      $wordsearch = " and (
                        Corporate_Name_TH like '%".$keyw."%' or
                        Corporate_Name_EN like '%".$keyw."%'
                    ) ";

    } else if($keywtype == 2) {
      $wordsearch = " and (
        exp_id in (
            select exp_id from tt_exportor_product where
            Product_Name_EN like '%".$keyw."%'
            group by exp_id
        )
      ) ";

    } else if($keywtype == 3) {
      $wordsearch = " and (
        exp_id in (
            select exp_id from tt_exportor_product where
            Product_Cat_Name_EN like '%".$keyw."%' or
            Product_Sub_Cat_Name_EN like '%".$keyw."%'
            group by exp_id
        )
      ) ";
    }
  }

  $showpage = 100;

  $sqlel = " select * from tt_exportor_list where Corporate_Name_TH != ' ' and Corporate_Name_EN != ' ' and Corporate_Name_TH != ' ' and Corporate_Name_EN != ' ' and Corporate_Name_EN NOT REGEXP '[ก-๙]' and exp_id > 0 $wordsearch $cat_search group by User_ID order by Corporate_Name_EN ASC  ";

  $stmtel = $mysqli->prepare($sqlel); */
  $cat_search = "";
  $wordsearch = "";
  $bindParams = [];
  $bindTypes = "";

  if(trim($cat) != "") {
      $cat_search = " AND exp_id IN (SELECT exp_id FROM tt_exportor_product WHERE Product_Cat_Name_EN = ? GROUP BY exp_id)";
      $bindParams[] = $cat;
      $bindTypes .= "s";
  }
 
  if(trim($keyw) != "") {
      if($keywtype == 0) {
          $wordsearch = " AND (
              DBD_Register_No LIKE CONCAT('%', ?, '%') OR
              Corporate_Name_TH LIKE CONCAT('%', ?, '%') OR
              Corporate_Name_EN LIKE CONCAT('%', ?, '%') OR
              Telephone LIKE CONCAT('%', ?, '%') OR
              Mail LIKE CONCAT('%', ?, '%') OR
              exp_id IN (
                  SELECT exp_id FROM tt_exportor_product WHERE
                  Product_Cat_Name_EN LIKE CONCAT('%', ?, '%') OR
                  Product_Sub_Cat_Name_EN LIKE CONCAT('%', ?, '%') OR
                  Product_Group_Name_EN LIKE CONCAT('%', ?, '%') OR
                  Product_Name_EN LIKE CONCAT('%', ?, '%') OR
                  Product_Brand_EN LIKE CONCAT('%', ?, '%') OR
                  Product_Description_EN LIKE CONCAT('%', ?, '%')
                  GROUP BY exp_id
              )
          )";
          $bindParams = array_merge($bindParams, array_fill(0, 11, $keyw));
          $bindTypes .= str_repeat("s", 11);
      } else if($keywtype == 1) {
          $wordsearch = " AND (
              Corporate_Name_TH LIKE CONCAT('%', ?, '%') OR
              Corporate_Name_EN LIKE CONCAT('%', ?, '%')
          )";
          $bindParams = array_merge($bindParams, array_fill(0, 2, $keyw));
          $bindTypes .= "ss";
      } else if($keywtype == 2) {
          $wordsearch = " AND exp_id IN (
              SELECT exp_id FROM tt_exportor_product WHERE
              Product_Name_EN LIKE CONCAT('%', ?, '%')
              GROUP BY exp_id
          )";
          $bindParams[] = $keyw;
          $bindTypes .= "s";
      } else if($keywtype == 3) {
          $wordsearch = " AND exp_id IN (
              SELECT exp_id FROM tt_exportor_product WHERE
              Product_Cat_Name_EN LIKE CONCAT('%', ?, '%') OR
              Product_Sub_Cat_Name_EN LIKE CONCAT('%', ?, '%')
              GROUP BY exp_id
          )";
          $bindParams = array_merge($bindParams, array_fill(0, 2, $keyw));
          $bindTypes .= "ss";
      }
  }

  $showpage = 100;

  $sqlel = "SELECT * FROM tt_exportor_list 
            WHERE Corporate_Name_TH != ' ' 
            AND Corporate_Name_EN != ' ' 
            AND Corporate_Name_TH != ' ' 
            AND Corporate_Name_EN != ' ' 
            AND Corporate_Name_EN NOT REGEXP '[ก-๙]' 
            AND exp_id > 0 $wordsearch $cat_search 
            GROUP BY User_ID 
            ORDER BY Corporate_Name_EN ASC";
 /* print_r($sqlel);
 print_r($bindTypes);
 exit(); */
  $stmtel = $mysqli->prepare($sqlel);

  if (!empty($bindParams)) {
      $stmtel->bind_param($bindTypes, ...$bindParams);
  }

  $stmtel->execute();
  $resultel = $stmtel->get_result();
  $numrowel = $resultel->num_rows;
  $allpage = ceil($numrowel/$showpage);
  if($numrowel<=$showpage) {
    $numrowelshow = $numrowel;
  } else {
    $numrowelshow = $showpage;
  }

  $page = $mysqli->real_escape_string((int)$_GET["page"]);
  if($page<=0) {
    $page = 1;
  } else {
    if($page>=$allpage) {
      $page = $allpage;
    }
  }

  if($page<=0) {
    $page = 1;
  }
  $start_page = $showpage*($page-1);
  $on_page = $start_page;
  if($on_page==0) {
    $on_page = 1;
  }
  if($on_page>=$numrowel) {
    $on_page = $numrowel;
  }
  $end_page = $start_page+$showpage;
  if($end_page>=$numrowel) {
    $end_page = $numrowel;
  }

  ?>
  <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem;">
      <div class="col-12 col-sm-6 _exleftpad" style="padding-left: 2rem;">
        <input class="form-check-input titleCheckDefault-1" type="checkbox" value=""
            id="titleCheckDefault" onchange="getCheckItem('act_id_ss','titleCheckDefault-1');">
          <label class="form-check-label" for="titleCheckDefault">
              Company Name
          </label>
          <span class="total-1">(<?=number_format($numrowel)?>)</span>
      </div>
      <div class="col-12 col-sm-6 text-white total-right text-end">
          <span class="">
              <?=number_format($on_page)?> - <?=number_format($end_page)?> of <?=number_format($numrowel)?>
          </span>
          <? if($page==1) { ?>
            <i class="icon-navigator-page bi bi-chevron-left text-white disable"></i>
          <? } else { ?>
            <?
            $backpage = $page-1;
            ?>
            <a onclick="changePageEx('<?=$backpage?>',1);"><i class="icon-navigator-page bi bi-chevron-left text-white"></i></a>
          <? } ?>

          <input type="text" value="<?=$page?>" id="pagebox" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onkeyup="changePageExKey(this.value,1)"  />
          <span class=""> / <?=$allpage?> </span>

          <? if($page>=$allpage) { ?>
            <i class="icon-navigator-page bi bi-chevron-right text-white disable"></i>
          <? } else { ?>
            <?
            $nextpage = $page+1;
            ?>
            <a onclick="changePageEx('<?=$nextpage?>',1);"><i class="icon-navigator-page bi bi-chevron-right text-white"></i></a>
          <? } ?>
      </div>
  </div>

  <div class="scrollbar-inner">
      <ul class="list-group checkbox-list-company border-0 px-4">

        <? if($keyw!="") { ?>
          <li
              class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
              <div class="divchkex" >
                &nbsp;
              </div>

              <div class="ms-2 me-auto">
                  <div class="title fw-bold ">
                      Search results for <span class="color_matchearch">"<?=mb_strtoupper($keyw)?>"</span>
                  </div>
              </div>
          </li>

        <? } ?>

        <?
        $sqleld = " select * from tt_exportor_list where Corporate_Name_TH != ' ' and Corporate_Name_EN != ' ' and Corporate_Name_EN NOT REGEXP '[ก-๙]' and exp_id > 0 $wordsearch $cat_search group by User_ID order by Corporate_Name_EN ASC limit $start_page,100 ";
        $stmteld = $mysqli->prepare($sqleld);
        if (!empty($bindParams)) {
          $stmteld->bind_param($bindTypes, ...$bindParams);
        }
        $stmteld->execute();
        $resulteld = $stmteld->get_result();
        $numroweld = $resulteld->num_rows;
        if($numroweld>0) {
          while($dataeld = $resulteld->fetch_assoc()) {

            if($dataeld["Corporate_Name_EN"]!="") {
              $namecom = $dataeld["Corporate_Name_EN"];
            } else {
              $namecom = $dataeld["Corporate_Name_TH"];
            }

            $namecomshow = $namecom;

            $productitem = "";
            $sqleldp = " select Product_Name_EN from tt_exportor_product where exp_id = ? and Product_Name_EN != '' group by Product_Name_EN order by Product_Name_EN ASC ";
            $stmteldp = $mysqli->prepare($sqleldp);
            $stmteldp->bind_param('i',$dataeld["exp_id"]);
            $stmteldp->execute();
            $resulteldp = $stmteldp->get_result();
            $numroweldp = $resulteldp->num_rows;
            if($numroweldp>0) {
              while($dataeldp = $resulteldp->fetch_assoc()) {
                if($productitem=="") {
                  $productitem = $dataeldp["Product_Name_EN"];
                } else {
                  $productitem = $productitem.", ".$dataeldp["Product_Name_EN"];
                }
              }
            }

            $categoryitem = "";
            $sqleldp = " select Product_Cat_Name_EN from tt_exportor_product where exp_id = ? and Product_Cat_Name_EN != '' group by Product_Cat_Name_EN order by Product_Cat_Name_EN ASC ";
            $stmteldp = $mysqli->prepare($sqleldp);
            $stmteldp->bind_param('i',$dataeld["exp_id"]);
            $stmteldp->execute();
            $resulteldp = $stmteldp->get_result();
            $numroweldp = $resulteldp->num_rows;
            if($numroweldp>0) {
              while($dataeldp = $resulteldp->fetch_assoc()) {
                if($categoryitem=="") {
                  $categoryitem = $dataeldp["Product_Cat_Name_EN"];
                } else {
                  $categoryitem = $categoryitem.", ".$dataeldp["Product_Cat_Name_EN"];
                }
              }
            }

            if($keyw!="") {
              $namecom = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">'.mb_strtoupper($keyw).'</span>',$namecom);


              if(!($productitem == '' || $productitem == '-')) {
                $productitem = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">'.mb_strtoupper($keyw).'</span>',$productitem);
              } else {
                $productitem = '';
              }

              $categoryitem = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">'.mb_strtoupper($keyw).'</span>',$categoryitem);
            }

            if($categoryitem != "") {
              $categoryitem = "<div><b>Category : </b>" . $categoryitem . "<div>";
            }

        ?>
          <li
              class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
              <div class="divchkex" >
                <input class="form-check-input mx-2 act_id_ss" name="chklist[]" type="checkbox" value="<?=$dataeld["exp_id"]?>">
              </div>

              <div class="ms-2 me-auto">
                  <div class="title fw-bold">
                      <a href="<?=ROOTPATHDOMAIN?>exporters-profile/<?=$dataeld["exp_id"]?>/<?=urlencode($namecomshow)?>/">
                          <?=$namecom?>
                      </a>
                  </div>
                  <?=$productitem?> 
                  <?=$categoryitem?>
              </div>
          </li>
        <? } } else { ?>
          <li
              class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0 ">
              <div class="ms-2 me-auto w-100 mt-5 mb-5 text-center">
                  Data not found.
              </div>

          </li>
        <? } ?>
      </ul>
  </div>

  <?

  exit();
}


if($pagetype==2) {

  if(trim($cat)=="") {
    $cat_search = "  ";
  } else {
    $cat_search = " and exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN = '".$cat."' group by exp_id ) ";
  }

  $wordsearch = " ";
  if(trim($keyw)!="") {
    // $wordsearch = " and (
    //                       a.DBD_Register_No like '%".$keyw."%'  or
    //                       a.Corporate_Name_TH like '%".$keyw."%' or
    //                       a.Corporate_Name_EN like '%".$keyw."%' or
    //                       a.Telephone like '%".$keyw."%' or
    //                       a.Mail like '%".$keyw."%' or
    //                       a.exp_id in (
    //                           select exp_id from tt_exportor_product where
    //                           Product_Cat_Name_TH like '%".$keyw."%' or
    //                           Product_Cat_Name_EN like '%".$keyw."%' or
    //                           Product_Sub_Cat_Name_TH like '%".$keyw."%' or
    //                           Product_Sub_Cat_Name_EN like '%".$keyw."%' or
    //                           Product_Group_Name_TH like '%".$keyw."%' or
    //                           Product_Group_Name_EN like '%".$keyw."%' or
    //                           Product_Name_TH like '%".$keyw."%' or
    //                           Product_Name_EN like '%".$keyw."%' or
    //                           Product_Brand_TH like '%".$keyw."%' or
    //                           Product_Brand_EN like '%".$keyw."%' or
    //                           Product_Description_TH like '%".$keyw."%' or
    //                           Product_Description_EN like '%".$keyw."%'
    //                           group by exp_id
    //                       )
    //                    ) ";

    /*$wordsearch = " and (
                          a.exp_id in (
                              select exp_id from tt_exportor_product where
                              Product_Name_TH like '%".$keyw."%' or
                              Product_Name_EN like '%".$keyw."%' or
                              Product_Brand_TH like '%".$keyw."%' or
                              Product_Brand_EN like '%".$keyw."%' or
                              Product_Description_TH like '%".$keyw."%' or
                              Product_Description_EN like '%".$keyw."%' or
                              Product_Group_Name_TH like '%".$keyw."%' or
                              Product_Group_Name_EN like '%".$keyw."%'
                              group by exp_id
                          )
                       ) ";
                       */

                       $wordsearch = " and (
                                             exp_id in (
                                                 select exp_id from tt_exportor_product where
                                                 Product_Name_EN like '%".$keyw."%'
                                                 group by exp_id
                                             )
                                          ) ";
  }

  $showpage = 100;

  //$sqlel = " select * from tt_exportor_list a left join tt_exportor_product b on a.exp_id=b.exp_id where b.Product_Name_EN != '' $wordsearch $cat_search group by b.Product_Name_EN order by a.Corporate_Name_EN ASC, a.Corporate_Name_TH ASC, b.Product_Name_EN ASC  ";

  $sqlel = " select * from tt_exportor_list where Corporate_Name_TH != ' ' and Corporate_Name_EN != ' ' and Corporate_Name_EN NOT REGEXP '[ก-๙]' and exp_id in (select exp_id from tt_exportor_product where Product_Name_EN != '' group by exp_id) $wordsearch $cat_search group by User_ID order by Corporate_Name_EN ASC  ";
  $stmtel = $mysqli->prepare($sqlel);
  $stmtel->execute();
  $resultel = $stmtel->get_result();
  $numrowel = $resultel->num_rows;
  $allpage = ceil($numrowel/$showpage);
  if($numrowel<=$showpage) {
    $numrowelshow = $numrowel;
  } else {
    $numrowelshow = $showpage;
  }

  $page = $mysqli->real_escape_string((int)$_GET["page"]);
  if($page<=0) {
    $page = 1;
  } else {
    if($page>=$allpage) {
      $page = $allpage;
    }
  }

  if($page<=0) {
    $page = 1;
  }
  $start_page = $showpage*($page-1);
  $on_page = $start_page;
  if($on_page==0) {
    $on_page = 1;
  }
  if($on_page>=$numrowel) {
    $on_page = $numrowel;
  }
  $end_page = $start_page+$showpage;
  if($end_page>=$numrowel) {
    $end_page = $numrowel;
  }

  ?>
  <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem;">
      <div class="col-12 col-sm-6 _exleftpad" style="padding-left: 2rem;">
        <input class="form-check-input titleCheckDefault-2" type="checkbox" value=""
            id="titleCheckDefault" onchange="getCheckItem('act_id_ss-2','titleCheckDefault-2');">
          <label class="form-check-label" for="titleCheckDefault">
              Products
          </label>
          <span class="total-1">(<?=number_format($numrowel)?>)</span>
      </div>
      <div class="col-12 col-sm-6 text-white total-right text-end">
          <span class="">
              <?=number_format($on_page)?> - <?=number_format($end_page)?> of <?=number_format($numrowel)?>
          </span>
          <? if($page==1) { ?>
            <i class="icon-navigator-page bi bi-chevron-left text-white disable"></i>
          <? } else { ?>
            <?
            $backpage = $page-1;
            ?>
            <a onclick="changePageEx('<?=$backpage?>',2);"><i class="icon-navigator-page bi bi-chevron-left text-white"></i></a>
          <? } ?>

          <input type="text" value="<?=$page?>" id="pagebox" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onkeyup="changePageExKey(this.value,2)"  />
          <span class=""> / <?=$allpage?> </span>

          <? if($page>=$allpage) { ?>
            <i class="icon-navigator-page bi bi-chevron-right text-white disable"></i>
          <? } else { ?>
            <?
            $nextpage = $page+1;
            ?>
            <a onclick="changePageEx('<?=$nextpage?>',2);"><i class="icon-navigator-page bi bi-chevron-right text-white"></i></a>
          <? } ?>
      </div>
  </div>
  <div class="scrollbar-inner">
      <ul class="list-group checkbox-list-company border-0 px-4">

        <? if($keyw!="") { ?>
          <li
              class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
              <div class="divchkex" >
                &nbsp;
              </div>

              <div class="ms-2 me-auto">
                  <div class="title fw-bold ">
                      Search results for <span class="color_matchearch">"<?=mb_strtoupper($keyw)?>"</span>
                  </div>
              </div>
          </li>

        <? } ?>

        <?
        //$sqleld = " select * from tt_exportor_list a left join tt_exportor_product b on a.exp_id=b.exp_id where b.Product_Name_EN != '' $wordsearch $cat_search group by b.Product_Name_EN order by a.Corporate_Name_EN ASC, a.Corporate_Name_TH ASC, b.Product_Name_EN ASC limit $start_page,100 ";

        $sqleld = " select * from tt_exportor_list where Corporate_Name_TH != ' ' and Corporate_Name_EN != ' ' and Corporate_Name_EN NOT REGEXP '[ก-๙]' and exp_id in (select exp_id from tt_exportor_product where Product_Name_EN != '' group by exp_id) $wordsearch $cat_search group by User_ID order by Corporate_Name_EN ASC limit $start_page,100 ";
        $stmteld = $mysqli->prepare($sqleld);
        $stmteld->execute();
        $resulteld = $stmteld->get_result();
        $numroweld = $resulteld->num_rows;
        if($numroweld>0) {
          while($dataeld = $resulteld->fetch_assoc()) {
            /*
            if($dataeld["Corporate_Name_EN"]!="") {
              $namecom = $dataeld["Corporate_Name_EN"];
            } else {
              $namecom = $dataeld["Corporate_Name_TH"];
            }
            */

            if($dataeld["Corporate_Name_EN"]!="") {
              $namecom = $dataeld["Corporate_Name_EN"];
            } else {
              $namecom = $dataeld["Corporate_Name_TH"];
            }

            $productitem = "";
            $sqleldp = " select Product_Name_EN from tt_exportor_product where exp_id = ? and Product_Name_EN != '' group by Product_Name_EN order by Product_Name_EN ASC ";
            $stmteldp = $mysqli->prepare($sqleldp);
            $stmteldp->bind_param('i',$dataeld["exp_id"]);
            $stmteldp->execute();
            $resulteldp = $stmteldp->get_result();
            $numroweldp = $resulteldp->num_rows;
            if($numroweldp>0) {
              while($dataeldp = $resulteldp->fetch_assoc()) {
                if($productitem=="") {
                  $productitem = $dataeldp["Product_Name_EN"];
                } else {
                  $productitem = $productitem.", ".$dataeldp["Product_Name_EN"];
                }
              }
            }

            $namecomshow = $namecom;


            if($keyw!="") {
              $namecom = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">'.mb_strtoupper($keyw).'</span>',$namecom);

              $productitem = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">'.mb_strtoupper($keyw).'</span>',$productitem);
            }

        ?>
          <li
              class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
              <div class="divchkex" >
                <input class="form-check-input mx-2 act_id_ss-2" name="chklist2[]" type="checkbox" value="<?=$dataeld["exp_id"]?>">
              </div>

              <div class="ms-2 me-auto">
                  <div class="title fw-bold">
                      <a href="<?=ROOTPATHDOMAIN?>exporters-profile/<?=$dataeld["exp_id"]?>/<?=urlencode($namecomshow)?>/">
                          <?=$namecom?>
                      </a>
                  </div>
                  <?=$productitem?>
              </div>
          </li>
        <? } } else { ?>
          <li
              class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0 ">
              <div class="ms-2 me-auto w-100 mt-5 mb-5 text-center">
                  Data not found.
              </div>

          </li>
        <? } ?>
      </ul>
  </div>

  <?

  exit();
}


if($pagetype==3) {

  if(trim($cat)=="") {
    $cat_search = "  ";
  } else {
    $cat_search = " and exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN = '".$cat."' group by exp_id ) ";
  }

  $wordsearch = " ";
  if(trim($keyw)!="") {
    // $wordsearch = " and (
    //                       DBD_Register_No like '%".$keyw."%'  or
    //                       Corporate_Name_TH like '%".$keyw."%' or
    //                       Corporate_Name_EN like '%".$keyw."%' or
    //                       Telephone like '%".$keyw."%' or
    //                       Mail like '%".$keyw."%' or
    //                       exp_id in (
    //                           select exp_id from tt_exportor_product where
    //                           Product_Cat_Name_TH like '%".$keyw."%' or
    //                           Product_Cat_Name_EN like '%".$keyw."%' or
    //                           Product_Sub_Cat_Name_TH like '%".$keyw."%' or
    //                           Product_Sub_Cat_Name_EN like '%".$keyw."%' or
    //                           Product_Group_Name_TH like '%".$keyw."%' or
    //                           Product_Group_Name_EN like '%".$keyw."%' or
    //                           Product_Name_TH like '%".$keyw."%' or
    //                           Product_Name_EN like '%".$keyw."%' or
    //                           Product_Brand_TH like '%".$keyw."%' or
    //                           Product_Brand_EN like '%".$keyw."%' or
    //                           Product_Description_TH like '%".$keyw."%' or
    //                           Product_Description_EN like '%".$keyw."%'
    //                           group by exp_id
    //                       )
    //                    ) ";
    /*$wordsearch = " and (
                          exp_id in (
                              select exp_id from tt_exportor_product where
                              Product_Cat_Name_TH like '%".$keyw."%' or
                              Product_Cat_Name_EN like '%".$keyw."%' or
                              Product_Sub_Cat_Name_TH like '%".$keyw."%' or
                              Product_Sub_Cat_Name_EN like '%".$keyw."%'
                              group by exp_id
                          )
                       ) ";
                       */

   $wordsearch = " and (
                         exp_id in (
                             select exp_id from tt_exportor_product where
                             Product_Cat_Name_EN like '%".$keyw."%' or
                             Product_Sub_Cat_Name_EN like '%".$keyw."%'
                             group by exp_id
                         )
                      ) ";
  }

  $showpage = 100;

  //$sqlel = " select * from tt_exportor_list where exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN != '' group by Product_Cat_Name_EN, exp_id ) $wordsearch $cat_search group by User_ID order by Corporate_Name_EN ASC  ";

  $sqlel = " select * from tt_exportor_list where Corporate_Name_TH != ' ' and Corporate_Name_EN != ' ' and Corporate_Name_EN NOT REGEXP '[ก-๙]' and exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN != '' or Product_Sub_Cat_Name_EN != '' group by exp_id) $wordsearch $cat_search group by User_ID order by Corporate_Name_EN ASC  ";
  $stmtel = $mysqli->prepare($sqlel);
  $stmtel->execute();
  $resultel = $stmtel->get_result();
  $numrowel = $resultel->num_rows;
  $allpage = ceil($numrowel/$showpage);
  if($numrowel<=$showpage) {
    $numrowelshow = $numrowel;
  } else {
    $numrowelshow = $showpage;
  }

  $page = $mysqli->real_escape_string((int)$_GET["page"]);
  if($page<=0) {
    $page = 1;
  } else {
    if($page>=$allpage) {
      $page = $allpage;
    }
  }

  if($page<=0) {
    $page = 1;
  }
  $start_page = $showpage*($page-1);
  $on_page = $start_page;
  if($on_page==0) {
    $on_page = 1;
  }
  if($on_page>=$numrowel) {
    $on_page = $numrowel;
  }
  $end_page = $start_page+$showpage;
  if($end_page>=$numrowel) {
    $end_page = $numrowel;
  }

  ?>
  <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem;">
      <div class="col-12 col-sm-6 _exleftpad" style="padding-left: 2rem;">
        <input class="form-check-input titleCheckDefault-3" type="checkbox" value=""
            id="titleCheckDefault" onchange="getCheckItem('act_id_ss-3','titleCheckDefault-3');">
          <label class="form-check-label" for="titleCheckDefault">
              Categories
          </label>
          <span class="total-1">(<?=number_format($numrowel)?>)</span>
      </div>
      <div class="col-12 col-sm-6 text-white total-right text-end">
          <span class="">
              <?=number_format($on_page)?> - <?=number_format($end_page)?> of <?=number_format($numrowel)?>
          </span>
          <? if($page==1) { ?>
            <i class="icon-navigator-page bi bi-chevron-left text-white disable"></i>
          <? } else { ?>
            <?
            $backpage = $page-1;
            ?>
            <a onclick="changePageEx('<?=$backpage?>',3);"><i class="icon-navigator-page bi bi-chevron-left text-white"></i></a>
          <? } ?>

          <input type="text" value="<?=$page?>" id="pagebox" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onkeyup="changePageExKey(this.value,3)"  />
          <span class=""> / <?=$allpage?> </span>

          <? if($page>=$allpage) { ?>
            <i class="icon-navigator-page bi bi-chevron-right text-white disable"></i>
          <? } else { ?>
            <?
            $nextpage = $page+1;
            ?>
            <a onclick="changePageEx('<?=$nextpage?>',3);"><i class="icon-navigator-page bi bi-chevron-right text-white"></i></a>
          <? } ?>
      </div>
  </div>
  <div class="scrollbar-inner">
      <ul class="list-group checkbox-list-company border-0 px-4">

        <? if($keyw!="") { ?>
          <li
              class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
              <div class="divchkex" >
                &nbsp;
              </div>

              <div class="ms-2 me-auto">
                  <div class="title fw-bold ">
                      Search results for <span class="color_matchearch">"<?=mb_strtoupper($keyw)?>"</span>
                  </div>
              </div>
          </li>

        <? } ?>

        <?
        //$sqleld = " select * from tt_exportor_list where exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN != '' group by Product_Cat_Name_EN, exp_id ) $wordsearch $cat_search group by User_ID order by Corporate_Name_EN ASC limit $start_page,100 ";

        $sqleld = " select * from tt_exportor_list where Corporate_Name_TH != ' ' and Corporate_Name_EN != ' ' and Corporate_Name_EN NOT REGEXP '[ก-๙]' and exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN != '' or Product_Sub_Cat_Name_EN != '' group by exp_id) $wordsearch $cat_search group by User_ID order by Corporate_Name_EN ASC limit $start_page,100 ";
        $stmteld = $mysqli->prepare($sqleld);
        $stmteld->execute();
        $resulteld = $stmteld->get_result();
        $numroweld = $resulteld->num_rows;
        if($numroweld>0) {
          while($dataeld = $resulteld->fetch_assoc()) {

            /*
            if($dataeld["Corporate_Name_EN"]!="") {
              $namecom = $dataeld["Corporate_Name_EN"];
            } else {
              $namecom = $dataeld["Corporate_Name_TH"];
            }

            $namecomshow = $namecom;

            $productitem = "";
            $sqleldp = " select Product_Cat_Name_EN from tt_exportor_product where exp_id = ? and Product_Cat_Name_EN != '' group by Product_Cat_Name_EN order by Product_Cat_Name_EN ASC ";
            $stmteldp = $mysqli->prepare($sqleldp);
            $stmteldp->bind_param('i',$dataeld["exp_id"]);
            $stmteldp->execute();
            $resulteldp = $stmteldp->get_result();
            $numroweldp = $resulteldp->num_rows;
            if($numroweldp>0) {
              while($dataeldp = $resulteldp->fetch_assoc()) {
                if($productitem=="") {
                  $productitem = $dataeldp["Product_Cat_Name_EN"];
                } else {
                  $productitem = $productitem.", ".$dataeldp["Product_Cat_Name_EN"];
                }
              }
            }
            */


            if($dataeld["Corporate_Name_EN"]!="") {
              $namecom = $dataeld["Corporate_Name_EN"];
            } else {
              $namecom = $dataeld["Corporate_Name_TH"];
            }

            $productitem = "";
            $sqleldp = " select Product_Cat_Name_EN from tt_exportor_product where exp_id = ? and Product_Cat_Name_EN != '' group by Product_Cat_Name_EN order by Product_Cat_Name_EN ASC ";
            $stmteldp = $mysqli->prepare($sqleldp);
            $stmteldp->bind_param('i',$dataeld["exp_id"]);
            $stmteldp->execute();
            $resulteldp = $stmteldp->get_result();
            $numroweldp = $resulteldp->num_rows;
            if($numroweldp>0) {
              while($dataeldp = $resulteldp->fetch_assoc()) {
                if($productitem=="") {
                  $productitem = $dataeldp["Product_Cat_Name_EN"];
                } else {
                  $productitem = $productitem.", ".$dataeldp["Product_Cat_Name_EN"];
                }
              }
            }

            $namecomshow = $namecom;


            if($keyw!="") {
              $namecom = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">'.mb_strtoupper($keyw).'</span>',$namecom);

              $productitem = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">'.mb_strtoupper($keyw).'</span>',$productitem);
            }


        ?>
          <li
              class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
              <div class="divchkex" >
                <input class="form-check-input mx-2 act_id_ss-3" name="chklist3[]" type="checkbox" value="<?=$dataeld["exp_id"]?>">
              </div>

              <div class="ms-2 me-auto">
                  <div class="title fw-bold">
                      <a href="<?=ROOTPATHDOMAIN?>exporters-profile/<?=$dataeld["exp_id"]?>/<?=urlencode($namecomshow)?>/">
                          <?=$namecom?>
                      </a>
                  </div>
                  <?=$productitem?>
              </div>
          </li>
        <? } } else { ?>
          <li
              class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0 ">
              <div class="ms-2 me-auto w-100 mt-5 mb-5 text-center">
                  Data not found.
              </div>

          </li>
        <? } ?>
      </ul>
  </div>

  <?

  exit();
}


  ?>
