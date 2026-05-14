<?
include_once ("backoffice/connect.php");

$fca_id = (int)$_GET["fca_id"];
$fair_id = $_GET["id"];
$sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_id = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start DESC ";
$stmtfair = $mysqli->prepare($sqlfair);
$stmtfair->bind_param('i',$fair_id);
$stmtfair->execute();
$resultfair = $stmtfair->get_result();
$numrowfair = $resultfair->num_rows;
if($numrowfair>0) {
  $datafair = $resultfair->fetch_assoc();

  $sqlcontent = "select * from  tt_fair_content_article where fca_id = ? and fca_pubish = '1' and fca_status = '1' limit 1  ";
  $stmtcontent = $mysqli->prepare($sqlcontent);
  $stmtcontent->bind_param('i',$fca_id);
  $stmtcontent->execute();
  $resultcontent = $stmtcontent->get_result();
  $numrowcontent = $resultcontent->num_rows;
  if($resultcontent>0) {
    $datacontent = $resultcontent->fetch_assoc();
    $fct_id = (int)$datacontent["fct_id"];

    if($datacontent["fca_title_en"]!="") {
      $contentdata_title = $datacontent["fca_title_en"];
    } else {
      $contentdata_title = $datacontent["fca_title_th"];
    }

    if($datacontent["fca_detail_en"]!="") {
      $contentdata = $datacontent["fca_detail_en"];
    } else {
      $contentdata = $datacontent["fca_detail_th"];
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
  <title>Thailand Trade Fair  : <?=$datafair["fair_name"]?> - <?=$datafair["fair_group_name_th"]?></title>
  <meta name="description" content="<?=$contentdata_title?>">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/fair-detail.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
<div class="loadingoverlay"></div>
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>
                    <?php include('components/top_slide.php'); ?>
                </div>
            </div>
        </header>

        <main>
            <div class="container-fluid">
                <div class="container">
                    <?php include('components/top_menu.php'); ?>

                    <div class="row mt-4 px-4 _faircontentblog">
                        <div class="col-12 col-md-7 navigator-text pt-2">
                            <?=$datafair["fair_name"]?> <?=date("d",strtotime($datafair["fair_event_start"]))?>-<?=date("d",strtotime($datafair["fair_event_end"]))?> <?=getMonthEng(date("m",strtotime($datafair["fair_event_end"])))?> <?=date("Y",strtotime($datafair["fair_event_end"]))?> /

                            <? if(getFairMenuName($fct_id)!="") { ?>
                              <?=getFairMasterMenuName($fct_id)?>
                              / <span class="active"><?=getFairMenuName($fct_id)?></span>
                              <? $namemenufair = getFairMenuName($fct_id); ?>
                            <? } else { ?>
                              <span class="active"><?=getFairMasterMenuName($fct_id)?></span>
                              <? $namemenufair = getFairMasterMenuName($fct_id); ?>
                            <? } ?>
                        </div>
                        
                        <div class="col-12 col-md-5">
                            <h1 class="title p-0 m-0 text-end text-uppercase"><?=$namemenufair?></h1>
                        </div>
                    </div>

                    <div class="row mt-4 px-4 mb-5 _faircontentblog">
                        <div class="col-12 mb-2">
                            <h2 class="title p-0 m-0"><?=$contentdata_title?></h2>
                        </div>
                        <div class="col-12 mb-2">
                          <?=$contentdata?>
                        </div>

                        <? if($datacontent["fca_map_status"]==1) { ?>

                          <div class="row clearall">


                            <div id="company-list" class="mt-5 mt-sm-3 px-0 px-md-4">

                                <div class="tab-content px-0 _exdatalist" id="myTabContent">


                                  <div class="fade show active" id="company-tab-pane" role="tabpanel"
                                      aria-labelledby="home-tab" tabindex="0">

                                      <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem; border-top-left-radius: 0.75rem;">
                                          <div class="col-12">
                                              <label class="form-check-label" for="titleCheckDefault">
                                                <? if($datacontent["fca_map_title"]=="") { ?>
                                                  Map
                                                <? } else { ?>
                                                  <?=$datacontent["fca_map_title"]?>
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

                            const uluru = { lat: <?=$datacontent["fca_lat"]?>, lng: <?=$datacontent["fca_lng"]?> };
                            const map = new google.maps.Map(document.getElementById("map"), {
                              center: { lat: <?=$datacontent["fca_lat"]?>, lng: <?=$datacontent["fca_lng"]?> },
                              zoom: 13,
                              mapTypeId: "roadmap",
                            });

                            marker = new google.maps.Marker({
                              position: uluru,
                              map: map
                            });

                            infowindow = new google.maps.InfoWindow({
                               content: '<?=$datacontent["fca_address"]?>'
                            });
                            infowindow.open(map,marker);


                          }
                          window.initMap = initMap;
                          </script>

                        <? } ?>


                        <? if($datacontent["fca_url"]!="") { ?>

                          <?
                          $totalurl = 0;
                          $urlall = array();
                          if($datacontent["fca_url"]!="") {
                            $urlall = explode("|",$datacontent["fca_url"]);
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
                                              if($datacontent["fca_url"]!="") {
                                                $urlall = explode("|",$datacontent["fca_url"]);
                                              }
                                              for($u=0;$u<count($urlall);$u++) {
                                                if($urlall[$u]!="") {
                                              ?>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">

                                                    <div class="ms-2 me-auto maxwidth" >
                                                        <div class="title fw-bold" style="max-width: 100%;word-wrap: break-word;white-space: normal;padding: 10px;">
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


                        <? if($datacontent["fca_youtube"]!="") { ?>

                          <?
                          $totalurl = 0;
                          $urlall = array();
                          if($datacontent["fca_youtube"]!="") {
                            $urlall = explode("|",$datacontent["fca_youtube"]);
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
                                              if($datacontent["fca_youtube"]!="") {
                                                $urlall = explode("|",$datacontent["fca_youtube"]);
                                              }
                                              for($u=0;$u<count($urlall);$u++) {
                                                if($urlall[$u]!="") {
                                              ?>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">

                                                    <div class="ms-2 me-auto maxwidth">
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
                        $stmtfile->bind_param('i',$datacontent["fca_id"]);
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

                                                  <div class="ms-2 me-auto maxwidth">
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


                        <? if($datacontent["fcat_id"]==7) { ?>
                          <form class="form-horizontal" method="post" name="saveformform" enctype="multipart/form-data" id="saveformform" action="<?=ROOTPATHDOMAIN?>savecontact.php" target="com_m" >
                          <div class="row mt-5 px-0 px-lg-4 mb-5 justify-content-center">

                              <div class="col-12 col-lg-9 mb-2">
                                  <div class="form-group mb-2">
                                      <input type="text" class="form-control px-3 py-2" placeholder="Name" required name="cont_name">
                                  </div>
                                  <div class="form-group mb-2">
                                      <input type="email" class="form-control px-3 py-2" placeholder="E-mail" required name="cont_email">
                                  </div>
                                  <div class="form-group mb-2">
                                      <input type="text" class="form-control px-3 py-2" placeholder="Telephone" name="cont_tel">
                                  </div>
                                  <div class="form-group mb-2">
                                      <input type="text" class="form-control px-3 py-2" placeholder="Subject" required name="cont_subject">
                                  </div>
                                  <div class="form-group mb-2">
                                      <textarea class="form-control p-3" placeholder="Message" required rows="10" name="cont_message"></textarea>
                                  </div>
                              </div>
                              <div class="col-12">
                                  <div class="d-block text-center">
                                      <button type="submit" class="btn btn-view-map mx-auto px-5">SEND</button>
                                  </div>
                              </div>



                          </div>
                          <input type="text" name="csrf_token" value="<?=$_SESSION['csrf_token']?>" hidden>
                          <input type="hidden" name="fair_id" value="<?=$fair_id?>">
                          <input type="hidden" name="method" value="saveformfair">
                          </form>
                        <? } ?>


                    </div>

                </div>
            </div>
        </main>
        <?php include('components/footer.php') ?>
    </div>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>

    <iframe id="com_m" name="com_m" class="ifsave" width="0" height="0" frameborder="0" scrolling="no"></iframe>

    <script type="text/javascript">
    document.querySelector('#saveformform').addEventListener('submit', function(e) {
        var form = this;
        e.preventDefault(); // <--- prevent form from submitting

        swal({
            title: "Confirm",
            text: "Confirm to send this contact form ?",
           type: "info",
            showCancelButton: true,
            confirmButtonColor: "#5cb85c",
            confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
            closeOnConfirm: true
          }, function (isConfirm) {
            if (isConfirm) {
               form.submit();
               pc_overlay(1);
            }
          });
      });

      function alertSuccess() {
        swal({
          title: "Success",
          text: "Send data successful",
          type: "success",
          showCancelButton: false,
          confirmButtonColor: "#5cb85c",
          confirmButtonText: "close",
          closeOnConfirm: false
        });
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

      function pc_overlay(type) {
        if(type==1) {
          $('.loadingoverlay').show();
        } else {
          $('.loadingoverlay').hide();
        }
      }
    </script>


</body>


</html>
