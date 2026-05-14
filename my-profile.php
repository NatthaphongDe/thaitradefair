<?
include_once ("backoffice/connect.php");
$top_menu_active = "my-profile";

$userid = $_COOKIE["ssoid"];
if($userid!="") {

  $sql = "select * from tt_sso_login where sso_id = ? ";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('s',$userid);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();

    $nname = "";

    if($data["sso_company_en"]!="") {
      $nname = $data["sso_company_en"];
    } else {
      if($data["sso_company_th"]!="") {
        $nname = $data["sso_company_th"];
      } else {
        if($data["sso_name_en"]!="") {
          $nname = $data["sso_name_en"];
        } else {
          $nname = $data["sso_name_th"];
        }
      }
    }

    $nnamex = $nname;

    $nname = mb_strtoupper(mb_substr($nname, 0, 1),'UTF-8');
  } else {
    ?>
    <script type="text/javascript">
      top.window.location='<?=ROOTPATHDOMAIN?>';
    </script>
    <?
    exit();
  }
} else {
  ?>
  <script type="text/javascript">
    top.window.location='<?=ROOTPATHDOMAIN?>';
  </script>
  <?
  exit();
}
?>
<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> (<?=$nnamex?>)</title>
  <meta name="description" content="<?=$nnamex?>">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/dist/slick/slick-theme.css">
</head>

<body class="d-flex flex-column h-100">
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>
                    <div class="row mt-4 px-4 mb-5 _faircontentblog">
                        <div class="col">
                            <div id="my-profile" class="row rounded-4 overflow-hidden">
                                <div class="col-12 col-lg-8 pt-4 px-5 pb-4 pb-lg-0 _exinner">
                                    <div class="d-grid company-name text-start mb-2">
                                        My Profile
                                    </div>

                                    <div class="row company-detail">
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Name :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                            <? if($data["sso_name_en"]!="") { ?>
                                              <?=$data["sso_name_en"]?>
                                            <? } else { ?>
                                              <?=$data["sso_name_th"]?>
                                            <? } ?>
                                        </div>
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Email :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                            <?=$data["sso_email"]?>
                                        </div>
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Company Name :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                          <? if($data["sso_company_en"]!="") { ?>
                                            <?=$data["sso_company_en"]?>
                                          <? } else { ?>
                                            <?=$data["sso_company_th"]?>
                                          <? } ?>
                                        </div>
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Country :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                            <?=$data["sso_country"]?>
                                        </div>
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Address :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                          <? if($data["sso_address_en"]!="") { ?>
                                            <?=$data["sso_address_en"]?>
                                          <? } else { ?>
                                            <?=$data["sso_address_th"]?>
                                          <? } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 p-0 contact-data-area pb-4">
                                    <div class="row p-0 contact-detail px-5 mb-5">
                                        <div class="col-12 py-4 text-center">
                                            <div class="rounded-circle avatar mx-auto avatar_blog">
                                                <span class="avatar_name"><?=$nname?></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid contact-btn mb-2">
                                                <!-- <button class="btn btn-outline-white d-block py-1">Change Photo</button> -->
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid contact-btn mb-2">
                                                <!-- <button class="btn btn-outline-white d-block py-1" onclick="window.location.href='edit-profile.php'">Edit Profile</button> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contact-btn-area mt-5">
                                        <div class="d-grid contact-btn px-5 mb-2">
                                            <a href="<?=ROOTPATHDOMAIN?>logout/" class="btn btn-outline-white d-block py-1">Log Out</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>


        <?php include('components/footer.php') ?>
    </div>

    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>

    <style>
    .combined-shape {
        min-height: 550px;
        height: auto;
    }

    #my-profile {
        box-shadow: 0 2px 15px 0 rgba(0, 0, 0, 0.5);
        background-color: #fff;
        /* min-height: 500px; */
        width: 95%;
        margin: auto;
        overflow: hidden;
    }

    #my-profile .company-name {
        font-size: 22px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #378dd7;
        border-bottom: solid 1px #979797;
    }

    #my-profile .txt-title {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 2.09;
        letter-spacing: normal;
        text-align: right;
        color: #000;
    }

    #my-profile .txt-detail {
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

    #my-profile .txt-detail mb-2 {
        font-size: 18px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2.09;
        letter-spacing: normal;
        text-align: left;
        color: #000;
    }

    #my-profile .contact-data-area {
        background-image: linear-gradient(to bottom, #ffd05c, #fba81d);
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

    .avatar {
        border: solid 5px #fff;
        background-color: #fff;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar_blog {
      width:150px;
      height:150px;
      text-align: center;
    }

    .avatar_name {
      font-weight: bold;
      font-size: 80px;
      line-height: 150px;
      color: #378dd7;
    }


    @media (max-width: 767px) {
        #my-profile .txt-title {
            text-align: left;
        }
    }
    </style>

</body>


</html>
