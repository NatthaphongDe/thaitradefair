<?
include_once ("backoffice/connect.php");
$top_menu_active = "contact-us";
?>
<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : Contact Us</title>
  <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : Contact Us">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/calendar.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/dist/scrollbar/jquery.scrollbar.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">

<div class="loadingoverlay"></div>

    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>

                    <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner">
                        <div class="col px-0">
                          <div id="fair-calendar-title" class="row _homebanner_bgheight">
                              <div class="col-12 align-self-center">
                                  <h1 class="title">CONTACT US</h1>
                              </div>
                          </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <main>
            <div class="container-fluid">
                <div class="container ">

                  <!-- <div class="row mt-4 px-4 _faircontentblog">
                      <div class="col-12 ">
                          <h1 class="title p-0 m-0 text-end text-uppercase">CONTACT US</h1>
                      </div>
                  </div> -->

                  <div class="row mt-4 px-4 mb-5 _faircontentblog">
                      <div class="col-12 mb-2">
                        <br>
                        <?
                        $sqlcontent = "select * from tt_contact_content where fc_id = 1 and fc_status = 1 ";
                        $stmtcontent = $mysqli->prepare($sqlcontent);
                        $stmtcontent->execute();
                        $resultcontent = $stmtcontent->get_result();
                        $numrowcontent = $resultcontent->num_rows;
                        if($numrowcontent>0) {
                          $datacontent= $resultcontent->fetch_assoc();
                          if($datacontent["fc_detail_en"]!="") {
                            $contentdata = $datacontent["fc_detail_en"];
                          } else {
                            $contentdata = $datacontent["fc_detail_th"];
                          }
                          $contentdata = str_replace('..//data','../data',$contentdata);
                          $contentdata = str_replace('../data',ROOTPATHDOMAIN.'data',$contentdata);
                          ?>
                          <?=$contentdata?>

                          <? if($datacontent["fc_map_status"]==1) { ?>


                            <div class="row clearall">


                              <div id="company-list" class="mt-2 px-2 px-md-4">

                                  <div class="tab-content px-0 _exdatalist" id="myTabContent">


                                    <div class="fade show active" id="company-tab-pane" role="tabpanel"
                                        aria-labelledby="home-tab" tabindex="0">

                                        <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem; border-top-left-radius: 0.75rem;">
                                            <div class="col-12">
                                                <label class="form-check-label" for="titleCheckDefault">
                                                  <? if($datacontent["fc_map_title"]=="") { ?>
                                                    Map
                                                  <? } else { ?>
                                                    <?=$datacontent["fc_map_title"]?>
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

                              const uluru = { lat: <?=$datacontent["fc_lat"]?>, lng: <?=$datacontent["fc_lng"]?> };
                              const map = new google.maps.Map(document.getElementById("map"), {
                                center: { lat: <?=$datacontent["fc_lat"]?>, lng: <?=$datacontent["fc_lng"]?> },
                                zoom: 13,
                                mapTypeId: "roadmap",
                              });

                              marker = new google.maps.Marker({
                                position: uluru,
                                map: map
                              });

                              infowindow = new google.maps.InfoWindow({
                                 content: '<?=$datacontent["fc_address"]?>'
                              });
                              infowindow.open(map,marker);


                            }
                            window.initMap = initMap;
                            </script>

                          <? } ?>


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
                          <input type="hidden" name="method" value="saveform">
                          </form>

                          <?
                        } else {
                        ?>
                        <p class="text-center">
                          No data available.</p>
                        <? } ?>

                      </div>




                  </div>

                </div>
            </div>

        </main>
        <?php include('components/footer.php') ?>

        <iframe id="com_m" name="com_m" class="ifsave" width="0" height="0" frameborder="0" scrolling="no"></iframe>

    </div>

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

    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>


    <style>
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

    .form-group .form-control {
        font-size: 16px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1;
        letter-spacing: -0.56px;
        text-align: justify;
        color: #111;
        border-radius: 29px;
        box-shadow: 0 3px 10px 0 rgba(0, 0, 0, 0.3);
        background-color: #fff;

    }

    ::-webkit-input-placeholder {
        /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #b2b2b2;
        /* Firefox */
    }

    :-moz-placeholder {
        /* Mozilla Firefox 4 to 18 */
        color: #b2b2b2;
    }

    :-ms-input-placeholder {
        /* Internet Explorer 10-11 */
        color: #b2b2b2;
    }

    ::-ms-input-placeholder {
        /* Microsoft Edge */
        color: #b2b2b2;
    }

    .btn-view-map {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #fff !important;
        border-radius: 46px;
        box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.4);
        background-image: linear-gradient(to left, #68c6f8, #004cb2) !important;
        border: none !important;
    }
    </style>

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



    #search-exhibitor-list {

        background-blend-mode: multiply;
        background-image: linear-gradient(to bottom, #378dd7, #378dd7);
    }

    #search-exhibitor-list .input-group {
        border-radius: 6px;
        border: solid 1px #fff;
        background-color: #fff;
    }


    #search-exhibitor-list #search .form-control,
    #search-exhibitor-list #search .form-select {
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #378dd7;

    }

    #search-exhibitor-list #search .form-select {
        background-image: url('<?=ROOTPATHDOMAIN?>assets/images/icon-chevron-down-blue.svg');
    }

    #search-exhibitor-list #search .btn-search {
        background-color: #378dd7 !important;
        border-color: #378dd7 !important;
    }

    #search-exhibitor-list #search .btn-search .bi {
        font-size: 20px;
    }

    #search-exhibitor-list #search .btn-search .bi:before {
        font-weight: bold !important;
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

    #search-exhibitor-list #search .form-select.type-exhibitors {
        font-size: 20px;
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
        border-top-left-radius: 0.75rem;
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

</body>


</html>
