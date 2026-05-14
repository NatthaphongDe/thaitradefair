<?
include_once("backoffice/connect.php");


$cat = $mysqli->real_escape_string($_GET["cat"]);
$keyw = $mysqli->real_escape_string($_GET["keyw"]);
$keywtype = $mysqli->real_escape_string((int)$_GET["keywtype"]);
$typesearch = $mysqli->real_escape_string((int)$_GET["typesearch"]);
$gid = $mysqli->real_escape_string((int)$_GET["gid"]);
$y = $mysqli->real_escape_string((int)$_GET["y"]);
$csrf_token = $mysqli->real_escape_string($_GET["csrf_token"]);

$cat = htmlspecialchars($cat, ENT_QUOTES, 'UTF-8');
$keyw = htmlspecialchars($keyw, ENT_QUOTES, 'UTF-8');
$keywtype = htmlspecialchars($keywtype, ENT_QUOTES, 'UTF-8');
$typesearch = htmlspecialchars($typesearch, ENT_QUOTES, 'UTF-8');
$gid = htmlspecialchars($gid, ENT_QUOTES, 'UTF-8');
$y = htmlspecialchars($y, ENT_QUOTES, 'UTF-8');
$csrf_token = htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8');

/* if(trim($cat)=="") {
    $cat_search = "  ";
  } else {
    $cat_search = " and product_cat = '".$cat."' ";
  }

  $g_search = " ";
  $g_search2 = " ";
  if($gid>0) {
    $g_search = " and fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and c.fair_group_id = '".$gid."' and b.fair_flag = '1' and c.fair_group_status = '1' ) ";

    $g_search2 = " and b.fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and c.fair_group_id = '".$gid."' and b.fair_flag = '1' and c.fair_group_status = '1' ) ";
  }

  $y_search = " ";
  $y_search2 = " ";
  if($y>0) {
    $y_search = " and fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = '".$y."' and b.fair_flag = '1' and c.fair_group_status = '1' ) ";

    $y_search2 = " and b.fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = '".$y."' and b.fair_flag = '1' and c.fair_group_status = '1' ) ";
  }

  $wordsearch = " ";
    if(trim($keyw)!="") {
      $wordsearch = " and (com_name like '%".$keyw."%'  or
                         com_taxno like '%".$keyw."%' or
                         product_group like '%".$keyw."%' or
                         product_brand like '%".$keyw."%' or
                         product_brand_desc like '%".$keyw."%' or
                         exl_id in (
                                   select a.exl_id from tt_exhibitor_booth a left join tt_exhibitor_list b on a.exl_id=b.exl_id where (CONCAT(a.Block_Code,'-',a.Booth_no) like '%".$keyw."%' or Hall_Name like '%".$keyw."%' ) group by a.exl_id, a.Hall_Name, a.Block_Code, a.Booth_no
                                  )
                        ) ";
    }

  $showpage = 100;
  $sqlel = " select * from tt_exhibitor_list where exl_id > 0 $g_search $y_search $cat_search $wordsearch group by com_taxno order by com_name ASC  ";
  $stmtel = $mysqli->prepare($sqlel); */

$cat_search = trim($cat) == "" ? "" : " AND product_cat = ?";
$g_search = "";
$g_search2 = "";
$y_search = "";
$y_search2 = "";
$wordsearch = "";


if ($gid > 0) {
  $g_search = " AND fair_id IN (
                      SELECT a.fair_id 
                      FROM tt_fair_list a 
                      LEFT JOIN tt_fair_group_list b ON a.fair_id = b.fair_id 
                      LEFT JOIN tt_fair_group c ON b.fair_group_id = c.fair_group_id 
                      WHERE a.fair_status = '1' 
                      AND c.fair_group_id = ? 
                      AND b.fair_flag = '1' 
                      AND c.fair_group_status = '1')";

  $g_search2 = " AND b.fair_id IN (
                      SELECT a.fair_id 
                      FROM tt_fair_list a 
                      LEFT JOIN tt_fair_group_list b ON a.fair_id = b.fair_id 
                      LEFT JOIN tt_fair_group c ON b.fair_group_id = c.fair_group_id 
                      WHERE a.fair_status = '1' 
                      AND c.fair_group_id = ? 
                      AND b.fair_flag = '1' 
                      AND c.fair_group_status = '1')";
}

if ($y > 0) {
  $y_search = " AND fair_id IN (
                      SELECT a.fair_id 
                      FROM tt_fair_list a 
                      LEFT JOIN tt_fair_group_list b ON a.fair_id = b.fair_id 
                      LEFT JOIN tt_fair_group c ON b.fair_group_id = c.fair_group_id 
                      WHERE a.fair_status = '1' 
                      AND a.fair_year = ? 
                      AND b.fair_flag = '1' 
                      AND c.fair_group_status = '1')";

  $y_search2 = " AND b.fair_id IN (
                      SELECT a.fair_id 
                      FROM tt_fair_list a 
                      LEFT JOIN tt_fair_group_list b ON a.fair_id = b.fair_id 
                      LEFT JOIN tt_fair_group c ON b.fair_group_id = c.fair_group_id 
                      WHERE a.fair_status = '1' 
                      AND a.fair_year = ? 
                      AND b.fair_flag = '1' 
                      AND c.fair_group_status = '1')";
}

if (trim($keyw) != "") {
  $wordsearch = " AND (
                        com_name LIKE ? 
                        OR com_taxno LIKE ? 
                        OR product_group LIKE ? 
                        OR product_brand LIKE ? 
                        OR product_brand_desc LIKE ? 
                        OR exl_id IN (
                          SELECT a.exl_id 
                          FROM tt_exhibitor_booth a 
                          LEFT JOIN tt_exhibitor_list b ON a.exl_id = b.exl_id 
                          WHERE (CONCAT(a.Block_Code, '-', a.Booth_no) LIKE ? 
                          OR Hall_Name LIKE ?)
                          GROUP BY a.exl_id, a.Hall_Name, a.Block_Code, a.Booth_no))";
}

$showpage = 100;
$sqlel = "SELECT * FROM tt_exhibitor_list WHERE exl_id > 0 $g_search $y_search $cat_search $wordsearch GROUP BY com_taxno ORDER BY com_name ASC";
$stmtel = $mysqli->prepare($sqlel);

$bindParams = [];
$bindTypes = '';

if (trim($cat) != "") {
  $bindParams[] = $cat;
  $bindTypes .= 's';
}
if ($gid > 0) {
  $bindParams[] = $gid;
  $bindTypes .= 'i';
}
if ($y > 0) {
  $bindParams[] = $y;
  $bindTypes .= 'i';
}
if (trim($keyw) != "") {
  $keywParam = '%' . $keyw . '%';
  $bindParams = array_merge($bindParams, array_fill(0, 7, $keywParam));
  $bindTypes .= str_repeat('s', 7);
}

if (!empty($bindParams)) {
  $stmtel->bind_param($bindTypes, ...$bindParams);
}

$stmtel->execute();
$resultel = $stmtel->get_result();
$numrowel = $resultel->num_rows;

$allpage = ceil($numrowel / $showpage);
if ($numrowel <= $showpage) {
  $numrowelshow = $numrowel;
} else {
  $numrowelshow = $showpage;
}


$page = (int)$_GET["page"];
if ($page <= 0) {
  $page = 1;
} else {
  if ($page >= $allpage) {
    $page = $allpage;
  }
}

if ($page <= 0) {
  $page = 1;
}

$start_page = $showpage * ($page - 1);

$on_page = $start_page;
if ($on_page == 0) {
  $on_page = 1;
}
if ($on_page >= $numrowel) {
  $on_page = $numrowel;
}
$end_page = $start_page + $showpage;
if ($end_page >= $numrowel) {
  $end_page = $numrowel;
}

?>
<div class="fade show active pb-3" id="company-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
  <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem;">
    <div class="col-12 col-sm-6 _exleftpad" style="padding-left: 2rem;">
      <input class="form-check-input " type="checkbox" value="" id="titleCheckDefault" onchange="getCheckItem('act_id_ss','titleCheckDefault');">
      <label class="form-check-label" for="titleCheckDefault">
        Company Name
      </label>
      <span class="total-1">(<?= number_format($numrowel) ?>)</span>
    </div>
    <div class="col-12 col-sm-6 text-white total-right text-end">
      <span class="">
        <?= number_format($on_page) ?> - <?= number_format($end_page) ?> of <?= number_format($numrowel) ?>
      </span>
      <? if ($page == 1) { ?>
        <i class="icon-navigator-page bi bi-chevron-left text-white disable"></i>
      <? } else { ?>
        <?
        $backpage = $page - 1;
        ?>
        <a onclick="changePageEx('<?= $backpage ?>');"><i class="icon-navigator-page bi bi-chevron-left text-white"></i></a>
      <? } ?>

      <input type="text" value="<?= $page ?>" id="pagebox" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onkeyup="changePageExKey(this.value)" />
      <span class=""> / <?= $allpage ?> </span>

      <? if ($page >= $allpage) { ?>
        <i class="icon-navigator-page bi bi-chevron-right text-white disable"></i>
      <? } else { ?>
        <?
        $nextpage = $page + 1;
        ?>
        <a onclick="changePageEx('<?= $nextpage ?>');"><i class="icon-navigator-page bi bi-chevron-right text-white"></i></a>
      <? } ?>
    </div>
  </div>

  <div class="scrollbar-inner">
    <ul class="list-group checkbox-list-company border-0 px-4">

      <? if ($keyw != "") { ?>
        <li class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
          <div class="divchkex">
            &nbsp;
          </div>

          <div class="ms-2 me-auto">
            <div class="title fw-bold ">
              Search results for <span class="color_matchearch">"<?= mb_strtoupper($keyw) ?>"</span>
            </div>
          </div>
        </li>

      <? } ?>

      <?

      $sqleld = " select * from tt_exhibitor_list where exl_id > 0 $g_search $y_search $cat_search $wordsearch group by com_taxno order by com_name ASC limit $start_page,100 ";
      
      $stmteld = $mysqli->prepare($sqleld);
      if (!empty($bindParams)) {
        $stmteld->bind_param($bindTypes, ...$bindParams);
      }
      $stmteld->execute();
      $resulteld = $stmteld->get_result();
      $numroweld = $resulteld->num_rows;
      if ($numroweld > 0) {
        while ($dataeld = $resulteld->fetch_assoc()) {

          $namecomshow = $dataeld["com_name"];

          if ($keyw != "") {
            $dataeld["com_name"] = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">' . mb_strtoupper($keyw) . '</span>', $dataeld["com_name"]);

            $dataeld["product_group"] = str_ireplace(mb_strtoupper($keyw), '<span class="color_matchearch">' . mb_strtoupper($keyw) . '</span>', $dataeld["product_group"]);
          }



      ?>
          <li class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
            <div class="divchkex">
              <input class="form-check-input mx-2 act_id_ss" name="chklist[]" type="checkbox" value="<?= $dataeld["exl_id"] ?>">
            </div>

            <div class="ms-2 me-auto">
              <div class="title fw-bold">
                <a href="<?= ROOTPATHDOMAIN ?>exhibitor-profile/<?= $dataeld["exl_id"] ?>/<?= urlencode($namecomshow) ?>/">
                  <?= $dataeld["com_name"] ?>
                </a>
              </div>
              <?= $dataeld["product_group"] ?>
            </div>
          </li>
        <? }
      } else { ?>
        <li class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0 ">
          <div class="ms-2 me-auto w-100 mt-5 mb-5 text-center">
            Data not found.
          </div>

        </li>
      <? } ?>

    </ul>
  </div>
</div>