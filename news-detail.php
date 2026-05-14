<?
include_once ("backoffice/connect.php");

$news_id = (int)$_GET["id"];
$sqlfair = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 and a.fca_id = ? ";
$stmtfair = $mysqli->prepare($sqlfair);
$stmtfair->bind_param('i',$news_id);
$stmtfair->execute();
$resultfair = $stmtfair->get_result();
$numrowfair = $resultfair->num_rows;
if($numrowfair>0) {
  $datafair = $resultfair->fetch_assoc();

  if($datafair["fca_title_en"]!="") {
    $contentdata_title = $datafair["fca_title_en"];
  } else {
    $contentdata_title = $datafair["fca_title_th"];
  }

  if($datafair["fca_detail_en"]!="") {
    $contentdata = $datafair["fca_detail_en"];
  } else {
    $contentdata = $datafair["fca_detail_th"];
  }
  $contentdata = str_replace('..//data','../data',$contentdata);
  $contentdata = str_replace('../data',ROOTPATHDOMAIN.'data',$contentdata);
} else {
  ?>
  <script type="text/javascript">
    window.location='<?=ROOTPATHDOMAIN?>';
  </script>
  <?
  exit();
}
?>
<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?></title>
  <meta name="description" content="<?=$contentdata_title?>">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/news-detail.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>
                    <?php include('components/top_slide_detail_cover.php'); ?>
                </div>
            </div>
        </header>

        <main>
            <div class="container-fluid">
                <div class="container">

                    <div class=" row mt-4 px-4 _faircontentblog">
                        <div class="col-12 col-md-7 navigator-text pt-2">
                          <br>
                            <?=$datafair["fair_group_name_th"]?> / <span class="active">News & Update</span>
                        </div>

                        <div class="col-12 col-md-5 _faircontentblog _faircontentblogwhicht">
                            <h1 class="title p-0 m-0 text-end text-uppercase">News & Update</h1>
                        </div>
                    </div>

                    <div id="news-detail" class="row mt-4 px-4 _faircontentblog _faircontentblogwhicht">
                        <div class="col-12">
                            <h1><?=$contentdata_title?></h1>
                        </div>

                        <div class="col-12">
                          <?=$contentdata?>
                        </div>

                        <? if($datafair["fca_map_status"]==1) { ?>

                          <div class="row clearall">


                            <div id="company-list" class="mt-5 mt-sm-3 px-0 px-md-4">

                                <div class="tab-content px-0 _exdatalist" id="myTabContent">


                                    <div class="fade show active" id="company-tab-pane" role="tabpanel"
                                        aria-labelledby="home-tab" tabindex="0">

                                        <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem; border-top-left-radius: 0.75rem;">
                                            <div class="col-12">
                                                <label class="form-check-label" for="titleCheckDefault">
                                                  <? if($datafair["fca_map_title"]=="") { ?>
                                                    Map
                                                  <? } else { ?>
                                                    <?=$datafair["fca_map_title"]?>
                                                  <? } ?>
                                                </label>
                                            </div>
                                        </div>


                                        <div class="scrollbar-inner">
                                            <ul class="list-group checkbox-list-company border-0">
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0" style="padding:0;">

                                                    <div id="map" class="mapshow"></div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                          </div>

                          <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfB9v8-IazC6JveT2VqMWPrP024evjATQ&callback=initMap&libraries=places&v=weekly" defer></script>
                          <script type="text/javascript">
                          var marker;
                          var infowindow = "";
                          function initMap() {

                            const uluru = { lat: <?=$datafair["fca_lat"]?>, lng: <?=$datafair["fca_lng"]?> };
                            const map = new google.maps.Map(document.getElementById("map"), {
                              center: { lat: <?=$datafair["fca_lat"]?>, lng: <?=$datafair["fca_lng"]?> },
                              zoom: 13,
                              mapTypeId: "roadmap",
                            });

                            marker = new google.maps.Marker({
                              position: uluru,
                              map: map
                            });

                            infowindow = new google.maps.InfoWindow({
                               content: '<?=$datafair["fca_address"]?>'
                            });
                            infowindow.open(map,marker);


                          }
                          window.initMap = initMap;
                          </script>

                        <? } ?>


                        <? if($datafair["fca_url"]!="") { ?>

                          <?
                          $totalurl = 0;
                          $urlall = array();
                          if($datafair["fca_url"]!="") {
                            $urlall = explode("|",$datafair["fca_url"]);
                          }
                          for($u=0;$u<count($urlall);$u++) {
                            if($urlall[$u]!="") {
                              $totalurl++;
                            }
                          }
                          ?>

                          <div class="row clearall">


                            <div id="company-list" class="mt-5 mt-sm-3 px-0 px-md-4">

                                <div class="tab-content px-0 _exdatalist" id="myTabContent">


                                    <div class="fade show active pb-3" id="company-tab-pane" role="tabpanel"
                                        aria-labelledby="home-tab" tabindex="0">

                                        <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem; border-top-left-radius: 0.75rem;">
                                            <div class="col-12">
                                                <label class="form-check-label" for="titleCheckDefault">
                                                    Link URL
                                                </label>
                                                <span class="total-1">(<?=number_format($totalurl)?>)</span>
                                            </div>
                                        </div>


                                        <div class="scrollbar-inner">
                                            <ul class="list-group checkbox-list-company border-0 px-4">
                                              <?
                                              $urlall = array();
                                              if($datafair["fca_url"]!="") {
                                                $urlall = explode("|",$datafair["fca_url"]);
                                              }
                                              for($u=0;$u<count($urlall);$u++) {
                                                if($urlall[$u]!="") {
                                              ?>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">

                                                    <div class="ms-2 me-auto">
                                                        <div class="title fw-bold">
                                                            <a href="<?=$urlall[$u]?>" target="_blank">
                                                                <?=$urlall[$u]?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                              <? } } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                          </div>
                        <? } ?>


                        <? if($datafair["fca_youtube"]!="") { ?>

                          <?
                          $totalurl = 0;
                          $urlall = array();
                          if($datafair["fca_youtube"]!="") {
                            $urlall = explode("|",$datafair["fca_youtube"]);
                          }
                          for($u=0;$u<count($urlall);$u++) {
                            if($urlall[$u]!="") {
                              $totalurl++;
                            }
                          }
                          ?>

                          <div class="row clearall">


                            <div id="company-list" class="mt-5 mt-sm-3 px-0 px-md-4">

                                <div class="tab-content px-0 _exdatalist" id="myTabContent">


                                    <div class="fade show active pb-3" id="company-tab-pane" role="tabpanel"
                                        aria-labelledby="home-tab" tabindex="0">

                                        <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem; border-top-left-radius: 0.75rem;">
                                            <div class="col-12">
                                                <label class="form-check-label" for="titleCheckDefault">
                                                    Youtube VDO URL
                                                </label>
                                                <span class="total-1">(<?=number_format($totalurl)?>)</span>
                                            </div>
                                        </div>


                                        <div class="scrollbar-inner">
                                            <ul class="list-group checkbox-list-company border-0 px-4">
                                              <?
                                              $urlall = array();
                                              if($datafair["fca_youtube"]!="") {
                                                $urlall = explode("|",$datafair["fca_youtube"]);
                                              }
                                              for($u=0;$u<count($urlall);$u++) {
                                                if($urlall[$u]!="") {
                                              ?>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">

                                                    <div class="ms-2 me-auto">
                                                        <div class="title fw-bold">
                                                            <a href="<?=$urlall[$u]?>" target="_blank">
                                                                <?=$urlall[$u]?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                              <? } } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                          </div>
                        <? } ?>


                        <?
                        $sqlfile = " select * from tt_fair_content_file where file_type = '2' and content_id = ? order by file_id DESC  ";
                        $stmtfile = $mysqli->prepare($sqlfile);
                        $stmtfile->bind_param('i',$datafair["fca_id"]);
                        $stmtfile->execute();
                        $resultfile = $stmtfile->get_result();
                        $numrowfile = $resultfile->num_rows;
                        if($numrowfile>0) {
                        ?>
                        <div class="row clearall">


                          <div id="company-list" class="mt-5 mt-sm-3 px-0 px-md-4">

                              <div class="tab-content px-0 _exdatalist" id="myTabContent">


                                  <div class="fade show active pb-3" id="company-tab-pane" role="tabpanel"
                                      aria-labelledby="home-tab" tabindex="0">

                                      <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem; border-top-left-radius: 0.75rem;">
                                          <div class="col-12">
                                              <label class="form-check-label" for="titleCheckDefault">
                                                  Attachment File
                                              </label>
                                              <span class="total-1">(<?=number_format($numrowfile)?>)</span>
                                          </div>
                                      </div>


                                      <div class="scrollbar-inner">
                                          <ul class="list-group checkbox-list-company border-0 px-4">
                                            <?
                                            while($datafile = $resultfile->fetch_assoc()) {
                                            ?>
                                              <li
                                                  class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">

                                                  <div class="ms-2 me-auto">
                                                      <div class="title fw-bold">
                                                        <a href="<?=ROOTPATHDOMAIN?><?=$datafile["file_path"]?>" target="_blank"><?=$datafile["file_name"]?></a>
                                                      </div>
                                                  </div>
                                              </li>
                                            <? } ?>
                                          </ul>
                                      </div>
                                  </div>
                              </div>
                          </div>

                        </div>
                        <? } ?>


                    </div>

                </div>
            </div>

        </main>
        <br><br>

        <?php include('components/footer.php') ?>

    </div>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>


</body>


</html>
