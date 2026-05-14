<?
include_once ("backoffice/connect.php");

$fcg_id = (int)$_GET["fcg_id"];
$fair_id = $_GET["id"];
$sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_id = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start DESC ";
$stmtfair = $mysqli->prepare($sqlfair);
$stmtfair->bind_param('i',$fair_id);
$stmtfair->execute();
$resultfair = $stmtfair->get_result();
$numrowfair = $resultfair->num_rows;
if($numrowfair>0) {
  $datafair = $resultfair->fetch_assoc();

  $sqlcontent = "select * from tt_fair_content_gallery where fcg_id = ? and fcg_pubish = '1' and fcg_status = '1' limit 1  ";
  $stmtcontent = $mysqli->prepare($sqlcontent);
  $stmtcontent->bind_param('i',$fcg_id);
  $stmtcontent->execute();
  $resultcontent = $stmtcontent->get_result();
  $numrowcontent = $resultcontent->num_rows;
  if($resultcontent>0) {
    $datacontent = $resultcontent->fetch_assoc();
    $fct_id = (int)$datacontent["fct_id"];

    if($datacontent["fcg_title_en"]!="") {
      $contentdata_title = $datacontent["fcg_title_en"];
    } else {
      $contentdata_title = $datacontent["fcg_title_th"];
    }
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
    <link href="<?=ROOTPATHDOMAIN?>assets/css/fair-gallery.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
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



                    <div class="row mt-4 px-4 _faircontentblog">
                        <div class="col-12 mb-2">
                            <h2 class="title p-0 m-0"><?=$contentdata_title?></h2>
                        </div>
                        <div id="gallery" class="col-12 mb-2">
                            <div class="row row-cols-1 row-cols-md-2 gallery-list">
                                <div class="row wf-container" id="lightgallery">
                                  <?
                                  $sqlnews = "select * from tt_fair_content_gallery_file where fcg_id = ? order by gall_file_create_date DESC ";
                                  $stmtnews= $mysqli->prepare($sqlnews);
                                  $stmtnews->bind_param('i',$fcg_id);
                                  $stmtnews->execute();
                                  $resultnews = $stmtnews->get_result();
                                  $numrownews = $resultnews->num_rows;

                                  if($numrownews>0) {
                                    while($datanews = $resultnews->fetch_assoc()) {
                                    $newsimg = "";
                                    $croppath = str_replace('cover/','cover/croptop/',$datanews["gall_file_path"]);
                                    $newsimg = ROOTPATHDOMAIN.$croppath;

                                    $newsimgfull = "";
                                    $croppathfull = str_replace('cover/','cover/resize/',$datanews["gall_file_path"]);
                                    $newsimgfull = ROOTPATHDOMAIN.$croppathfull;
                                  ?>
                                  <div class="col-md-3" style="cursor:pointer" data-src="<?=$newsimgfull?>">
                                    <!-- <a href="<?=$newsimgfull?>"> -->
                                      <div class="wf-box">
                                          <img src="<?=$newsimg?>">
                                      </div>
                                    <!-- </a> -->
                                  </div>
                                  <? } } ?>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </main>
        <?php include('components/footer.php') ?>
    </div>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/waterfall-light.js"></script>

    <script>
    // use querySelector/querySelectorAll internally
    // var waterfall = new Waterfall({
    //     containerSelector: '.wf-container',
    //     boxSelector: '.wf-box',
    //     minBoxWidth: 250
    // });

    var setting = {
        gap: 10,
        gridWidth: [0, 576, 768],
        refresh: 500
    };
    $(function() {
        $('.wf-container').waterfall(setting);
    })
    </script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.12/css/lightgallery.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lightgallery@1.6.12/dist/js/lightgallery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lg-thumbnail/1.1.0/lg-thumbnail.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lg-fullscreen/1.1.0/lg-fullscreen.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      $("#lightgallery").lightGallery({
        selector: '.col-md-3',
      });
    });
    </script>


</html>
