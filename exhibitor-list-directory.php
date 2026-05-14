<?
include_once ("backoffice/connect.php");
$top_menu_active = "exhibitor-list-directory";

$typesearch = (int)$_GET["typesearch"];
$typesearchgid = (int)$_GET["typesearchgid"];
$typesearchyear = (int)$_GET["typesearchyear"];
$kw = $_GET["kw"];
$kw = htmlspecialchars($kw, ENT_QUOTES, 'UTF-8');
$typesearchgid = htmlspecialchars($typesearchgid, ENT_QUOTES, 'UTF-8');
$typesearchyear = htmlspecialchars($typesearchyear, ENT_QUOTES, 'UTF-8');

$kw = $mysqli->real_escape_string($kw);
$typesearchgid = $mysqli->real_escape_string($typesearchgid);
$typesearchyear = $mysqli->real_escape_string($typesearchyear);
$csrf_token = $mysqli->real_escape_string($_GET["csrf_token"]);
?>
<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : Exhibitor List Directory</title>
  <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : Exhibitor List Directory">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">

    <link href="<?=ROOTPATHDOMAIN?>assets/dist/scrollbar/jquery.scrollbar.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>

                    <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner">
                        <div class="col">

                          <form method="get" action="<?=ROOTPATHDOMAIN?>exhibitor-list-directory/">
                          <input type="text" name="csrf_token" value="<?=$_SESSION['csrf_token']?>" hidden>
                          <div id="search-exhibitor-list-directory" class="row _homebanner_bgheight">
                              <div class="col-12 align-self-center pt-5">
                                  <h1 class="title">Exhibitor List Directory</h1>
                              </div>
                              <div class="col-12 align-self-center _exspad">

                                  <div id="search" class="input-group mt-2 mb-4 rounded-2">


                                      <div class="dropdown form-select type-exhibitors dropdown-exl" onclick="toogleMenuClose('_exltypeyear');toogleMenu('_exltype');">
                                        <button class="btn-exl2 _exlt" type="button">Search All</button>
                                        <input type="hidden" name="typesearch" id="typesearch" value="<?=$typesearch?>">
                                        <input type="hidden" name="typesearchgid" id="typesearchgid" value="<?=$typesearchgid?>">
                                        <input type="hidden" name="typesearchyear" id="typesearchyear" value="<?=$typesearchyear?>">
                                        <ul class="dropdown-menu exl-blog _exltype">
                                          <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(0,'Search All','0');">Search All</a></li>
                                          <li>
                                            <a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(0,'Search All','0');">
                                              by Exhibition Title <span><img class="dropdown-arrow" src="<?=ROOTPATHDOMAIN?>assets/images/arrow-right.png"></span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-submenu">
                                              <?
                                              $sqlg = "select * from tt_fair_group where fair_group_status = 1 order by fair_group_name_th ASC ";
                                              $stmtg= $mysqli->prepare($sqlg);
                                              $stmtg->execute();
                                              $resultg = $stmtg->get_result();
                                              $numrowg = $resultg->num_rows;
                                              if($numrowg>0) {
                                                while($datag = $resultg->fetch_assoc()) {
                                              ?>
                                              <li>
                                                <a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(1,'<?=$datag["fair_group_name_th"]?>','<?=$datag["fair_group_id"]?>');"><?=$datag["fair_group_name_th"]?></a>
                                              </li>

                                              <? if($typesearch==1) { ?>
                                                <script type="text/javascript">
                                                $(document).ready(function() {
                                                  <? if($typesearchgid==$datag["fair_group_id"]) { ?>
                                                    changeTypeSearch(1,'<?=$datag["fair_group_name_th"]?>','<?=$datag["fair_group_id"]?>');
                                                  <? } ?>
                                                });
                                                </script>
                                              <? } ?>

                                              <? } } ?>
                                            </ul>
                                            <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(2,'by Company','0');">by Company</a></li>
                                            <? if($typesearch==2) { ?>
                                              <script type="text/javascript">
                                              $(document).ready(function() {
                                                <? if($typesearchgid==$datag["fair_group_id"]) { ?>
                                                  changeTypeSearch(2,'by Company','0');
                                                <? } ?>
                                              });
                                              </script>
                                            <? } ?>
                                            <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(3,'by Product','0');">by Product</a></li>
                                            <? if($typesearch==2) { ?>
                                              <script type="text/javascript">
                                              $(document).ready(function() {
                                                <? if($typesearchgid==$datag["fair_group_id"]) { ?>
                                                  changeTypeSearch(3,'by Product','0');
                                                <? } ?>
                                              });
                                              </script>
                                            <? } ?>
                                          </li>
                                        </ul>
                                      </div>

                                      <div class="dropdown form-select type-exhibitors dropdown-exl" onclick="toogleMenuClose('_exltype');toogleMenu('_exltypeyear');">
                                        <button class="btn-exl2 _exly" type="button">Year</button>
                                        <ul class="dropdown-menu exl-blog _exltypeyear">
                                          <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearchYear('0','Year');">Year</a></li>
                                          <?
                                          $sqlhy = "select fair_year from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = 1 and b.fair_flag = 1 and c.fair_group_status = 1 group by fair_year order by fair_year DESC ";
                                          $stmthy= $mysqli->prepare($sqlhy);
                                          $stmthy->execute();
                                          $resulthy = $stmthy->get_result();
                                          $numrowhy = $resulthy->num_rows;
                                          if($numrowhy>0) {
                                            while($datahy = $resulthy->fetch_assoc()) {
                                          ?>
                                          <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearchYear('<?=$datahy["fair_year"]?>','<?=$datahy["fair_year"]?>');"><?=$datahy["fair_year"]?></a></li>

                                          <? if($typesearchyear>0) { ?>
                                            <script type="text/javascript">
                                            $(document).ready(function() {
                                              <? if($typesearchyear==$datahy["fair_year"]) { ?>
                                                changeTypeSearchYear('<?=$datahy["fair_year"]?>','<?=$datahy["fair_year"]?>');
                                              <? } ?>
                                            });
                                            </script>
                                          <? } ?>

                                          <? } } ?>
                                        </ul>
                                      </div>

                                      <input type="text" id="wordsearchexhi" name="kw" class="form-control w-50"
                                          placeholder="Company Name, Product Group, Product Name, Brand Name, Business Register No., Vat No. or Booth No." value="<?=$kw?>" onkeyup="getWtxt();">

                                      <button class="btn btn-search rounded-end text-center pt-2" type="submit">
                                          <i class="bi bi-search text-white "></i>
                                      </button>

                                  </div>

                              </div>
                              <div class="col-12 align-self-center pb-5 ">
                                  <span class="description-2 d-block">Examples: furniture, diamond, handicraft</span>
                              </div>
                          </div>
                          </form>

                        </div>
                    </div>

                </div>
            </div>


        </header>

        <main>
            <div class="container-fluid">
                <div class="container">
                  <form method="post" action="<?=ROOTPATHDOMAIN?>print-exhibitor-list-directory.php" target="com_m" >
                  <input type="text" name="csrf_token" value="<?=$_SESSION['csrf_token']?>" hidden>
                  <div id="company-list" class="mt-5 mt-sm-3 px-0 px-md-4">
                      <ul class="nav nav-tabs border-0" style="--bs-nav-tabs-border-width: 0 !important;">
                          <li class="nav-item">
                              <a class="nav-link active" aria-current="page">Company </a>
                          </li>
                      </ul>


                      <div class="float-end w-auto search-area pt-2" style="margin-top: -55px;">
                          <button type="submit" class="btn w-auto float-end bg-green">
                              <img src="<?=ROOTPATHDOMAIN?>assets/images/anticon-local-printshop-material.png" style="height:15px;" />
                              Print
                          </button>
                          <select id="excat_select" class="form-select selectpicker w-auto float-end"
                              style="padding-right:2rem; margin-right:0.5rem;" onchange="changePageEx(1);">
                              <option value="" selected>All Categories</option>
                              <?
                              $sqlelc = " select product_cat from tt_exhibitor_list where exl_id > 0 and product_cat != '' group by product_cat order by product_cat ASC  ";
                              $stmtelc = $mysqli->prepare($sqlelc);
                              $stmtelc->execute();
                              $resultelc = $stmtelc->get_result();
                              $numrowelc = $resultelc->num_rows;
                              if($numrowelc>0) {
                                while($dataelc = $resultelc->fetch_assoc()) {
                              ?>
                              <option value="<?=$dataelc["product_cat"]?>"><?=$dataelc["product_cat"]?></option>
                              <? } } ?>
                          </select>
                      </div>


                      <div class="tab-content px-0 _exdatalist" id="myTabContent">

                        <?

                        $keyw = $kw;
                        $keywtype = 0;
                        $typesearch = $typesearch;
                        $gid = $typesearchgid;
                        $y = $typesearchyear;
                        $bindTypes = ''; 
                        $bindValues = [];

                        $g_search = " ";
                        $g_search2 = " ";
                        if($gid>0) {
                          $g_search = " and fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and c.fair_group_id = ? and b.fair_flag = '1' and c.fair_group_status = '1' ) ";

                          $g_search2 = " and b.fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and c.fair_group_id = '".$gid."' and b.fair_flag = '1' and c.fair_group_status = '1' ) ";
                          $bindTypes .= 'i';
                          $bindValues[] = $gid;
                        }

                        $y_search = " ";
                        $y_search2 = " ";
                        if($y>0) {
                          $y_search = " and fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' ) ";

                          $y_search2 = " and b.fair_id in (select a.fair_id from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = '".$y."' and b.fair_flag = '1' and c.fair_group_status = '1' ) ";
                          $bindTypes .= 'i';
                          $bindValues[] = $y;
                        }

                        $wordsearch = " ";
                        //if($keywtype==0) {
                          if(trim($keyw)!="") {
                            $wordsearch = " and (com_name LIKE ? 
                                              OR com_taxno LIKE ? 
                                              OR product_group LIKE ? 
                                              OR product_brand LIKE ? 
                                              OR product_brand_desc LIKE ? 
                                              OR exl_id IN (
                                                  SELECT a.exl_id 
                                                  FROM tt_exhibitor_booth a 
                                                  LEFT JOIN tt_exhibitor_list b ON a.exl_id = b.exl_id 
                                                  WHERE CONCAT(a.Block_Code, '-', a.Booth_no) LIKE ? 
                                                      OR Hall_Name LIKE ? 
                                                  GROUP BY a.exl_id, a.Hall_Name, a.Block_Code, a.Booth_no
                                              )
                                          )";

                            // เตรียมค่าค้นหาโดยใช้ % สำหรับ LIKE
                            $searchValue = "%$keyw%";

                            // เพิ่ม bind types และ bind values
                            $bindTypes .= "sssssss"; // 7 ตัวแปร เป็น string ทั้งหมด
                            array_push($bindValues, $searchValue, $searchValue, $searchValue, $searchValue, $searchValue, $searchValue, $searchValue);
                          }
                        //}



                        $showpage = 100;

                        $sqlel = " select * from tt_exhibitor_list where exl_id > 0 and com_name != '' $g_search $y_search $wordsearch group by com_taxno order by com_name ASC  ";
                        $stmtel = $mysqli->prepare($sqlel);
                        if (!empty($bindValues)) {
                          $stmtel->bind_param($bindTypes, ...$bindValues);
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
                                          1 - <?=$numrowelshow?> of <?=number_format($numrowel)?>
                                      </span>
                                      <? if($page==1) { ?>
                                        <i class="icon-navigator-page bi bi-chevron-left text-white disable"></i>
                                      <? } else { ?>
                                        <?
                                        $backpage = $page-1;
                                        ?>
                                        <a onclick="changePageEx('<?=$backpage?>');"><i class="icon-navigator-page bi bi-chevron-left text-white"></i></a>
                                      <? } ?>

                                      <input type="text" value="<?=$page?>" id="pagebox" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onkeyup="changePageExKey(this.value)"  />
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

                                    $sqleld = " select * from tt_exhibitor_list where exl_id > 0  and com_name != '' $g_search $y_search $wordsearch group by com_taxno order by com_name ASC limit $start_page,100 ";
                                    $stmteld = $mysqli->prepare($sqleld);
                                    if (!empty($bindValues)) {
                                      $stmteld->bind_param($bindTypes, ...$bindValues);
                                    }
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
                                                  <a href="<?=ROOTPATHDOMAIN?>exhibitor-profile/<?=$dataeld["exl_id"]?>/<?=urlencode($namecomshow)?>/">
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
                      </div>
                  </div>

                  <br><br>

                </form>
                </div>
            </div>
        </main>

        <?php include('components/footer.php') ?>
    </div>



    <script src="<?=ROOTPATHDOMAIN?>assets/dist/scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>
    <iframe id="com_m" name="com_m" class="ifsave" width="0" height="0" frameborder="0" scrolling="no"></iframe>


    <script>
    $(document).ready(function() {
        $('#company-list .nav-link').click(() => {
            $('#company-list .nav-item').removeClass('active');
            $(this).parent('li').addClass('active');
        })
    });


    function toogleMenu(divclass) {
      if($('.'+divclass).is(":visible")) {
        $('.'+divclass).hide();
      } else {
        $('.'+divclass).show();
      }

    }

    function toogleMenuClose(divclass) {
      $('.'+divclass).hide();
    }

    function changeTypeSearch(val,txt,id) {
      $('#typesearch').val(val);
      $('._exlt').html(txt);
      if(val==1) {
        $('#typesearchgid').val(id);
      } else {
        $('#typesearchgid').val('0');
      }
    }

    function changeTypeSearchYear(val,txt) {
      $('#typesearchyear').val(val);
      $('._exly').html(txt);
    }


    </script>

    <style>
    ._homebanner {
      overflow: visible !important;
    }
    .exl-blog {
      top: 50px !important;
    }
    .dropdown-item-txt:hover {
      color: #378dd7 !important;
    }

    .navigator-text {
        font-size: 18px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #111;
    }

    .navigator-text .active {
        font-weight: bold;
    }


    h1.title {
        font-size: 40px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.34;
        letter-spacing: normal;
    }



    #search-exhibitor-list-directory {
        border-radius: 15px;
        background-blend-mode: multiply;
        border-radius: 15px;
        box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.28);
        background-blend-mode: multiply, normal;
        background: url('<?=ROOTPATHDOMAIN?>assets/images/bg-search-exhibit-export.png') no-repeat center center;
        background-size: cover;
    }

    #search-exhibitor-list-directory .title {
        font-size: 40px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.01;
        letter-spacing: normal;
        text-align: center;
        color: #fff;
    }

    #search-exhibitor-list-directory .input-group {
        border-radius: 6px;
        border: none;
    }


    #search-exhibitor-list-directory #search .form-control,
    #search-exhibitor-list-directory #search .form-select {
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #378dd7;

    }

    #search-exhibitor-list-directory #search .form-select {
        background-image: url('<?=ROOTPATHDOMAIN?>assets/images/icon-chevron-down-blue.svg');
    }

    #search-exhibitor-list-directory #search .btn-search {
        background-color: #378dd7 !important;
        border-color: #378dd7 !important;
    }

    #search-exhibitor-list-directory #search .btn-search .bi {
        font-size: 20px;
    }

    #search-exhibitor-list-directory #search .btn-search .bi:before {
        font-weight: bold !important;
    }

    @media (max-width: 767px) {
        #search-exhibitor-list-directory #search .form-select {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .input-group:not(.has-validation)>.dropdown-toggle:nth-last-child(n+3),
        .input-group:not(.has-validation)>:not(:last-child):not(.dropdown-toggle):not(.dropdown-menu) {
            border-radius: 5px;
        }

        .input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
            border-radius: 5px;
        }
    }


    #search-exhibitor-list-directory .description-2 {
        font-size: 14px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        letter-spacing: normal;
        text-align: center;
        color: #fff;
    }

    @media (max-width: 575px) {
        .search-area {
            margin-top: -105px !important;
        }
    }


    .search-area .form-select {
        border: none !important;
        background: none !important;
        padding: 0;
        margin-right: 0.5rem;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
    }

    .search-area .dropdown-toggle {
        padding-top: 4px !important;
        padding-bottom: 2px !important;
        height: auto;
        border-radius: 7px;
        background-color: #fba91e !important;
        border: none !important;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
        /* padding: 0.3rem 1rem 0.3rem 1rem; */
        padding-left: 11px !important;
        padding-right: 11px !important;
        outline: none !important;

    }

    .bootstrap-select .dropdown-menu li a span.text {
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;

    }

    .bootstrap-select .dropdown-toggle:focus,
    .bootstrap-select>select.mobile-device:focus+.dropdown-toggle {
        outline: none !important;
        outline-offset: inherit;
    }

    .search-area .bg-green {
        height: 28px;
        border-radius: 7px;
        background-color: none !important;
        border: none !important;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
        padding: 0.1rem 1rem 0.3rem 1rem;
        background-color: #659a83 !important;
    }

    #company-list .nav-link.active {
        background-color: #378dd7;
        color: #fff;
    }

    #company-list .nav-tabs .nav-item {
        z-index: 2;
    }

    #myTabContent {
        border-top-right-radius: 0.75rem;
        box-shadow: 0 2px 15px 0 rgba(0, 0, 0, 0.5);
        background-color: #fff;
        z-index: 1;
        margin-top: 0px;
        position: relative;
        border-end-end-radius: 0.75em;
        border-end-start-radius: 0.75em;
    }

    #myTabContent .tab-title {
        background-color: #378dd7;
    }

    #myTabContent .tab-title .form-check-label {
        font-size: 16px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
        margin-left: 1.5rem;
    }

    #myTabContent .tab-title .total-1 {
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
    }

    #myTabContent .tab-title .total-right {
        font-size: 16px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
    }

    #myTabContent .tab-title .total-right input {
        border-radius: 6px;
        border: solid 1px #378dd7 !important;
        background-color: #fff;
        width: 50px;
        text-align: center;
        outline: none;
    }

    #myTabContent .tab-title .total-right .bi {
        font-size: 14px;
        line-height: 1;
    }

    #myTabContent .tab-title .total-right .bi.disable {
        opacity: 0.14;
    }

    #myTabContent .tab-title .total-right .bi:before {
        font-weight: bold;
    }

    .checkbox-list-company .list-group-item {
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #000;
    }

    .checkbox-list-company .list-group-item .title {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #000;
    }

    .checkbox-list-company .list-group-item .form-check-input {
        border: solid 1px #378dd7;
    }

    #titleCheckDefault {
        width: 0.8em;
        height: 0.8em;
        border: solid 1px #378dd7;
    }



    .btn-all-category {
        font-size: 20px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff !important;
        border-radius: 14px;
        border: none !important;
    }
    </style>


    <style media="screen">
    .combined-shape {
        height: auto;
    }

    #fair-calendar-title {
        border-radius: 15px;
        background-blend-mode: multiply;
        border-radius: 0.75rem;
        box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.28);
        background-blend-mode: multiply, normal;
        background: url('<?=ROOTPATHDOMAIN?>assets/images/bg-search-exhibit-export.png') no-repeat center center;
        background-size: cover;
        padding-top: 100px;
        padding-bottom: 100px;
        margin: auto;
    }


    @media (max-width: 767px) {

        #fair-calendar-title {
            width: 100%;
        }
    }

    #fair-calendar-title .title {
        font-size: 60px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.01;
        letter-spacing: normal;
        text-align: center;
        color: #fff;
    }

    @media (max-width: 767px) {

        #fair-calendar-title.title {
            font-size: 50px;
        }
    }

    .search-area .form-select {
        border: none !important;
        background: none !important;
        padding: 0;
        margin-right: 0.5rem;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
    }

    .search-area .dropdown-toggle {
        padding-top: 4px !important;
        padding-bottom: 2px !important;
        height: auto;
        border-radius: 7px;
        background-color: #fba91e !important;
        border: none !important;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
        /* padding: 0.3rem 1rem 0.3rem 1rem; */
        padding-left: 11px !important;
        padding-right: 11px !important;
        outline: none !important;

    }

    .bootstrap-select .dropdown-menu li a span.text {
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;

    }

    .bootstrap-select .dropdown-toggle:focus,
    .bootstrap-select>select.mobile-device:focus+.dropdown-toggle {
        outline: none !important;
        outline-offset: inherit;
    }

    .search-area .form-select.bg-green .dropdown-toggle {
        background-color: #659a83 !important;
    }


    .fair-calendar-list {
        border-radius: 30px;
        box-shadow: 0 0 36px 0 rgba(0, 0, 0, 0.25);
        background-color: #fff;
        min-height: 250px;
    }

    .fair-calendar-list .bg-img {
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        border-top-left-radius: 30px;
        border-bottom-left-radius: 30px;
    }

    @media (max-width: 576px) {
        .fair-calendar-list .bg-img {
            min-height: 260px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
        }
    }


    .fair-calendar-list .title {
        font-size: 22px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #111;
        text-decoration: underline;
    }

    .fair-calendar-list .p {
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #111;
    }

    .fair-calendar-list .p b {
        font-weight: 600;
    }

    .fair-calendar-list .p .icon-p {
        width: 17px;
        margin-right: 0.5rem;
    }

    .fair-calendar-list .btn-bg-blue {
        border-radius: 26px;
        box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.4);
        background-image: linear-gradient(to left, #68c6f8, #004cb2) !important;
        border: none !important;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #fff;
    }

    .fair-calendar-list .btn-bg-blue .icon {
        width: 14px;
        margin-right: 0.5rem;
    }
    </style>


<input type="hidden" id="wsearch" value="<?=$kw?>">
<input type="hidden" id="wsearchtype" value="0">


<link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/js/jquery-ui.min.css" />
<script type="text/javascript" src="<?=ROOTPATHDOMAIN?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript">
function getWtxt() {
  $('#wsearch').val($('#wordsearchexhi').val());
}

function changePageExKey(val) {
  if(val!="") {
    val = parseInt(val);
    if(val>0) {
      changePageEx(val);
    }
  }
}

function changePageEx(page) {
  var cat = $('#excat_select').val();
  var keyw = $('#wsearch').val();
  var keywtype = $('#wsearchtype').val();
  var typesearch = $('#typesearch').val();
  var gid = $('#typesearchgid').val();
  var y = $('#typesearchyear').val();
  $.ajax({
      type: "GET",
      url: "<?=ROOTPATHDOMAIN?>ajax-chagepage-exhibitor-directory.php?cat="+encodeURIComponent(cat)+"&page="+page+'&keyw='+encodeURIComponent(keyw)+'&keywtype='+keywtype+'&typesearch='+typesearch+'&gid='+gid+'&y='+y,
      dataType: "text",
      success : function(data) {
        $('._exdatalist').empty();
        $("._exdatalist").html(data);
      }
  });
}

$(document).ready(function() {
  $('#wordsearchexhi').autocomplete({
    source: function( request, response ) {
      var typesearch = $('#typesearch').val();
      var gid = $('#typesearchgid').val();
      var y = $('#typesearchyear').val();

      $.ajax({
        url : '<?=ROOTPATHDOMAIN?>ajax-keyword-exhibitor-index.php?typesearch='+typesearch+'&gid='+gid+'&y='+y,
        dataType: "json",
        data: {
           name_startsWith: request.term,
           type: 'country_table',
           row_num : 1
        },
         success: function( data ) {
           response( $.map( data, function( item ) {
            var code = item.split("|");
            return {
              label: code[0]+' - '+code[1],
              value: '',
              data : item
            }
          }));
        }
      });
    },
    autoFocus: true,
    minLength: 1,
    appendTo: '#search',
    select: function( event, ui ) {
      var names = ui.item.data.split("|");
      if(names[3]==1) {
        window.location='<?=ROOTPATHDOMAIN?>exhibitor-profile/'+names[2]+'/'+encodeURIComponent(names[4])+'/';
      } else {
        setTimeout(function () { $('#wordsearchexhi').val(names[2]); getWtxt(); changePageEx(1); },100);
      }
    }
  }).data("ui-autocomplete")._renderItem = function( ul, item ) {
      let txt = String(item.value).replace(new RegExp(this.term, "gi"),"<b class='searchmatchtxt'>$&</b>");
      return $("<li></li>")
          .data("ui-autocomplete-item", item)
          .append("<a>" + txt + "</a>")
          .appendTo(ul);
  };

} );


function getCheckItem(div,id) {
  if($('#' + id).is(":checked")) {
    $("."+div).prop("checked", true);
  } else {
    $("."+div).prop("checked", false);
  }
}

</script>

</body>


</html>
