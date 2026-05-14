<?
include_once ("backoffice/connect.php");
$top_menu_active = "exporters-profile";

$exp_id = $mysqli->real_escape_string((int)$_GET["exp_id"]);
$exl_id = '';

$sqleld = " select * from  tt_exportor_list where exp_id = ? ";
$stmteld = $mysqli->prepare($sqleld);
$stmteld->bind_param('i',$exp_id);
$stmteld->execute();
$resulteld = $stmteld->get_result();
$numroweld = $resulteld->num_rows;
if($numroweld>0) {
  $dataeld = $resulteld->fetch_assoc();

  if($dataeld["Corporate_Name_EN"]!="") {
    $namex = $dataeld["Corporate_Name_EN"];
  } else {
    $namex = $dataeld["Corporate_Name_TH"];
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

  $branditem = "";
  $sqleldp = " select Product_Brand_EN from tt_exportor_product where exp_id = ? and Product_Brand_EN != '' group by Product_Brand_EN order by Product_Brand_EN ASC ";
  $stmteldp = $mysqli->prepare($sqleldp);
  $stmteldp->bind_param('i',$dataeld["exp_id"]);
  $stmteldp->execute();
  $resulteldp = $stmteldp->get_result();
  $numroweldp = $resulteldp->num_rows;
  if($numroweldp>0) {
    while($dataeldp = $resulteldp->fetch_assoc()) {
      if($branditem=="") {
        $branditem = $dataeldp["Product_Brand_EN"];
      } else {
        $branditem = $branditem.", ".$dataeldp["Product_Brand_EN"];
      }
    }
  }

  $catitem = "";
  $sqleldp = " select Product_Cat_Name_EN from tt_exportor_product where exp_id = ? and Product_Cat_Name_EN != '' group by Product_Cat_Name_EN order by Product_Cat_Name_EN ASC ";
  $stmteldp = $mysqli->prepare($sqleldp);
  $stmteldp->bind_param('i',$dataeld["exp_id"]);
  $stmteldp->execute();
  $resulteldp = $stmteldp->get_result();
  $numroweldp = $resulteldp->num_rows;
  if($numroweldp>0) {
    while($dataeldp = $resulteldp->fetch_assoc()) {
      if($catitem=="") {
        $catitem = $dataeldp["Product_Cat_Name_EN"];
      } else {
        $catitem = $catitem.", ".$dataeldp["Product_Cat_Name_EN"];
      }
    }
  }

  $subcatitem = "";
  $sqleldp = " select Product_Sub_Cat_Name_EN from tt_exportor_product where exp_id = ? and Product_Sub_Cat_Name_EN != '' group by Product_Sub_Cat_Name_EN order by Product_Sub_Cat_Name_EN ASC ";
  $stmteldp = $mysqli->prepare($sqleldp);
  $stmteldp->bind_param('i',$dataeld["exp_id"]);
  $stmteldp->execute();
  $resulteldp = $stmteldp->get_result();
  $numroweldp = $resulteldp->num_rows;
  if($numroweldp>0) {
    while($dataeldp = $resulteldp->fetch_assoc()) {
      if($subcatitem=="") {
        $subcatitem = $dataeldp["Product_Sub_Cat_Name_EN"];
      } else {
        $subcatitem = $subcatitem.", ".$dataeldp["Product_Sub_Cat_Name_EN"];
      }
    }
  }

  $shortdetail = "";
  $sqleldp = " select Product_Description_EN from tt_exportor_product where exp_id = ? and Product_Description_EN != '' group by Product_Description_EN order by Product_Description_EN ASC ";
  $stmteldp = $mysqli->prepare($sqleldp);
  $stmteldp->bind_param('i',$dataeld["exp_id"]);
  $stmteldp->execute();
  $resulteldp = $stmteldp->get_result();
  $numroweldp = $resulteldp->num_rows;
  if($numroweldp>0) {
    while($dataeldp = $resulteldp->fetch_assoc()) {
      if($shortdetail=="") {
        $shortdetail = $dataeldp["Product_Description_EN"];
      } else {
        $shortdetail = $subcatitem.", ".$dataeldp["Product_Description_EN"];
      }
    }
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
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> (<?=$namex?>)</title>
  <meta name="description" content="<?=$namex?>">
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
        <main class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 mb-5">
                    <?php include('components/header_menu.php'); ?>

                    <div class="row mt-4 px-4 _faircontentblog">
                        <div class="col">
                            <div id="company-data" class="row rounded-4 overflow-hidden">
                                <div class="col-12 col-lg-8 pt-4 px-5 pb-4 pb-lg-0 _exinner">
                                    <div class="d-grid company-name text-start mb-2">
                                      <? if($dataeld["Corporate_Name_EN"]!="") { ?>
                                        <?=$dataeld["Corporate_Name_EN"]?>
                                      <? } else { ?>
                                        <?=$dataeld["Corporate_Name_TH"]?>
                                      <? } ?>
                                    </div>
                                    <div class="row company-detail">
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Company Register Date :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                            <?=getDateCompany($dataeld["DBD_Register_Date"])?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Product :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                            <?=$productitem?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Brand :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                            <?=$branditem?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Category :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                            <?=$catitem?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Sub Category :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                            <?=$subcatitem?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Short Detail :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-2">
                                            <?=$shortdetail?>
                                        </div>
                                        <div class="col-12 col-sm-4 txt-title mb-0 mb-sm-2">
                                            Last Update :
                                        </div>
                                        <div class="col-12 col-sm-8 txt-detail mb-4">
                                            <?=getDateContent($dataeld["Modify_Date"])?>
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
                                                <?=$dataeld["Telephone"]?>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="col-12">
                                            <div class="row">
                                              <div class="col-1">
                                                <i class="bi  bi-envelope-fill text-white"></i>
                                              </div>
                                              <div class="col-10" style="padding-right:0">
                                                <?=$dataeld["Mail"]?>
                                              </div>
                                            </div>
                                          </div>

                                    </div>
                                    <div class="contact-btn-area mt-5">
                                        <div class="d-grid contact-btn px-5 mb-2">
                                          <? if($dataeld["thaitrand_shop_url"]!="") { ?>
                                            <a href="<?=$dataeld["thaitrand_shop_url"]?>" target="_blank" class="btn btn-white d-block py-1"><img
                                                    src="<?=ROOTPATHDOMAIN?>assets/images/btn-thaitrade.png" width="100%"  /></a>
                                          <? } else { ?>
                                            <!-- <a href="https://www.thaitrade.com/search/seller?keyword=<?=$dataeld["Corporate_Name_EN"]?>" target="_blank" class="btn btn-white d-block py-1"><img
                                                    src="<?=ROOTPATHDOMAIN?>assets/images/btn-thaitrade.png" width="100%" /></a> -->
                                          <? } ?>
                                        </div>
                                        <div class="d-grid contact-btn px-5 mb-2">

                                          <? if($_COOKIE["ssoid"]!="") { ?>
                                            <?
                                            // $vendor_id = $dataeld["DBD_Register_No"];
                                            // $userid = $_COOKIE["ssoid"];  ?>
                                            <a class="btn btn-outline-white d-block py-1"  data-bs-toggle="modal" data-bs-target="#Modal_Meeting">Online Meeting</a>
                                          <? } else { ?>
                                            <a onclick="alertLogin();" class="btn btn-outline-white d-block py-1">Online Meeting</a>
                                          <? } ?>

                                        </div>
                                        <div class="d-grid contact-btn px-5 mb-2">

                                          <? if($_COOKIE["ssoid"]!="") { ?>
                                            <input type="hidden" id="com_taxno" value="<?=$dataeld["User_ID"]?>">
                                            <input type="hidden" id="room_type" value="2">
                                            <?
                                            // $vendor_id =  $dataeld["DBD_Register_No"];
                                            // $userid = $_COOKIE["ssoid"];  ?>
                                            <a class="btn btn-outline-white d-block py-1" onclick="openChat('<?=$dataeld["User_ID"]?>', 2)">Live Chat</a>
                                          <? } else { ?>
                                            <a onclick="alertLogin();" class="btn btn-outline-white d-block py-1">Live Chat</a>
                                          <? } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>



        <?php include('liveChat.php') ?>
        <?php include('components/footer.php') ?>

        <script src="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick.js" type="text/javascript" charset="utf-8"></script>
        <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>
        <script src="<?=ROOTPATHDOMAIN?>assets/dist/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="<?=ROOTPATHDOMAIN?>assets/dist/bootstrap-datepicker/locales/bootstrap-datepicker.th.min.js" type="text/javascript"></script>

        <?php include('meeting.php') ?>

    </div>


    <script>
    $(document).ready(function() {

        $('#galleryCarousel').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            variableWidth: true,
            infinite: true
        });
    });
    </script>

    <style>
    .combined-shape {
        min-height: 550px;
        height: auto;
    }

    #company-data {
        box-shadow: 0 2px 15px 0 rgba(0, 0, 0, 0.5);
        background-color: #fff;
        width: 95%;
        margin: auto;
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

    #company-data .txt-detail mb-2 {
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

    .contact-btn-area {
        width: 100%;
        position: absolute;
        bottom: 1rem;
    }
    </style>


    <script type="text/javascript">
      function alertLogin() {
        var currentUrl = window.location.href;
        var redirectUrl = 'https://sso.ditp.go.th/sso/auth?client_id=SSO220628&response_type=token&redirect_uri=<?=ROOTPATHDOMAIN?>get-sso-login.php?page=' + encodeURIComponent(currentUrl);
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
               window.location=redirectUrl;
            }
          });
      }
    </script>

</body>


</html>
