<?
include_once ("backoffice/connect.php");
$top_menu_active = "about-fair";
$kw = $_POST["kw"];
$kw = htmlspecialchars($kw, ENT_QUOTES, 'UTF-8');
if (!empty($kw)) {
    if ($_SESSION['csrf_token'] != $_POST["csrf_token"]) { ?>
      <script type="text/javascript">
        setTimeout(function () {top.alertToken();},1000);
      </script>
    <?exit();}
}

?>

<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : News</title>
  <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : News">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/calendar.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/all-news.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>
                    <?php include('components/top_slide_cover.php'); ?>
                    <!-- <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner" id="fair-calendar">
                        <div class="col _homebannercol">
                            <div id="allNewsCarousel" class="carousel slide m-0" data-bs-ride="carousel">
                                <div class="carousel-inner allnewsbanner">

                                  <?
                                  $runimg = 0;
                                  $sqlnewsg = "select * from tt_fair_group where fair_group_status = 1 order by fair_group_name_th ASC ";
                                  $stmtnewsg= $mysqli->prepare($sqlnewsg);
                                  $stmtnewsg->execute();
                                  $resultnewsg = $stmtnewsg->get_result();
                                  $numrownewsg = $resultnewsg->num_rows;
                                  if($numrownewsg>0) {
                                    while($datanewsg = $resultnewsg->fetch_assoc()) {

                                      $fgimg = "";
                                      $croppath = str_replace('logo/','logo/crop/',$datanewsg["fair_group_logo_path"]);
                                      $fgimg = ROOTPATHDOMAIN.$croppath;


                                      $sqlnews = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 and d.fair_group_id = ? and a.fca_banner_path != '' order by a.fca_create_date DESC limit 1 ";
                                      $stmtnews= $mysqli->prepare($sqlnews);
                                      $stmtnews->bind_param('i',$datanewsg["fair_group_id"]);
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
                                          }

                                          if($datanews["fca_title_en"]!="") {
                                            $newstitle = $datanews["fca_title_en"];
                                          } else {
                                            $newstitle = $datanews["fca_title_th"];
                                          }
                                          ?>
                                          <div class="carousel-item newsbanner_bg <? if($runimg==0) { echo "active"; } ?> " style="background-image: url('<?=$newsimg?>');" onclick="window.location='<?=ROOTPATHDOMAIN?>news-detail/<?=$datanews["fca_id"]?>/<?=urlencode($newstitle)?>/';">
                                              /*<img class="bd-placeholder-img" src="<?=$newsimg?>" /> /*
                                          </div>
                                          <?
                                          $runimg++;
                                        }
                                      }
                                    }
                                  }
                                  ?>

                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#allNewsCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon">
                                        <i class="bi bi-chevron-left text-dark"></i>
                                    </span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#allNewsCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon">
                                        <i class="bi bi-chevron-right text-dark"></i>
                                    </span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </header>

        <br>
        <main>
            <div class="container-fluid">
                <div class="container ">
                    <div class="row mb-3 px-4 pt-md-0 _faircontentblog">
                        <div class="col-12 col-md-7">
                            <h2 class="title p-0 m-0">NEWS</h2>
                        </div>
                        <div class="col-12 col-md-5">
                          <form method="post" action="<?=ROOTPATHDOMAIN?>all-news/">
                            <div id="search" class="input-group mt-3">
                                <select type="text" class="form-select type-exhibitors w-20 _eenews" onchange="changeMore(this.value)">
                                    <option value="">All Event</option>
                                    <?
                                    $sqlnewsg = "select * from tt_fair_group where fair_group_status = 1 order by fair_group_name_th ASC ";
                                    $stmtnewsg= $mysqli->prepare($sqlnewsg);
                                    $stmtnewsg->execute();
                                    $resultnewsg = $stmtnewsg->get_result();
                                    $numrownewsg = $resultnewsg->num_rows;
                                    if($numrownewsg>0) {
                                      while($datanewsg = $resultnewsg->fetch_assoc()) {

                                        $fgimg = "";
                                        $croppath = str_replace('logo/','logo/crop/',$datanewsg["fair_group_logo_path"]);
                                        $fgimg = ROOTPATHDOMAIN.$croppath;


                                        $sqlnews = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 and d.fair_group_id = ? order by a.fca_create_date DESC limit 3 ";
                                        $stmtnews= $mysqli->prepare($sqlnews);
                                        $stmtnews->bind_param('i',$datanewsg["fair_group_id"]);
                                        $stmtnews->execute();
                                        $resultnews = $stmtnews->get_result();
                                        $numrownews = $resultnews->num_rows;
                                        if($numrownews>0) {
                                    ?>

                                    <option value="<?=$datanewsg["fair_group_id"]?>"><?=$datanewsg["fair_group_name_th"]?></option>
                                    <? } } } ?>
                                </select>
                                <input type="text" class="form-control w-25" placeholder="Search" name="kw" value="<?=$kw?>">
                                <input type="text" name="csrf_token" value="<?=$_SESSION['csrf_token']?>" id="csrf_token" hidden>
                                <button class="btn btn-search rounded-end text-center pt-1" type="submit">
                                    <i class="bi bi-search text-white "></i>
                                </button>

                            </div>
                          </form>
                        </div>
                    </div>

                    <div class="_shownewss">

                    <?
                    $sqlnewsg = "select * from tt_fair_group where fair_group_status = 1 order by fair_group_name_th ASC ";
                    $stmtnewsg= $mysqli->prepare($sqlnewsg);
                    $stmtnewsg->execute();
                    $resultnewsg = $stmtnewsg->get_result();
                    $numrownewsg = $resultnewsg->num_rows;
                    if($numrownewsg>0) {
                      while($datanewsg = $resultnewsg->fetch_assoc()) {

                        $fgimg = "";
                        $croppath = str_replace('logo/','logo/crop/',$datanewsg["fair_group_logo_path"]);
                        $fgimg = ROOTPATHDOMAIN.$croppath;


                        $kw_search = " ";
                        if($kw_search!="") {
                          $kw_search = " and (a.fca_title_th like '%".$kw."%' or a.fca_title_en like '%".$kw."%' or a.fca_detail_th like '%".$kw."%' or a.fca_detail_en like '%".$kw."%') ";
                        }

                        $sqlnews = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 and b.fair_status = '1' and d.fair_group_id = ? $kw_search order by a.fca_create_date DESC limit 3 ";
                        $stmtnews= $mysqli->prepare($sqlnews);
                        $stmtnews->bind_param('i',$datanewsg["fair_group_id"]);
                        $stmtnews->execute();
                        $resultnews = $stmtnews->get_result();
                        $numrownews = $resultnews->num_rows;
                        if($numrownews>0) {
                          ?>
                          <div class="row mb-3 px-4 event-type _faircontentblog">

                            <div class="d-block d-md-none col-12 col-md-3 view-more pt-2 text-end">
                              <a href="<?=ROOTPATHDOMAIN?>all-news-group/<?=$datanewsg["fair_group_id"]?>/<?=urlencode($datanewsg["fair_group_name_th"])?>/">
                                  <i class="bi bi-arrow-right-short"></i>
                                  <span>View More</span>
                              </a>
                            </div>

                              <div class="col-12 col-md-9">
                                  <div class="row event-area align-items-center">
                                      <div class="col-12 pt-1">
                                          <img class="float-start" src="<?=$fgimg?>" style="border-radius:50%;">
                                          <span class="event-name h-100 float-start pt-0">
                                              <?=$datanewsg["fair_group_name_th"]?>
                                          </span>
                                      </div>
                                  </div>
                              </div>
                              <div class="d-none d-md-block col-12 col-md-3 view-more pt-2 text-end">
                                <a href="<?=ROOTPATHDOMAIN?>all-news-group/<?=$datanewsg["fair_group_id"]?>/<?=urlencode($datanewsg["fair_group_name_th"])?>/">
                                    <i class="bi bi-arrow-right-short"></i>
                                    <span>View More</span>
                                </a>
                              </div>

                          </div>

                          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-3 mb-5 px-4 continer-content-list px-4">
                          <?
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
                                  <div class="card-image rounded-3">
                                    <a href="<?=ROOTPATHDOMAIN?>news-detail/<?=$datanews["fca_id"]?>/<?=urlencode($newstitle)?>/"><img src="<?=$newsimg?>"/>  <span class="label-txt-press-con px-2 py-0"><?=getTagMasterNews($datanews["fca_tag_master_id"])?></span></a>
                                  </div>
                                  <div class="card-body pb-0">
                                      <h5 class="card-title"><a href="<?=ROOTPATHDOMAIN?>news-detail/<?=$datanews["fca_id"]?>/<?=urlencode($newstitle)?>/"><?=$newstitle?></a></h5>
                                      <div class="event-area row row-cols-2">
                                          <div class="col-2 pt-1">
                                              <img src="<?=$fgimg?>" style="border-radius:50%; border:" />
                                          </div>
                                          <div class="col-10 pt-2">
                                              <span class="event-name d-block"><?=$datanews["fair_name"]?></span>
                                              <span class="event-date d-block"><?=getDateContent($datanews["fca_create_date"])?></span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <?
                          }
                          ?>
                          </div>
                          <?
                        }

                      }
                    }
                    ?>

                  </div>

                    </div>
                </div>
            </div>
        </main>
        <?php include('components/footer.php') ?>
    </div>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>

    <script type="text/javascript">
      function changeMore(id) {
        if(id!="") {
          var name = $("._eenews option:selected").text();
          name = encodeURIComponent(name);
          window.location='<?=ROOTPATHDOMAIN?>all-news-group/'+id+'/'+name+'/';
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
