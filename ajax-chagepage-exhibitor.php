<?
include_once ("backoffice/connect.php");

  $fair_id = (int)$_GET["fair_id"];
  $fct_id = (int)$_GET["fct_id"];
  $cat = $_GET["cat"];
  $keyw = $_GET["keyw"];
  $keywtype = (int)$_GET["keywtype"];  
  /* print_r($_SESSION['csrf_token']);
  print_r('<pre>'); */
  if ($_SESSION['csrf_token'] != $_GET["token"]) { ?>
    <script type="text/javascript">
      setTimeout(function () {top.alertToken();},2000);
    </script>
  <?
  exit();
  }

  $sqlc = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and a.fct_id = ?  ";
  $stmtc = $mysqli->prepare($sqlc);
  $stmtc->bind_param('ii',$fair_id,$fct_id);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;
  $datac = $resultc->fetch_assoc();
  $fctname = $datac["fcat_name"];
  
  $sqlfair = " select * from tt_fair_list where fair_id = ? ";
  $stmtfair = $mysqli->prepare($sqlfair);
  $stmtfair->bind_param('i',$fair_id);
  $stmtfair->execute();
  $resultfair = $stmtfair->get_result();
  $numrowfair = $resultfair->num_rows;
  $datafair = $resultfair->fetch_assoc();

  if(trim($cat)=="") {
    $cat_search = "  ";
  } else {
    $cat_search = " and product_cat = '".$cat."' ";
  }

  $showpage = 100;

  $wordsearch = " ";
  //if($keywtype==0) {
    if(trim($keyw)!="") {
      $wordsearch = " and (com_name like '%".$keyw."%'  or
                         com_taxno like '%".$keyw."%' or
                         product_group like '%".$keyw."%' or
                         product_brand like '%".$keyw."%' or
                         product_brand_desc like '%".$keyw."%' or
                         exl_id in (
                                   select a.exl_id from tt_exhibitor_booth a left join tt_exhibitor_list b on a.exl_id=b.exl_id where b.fair_id = '".$fair_id."' and (CONCAT(a.Block_Code,'-',a.Booth_no) like '%".$keyw."%' or Hall_Name like '%".$keyw."%' ) group by a.exl_id, a.Hall_Name, a.Block_Code, a.Booth_no
                                  )
                        ) ";
    }
  //}

  $sqlel = " select * from tt_exhibitor_list where fair_id = ? $cat_search $wordsearch group by com_taxno order by com_name ASC  ";
  $stmtel = $mysqli->prepare($sqlel);
  $stmtel->bind_param('i',$fair_id);
  $stmtel->execute();
  $resultel = $stmtel->get_result();
  $numrowel = $resultel->num_rows;
  $allpage = ceil($numrowel/$showpage);
  if($numrowel<=$showpage) {
    $numrowelshow = $numrowel;
  } else {
    $numrowelshow = $showpage;
  }


  $page = (int)$_GET["page"];
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
    <div class="fade show active pb-3" id="company-tab-pane" role="tabpanel"
        aria-labelledby="home-tab" tabindex="0">
        <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem;">
            <div class="col-12 col-sm-6 _exleftpad" style="padding-left: 2rem;">
              <input class="form-check-input " type="checkbox" value=""
                  id="titleCheckDefault" onchange="getCheckItem('act_id_ss','titleCheckDefault');">
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
                  <a onclick="changePageEx('<?=$backpage?>');"><i class="icon-navigator-page bi bi-chevron-left text-white"></i></a>
                <? } ?>

                <input type="text" value="<?=$page?>" id="pagebox" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onkeyup="changePageExKey(this.value)" />
                <span class=""> / <?=$allpage?> </span>

                <? if($page>=$allpage) { ?>
                  <i class="icon-navigator-page bi bi-chevron-right text-white disable"></i>
                <? } else { ?>
                  <?
                  $nextpage = $page+1;
                  ?>
                  <a onclick="changePageEx('<?=$nextpage?>');"><i class="icon-navigator-page bi bi-chevron-right text-white"></i></a>
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

              $sqleld = " select * from tt_exhibitor_list where fair_id = ? $cat_search $wordsearch group by com_taxno order by com_name ASC limit $start_page,100 ";
              $stmteld = $mysqli->prepare($sqleld);
              $stmteld->bind_param('i',$fair_id);
              $stmteld->execute();
              $resulteld = $stmteld->get_result();
              $numroweld = $resulteld->num_rows;
              if($numroweld>0) {
                while($dataeld = $resulteld->fetch_assoc()) {

                  $namecomshow = $dataeld["com_name"];

                  if($keyw!="") {
                    $dataeld["com_name"] = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">'.mb_strtoupper($keyw).'</span>',$dataeld["com_name"]);

                    $dataeld["product_group"] = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">'.mb_strtoupper($keyw).'</span>',$dataeld["product_group"]);
                  }

              ?>
                <li
                    class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                    <div class="divchkex" >
                      <input class="form-check-input mx-2 act_id_ss" name="chklist[]" type="checkbox" value="<?=$dataeld["exl_id"]?>">
                    </div>

                    <div class="ms-2 me-auto">
                        <div class="title fw-bold">
                            <a href="<?=ROOTPATHDOMAIN?>fair-exhibitor/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$fct_id?>/<?=urlencode($fctname)?>/<?=$dataeld["exl_id"]?>/<?=urlencode($namecomshow)?>/">
                                <?=$dataeld["com_name"]?>
                            </a>
                        </div>
                        <?=$dataeld["product_group"]?>
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
    </div>
