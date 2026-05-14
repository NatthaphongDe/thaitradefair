<div class="loadingoverlay"></div>
<link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
<link href="<?=ROOTPATHDOMAIN?>assets/dist/scrollbar/jquery.scrollbar.css" rel="stylesheet">
<?
$sqlcontent = "select * from tt_fair_content_onepage where fair_id = ? and fct_id = ? and fc_pubish = '1' and fc_status = '1' limit 1 ";
$stmtcontent = $mysqli->prepare($sqlcontent);
$stmtcontent->bind_param('ii',$fair_id,$fct_id);
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


      <div id="company-list" class="mt-5 mt-sm-3 px-0 px-md-4">

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


  <? if($datacontent["fc_url"]!="") { ?>

    <?
    $totalurl = 0;
    $urlall = array();
    if($datacontent["fc_url"]!="") {
      $urlall = explode("|",$datacontent["fc_url"]);
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
                        if($datacontent["fc_url"]!="") {
                          $urlall = explode("|",$datacontent["fc_url"]);
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


  <? if($datacontent["fc_youtube"]!="") { ?>

    <?
    $totalurl = 0;
    $urlall = array();
    if($datacontent["fc_youtube"]!="") {
      $urlall = explode("|",$datacontent["fc_youtube"]);
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
                        if($datacontent["fc_youtube"]!="") {
                          $urlall = explode("|",$datacontent["fc_youtube"]);
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
  $sqlfile = " select * from tt_fair_content_file where file_type = '1' and content_id = ? order by file_id DESC  ";
  $stmtfile = $mysqli->prepare($sqlfile);
  $stmtfile->bind_param('i',$datacontent["fc_id"]);
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


  <?
} else {
?>
<p class="text-center">
  No data available.</p>
<? } ?>

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
@media (min-width: 768px) {
    .img-size{
        width: 70%;
    }
}
@media (min-width: 992px) {
    .img-size{
        width: 50%;
    }
}
</style>
