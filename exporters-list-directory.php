<?
include_once ("backoffice/connect.php");
$top_menu_active = "exporters-list-directory";

$csrf_token = $mysqli->real_escape_string($_GET["csrf_token"]);
?>
<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : Exporters List Directory</title>
  <meta charset="UTF-8">
  <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : Exporters List Directory">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">

    <link href="<?=ROOTPATHDOMAIN?>assets/dist/scrollbar/jquery.scrollbar.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
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



    #search-exporters-list-directory {
        border-radius: 15px;
        background-blend-mode: multiply;
        border-radius: 15px;
        box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.28);
        background-blend-mode: multiply, normal;
        background: url('<?=ROOTPATHDOMAIN?>assets/images/bg-search-exhibit-export.png') no-repeat center center;
        background-size: cover;
    }

    #search-exporters-list-directory .title {
        font-size: 40px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.01;
        letter-spacing: normal;
        text-align: center;
        color: #fff;
    }

    #search-exporters-list-directory .input-group {
        border-radius: 6px;
        border: none;
    }


    #search-exporters-list-directory #search .form-control,
    #search-exporters-list-directory #search .form-select {
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #378dd7;

    }

    #search-exporters-list-directory #search .form-select {
        background-image: url('<?=ROOTPATHDOMAIN?>assets/images/icon-chevron-down-blue.svg');
    }

    #search-exporters-list-directory #search .btn-search {
        background-color: #378dd7 !important;
        border-color: #378dd7 !important;
    }

    #search-exporters-list-directory #search .btn-search .bi {
        font-size: 20px;
    }

    @media (max-width: 767px) {
        #search-exporters-list-directory #search .form-select {
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

    #search-exporters-list-directory #search .btn-search .bi:before {
        font-weight: bold !important;
    }

    #search-exporters-list-directory .description-2 {
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        letter-spacing: normal;
        text-align: center;
        color: #fff;
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

    #company-list {
        position: relative;
    }

    #company-list .nav-tabs {
      height: 3rem;
    }

    #company-list .nav-tabs li {
      display: none;
    }

    #company-list .nav-link {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        border: none;
        font-size: 20px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: justify;
        background-color: #e3e2e2;
        color: #111;
    }

    #company-list .nav-link.active {
        background-color: #378dd7;
        color: #fff;
    }

    #company-list .nav-tabs .nav-item {
        z-index: 1;
        position: relative;
        margin-right: 0.5rem
    }

    #company-list .nav-tabs .nav-item.active {
        z-index: 3;
        position: relative;
    }

    #myTabContent {
        border-radius: 0.75rem;
        /* border-top-right-radius: 0.75rem; */
        box-shadow: 0 2px 15px 0 rgba(0, 0, 0, 0.5);
        background-color: #fff;
        z-index: 2;
        margin-top: 0px;
        position: relative;
        border-bottom-right-radius: 0.75rem;
        border-bottom-left-radius: 0.75rem;
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

    .checkbox-list-company .list-group-item:last-child {
        border-radius: 0 !important;
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

    .check-comp-all {
        padding-left: 2rem;
    }

    @media (max-width: 576px) {
        .check-comp-all {
            padding-left: 0rem;
        }
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

    
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>

                    <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner">
                        <div class="col">

                          <form onsubmit="changePageEx(1,1);changePageEx(1,2);changePageEx(1,3); return false;" >
                          <div id="search-exporters-list-directory" class="row _homebanner_bgheight">
                              <div class="col-12 align-self-center pt-5">
                                  <h1 class="title">Exporters List Directory </h1>
                              </div>
                              <div class="col-12 align-self-center _exspad">

                                  <div id="search" class="input-group mt-2 mb-4 rounded-2">


                                      <div class="dropdown form-select type-exhibitors dropdown-exl" onclick="toogleMenu('_exltype');">
                                        <button class="btn-exl2 _exlt" type="button">Search All</button>
                                        <ul class="dropdown-menu exl-blog _exltype">
                                          <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(0,'Search All','0');">Search All</a></li>
                                          <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(1,'by Company','0');">by Company</a></li>
                                          <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(2,'by Product','0');">by Product</a></li>
                                          <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(3,'by Categories','0');">by Categories</a></li>
                                        </ul>
                                      </div>


                                      <input type="text" id="wordsearchexhi" name="kw" class="form-control w-50"
                                          placeholder="Type Company, Brand, Category, Product or Keyword" value="" onkeyup="getWtxt();">

                                      <button class="btn btn-search rounded-end text-center pt-2" type="submit">
                                          <i class="bi bi-search text-white "></i>
                                      </button>

                                  </div>

                              </div>
                              <div class="col-12 align-self-center pb-5 ">
                                  <span class="description-2 d-block">Examples: furniture, diamond, handicraft</span>
                              </div>
                          </div>
                          <input type="text" name="csrf_token" id="csrf_token" value="<?=$_SESSION['csrf_token']?>" hidden>
                          </form>

                        </div>
                    </div>

                </div>
            </div>


        </header>

        <main>
            <div class="container-fluid">
                <div class="container">


                <form method="post" target="com_m" action="<?=ROOTPATHDOMAIN?>print-exportor-list-directory.php">

                    <div id="company-list" class="mt-5 mt-lg-3 mb-5 px-0 px-md-4">
                        <ul class="nav nav-tabs border-0">
                            <!-- <li class="nav-item active">
                                <a class="nav-link px-2 px-sm-3 active" id="nav-company-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-company" role="tab" aria-controls="nav-company"
                                    aria-selected="true">Company</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 px-sm-3" id="nav-products-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-products" role="tab" aria-controls="nav-products"
                                    aria-selected="true">Products</a>
                            </li>
                            <li class="nav-item ml-2">
                                <a class="nav-link px-2 px-sm-3" id="nav-categories-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-categories" role="tab" aria-controls="nav-categories"
                                    aria-selected="true">Categories </a>
                            </li> -->
                        </ul>

                        <div class="float-end w-auto search-area pt-2" style="margin-top: -55px;">
                            <button type="submit" class="btn w-auto float-end bg-green">
                                <img src="<?=ROOTPATHDOMAIN?>assets/images/anticon-local-printshop-material.png" style="height:15px;" />
                                Print
                            </button>
                            <select id="excat_select" class="form-select selectpicker w-auto float-end"
                                style="padding-right:2rem; margin-right:0.5rem;" onchange="changePageEx(1,1);changePageEx(1,2);changePageEx(1,3);">
                                <option value="" selected>All Categories</option>
                                <?
                                $sqlelc = " select Product_Cat_Name_EN from tt_exportor_product where Product_Cat_Name_EN != '' group by Product_Cat_Name_EN order by Product_Cat_Name_EN ASC  ";
                                $stmtelc = $mysqli->prepare($sqlelc);
                                $stmtelc->execute();
                                $resultelc = $stmtelc->get_result();
                                $numrowelc = $resultelc->num_rows;
                                if($numrowelc>0) {
                                  while($dataelc = $resultelc->fetch_assoc()) {

                                    $numcatitem = 0;
                                    $sqlelci = " select exp_id from tt_exportor_product where Product_Cat_Name_EN = ? group by exp_id ";
                                    $stmtelci = $mysqli->prepare($sqlelci);
                                    $stmtelci->bind_param('s',$dataelc["Product_Cat_Name_EN"]);
                                    $stmtelci->execute();
                                    $resultelci = $stmtelci->get_result();
                                    $numrowelci = $resultelci->num_rows;
                                    $numcatitem = $numrowelci;
                                ?>
                                <option value="<?=$dataelc["Product_Cat_Name_EN"]?>"><?=$dataelc["Product_Cat_Name_EN"]?> (<?=number_format($numcatitem)?>)</option>
                                <? } } ?>
                            </select>
                        </div>


                        <div class="tab-content px-0 " id="myTabContent">

                          <?
                          $showpage = 100;

                          $sqlel = " select * from tt_exportor_list where Corporate_Name_TH != ' ' and Corporate_Name_EN != ' ' and Corporate_Name_EN NOT REGEXP '[ก-๙]' group by User_ID order by Corporate_Name_EN ASC  ";
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


                            <div class="tab-pane fade show active pb-3 __expdata_1" id="nav-company" role="tabpanel"
                                aria-labelledby="home-tab" tabindex="0">
                                <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem; border-top-left-radius: 0.75rem;">
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
                                            1 - <?=$numrowelshow?> of <?=number_format($numrowel)?>
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
                                      <?
                                      $sqleld = " select * from tt_exportor_list where Corporate_Name_TH != ' ' group by User_ID order by Corporate_Name_EN ASC limit $start_page,100 ";
                                      $stmteld = $mysqli->prepare($sqleld);
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

                                      ?>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <div class="divchkex" >
                                              <input class="form-check-input mx-2 act_id_ss" name="chklist[]" type="checkbox" value="<?=$dataeld["exp_id"]?>">
                                            </div>

                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="<?=ROOTPATHDOMAIN?>exporters-profile/<?=$dataeld["exp_id"]?>/<?=urlencode($namecom)?>/">
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
                            </div>




                            <?
                            /*
                            $showpage = 100;

                            $sqlel = " select * from tt_exportor_list a left join tt_exportor_product b on a.exp_id=b.exp_id where b.Product_Name_EN != '' group by b.Product_Name_EN order by a.Corporate_Name_EN ASC, a.Corporate_Name_TH ASC, b.Product_Name_EN ASC  ";
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
                            */

                            $showpage = 100;

                            $sqlel = " select * from tt_exportor_list where exp_id in (select exp_id from tt_exportor_product where Product_Name_EN != '' group by exp_id) group by User_ID order by Corporate_Name_EN ASC  ";
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

                            <div class="tab-pane fade pb-3 __expdata_2" id="nav-products" role="tabpanel" aria-labelledby="home-tab"
                                tabindex="1">
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
                                            1 - <?=$numrowelshow?> of <?=number_format($numrowel)?>
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
                                      <?
                                      /*
                                      $sqleld = " select * from tt_exportor_list a left join tt_exportor_product b on a.exp_id=b.exp_id where b.Product_Name_EN != '' group by b.Product_Name_EN order by a.Corporate_Name_EN ASC, a.Corporate_Name_TH ASC, b.Product_Name_EN ASC limit $start_page,100 ";
                                      $stmteld = $mysqli->prepare($sqleld);
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
                                          */

                                          $sqleld = " select * from tt_exportor_list where exp_id in (select exp_id from tt_exportor_product where Product_Name_EN != '' group by exp_id) group by User_ID order by Corporate_Name_EN ASC limit $start_page,100 ";
                                          $stmteld = $mysqli->prepare($sqleld);
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

                                      ?>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <div class="divchkex" >
                                              <input class="form-check-input mx-2 act_id_ss-2" name="chklist2[]" type="checkbox" value="<?=$dataeld["exp_id"]?>">
                                            </div>

                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="<?=ROOTPATHDOMAIN?>exporters-profile/<?=$dataeld["exp_id"]?>/<?=urlencode($namecom)?>/">
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
                            </div>



                            <?
                            /*
                            $showpage = 100;

                            $sqlel = " select * from tt_exportor_list where exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN != '' group by Product_Cat_Name_EN, exp_id ) group by User_ID order by Corporate_Name_EN ASC  ";
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
                            */

                            $showpage = 100;

                            $sqlel = " select * from tt_exportor_list where exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN != '' or Product_Sub_Cat_Name_EN != '' group by exp_id) group by User_ID order by Corporate_Name_EN ASC  ";
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
                            <div class="tab-pane fade pb-3 __expdata_3" id="nav-categories" role="tabpanel"
                                aria-labelledby="home-tab" tabindex="3">
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
                                            1 - <?=$numrowelshow?> of <?=number_format($numrowel)?>
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
                                      <?
                                      /*
                                      $sqleld = " select * from tt_exportor_list where exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN != '' group by Product_Cat_Name_EN, exp_id ) group by User_ID order by Corporate_Name_EN ASC limit $start_page,100 ";
                                      $stmteld = $mysqli->prepare($sqleld);
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

                                          $sqleld = " select * from tt_exportor_list where exp_id in (select exp_id from tt_exportor_product where Product_Cat_Name_EN != '' or Product_Sub_Cat_Name_EN != '' group by exp_id) group by User_ID order by Corporate_Name_EN ASC limit $start_page,100 ";
                                          $stmteld = $mysqli->prepare($sqleld);
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

                                      ?>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                                            <div class="divchkex" >
                                              <input class="form-check-input mx-2 act_id_ss-3" name="chklist3[]" type="checkbox" value="<?=$dataeld["exp_id"]?>">
                                            </div>

                                            <div class="ms-2 me-auto">
                                                <div class="title fw-bold">
                                                    <a href="<?=ROOTPATHDOMAIN?>exporters-profile/<?=$dataeld["exp_id"]?>/<?=urlencode($namecom)?>/">
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
                            </div>



                        </div>

                    </div>
                    <input type="text" name="csrf_token" value="<?=$_SESSION['csrf_token']?>" hidden>
                  </form>
                </div>
            </div>
        </main>

        <?php include('components/footer.php') ?>
    </div>

    <input type="hidden" id="wsearch" value="">
    <input type="hidden" id="wsearchtype" value="0">

    <iframe id="com_m" name="com_m" class="ifsave" width="0" height="0" frameborder="0" scrolling="no"></iframe>

    <script src="<?=ROOTPATHDOMAIN?>assets/dist/scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/js/jquery-ui.min.css" />
    <script type="text/javascript" src="<?=ROOTPATHDOMAIN?>assets/js/jquery-ui.min.js"></script>

    <script>
    $(document).ready(function() {
      var queryString = window.location.search;
      var urlParams = new URLSearchParams(queryString);
      var chkcate = [urlParams.get('category')];
      if (chkcate[0] != null) {
        var sText = chkcate[0];
        console.log(chkcate);
        var typesearch = $('#wordsearchexhi').val(sText);
        getWtxt();
        changePageEx(1,1);changePageEx(1,2);changePageEx(1,3);
        $('#nav-company-tab').removeClass('active');
        $('#nav-categories-tab').addClass('active');
        $(".__expdata_1").removeClass('active');
        $(".__expdata_1").removeClass('show');
        $(".__expdata_3").addClass('active');
        $(".__expdata_3").addClass('show');
      }
      
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
      $('#wsearchtype').val(val);
      $('._exlt').html(txt);
    }


    </script>

<script type="text/javascript">
function getWtxt() {
  $('#wsearch').val($('#wordsearchexhi').val());
}

function changePageExKey(val,pagetype) {
  if(val!="") {
    val = parseInt(val);
    if(val>0) {
      changePageEx(val,pagetype);
    }
  }
}

function changePageEx(page,pagetype) {
  var cat = $('#excat_select').val();
  var keyw = $('#wsearch').val();
  var keywtype = $('#wsearchtype').val();
  var csrf_token = $('#csrf_token').val();
  $.ajax({
      type: "GET",
      //url: "<?=ROOTPATHDOMAIN?>ajax-chagepage-exporters-directory.php?cat="+encodeURIComponent(cat)+"&page="+page+'&keyw='+encodeURIComponent(keyw)+'&keywtype='+keywtype+'&pagetype='+pagetype,
      url: "<?=ROOTPATHDOMAIN?>ajax-chagepage-exporters-directory.php",
      data: {
        cat: encodeURIComponent(cat), // ส่งค่า method
        page: page, // ส่งค่า id
        keyw: encodeURIComponent(keyw), // ส่งค่า keyword
        keywtype:keywtype,
        pagetype:pagetype,
        csrf_token: csrf_token
      },
      dataType: "text",
      success : function(data) {
        $('.__expdata_'+pagetype).empty();
        $(".__expdata_"+pagetype).html(data);
      }
  });
}

$(document).ready(function() {
  $('#wordsearchexhi').autocomplete({
    source: function( request, response ) {
      var typesearch = $('#wsearchtype').val();

      $.ajax({
        url : '<?=ROOTPATHDOMAIN?>ajax-keyword-exporters-index.php?typesearch='+typesearch,
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
        window.location='<?=ROOTPATHDOMAIN?>exporters-profile/'+names[2]+'/'+encodeURIComponent(names[4])+'/';
      } else {
        setTimeout(function () { $('#wordsearchexhi').val(names[2]); getWtxt(); changePageEx(1,1);changePageEx(1,2);changePageEx(1,3); },100);
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
  if($('.' + id).is(":checked")) {
    $("."+div).prop("checked", true);
  } else {
    $("."+div).prop("checked", false);
  }
}
function alertToken() {
        swal({
        title: "",
        text: "The token provided is invalid. Please try again",
        type: "error",
        showCancelButton: false,
        confirmButtonColor: "#5cb85c",
        confirmButtonText: "close",
        closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) {
                window.location="/";
            }
        });
    }
</script>


</body>


</html>
