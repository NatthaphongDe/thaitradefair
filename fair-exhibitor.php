<?
include_once ("backoffice/connect.php");

$exp_id = '';
$exl_id = $mysqli->real_escape_string((int)$_GET["exl_id"]);
$fct_id = $mysqli->real_escape_string((int)$_GET["fct_id"]);
$fair_id = $mysqli->real_escape_string($_GET["id"]);

$sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_id = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start DESC ";
$stmtfair = $mysqli->prepare($sqlfair);
$stmtfair->bind_param('i',$fair_id);
$stmtfair->execute();
$resultfair = $stmtfair->get_result();
$numrowfair = $resultfair->num_rows;
if($numrowfair>0) {
  $datafair = $resultfair->fetch_assoc();
} else {
  ?>
  <script type="text/javascript">
    window.location='<?=ROOTPATHDOMAIN?>';
  </script>
  <?
  exit();
}


$sqleld = " select * from tt_exhibitor_list where fair_id = ? and exl_id = ? ";
$stmteld = $mysqli->prepare($sqleld);
$stmteld->bind_param('ii',$fair_id,$exl_id);
$stmteld->execute();
$resulteld = $stmteld->get_result();
$numroweld = $resulteld->num_rows;
if($numroweld>0) {
  $dataeld = $resulteld->fetch_assoc();
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
  <title>Thailand Trade Fair  : <?=$datafair["fair_name"]?> - <?=$datafair["fair_group_name_th"]?> (<?=$dataeld["com_name"]?>)</title>
  <meta name="description" content="<?=$dataeld["com_name"]?>">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/dist/bootstrap-datepicker/css/bootstrap-datepicker.min.css">

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
                            <h1 class="title p-0 m-0 text-end text-uppercase">Exhibitor List</h1>
                        </div>
                    </div>

                    <div class="row mt-4 px-4 _faircontentblogex">
                        <div class="col">
                            <div id="company-data" class="row rounded-4 overflow-hidden">
                                <div class="col-12 col-lg-8 pt-4 px-5 pb-4 pb-lg-0 _exinner">
                                    <div class="d-grid company-name text-start mb-2">
                                        <?=$dataeld["com_name"]?>
                                    </div>
                                    <div class="row company-detail">
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Product :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                            <?=$dataeld["product_group"]?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Brand :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                          <?
                                          ?>
                                          <? if($dataeld["product_brand"]=="") { ?>
                                            <?=$dataeld["product_brand_desc"]?>
                                          <? } else { ?>
                                            <?=$dataeld["product_brand"]?>
                                            <br />
                                            <?=$dataeld["product_brand_desc"]?>
                                          <? } ?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Category :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                            <?=$dataeld["product_cat"]?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Booth :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                          <?
                                          $sqleldb = " select Hall_Name from tt_exhibitor_booth where exl_id = ? group by Hall_Name order by Hall_Name ASC ";
                                          $stmteldb = $mysqli->prepare($sqleldb);
                                          $stmteldb->bind_param('i',$exl_id);
                                          $stmteldb->execute();
                                          $resulteldb = $stmteldb->get_result();
                                          $numroweldb = $resulteldb->num_rows;
                                          if($numroweldb>0) {
                                            while($dataeldb = $resulteldb->fetch_assoc()) {

                                              $sqleldbb = " select Block_Code from tt_exhibitor_booth where exl_id = ? and Hall_Name = ?  group by Block_Code order by Block_Code ASC ";
                                              $stmteldbb = $mysqli->prepare($sqleldbb);
                                              $stmteldbb->bind_param('is',$exl_id,$dataeldb["Hall_Name"]);
                                              $stmteldbb->execute();
                                              $resulteldbb = $stmteldbb->get_result();
                                              $numroweldbb = $resulteldbb->num_rows;
                                              if($numroweldbb>0) {
                                                while($dataeldbb = $resulteldbb->fetch_assoc()) {
                                              ?>
                                              <?=$dataeldb["Hall_Name"]?> : <?=$dataeldbb["Block_Code"]?> -
                                              <?
                                              $bb = 0;
                                              $sqleldbbb = " select Booth_no from tt_exhibitor_booth where exl_id = ? and Hall_Name = ? and Block_Code = ? order by Block_Code ASC ";
                                              $stmteldbbb = $mysqli->prepare($sqleldbbb);
                                              $stmteldbbb->bind_param('iss',$exl_id,$dataeldb["Hall_Name"],$dataeldbb["Block_Code"]);
                                              $stmteldbbb->execute();
                                              $resulteldbbb = $stmteldbbb->get_result();
                                              $numroweldbbb = $resulteldbbb->num_rows;
                                              if($numroweldbbb>0) {
                                                while($dataeldbbb = $resulteldbbb->fetch_assoc()) {
                                              ?>
                                                <? if($bb==0) { ?>
                                                  <?=$dataeldbbb["Booth_no"]?>
                                                <? } else { ?>
                                                  , <?=$dataeldbbb["Booth_no"]?>
                                                <? } ?>
                                              <? $bb++; } } ?>
                                              <br />
                                              <?
                                                }
                                              }
                                            }
                                          }
                                          ?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Address :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                            <?=$dataeld["com_address"]?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Contact Person :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-4">
                                            <?=$dataeld["contact_name"]?> - <?=$dataeld["contact_position"]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 p-0 contact-data-area pb-4">
                                    <div class="d-grid title text-center px-5 mt-4">
                                        Company Contact
                                    </div>
                                    <div class="row p-0 contact-detail px-5 mb-5">
                                        <div class="col-12">
                                          <div class="row">
                                            <div class="col-1">
                                              <i class="bi  bi-telephone-fill text-white"></i>
                                            </div>
                                            <div class="col-10" style="padding-right:0">
                                              <?=$dataeld["com_tel"]?>
                                            </div>
                                          </div>

                                        </div>
                                        <div class="col-12">
                                          <div class="row">
                                            <div class="col-1">
                                              <i class="bi  bi-printer-fill text-white"></i>
                                            </div>
                                            <div class="col-10" style="padding-right:0">
                                              <?=$dataeld["com_fax"]?>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-12">
                                          <div class="row">
                                            <div class="col-1">
                                              <i class="bi  bi-envelope-fill text-white"></i>
                                            </div>
                                            <div class="col-10" style="padding-right:0">
                                              <?=$dataeld["com_email"]?>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="contact-btn-area mt-auto">
                                        <div class="d-grid contact-btn px-5 mb-2">
                                          <? if($dataeld["thaitrand_shop_url"]!="") { ?>
                                            <a href="<?=$dataeld["thaitrand_shop_url"]?>" target="_blank" class="btn btn-white d-block py-1"><img
                                                    src="<?=ROOTPATHDOMAIN?>assets/images/btn-thaitrade.png" width="100%"  /></a>
                                          <? } else { ?>
                                            <a href="https://www.thaitrade.com/search/seller?keyword=<?=$dataeld["com_name"]?>" target="_blank" class="btn btn-white d-block py-1"><img
                                                    src="<?=ROOTPATHDOMAIN?>assets/images/btn-thaitrade.png" width="100%"  /></a>
                                          <? } ?>


                                        </div>
                                        <div class="d-grid contact-btn px-5 mb-2">
                                          <? if($_COOKIE["ssoid"]!="") { ?>
                                            <?
                                            // $vendor_id = $dataeld["com_taxno"];
                                            // $userid = $_COOKIE["ssoid"];  ?>
                                            <a class="btn btn-outline-white d-block py-1" data-bs-toggle="modal" data-bs-target="#Modal_Meeting">Online Meeting</a>
                                          <? } else { ?>
                                            <a onclick="alertLogin();" class="btn btn-outline-white d-block py-1">Online Meeting</a>
                                          <? } ?>


                                        </div>
                                        <div class="d-grid contact-btn px-5 mb-4">

                                          <? if($_COOKIE["ssoid"]!="") { ?>
                                            <input type="hidden" id="com_taxno" value="<?=$dataeld["com_taxno"]?>">
                                            <input type="hidden" id="room_type" value="1">
                                            <?
                                            // $vendor_id = $dataeld["com_taxno"];
                                            // $userid = $_COOKIE["ssoid"];  ?>
                                            <a class="btn btn-outline-white d-block py-1" onclick="openChat('<?=$dataeld["com_taxno"]?>', 1)">Live Chat</a>
                                          <? } else { ?>
                                            <a onclick="alertLogin();" class="btn btn-outline-white d-block py-1">Live Chat</a>
                                          <? } ?>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?
                    $sqleldp = " select * from tt_exhibitor_product where exl_id = ? order by pro_id ASC ";
                    $stmteldp = $mysqli->prepare($sqleldp);
                    $stmteldp->bind_param('i',$exl_id);
                    $stmteldp->execute();
                    $resulteldp = $stmteldp->get_result();
                    $numroweldp = $resulteldp->num_rows;
                    if($numroweldp>3) {
                      $numgallslide = 3;
                      $infi = "true";
                    } else {
                      $numgallslide = $numroweldp-1;
                      $infi = "false";
                    }
                    if($numroweldp>0) {
                    ?>
                    <div class="row mt-5 mb-5 p-0">
                        <div class="col p-0">
                            <div id="galleryCarousel" class="slider rounded-4 overflow-hidden _light">
                              <?  while($dataeldp = $resulteldp->fetch_assoc()) { ?>
                                <a href="<?=$dataeldp["ImageUrl"]?>">
                                <div class="px-1">
                                    <img src="<?=$dataeldp["ImageUrl"]?>" alt="<?=$dataeldp["ImageTitle"]?>" class="rounded-4" />
                                </div>
                                </a>
                              <? } ?>

                            </div>
                        </div>
                    </div>
                    <? } ?>


                </div>
            </div>
        </main>
        <br>
        <?php include('liveChat.php') ?>
        <?php include('components/footer.php') ?>
    </div>
    <script src="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>
    <script src="<?=ROOTPATHDOMAIN?>assets/dist/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="<?=ROOTPATHDOMAIN?>assets/dist/bootstrap-datepicker/locales/bootstrap-datepicker.th.min.js" type="text/javascript"></script>

    <?php include('meeting.php') ?>

    <style>
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

    h2.title {
        font-size: 26px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        /* text-align: justify; */
        color: #111;
    }

    p {
        font-size: 16px;
    }

    .text-indent {
        text-indent: 20px;
    }

    .logo-event-list img {
        height: 30px;
    }

    .right-event-name {
        font-size: 20px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: right;
    }

    @media (max-width: 767px) {
        .right-event-name {
            text-align: left;
        }
    }

    .content-event-list .list-group {
        background: none;
        border: none;
    }

    .content-event-list .list-group-item {
        background: none;
        border: none;
    }

    .lifestyle-categories-list .col {
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.04;
        letter-spacing: normal;
    }
    </style>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.12/css/lightgallery.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lightgallery@1.6.12/dist/js/lightgallery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lg-thumbnail/1.1.0/lg-thumbnail.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lg-fullscreen/1.1.0/lg-fullscreen.min.js"></script>

    <script>



    $(document).ready(function() {

      $("._light").lightGallery();

      $('#galleryCarousel').slick({
          autoplay: true,
          autoplaySpeed: 2000,
          variableWidth: true,
          infinite: '<?=$infi?>'
      });
    });
    </script>

    <style>
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

    #company-data {
        box-shadow: 0 2px 15px 0 rgba(0, 0, 0, 0.5);
        background-color: #fff;
    }

    #company-data .company-name {
        font-size: 22px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #378dd7;
        border-bottom: solid 1px #979797;
    }

    #company-data .txt-title {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 2.09;
        letter-spacing: normal;
        text-align: right;
        color: #000;
    }

    #company-data .txt-detail {
        font-size: 16px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2.09;
        letter-spacing: normal;
        text-align: left;
        color: #000;
        padding-top: 4px;
    }

    #company-data .contact-data-area {
        position: relative;
        background-image: linear-gradient(to bottom, #ffd05c, #fba81d);
    }

    @media (max-width: 767px) {
        #company-data .txt-title {
            text-align: left;
        }
    }

    .contact-data-area .btn-white {
        border-radius: 14px;
        background-color: #fff !important;
        border-color: #fff !important;
    }

    .contact-data-area .title {
        font-size: 22px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
    }

    .contact-data-area .contact-detail {
        font-size: 16px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2.23;
        letter-spacing: normal;
        color: #fff;
    }

    .contact-data-area .btn-outline-white {
        border-radius: 14px;
        border: solid 2px #fff;
        font-size: 20px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #fff;
    }



    #galleryCarousel .slider {
        width: 100%;
        height: 300px;

    }

    #galleryCarousel .slick-slider .slick-list {}

    #galleryCarousel .slick-slide img {
        width: auto;
        height: 270px;
        max-height: 270px;
    }



    #galleryCarousel .slick-prev,
    #galleryCarousel .slick-next {
        width: 40px;
        height: 38px;
        height: 100%;
        background: rgba(255, 255, 255, 0.56);
    }

    #galleryCarousel .slick-prev:before,
    #galleryCarousel .slick-next:before {
        color: black;
        content: '';
    }

    #galleryCarousel .slick-prev {
        background: url('<?=ROOTPATHDOMAIN?>assets/images/left-anticon.png'), rgba(255, 255, 255, 0.56);
        background-repeat: no-repeat;
        background-position: center center;
        left: 0px;
        z-index: 2;
    }

    #galleryCarousel .slick-next {
        background: url('<?=ROOTPATHDOMAIN?>assets/images/right-anticon.png') no-repeat center center, rgba(255, 255, 255, 0.56);
        background-repeat: no-repeat;
        background-position: center center;
        right: 0px;
        z-index: 2;
    }

    .contact-btn-area {
        width: 100%;
        position: absolute;
        bottom: 1rem;
    }

    </style>


    <script type="text/javascript">
      function alertLogin() {
        swal({
            title: "Please sign in",
            text: "Please sign in with SSO account.",
           type: "info",
            showCancelButton: true,
            confirmButtonColor: "#5cb85c",
            confirmButtonText: "Sign In",
        cancelButtonText: "Cancel",
            closeOnConfirm: true
          }, function (isConfirm) {
            if (isConfirm) {
               window.location='https://sso.ditp.go.th/sso/auth?client_id=SSO220628&response_type=token&redirect_uri=<?=ROOTPATHDOMAIN?>get-sso-login.php';
            }
          });
      }
    </script>



</body>


</html>
