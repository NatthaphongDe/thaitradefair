<?
include_once ("backoffice/connect.php");
$top_menu_active = "pre-registion";
$yearnow = '2025'; //date("Y");


if($_REQUEST["method"]=="load") {
  $m = (int)$_GET["m"];
  $y = (int)$_GET["y"];
  $o = (int)$_GET["o"];

  if($y>0) {
    $ysearch = " and a.fair_year = '".$y."' ";
  } else {
    $ysearch = " ";
  }

  if($m>0) {
    $mdata = str_pad($m, 2, "0", STR_PAD_LEFT);
    $evt = "%-".$mdata."-%";
    $msearch = " and a.fair_event_start like '".$evt."' ";
  } else {
    $msearch = " ";
  }

  if($o>0) {
    if($o==1) {
      $osearch = " order by a.fair_name ASC ";
    } else {
      if($o==2) {
        $osearch = " order by a.fair_name DESC ";
      } else {
        $osearch = " order by a.fair_event_start DESC ";
      }

    }
  } else {
    $osearch = " order by a.fair_event_start DESC ";
  }


  $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and b.fair_flag = '1' and c.fair_group_status = '1' and a.fair_url_register != '' $ysearch $msearch  $osearch ";
  $stmtfair = $mysqli->prepare($sqlfair);
  $stmtfair->execute();
  $resultfair = $stmtfair->get_result();
  $numrowfair = $resultfair->num_rows;
  if($numrowfair>0) {
    $runno = 0;
    while($datafair = $resultfair->fetch_assoc()) {
      $imgfair = "";
      if($datafair["fair_path_banner"]!="") {
        $imgfair = ROOTPATHDOMAIN.$datafair["fair_path_banner"];
      } else {
        $croppath = str_replace('/main','/resize',$datafair["fair_group_image_path"]);
        $imgfair = ROOTPATHDOMAIN.$croppath;
      }
      ?>
      <div class="col mb-3 px-2 pre-registion-list">
          <div class="bg-img"
              style="background-image: url('<?=$imgfair?>'); cursor: pointer;" onclick="window.location='<?=ROOTPATHDOMAIN?>fair/<?=$datafair["fair_id"]?>/<?=urlencode($datafair["fair_name"])?>/';" >
          </div>
          <?
          $todaydatechk = date("Y-m-d");
          
          if($datafair["fair_url_register"]!="" and $todaydatechk>=$datafair["fair_pre_start"]) { ?>
            <?  if($datafair["fair_pre_end"]<=$todaydatechk) { ?>
              <div class="button-area text-center py-3">
                  <a class="btn btn-bg-blue p-1 px-5 mx-auto">
                  Pre-Register is Closed
                  </a>
              </div>
            <? } else { ?>
              <div class="button-area text-center py-3">
                  <a href="<?=$datafair["fair_url_register"]?>" target="_blank" class="btn btn-bg-blue p-1 px-5 mx-auto">
                      <img src="<?=ROOTPATHDOMAIN?>assets/images/anticon-edit.png" class="icon" /> Pre-Register
                  </a>
              </div>
            <? } ?>
          <? }  elseif($datafair["fair_pre_start"]>=$todaydatechk) { ?>
            <div class="button-area text-center py-3">
                <a class="btn btn-bg-blue p-1 px-5 mx-auto">
                Coming soon
                </a>
            </div>
          <? } ?>
      </div>
  <? } } ?>
  <?



  exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : PRE-REGISTRATION</title>
  <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : PRE-REGISTRATION">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/calendar.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container combined-shape-2 ">
                <?php include('components/header_menu.php'); ?>

                <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner">
                    <div class="col px-0">
                      <div id="pre-registion-title" class="row _homebanner_bgheight">
                          <div class="col-12 align-self-center">
                              <h1 class="title">PRE-REGISTRATION</h1>
                          </div>
                      </div>
                    </div>
                </div>

            </div>
        </header>

        <main>
            <div class="container-fluid">
                <div class="container ">
                  <div class="row my-3 px-0 px-md-5">
                      <div class="col search-area px-3 px-md-0">
                          <select class="form-select selectpicker w-auto float-end bg-green"
                              style="padding-right:2.5rem; margin:0 0 0.5rem 0.5rem;" id="o_search" onchange="loadData();">
                              <option selected value="0">By Date</option>
                              <option value="1">By Name A-Z</option>
                              <option value="2">By Name Z-A</option>
                          </select>
                          <select class="form-select selectpicker w-auto float-end"
                              style="padding-right:2rem; margin:0 0 0.5rem 0.5rem;" id="y_search" onchange="loadData();">
                              <option value="0" >Year</option>
                              <?
                              $sqlfair = " select a.fair_year from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and b.fair_flag = '1' and c.fair_group_status = '1' group by a.fair_year order by a.fair_year DESC ";
                              $stmtfair = $mysqli->prepare($sqlfair);
                              $stmtfair->execute();
                              $resultfair = $stmtfair->get_result();
                              $numrowfair = $resultfair->num_rows;
                              if($numrowfair>0) {
                                $runno = 0;
                                while($datafair = $resultfair->fetch_assoc()) {
                              ?>
                                <option value="<?=$datafair["fair_year"]?>" <? if($yearnow==$datafair["fair_year"]) { echo "selected"; } ?> ><?=$datafair["fair_year"]?></option>
                              <? } } ?>
                          </select>
                          <? $arr_m = array("","January","February","March","April","May","June","July","August","September","
                          October","November","December"); ?>
                          <select class="form-select selectpicker w-auto float-end" id="m_search" style="padding-right:2rem;" onchange="loadData();">
                              <option selected value="0">Month</option>
                              <? for($m=1;$m<count($arr_m);$m++) { ?>
                                <option value="<?=$m?>"><?=$arr_m[$m]?></option>
                              <? } ?>
                          </select>
                      </div>
                  </div>

                    <div class="row row-cols-1 row-cols-lg-2 my-3 px-0 px-md-5 mt-4 mb-5 _datashow">
                      <?
                      $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' and a.fair_url_register != '' order by fair_event_start DESC ";
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
                            $imgfair = ROOTPATHDOMAIN.$datafair["fair_path_banner"];
                          } else {
                            $croppath = str_replace('/main','/resize',$datafair["fair_group_image_path"]);
                            $imgfair = ROOTPATHDOMAIN.$croppath;
                          }
                          ?>
                        <div class="col mb-3 px-2 pre-registion-list">
                            <div class="bg-img"
                                style="background-image: url('<?=$imgfair?>'); cursor: pointer;" onclick="window.location='<?=ROOTPATHDOMAIN?>fair/<?=$datafair["fair_id"]?>/<?=urlencode($datafair["fair_name"])?>/';" >
                            </div>

                            <?
                            $todaydatechk = date("Y-m-d");
                            if($datafair["fair_url_register"]!="" and $todaydatechk>=$datafair["fair_pre_start"]) { ?>
                              <?  if($datafair["fair_pre_end"]<=$todaydatechk) { ?>
                                <div class="button-area text-center py-3">
                                    <a class="btn btn-bg-blue p-1 px-5 mx-auto">
                                        Pre-Register is Closed
                                    </a>
                                </div>
                              <? } else { ?>
                                <div class="button-area text-center py-3">
                                    <a href="<?=$datafair["fair_url_register"]?>" target="_blank" class="btn btn-bg-blue p-1 px-5 mx-auto">
                                        <img src="<?=ROOTPATHDOMAIN?>assets/images/anticon-edit.png" class="icon" /> Pre-Register
                                    </a>
                                </div>
                              <? } ?>
                            <? } elseif($datafair["fair_pre_start"]>=$todaydatechk) { ?>
                                <div class="button-area text-center py-3">
                                    <a class="btn btn-bg-blue p-1 px-5 mx-auto">
                                    Coming soon
                                    </a>
                                </div>
                              <? } ?> 




                        </div>
                      <? } } ?>


                    </div>
                </div>
            </div>

        </main>
        <?php include('components/footer.php') ?>
    </div>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>


    <script type="text/javascript">
      function loadData() {
        var m = $('#m_search').val();
        var y = $('#y_search').val();
        var o = $('#o_search').val();

        $.ajax({
            type: "GET",
            url: "<?=ROOTPATHDOMAIN?>/pre-registion.php?method=load&m="+m+"&y="+y+"&o="+o,
            dataType: "text",
            success : function(data) {
              $('._datashow').empty();
              $("._datashow").html(data);
            }
        });


      }
    </script>

    <style>
    .combined-shape {
        height: auto;
    }

    #pre-registion-title {
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

        #pre-registion-title .title {
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

    .pre-registion-list .bg-img {
        border-radius: 15px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        border-radius: 15px;
        min-height: 280px;
    }

    .pre-registion-list .btn-bg-blue {
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

    .pre-registion-list .btn-bg-blue .icon {
        width: 14px;
        margin-right: 0.5rem;
    }
    </style>
</body>


</html>
