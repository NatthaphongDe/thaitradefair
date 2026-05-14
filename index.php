<?
include_once ("backoffice/connect.php");
$yearnow = /* date("Y"); */'2026';
$index = 1;
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

//$_SESSION["zxc"] = 1;
?>
<!doctype html>
<html lang="en">

<head>
    <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?></title>
    <meta charset="UTF-8">
    <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?>">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick-theme.css">

    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/css/index-css.css">
</head>

<body class="d-flex flex-column h-100">

    <div id="vue-app" class="bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid p0lr">
                <div class="container combined-shape ">
                    <?php include('components/header_menu.php'); ?>

                    <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner _hometopblogitem" id="fair-calendar">
                        <div class="col-12 col-lg-3 mb-4 mb-lg-0 d-none d-xl-block">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-lg-12 _home-calendar-logo-padding">
                                    <div class="card _homelogoblog">
                                        <?
                                        $earlyFair = getEarlyFair($yearnow);
                                        $runno = 0;
                                        $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start ASC ";
                                        $stmtfair = $mysqli->prepare($sqlfair);
                                        $stmtfair->bind_param('i',$yearnow);
                                        $stmtfair->execute();
                                        $resultfair = $stmtfair->get_result();
                                        $numrowfair = $resultfair->num_rows;
                                        if($numrowfair>0) {
                                          while($datafair = $resultfair->fetch_assoc()) {
                                            $imgfair = "";
                                            $croppath = str_replace('/main','/crop',$datafair["fair_group_image_path"]);
                                            $imgfair = ROOTPATHDOMAIN.$croppath;


                                            $thisactive = "";
                                            if($earlyFair<=0) {
                                              if($runno!=0) {
                                                $thisactive = "homelogotopdis";
                                              }
                                            } else {
                                              if($datafair["fair_id"]!=$earlyFair) {
                                                $thisactive = "homelogotopdis";
                                              }
                                            }

                                        ?>
                                        <img class="bd-placeholder-img card-img-top _logogtopall _logogtop_<?=$runno?> <?=$thisactive?> "
                                            src="<?=$imgfair?>" alt="<?=$datafair["fair_group_name_th"]?>" height="140" />
                                        <? $runno++; } } ?>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-12 pt-0 mt-3 mt-sm-0 pt-0 pt-lg-3 _home-calendar-padding">
                                    <div class="h-100 _home-calendar-bg">

                                      <div class="homeCalendarSlick">

                                        <div id="homeCalendarSlickBlog" class="carousel slide m-0">
                                              <?
                                              $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start ASC ";
                                              $stmtfair = $mysqli->prepare($sqlfair);
                                              $stmtfair->bind_param('i',$yearnow);
                                              $stmtfair->execute();
                                              $resultfair = $stmtfair->get_result();
                                              $numrowfair = $resultfair->num_rows;
                                              if($numrowfair>0) {
                                                $runno = 0;
                                                while($datafair = $resultfair->fetch_assoc()) {

                                                  $thisactive = "";
                                                  if($earlyFair<=0) {
                                                    if($runno==0) {
                                                      $thisactive = "active";
                                                    }
                                                  } else {
                                                    if($datafair["fair_id"]==$earlyFair) {
                                                      $thisactive = "active";
                                                    }
                                                  }

                                                ?>
                                                  <div class="carousel-item <?=$thisactive?>">
                                                    <div class="row">
                                                      <div class="col-12 text-center">
                                                        <div class="homeCalendarSlickBlogTitle">EVENT DATE</div>
                                                        <?php if (date("M",strtotime($datafair["fair_event_start"])) != date("M",strtotime($datafair["fair_event_end"]))) {?>
                                                          <div class="homeCalendarSlickBlogDate" style="font-size: 32px; padding-top: 32px;">
                                                            <?=date("d",strtotime($datafair["fair_event_start"]))?> <?=mb_strtoupper(date("M",strtotime($datafair["fair_event_start"])))?>  - <?=date("d",strtotime($datafair["fair_event_end"]))?> <?=mb_strtoupper(date("M",strtotime($datafair["fair_event_end"])))?>&nbsp;&nbsp;
                                                            
                                                          </div>
                                                          <div class="homeCalendarSlickBlogDateMonth" style="font-size: 32px; padding-top: 30px;">
                                                            <?=date("Y",strtotime($datafair["fair_event_start"]))?>
                                                          </div>
                                                        <?}else{?>
                                                          <div class="homeCalendarSlickBlogDate">
                                                            <?=date("d",strtotime($datafair["fair_event_start"]))?> - <?=date("d",strtotime($datafair["fair_event_end"]))?>
                                                          </div>
                                                          <div class="homeCalendarSlickBlogDateMonth">
                                                            <?=mb_strtoupper(date("M",strtotime($datafair["fair_event_start"])))?>
                                                            &nbsp;&nbsp;
                                                            <?=date("Y",strtotime($datafair["fair_event_start"]))?>
                                                          </div>
                                                        <?}?>
                                                      </div>
                                                    </div>
                                                  </div>
                                              <? $runno++; } } ?>


                                            </div>

                                      </div>



                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-xl-9 _fairbannerSlide" style="padding-left:0;">
                            <div id="indexCarousel" class="carousel slide m-0" data-bs-ride="carousel">
                                <div class="carousel-inner _banner_carousel-inner">
                                  <?
                                  $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start ASC ";
                                  $stmtfair = $mysqli->prepare($sqlfair);
                                  $stmtfair->bind_param('i',$yearnow);
                                  $stmtfair->execute();
                                  $resultfair = $stmtfair->get_result();
                                  $numrowfair = $resultfair->num_rows;
                                  if($numrowfair>0) {
                                    $runno = 0;
                                    while($datafair = $resultfair->fetch_assoc()) {
                                      $imgfair = "";
                                      if($datafair["fair_path_banner"]!="") {
                                        $croppath = str_replace('/banner','/banner/crop',$datafair["fair_path_banner"]);
                                        $imgfair = ROOTPATHDOMAIN.$croppath;
                                      } else {
                                        $croppath = str_replace('/main','/crop',$datafair["fair_group_image_path"]);
                                        $imgfair = ROOTPATHDOMAIN.$croppath;
                                      }

                                      $thisactive = "";
                                      if($earlyFair<=0) {
                                        if($runno==0) {
                                          $thisactive = "active";
                                        }
                                      } else {
                                        if($datafair["fair_id"]==$earlyFair) {
                                          $thisactive = "active";
                                        }
                                      }

                                      ?>
                                      <div class="carousel-item <?=$thisactive?>">
                                          <a href="<?=ROOTPATHDOMAIN?>fair/<?=$datafair["fair_id"]?>/<?=urlencode($datafair["fair_name"])?>/"><img src="<?=$imgfair?>" alt="<?=$datafair["fair_name"]?>" /></a>
                                      </div>

                                      <!-- <div class="carousel-item <?=$thisactive?>">
                                        <div class="_homebannerfairblog" onclick="window.location='<?=ROOTPATHDOMAIN?>fair/<?=$datafair["fair_id"]?>/<?=urlencode($datafair["fair_name"])?>/';" style="background-image: url('<?=$imgfair?>');">
                                        </div>
                                      </div> -->
                                      <?
                                      $runno++;
                                    }
                                  }
                                  ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#indexCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon">
                                        <i class="bi bi-chevron-left text-dark"></i>
                                    </span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#indexCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon">
                                        <i class="bi bi-chevron-right text-dark"></i>
                                    </span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mt-4 mt-md-2">
                    <div class="row px-4 mt-4">
                        <div class="col text-end _fr0">
                            <h2 class="fair-calendar-title py-0 px-2 m-0">FAIR CALENDAR</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <ul class="nav justify-content-center _homecalendarblogitem _hcb" id="month-list">
                              <? $array_m = array("JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"); ?>

                                <?
                                $mt = 1;
                                for($mm=0;$mm<count($array_m);$mm++) { ?>
                                <li class="nav-item">
                                    <a class="nav-link position-relative <? if(getFairListMonth($yearnow,$mt)>0) { ?> active <? } ?>" <? if(getFairListMonth($yearnow,$mt)>0) { ?> href="<?=ROOTPATHDOMAIN?>fair-calendar/?year=<?=$yearnow?>&month=<?=$mt?>" <? } ?>>
                                        <?=$array_m[$mm]?>
                                        <? if(getFairListMonth($yearnow,$mt)>0) { ?>
                                        <span
                                            class="position-absolute top-10 end-10 translate-middle badge rounded-pill bg-danger badgeCalerdarHome">
                                            <?=getFairListMonth($yearnow,$mt)?>
                                        </span>
                                        <? } ?>
                                    </a>
                                </li>
                                <? $mt++; } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <div class="container-fluid">
                <div class="container">
                    <div class="row mt-2 mt-md-4">
                        <div class="col  rounded-4 overflow-hidden px-3 px-sm-0">
                            <img src="<?=ROOTPATHDOMAIN?>assets/images/ex-home-banner-1.png" class="w-100" alt="icon" />
                        </div>
                    </div>
                    <div id="nav-business-type" class="row row-cols-3 row-cols-sm-3 row-cols-lg-6 mt-4">
                      <?
                      $sqlwhy = "select * from tt_why_content where why_status = '1' order by why_id ASC ";
                      $stmtwhy = $mysqli->prepare($sqlwhy);
                      $stmtwhy->execute();
                      $resultwhy = $stmtwhy->get_result();
                      $numrowwhy = $resultwhy->num_rows;
                      if($numrowwhy>0) {
                        while($datawhy = $resultwhy->fetch_assoc()) {
                          $imgwhy_a = ROOTPATHDOMAIN.$datawhy["why_logo_act_path"];
                          $imgwhy_n = ROOTPATHDOMAIN.$datawhy["why_logo_null_path"];
                      ?>
                      <div class="col-lg-3 col px-3 nav-item mb-4 mb-lg-4" onmouseover="whyHover('_why_img_<?=$datawhy["why_id"]?>','<?=$imgwhy_a?>');" onmouseout="whyHover('_why_img_<?=$datawhy["why_id"]?>','<?=$imgwhy_n?>');">
                          <a class="nav-link text-center" aria-current="page"
                              href="<?=ROOTPATHDOMAIN?>what-industry/<?=$datawhy["why_id"]?>/<?=urlencode($datawhy["why_title"])?>/" >
                              <img src="<?=$imgwhy_n?>" alt="<?=$datawhy["why_title"]?>" class="mb-2" width="70" id="_why_img_<?=$datawhy["why_id"]?>">
                              <span class="d-block"><?=$datawhy["why_title"]?></span>
                          </a>
                      </div>
                      <? } } ?>

                    </div>
                </div>
            </div>
            <div class="container-fluid px-md-0 bg-home-2">
                <div class="container px-4">

                    <form method="get" action="<?=ROOTPATHDOMAIN?>exhibitor-list-directory/">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div id="meet-connect-exhibitors" class="row mt-4">
                        <div class="col align-self-center p-4">
                            <span class="title d-block">Meet & Connect with Exhibitors</span>
                            <span class="description d-block">From over 1,000+ exhibitors from Thailand</span>


                            <div id="search" class="input-group my-4 prfrom">
                                <!-- <select type="text" class="form-select type-exhibitors">
                                    <option>
                                        Search All
                                    </option>
                                </select> -->

                                <div class="dropdown form-select type-exhibitors dropdown-exl" onclick="toogleMenuClose('_exltypeyear');toogleMenu('_exltype');">
                                  <button class="btn-exl _exlt" type="button">Search All</button>
                                  <input type="hidden" name="typesearch" id="typesearch" value="0">
                                  <input type="hidden" name="typesearchgid" id="typesearchgid" value="0">
                                  <input type="hidden" name="typesearchyear" id="typesearchyear" value="0">

                                  <ul class="dropdown-menu exl-blog _exltype">
                                    <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(0,'Search All','0');">Search All</a></li>
                                    <li>
                                      <a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(0,'Search All','0');">
                                        by Exhibition Title <span><img class="dropdown-arrow" src="<?=ROOTPATHDOMAIN?>assets/images/arrow-right.png" alt="icon"></span>
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
                                        <? } } ?>
                                      </ul>
                                      </li>
                                      <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(2,'by Company','0');">by Company</a></li>
                                      <li><a class="dropdown-item dropdown-item-txt" onclick="changeTypeSearch(3,'by Product','0');">by Product</a></li>

                                  </ul>
                                </div>

                                <div class="dropdown form-select type-exhibitors dropdown-exl" onclick="toogleMenuClose('_exltype');toogleMenu('_exltypeyear');">
                                  <button class="btn-exl _exly" type="button">Year</button>
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
                                    <? } } ?>
                                  </ul>
                                </div>

                                <!-- <select type="text" class="form-select year-exhibitors">
                                    <option>
                                        Year
                                    </option>
                                </select> -->
                                <input type="text" id="wordsearchexhi" name="kw" class="form-control w-50"
                                    placeholder="Company Name, Product Group, Product Name, Brand Name, Business Register No., Vat No. or Booth No.">

                                <button class="btn btn-search rounded-end text-center pt-2" type="submit">
                                    <i class="bi bi-search text-white "></i>
                                </button>

                            </div>


                            <span class="description-2 d-block">Examples: furniture, diamond, handicraft</span>
                        </div>

                    </div>
                    </form>


                    <div id="fair-highlight" class="row mt-4 p-0">
                        <div class="col p-0">
                            <div id="fairHighlightCarousel" class="carousel slide m-0 px-4 pb-4 pb-md-0"
                                data-bs-ride="false">
                                <div class="carousel-inner pb-4">

                                  <?
                                  $datetoyear =  '2024';//date("Y");
                                  $sqlhy = "select fair_year from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = 1 and b.fair_flag = 1 and c.fair_group_status = 1 group by fair_year order by fair_year DESC ";
                                  $stmthy= $mysqli->prepare($sqlhy);
                                  $stmthy->execute();
                                  $resulthy = $stmthy->get_result();
                                  $numrowhy = $resulthy->num_rows;
                                  if($numrowhy>0) {
                                    while($datahy = $resulthy->fetch_assoc()) {

                                      $sqlnews = "select * from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and c.fair_year = ?  and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 order by RAND() limit 10 ";
                                      $stmtnews= $mysqli->prepare($sqlnews);
                                      $stmtnews->bind_param('i',$datahy["fair_year"]);
                                      $stmtnews->execute();
                                      $resultnews = $stmtnews->get_result();
                                      $numrownews = $resultnews->num_rows;
                                      if($numrownews>0) {
                                  ?>
                                    <div class="carousel-item <? if($datahy["fair_year"]==$datetoyear) { echo "active"; } ?> ">
                                        <div class="row">
                                            <div class="col-12 col-lg-6 col-xl-5">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h2 class="title m-0 mt-4 p-0">FAIR<br />HIGHLIGHTS</h2>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="carousel-control float-start px-3 py-2"
                                                            data-bs-target="#fairHighlightCarousel"
                                                            data-bs-slide="prev">
                                                            <i class="bi bi-chevron-left text-dark"></i>
                                                        </div>
                                                        <span class="year-select float-start pt-2"><?=$datahy["fair_year"]?></span>
                                                        <div class="carousel-control float-start px-3 py-2"
                                                            data-bs-target="#fairHighlightCarousel"
                                                            data-bs-slide="next">
                                                            <i class="bi bi-chevron-right text-dark"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <span class="All-Fair-Highlight-D d-block text-center">All Fair
                                                            Highlight<br />DITP’s Trade Fair in
                                                        </span>
                                                        <span class="year-text d-block text-center">
                                                            <?=$datahy["fair_year"]?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-12 col-lg-6 col-xl-7 gallery-high-light md-px-4 pt-3 pt-md-0">
                                                <div id="fairCarousel-2022" class="_fairCarousel-inner card border-0 p-0 mx-auto"
                                                    style=" box-shadow: none">
                                                  <?
                                                    while($datanews = $resultnews->fetch_assoc()) {
                                                      $fgimg = "";
                                                      $croppath = str_replace('logo/','logo/crop/',$datanews["fair_group_logo_path"]);
                                                      $fgimg = ROOTPATHDOMAIN.$croppath;

                                                      $newsimg = "";
                                                      $croppath = str_replace('cover/','cover/cropsq/',$datanews["gall_file_path"]);
                                                      $newsimg = ROOTPATHDOMAIN.$croppath;
                                                    ?>
                                                    <div class="finneritem" style="background: url('<?=$newsimg?>');" onclick="window.location='<?=ROOTPATHDOMAIN?>all-gallery-group/<?=$datanews["fair_group_id"]?>/<?=urlencode($datanews["fair_group_name_th"])?>/';">
                                                        <!-- <a href="<?=ROOTPATHDOMAIN?>all-gallery-group/<?=$datanews["fair_group_id"]?>/<?=urlencode($datanews["fair_group_name_th"])?>/">
                                                          <img src="<?=$newsimg?>" class="w-100" alt="<?=$datanews["fair_group_name_th"]?>" />
                                                        </a> -->
                                                        <div class="event-areax px-2 pb-2 w-100"></div>
                                                        <div class="event-area px-2 pb-2 w-100">
                                                            <img class="img-gallery" style="width:40px !important; float:left;"
                                                                src="<?=$fgimg?>" alt="<?=$datanews["fair_group_name_th"]?>" />
                                                            <span class="event-name d-block pt-2 "
                                                                style="padding-left:20px; float:left;"><?=$datanews["fair_group_name_th"]?></span>
                                                        </div>
                                                    </div>
                                                  <? } ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                  <? } ?>

                                  <? } } ?>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid p-0 bg-home-3">
                <div class="container px-4">
                    <div id="news" class="continer-content-list row mt-4 px-4">
                        <div class="col">
                            <div class="row mb-2 mb-md-4">
                                <div class="col-6 col-lg-10">
                                    <h2 class="title p-0 m-0">NEWS</h2>
                                </div>
                                <div class="col-6 col-lg-2 view-more pt-0 pt-md-4 text-end">
                                    <a href="<?=ROOTPATHDOMAIN?>all-news/">
                                        <i class="bi bi-arrow-right-short"></i>
                                        <span>View More</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">

                              <?
                              $sqlnews = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 order by a.fca_create_date DESC limit 3 ";
                              $stmtnews= $mysqli->prepare($sqlnews);
                              $stmtnews->execute();
                              $resultnews = $stmtnews->get_result();
                              $numrownews = $resultnews->num_rows;
                              if($numrownews>0) {
                                while($datanews = $resultnews->fetch_assoc()) {

                                  $fgimg = "";
                                  $croppath = str_replace('logo/','logo/crop/',$datanews["fair_group_logo_path"]);
                                  $fgimg = ROOTPATHDOMAIN.$croppath;

                                  $newsimg = "";
                                  //$newsimg = ROOTPATHDOMAIN.$datanews["news_banner_path"];

                                  if($datanews["fca_banner_path"]!="") {
                                    $newsimgx = "";
                                    $newsimg = "";
                                    $croppath = str_replace('banner/','banner/resize/',$datanews["fca_banner_path"]);
                                    $newsimg = ROOTPATHDOMAIN.$croppath;
                                    $newsimgx = ROOTPATH.$croppath;
                                    if(!file_exists($newsimgx)) {
                                      $newsimg = ROOTPATHDOMAIN.$datanews["fca_banner_path"];
                                    }
                                  } else {

                                    if($datanews["fair_path_banner"]!="") {
                                      $croppath = str_replace('/banner','/banner/crop',$datanews["fair_path_banner"]);
                                      $newsimg = ROOTPATHDOMAIN.$croppath;
                                    } else {
                                      $croppath = str_replace('/main','/crop',$datanews["fair_group_image_path"]);
                                      $newsimg = ROOTPATHDOMAIN.$croppath;
                                    }
                                  }

                                  if($datanews["fca_title_en"]!="") {
                                    $newstitle = $datanews["fca_title_en"];
                                  } else {
                                    $newstitle = $datanews["fca_title_th"];
                                  }
                                  ?>

                                <div class="col px-1">
                                    <div class="card border-0 rounded-4 p-2 homenewsitem homenewsitem_bt">
                                        <div class="card-image">
                                            <a href="<?=ROOTPATHDOMAIN?>news-detail/<?=$datanews["fca_id"]?>/<?=urlencode($newstitle)?>/"><img src="<?=$newsimg?>" alt="<?=$newstitle?>" />
                                                <span class="label-txt-press-con px-2 py-0"><?=getTagMasterNews($datanews["fca_tag_master_id"])?></span></a>
                                        </div>
                                        <div class="card-body pb-0">
                                            <h5 class="card-title"><a href="<?=ROOTPATHDOMAIN?>news-detail/<?=$datanews["fca_id"]?>/<?=urlencode($newstitle)?>/"><?=$newstitle?></a></h5>
                                            <div class="event-area row row-cols-2">
                                                <div class="col-2 pt-1">
                                                    <img src="<?=$fgimg?>" alt="<?=$newstitle?>" style="border-radius:50%;" />
                                                </div>
                                                <div class="col-10 pt-2">
                                                    <span class="event-name d-block"><?=$datanews["fair_name"]?></span>
                                                    <span class="event-date d-block"><?=getDateContent($datanews["fca_create_date"])?></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                              <? } } ?>


                            </div>

                        </div>
                    </div>
                    <div id="gallery" class="continer-content-list row 4 px-4">
                        <div class="col">
                            <div class="row mb-2 mb-md-4">
                                <div class="col-6 col-lg-10">
                                    <h2 class="title p-0 m-0">GALLERY</h2>
                                </div>
                                <div class="col-6 col-lg-2 view-more pt-0 pt-md-4 text-end">
                                    <a href="<?=ROOTPATHDOMAIN?>all-gallery/">
                                        <i class="bi bi-arrow-right-short"></i>
                                        <span>View More</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row row-cols-1 row-cols-md-2 gallery-list">

                              <?
                              $sqlhy = "select e.* from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 GROUP by e.fair_group_id order by RAND() limit 4 ";
                              $stmthy= $mysqli->prepare($sqlhy);
                              $stmthy->execute();
                              $resulthy = $stmthy->get_result();
                              $numrowhy = $resulthy->num_rows;
                              if($numrowhy>0) {
                                while($datahy = $resulthy->fetch_assoc()) {
                                  $fgimg = "";
                                  $croppath = str_replace('logo/','logo/crop/',$datahy["fair_group_logo_path"]);
                                  $fgimg = ROOTPATHDOMAIN.$croppath;

                                  $sqlnews = "select * from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id left join tt_fair_category f on b.fcat_id=f.fcat_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and e.fair_group_id = ?  and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 group by a.fcg_id order by RAND() limit 1 ";
                                  $stmtnews= $mysqli->prepare($sqlnews);
                                  $stmtnews->bind_param('i',$datahy["fair_group_id"]);
                                  $stmtnews->execute();
                                  $resultnews = $stmtnews->get_result();
                                  $numrownews = $resultnews->num_rows;

                                  if($numrownews>0) {
                                    $datanews = $resultnews->fetch_assoc();
                                    $newsimg = "";
                                    $croppath = str_replace('cover/','cover/resize/',$datanews["gall_file_path"]);
                                    $newsimg = ROOTPATHDOMAIN.$croppath;
                              ?>

                                <div class="col px-1 pb-2" onclick="window.location.href='<?=ROOTPATHDOMAIN?>fair-content/<?=$datanews["fair_id"]?>/<?=urlencode($datanews["fair_name"])?>/<?=$datanews["fct_id"]?>/<?=urlencode($datanews["fcat_name"])?>/';">
                                    <div class="h-100 d-block rounded-4 p-2 bg-cover"
                                        style="background-image: url('<?=$newsimg?>') , linear-gradient(to bottom, #000 -24%, rgba(0, 0, 0, 0) 40%);">
                                        <div class="row row-cols-2">
                                            <div class="col-8 _xsg">
                                                <div class="row row-cols-2 event-area">
                                                    <div class="col-2 pt-1">
                                                        <img src="<?=$fgimg?>" alt="<?=$datahy["fair_group_name_th"]?>" />
                                                    </div>
                                                    <div class="col-10 pt-2">
                                                        <span class="event-name d-block txtshadow" ><?=$datahy["fair_group_name_th"]?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 pt-1 px-3 txtshadow _xsg">
                                                <span class="d-block lastest-album">Lastest Album</span>
                                                <a href="<?=ROOTPATHDOMAIN?>fair-content/<?=$datanews["fair_id"]?>/<?=urlencode($datanews["fair_name"])?>/<?=$datanews["fct_id"]?>/<?=urlencode($datanews["fcat_name"])?>/" class="d-block see-all-fair-gallery">
                                                    See all fair gallery
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              <? } } }  ?>




                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid p-0 bg-home-4">
                <div class="container px-4">
                    <div id="social-update" class="continer-content-list row mt-4 px-4">
                        <div class="col">
                            <div class="row mb-2 mb-md-4">
                                <div class="col">
                                    <h2 class="title p-0 m-0">SOCIAL UPDATES</h2>
                                </div>
                            </div>

                            <div id="socialupdateCarousel" class="slider">

                              <?
                              $sqlof = " select * from tt_fair_group where fair_group_status = '1' and fair_group_social != '' order by RAND()  ";
                              $stmtof= $mysqli->prepare($sqlof);
                              $stmtof->execute();
                              $resultof = $stmtof->get_result();
                              $numrowof = $resultof->num_rows;
                              if($numrowof>0) {
                                while($dataof= $resultof->fetch_assoc()) {
                                  $croppath = str_replace('logo/','logo/crop/',$dataof["fair_group_logo_path"]);

                                  $fgimg = "";
                                  $fgimg = ROOTPATHDOMAIN.$croppath;

                                  $fgbanner = "";
                                  if($dataof["fair_group_image_path"]!="") {
                                    $croppath = str_replace('/main','/crop',$dataof["fair_group_image_path"]);
                                    $fgbanner = ROOTPATHDOMAIN.$croppath;
                                  } else {
                                    $croppath = str_replace('logo/','logo/crop/',$dataof["fair_group_logo_path"]);
                                    $fgbanner = ROOTPATHDOMAIN.$dataof["fair_group_logo_path"];
                                  }

                                  $sqlofnews = " select * from tt_fair_content_article where fcat_id = 26 and fca_pubish = 1 and fca_status = 1  and fair_id in (select fair_id from tt_fair_group_list where fair_group_id = ? and fair_flag = 1) order by fca_update_date DESC limit 1  ";
                                  $stmtofnews = $mysqli->prepare($sqlofnews);
                                  $stmtofnews->bind_param('i',$dataof["fair_group_id"]);
                                  $stmtofnews->execute();
                                  $resultofnews = $stmtofnews->get_result();
                                  $numrowofnews = $resultofnews->num_rows;
                                  if($numrowofnews>0) {
                                    $dataofnews = $resultofnews->fetch_assoc();
                                    if($dataofnews["fca_banner_path"]!="") {
                                      $fgbannerx = "";
                                      $fgbanner = "";
                                      $croppath = str_replace('banner/','banner/resize/',$dataofnews["fca_banner_path"]);
                                      $fgbanner = ROOTPATHDOMAIN.$croppath;
                                      $fgbannerx = ROOTPATH.$croppath;
                                      if(!file_exists($fgbannerx)) {
                                        $fgbanner = ROOTPATHDOMAIN.$dataofnews["fca_banner_path"];
                                      }

                                    }

                                  }

                                  ?>
                                  <div class="col px-1 mb-3 mb-lg-0 _homeofcard" onclick="window.open('<?=$dataof["fair_group_social"]?>');" >
                                      <div class="card h-100 d-block bg-white rounded-4 p-2 px-3 pb-3">
                                          <div class="row event-area align-items-center">
                                              <div class="col-2 pt-1">
                                                  <img src="<?=$fgimg?>" alt="<?=$dataof["fair_group_name_th"]?>" style="border-radius:50%;" />
                                              </div>
                                              <div class="col-9 pt-1">
                                                  <span class="event-name d-block h-100 socialupdatelineheight" style="margin-left:0.5rem;">
                                                    <?=$dataof["fair_group_name_th"]?>
                                                  </span>
                                              </div>
                                              <div class="col-1 pt-1 px-0">
                                                  <i class="bi bi-chevron-right"></i>
                                              </div>
                                          </div>
                                          <div class="d-grid mt-2">
                                            <div class="_homeblogsocialimg" style="background: url('<?=$fgbanner?>');">
                                              <!-- <img src="<?=$fgbanner?>" alt="<?=$dataof["fair_group_name_th"]?>" height="100%" /> -->
                                            </div>

                                          </div>
                                      </div>
                                  </div>
                                  <?
                                }
                              }
                              ?>
                            </div>
                        </div>
                    </div>
                    <div id="official-website" class="continer-content-list row 4 px-4 mb-5">
                        <div class="col">
                            <div class="row mb-2 mb-md-4">
                                <div class="col">
                                    <h2 class="title p-0 m-0">OFFICIAL WEBSITES</h2>
                                </div>
                            </div>

                            <div id="officialWebsiteCarousel" class="slider">
                              <?
                              $sqlof = " select * from tt_fair_group where fair_group_status = '1' and fair_group_link != '' ";
                              $stmtof= $mysqli->prepare($sqlof);
                              //$stmtof->bind_param('i',$yearnow);
                              $stmtof->execute();
                              $resultof = $stmtof->get_result();
                              $numrowof = $resultof->num_rows;
                              if($numrowof>0) {
                                while($dataof= $resultof->fetch_assoc()) {
                                  $fgimg = "";
                                  $croppath = str_replace('/main','/crop',$dataof["fair_group_image_path"]);
                                  $fgimg = ROOTPATHDOMAIN.$croppath;
                                  ?>
                                  <div class="homeofbox">
                                      <a href="<?=$dataof["fair_group_link"]?>" target="
                                        "><img src="<?=$fgimg?>" alt="<?=$dataof["fair_group_name_th"]?>" /></a>
                                  </div>
                                  <?
                                }
                              }
                              ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </main>

        <?php include('components/footer.php') ?>
    </div>


    <script src="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick.js" ></script>
    <link rel="stylesheet" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick.css">
    <link rel="stylesheet" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick-theme.css">


    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" ></script>


    <script>
    $(document).ready(function() {
      $('#socialupdateCarousel').slick({
          slidesToShow: 4,
          slidesToScroll: 1,
          autoplay: false,
          autoplaySpeed: 2000,
          variableWidth: true,
          infinite: true
      });

        $('#officialWebsiteCarousel').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            variableWidth: true,
            infinite: true
        });

        $('._fairCarousel-inner').slick({
            slidesToShow: 1,
            autoplay: false,
            autoplaySpeed: 2000,
            variableWidth: true,
            infinite: true,
            dots: true,
            arrows: false
        });

        $('#indexCarousel').bind('slide.bs.carousel', function (e) {
          var slideFrom = $(this).find('.active').index();
          var slideTo = $(e.relatedTarget).index();

          setTimeout(function () {
            $('#homeCalendarSlickBlog').carousel(slideTo);
            $('._logogtopall').addClass('homelogotopdis');
            $('._logogtop_'+slideTo).removeClass('homelogotopdis');
          },50);

        });

        setTimeout(function(){
          var gw = $('#fairCarousel-2022').width();
          //console.log(gw);
        },1);


        /*
        $('#homeCalendarSlickBlog').bind('slide.bs.carousel', function (e) {
          var slideFrom = $(this).find('.active').index();
          var slideTo = $(e.relatedTarget).index();

          setTimeout(function () {
            $('#indexCarousel').carousel(slideTo);
            $('._logogtopall').addClass('homelogotopdis');
            $('._logogtop_'+slideTo).removeClass('homelogotopdis');
          },50);

        });
        */





    });

    function whyHover(id,src) {
      $("#"+id).attr("src",src);
    }

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




    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/js/jquery-ui.min.css" />
    <script  src="<?=ROOTPATHDOMAIN?>assets/js/jquery-ui.min.js"></script>
    <script >
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
            setTimeout(function () { $('#wordsearchexhi').val(names[2]); },100);
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


    function changeYearFairData(year) {

      $('._ffyear').removeClass('active');
      $('._ffyear_'+year).addClass('active');

      $.ajax({
          type: "GET",
          url: "<?=ROOTPATHDOMAIN?>ajax-homebanner.php?y="+year,
          dataType: "text",
          success : function(data) {
            $('._hometopblogitem').empty();
            $("._hometopblogitem").html(data);
          }
      });

      $.ajax({
          type: "GET",
          url: "<?=ROOTPATHDOMAIN?>ajax-homecalendar.php?y="+year,
          dataType: "text",
          success : function(data) {
            $('._homecalendarblogitem').empty();
            $("._homecalendarblogitem").html(data);
          }
      });



    }
    function log555() {comsole.log(55);}
    function nottoken() {
     comsole.log(55);
      swal({
        title: "Success",
        text: message,
        icon: "success", // เปลี่ยนจาก type เป็น icon ใน SweetAlert2
        buttons: {
            confirm: {
                text: "Close",
                value: true,
                visible: true,
                className: "btn btn-success",
                closeModal: true
            }
        }
        }).then((result) => {
        if (result) {
            // ฟังก์ชั่นที่ต้องการทำงานเมื่อคลิกปุ่ม Close
            console.log('Success message closed.');
        }
      });
    }
    </script>


</body>

</html>
