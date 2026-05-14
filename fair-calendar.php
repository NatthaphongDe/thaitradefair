<?
include_once("backoffice/connect.php");
$top_menu_active = "fair-calendar";


$yearnow = (int)$_GET["year"];
if ($yearnow <= 0) {
  $yearnow = date("Y");
}

$month = (int)$_GET["month"];

if ($_REQUEST["method"] == "load") {
  $m = (int)$_GET["m"];
  $y = (int)$_GET["y"];
  $o = (int)$_GET["o"];

  if ($y > 0) {
    $ysearch = " and a.fair_year = ? ";
  } else {
    $ysearch = " ";
  }

  if ($m > 0) {
    $mdata = str_pad($m, 2, "0", STR_PAD_LEFT);
    $evt = "%-" . $mdata . "-%";
    $msearch = " and a.fair_event_start like ? ";
  } else {
    $msearch = " ";
  }

  if ($o > 0) {
    if ($o == 1) {
      $osearch = " order by a.fair_name ASC ";
    } else {
      if ($o == 2) {
        $osearch = " order by a.fair_name DESC ";
      } else {
        if ($y >= date("Y")) {
          $osearch = " order by a.fair_event_start ASC ";
        } else {
          $osearch = " order by a.fair_event_start DESC ";
        }
      }
    }
  } else {
    if ($y >= date("Y")) {
      $osearch = " order by a.fair_event_start ASC ";
    } else {
      $osearch = " order by a.fair_event_start DESC ";
    }
  }



  $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and b.fair_flag = '1' and c.fair_group_status = '1' $ysearch $msearch  $osearch ";
  $stmtfair = $mysqli->prepare($sqlfair);
  if ($y > 0 && $m > 0) {
    $stmtfair->bind_param('is', $y, $evt);
  } elseif ($y > 0) {
      $stmtfair->bind_param('i', $y);
  } elseif ($m > 0) {
      $stmtfair->bind_param('s', $evt);
  }
  $stmtfair->execute();
  $resultfair = $stmtfair->get_result();
  $numrowfair = $resultfair->num_rows;
  if ($numrowfair > 0) {
    $runno = 0;
    while ($datafair = $resultfair->fetch_assoc()) {
      $imgfair = "";
      if ($datafair["fair_path_banner"] != "") {
        $imgfair = ROOTPATHDOMAIN . $datafair["fair_path_banner"];
      } else {
        $croppath = str_replace('/main', '/resize', $datafair["fair_group_image_path"]);
        $imgfair = ROOTPATHDOMAIN . $croppath;
      }
?>
      <div class="row fair-calendar-list mb-3">
        <div class="col-12 col-sm-6 bg-img" style="display: flex;align-items: center;justify-content: center;flex-direction: column;padding: 0;background: radial-gradient(circle, rgba(255,255,255,1) 0%, rgb(149 143 143) 80%); cursor: pointer;" onclick="window.location='<?= ROOTPATHDOMAIN ?>fair/<?= $datafair["fair_id"] ?>/<?= urlencode($datafair["fair_name"]) ?>/';">
          <img class="img_cover" style="width: -webkit-fill-available;" src="<?= $imgfair ?>" alt="">
        </div>
        <div class="col-12 col-sm-6 p-3">
          <a href="<?= ROOTPATHDOMAIN ?>fair/<?= $datafair["fair_id"] ?>/<?= urlencode($datafair["fair_name"]) ?>/">
            <h3 class="title"><?= $datafair["fair_name"] ?></h3>
          </a>

          <div class="d-block p">
            <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-calendar.png" class="icon-p" /><b>Trade:</b>
            <?php
            if (date("m", strtotime($datafair["fair_trade_end"])) != date("m", strtotime($datafair["fair_trade_start"]))) {
              echo getMonthEng(date("m", strtotime($datafair["fair_trade_start"])));
            } else {
              echo getMonthEng(date("m", strtotime($datafair["fair_trade_end"])));
            }
            ?>
            <?php if ($datafair["fair_trade_end"] != $datafair["fair_trade_start"]) {
              echo date("d", strtotime($datafair["fair_trade_start"]));
            ?> -<?php } ?>
              <?php
              if (date("m", strtotime($datafair["fair_trade_end"])) != date("m", strtotime($datafair["fair_trade_start"]))) {
                echo getMonthEng(date("m", strtotime($datafair["fair_trade_end"])));
              }
              ?> <?php echo date("d", strtotime($datafair["fair_trade_end"])) ?>, <?= date("Y", strtotime($datafair["fair_trade_end"])) ?>
          </div>
          <div class="d-block p">
            <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-calendar.png" class="icon-p" /><b>Public:</b>
            <?php
            if (date("m", strtotime($datafair["fair_public_end"])) != date("m", strtotime($datafair["fair_public_start"]))) {
              echo getMonthEng(date("m", strtotime($datafair["fair_public_start"])));
            } else {
              echo getMonthEng(date("m", strtotime($datafair["fair_public_end"])));
            }
            ?>
            <?php if ($datafair["fair_public_end"] != $datafair["fair_public_start"]) {
              echo date("d", strtotime($datafair["fair_public_start"]));
            ?> -<?php } ?>
              <?php
              if (date("m", strtotime($datafair["fair_public_end"])) != date("m", strtotime($datafair["fair_public_start"]))) {
                echo getMonthEng(date("m", strtotime($datafair["fair_public_end"])));
              }
              ?> <?php echo date("d", strtotime($datafair["fair_public_end"])) ?>, <?= date("Y", strtotime($datafair["fair_public_end"])) ?>
          </div>
          <div class="d-block p">
            <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-pin-drop-material.png" class="icon-p" /><b>Venue:</b>
            <?= $datafair["fair_vanue"] ?>
          </div>
          <div class="row row-cols-1 row-cols-lg-2  mt-3">

            <?
            $hotel = getFairHotel($datafair["fair_id"]);
            if ($hotel > 0) { ?>
              <div class="col mb-2 mb-lg-0">
                <a href="<?= ROOTPATHDOMAIN ?>fair-content/<?= $datafair["fair_id"] ?>/<?= urlencode($datafair["fair_name"]) ?>/<?= $hotel ?>/<?= urlencode("Official Hotel") ?>/" class="btn btn-bg-blue w-100 p-1">
                  <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-hotel-material.png" class="icon" /> Fair
                  Hotel
                </a>
              </div>
            <? } ?>

            <?
            $todaydatechk = date("Y-m-d");
            if ($datafair["fair_url_register"] != "" and $todaydatechk >= $datafair["fair_pre_start"]) { ?>
              <? if ($datafair["fair_pre_end"] <= $todaydatechk) { ?>
                <div class="col">
                  <a class="btn btn-bg-blue  w-100 p-1">
                    Pre-Register is Closed
                  </a>
                </div>
              <? } else { ?>
                <div class="col">
                  <a href="<?= $datafair["fair_url_register"] ?>" target="_blank" class="btn btn-bg-blue  w-100 p-1">
                    <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-edit.png" class="icon" /> Pre-Register
                  </a>
                </div>

              <? } ?>
            <? } else if($datafair["fair_url_register"] != "" and $todaydatechk <= $datafair["fair_pre_start"]){?>
                <div class="col">
                  <a class="btn btn-bg-blue  w-100 p-1">
                    Coming soon
                  </a>
                </div>
            <? }?>
          </div>
        </div>
      </div>
  <? }
  } ?>
<?



  exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair | Thailand Exhibition Calendar <?= date("Y") ?> - Bangkok Show <?= date("Y") ?> - Thai Trade Fair <?= date("Y") ?> : Fair Calendar</title>
  <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?= date("Y") ?> - Bangkok Show <?= date("Y") ?> - Thai Trade Fair <?= date("Y") ?> : Fair Calendar">
  <?php include('components/header.php') ?>

  <!-- Custom styles for this template -->
  <link href="<?= ROOTPATHDOMAIN ?>assets/css/carousel.css" rel="stylesheet">
  <link href="<?= ROOTPATHDOMAIN ?>assets/css/calendar.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
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
                  <h1 class="title">FAIR CALENDAR</h1>
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
          <div class="row my-3 px-0 px-md-5">
            <div class="col search-area px-3 px-md-0">
              <select class="form-select selectpicker w-auto float-end bg-green" style="padding-right:2.5rem; margin:0 0 0.5rem 0.5rem;" id="o_search" onchange="loadData();">
                <option selected value="0">By Date</option>
                <option value="1">By Name A-Z</option>
                <option value="2">By Name Z-A</option>
              </select>
              <select class="form-select selectpicker w-auto float-end bg-greenx1" style="padding-right:2rem; margin:0 0 0.5rem 0.5rem;" id="y_search" onchange="loadData();">
                <option value="0">Year</option>
                <?
                $sqlfair = " select a.fair_year from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and b.fair_flag = '1' and c.fair_group_status = '1' group by a.fair_year order by a.fair_year DESC ";
                $stmtfair = $mysqli->prepare($sqlfair);
                $stmtfair->execute();
                $resultfair = $stmtfair->get_result();
                $numrowfair = $resultfair->num_rows;
                if ($numrowfair > 0) {
                  $runno = 0;
                  while ($datafair = $resultfair->fetch_assoc()) {
                ?>
                    <option value="<?= $datafair["fair_year"] ?>" <? if ($yearnow == $datafair["fair_year"]) {
                                                                  echo "selected";
                                                                } ?>><?= $datafair["fair_year"] ?></option>
                <? }
                } ?>
              </select>
              <? $arr_m = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "
                            October", "November", "December"); ?>
              <select class="form-select selectpicker w-auto float-end bg-greenx2" id="m_search" style="padding-right:2rem;" onchange="loadData();">
                <option selected value="0">Month</option>
                <? for ($m = 1; $m < count($arr_m); $m++) { ?>
                  <option value="<?= $m ?>" <? if ($month == $m) {
                                            echo "selected";
                                          } ?>><?= $arr_m[$m] ?></option>
                <? } ?>
              </select>
            </div>
          </div>

          <div class="row my-3 px-0 px-md-5 mb-5">
            <div class="col px-4 px-md-0 _datashow">
              <?

              $ysearch = " and a.fair_year = ? ";
              if ($month > 0) {
                $mdata = str_pad($month, 2, "0", STR_PAD_LEFT);
                $evt = "%-" . $mdata . "-%";
                $msearch = " and a.fair_event_start like ? ";
              } else {
                $msearch = " ";
              }

              if ($yearnow >= date("Y")) {
                $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and  b.fair_flag = '1' and c.fair_group_status = '1' $ysearch $msearch and a.fair_pre_end >= CURRENT_DATE order by fair_event_start ASC ";
              } else {
                $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and  b.fair_flag = '1' and c.fair_group_status = '1' $ysearch $msearch and a.fair_pre_end >= CURRENT_DATE order by fair_event_start DESC ";
              }
              $stmtfair = $mysqli->prepare($sqlfair);
              if ($month > 0) {
                $stmtfair->bind_param('is', $yearnow, $evt);
              } else {
                $stmtfair->bind_param('i', $yearnow);
              }
            
              $stmtfair->execute();
              $resultfair = $stmtfair->get_result();
              $numrowfair = $resultfair->num_rows;
              if ($numrowfair > 0) {
                $runno = 0;
                while ($datafair = $resultfair->fetch_assoc()) {
                  $imgfair = "";
                  if ($datafair["fair_path_banner"] != "") {
                    $imgfair = ROOTPATHDOMAIN . $datafair["fair_path_banner"];
                  } else {
                    $croppath = str_replace('/main', '/resize', $datafair["fair_group_image_path"]);
                    $imgfair = ROOTPATHDOMAIN . $croppath;
                  }
              ?>
                  <div class="row fair-calendar-list mb-3">
                    <div class="col-12 col-sm-6 bg-img" style="display: flex;align-items: center;justify-content: center;flex-direction: column;padding: 0;background: radial-gradient(circle, rgba(255,255,255,1) 0%, rgb(149 143 143) 80%); cursor: pointer;" onclick="window.location='<?= ROOTPATHDOMAIN ?>fair/<?= $datafair["fair_id"] ?>/<?= urlencode($datafair["fair_name"]) ?>/';">
                      <img class="img_cover" style="width: -webkit-fill-available;" src="<?= $imgfair ?>" alt="">
                    </div>
                    <div class="col-12 col-sm-6 p-3">
                      <a href="<?= ROOTPATHDOMAIN ?>fair/<?= $datafair["fair_id"] ?>/<?= urlencode($datafair["fair_name"]) ?>/">
                        <h3 class="title"><?= $datafair["fair_name"] ?></h3>
                      </a>

                      <div class="d-block p">
                        <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-calendar.png" class="icon-p" /><b>Trade:</b>
                        <?php
                        if (date("m", strtotime($datafair["fair_trade_end"])) != date("m", strtotime($datafair["fair_trade_start"]))) {
                          echo getMonthEng(date("m", strtotime($datafair["fair_trade_start"])));
                        } else {
                          echo getMonthEng(date("m", strtotime($datafair["fair_trade_end"])));
                        }
                        ?>
                        <?php if ($datafair["fair_trade_end"] != $datafair["fair_trade_start"]) {
                          echo date("d", strtotime($datafair["fair_trade_start"]));
                        ?> -<?php } ?>
                          <?php
                          if (date("m", strtotime($datafair["fair_trade_end"])) != date("m", strtotime($datafair["fair_trade_start"]))) {
                            echo getMonthEng(date("m", strtotime($datafair["fair_trade_end"])));
                          }
                          ?> <?php echo date("d", strtotime($datafair["fair_trade_end"])) ?>, <?= date("Y", strtotime($datafair["fair_trade_end"])) ?>
                      </div>
                      <div class="d-block p">
                        <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-calendar.png" class="icon-p" /><b>Public:</b>
                        <?php
                        if (date("m", strtotime($datafair["fair_public_end"])) != date("m", strtotime($datafair["fair_public_start"]))) {
                          echo getMonthEng(date("m", strtotime($datafair["fair_public_start"])));
                        } else {
                          echo getMonthEng(date("m", strtotime($datafair["fair_public_end"])));
                        }
                        ?>
                        <?php if ($datafair["fair_public_end"] != $datafair["fair_public_start"]) {
                          echo date("d", strtotime($datafair["fair_public_start"]));
                        ?> -<?php } ?>
                          <?php
                          if (date("m", strtotime($datafair["fair_public_end"])) != date("m", strtotime($datafair["fair_public_start"]))) {
                            echo getMonthEng(date("m", strtotime($datafair["fair_public_end"])));
                          }
                          ?> <?php echo date("d", strtotime($datafair["fair_public_end"])) ?>, <?= date("Y", strtotime($datafair["fair_public_end"])) ?>
                      </div>
                      <div class="d-block p">
                        <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-pin-drop-material.png" class="icon-p" /><b>Venue:</b>
                        <?= $datafair["fair_vanue"] ?>
                      </div>
                      <div class="row row-cols-1 row-cols-lg-2  mt-3">

                        <?
                        $hotel = getFairHotel($datafair["fair_id"]);
                        if ($hotel > 0) { ?>
                          <div class="col mb-2 mb-lg-0">
                            <a href="<?= ROOTPATHDOMAIN ?>fair-content/<?= $datafair["fair_id"] ?>/<?= urlencode($datafair["fair_name"]) ?>/<?= $hotel ?>/<?= urlencode("Official Hotel") ?>/" class="btn btn-bg-blue w-100 p-1">
                              <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-hotel-material.png" class="icon" /> Fair
                              Hotel
                            </a>
                          </div>
                        <? } ?>

                        <?
                        $todaydatechk = date("Y-m-d");
                        if ($datafair["fair_url_register"] != "" and $todaydatechk >= $datafair["fair_pre_start"]) { ?>
                          <? if ($datafair["fair_pre_end"] <= $todaydatechk) { ?>
                            <div class="col">
                              <a class="btn btn-bg-blue  w-100 p-1">
                                Pre-Register is Closed
                              </a>
                            </div>
                          <? } else { ?>
                            <div class="col">
                              <a href="<?= $datafair["fair_url_register"] ?>" target="_blank" class="btn btn-bg-blue  w-100 p-1">
                                <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-edit.png" class="icon" /> Pre-Register
                              </a>
                            </div>

                          <? } ?>
                        <? } else if($datafair["fair_url_register"] != "" and $todaydatechk <= $datafair["fair_pre_start"]){?>
                            <div class="col">
                              <a class="btn btn-bg-blue  w-100 p-1">
                                Coming soon
                              </a>
                            </div>
                        <? }?>
                      </div>
                    </div>
                  </div>
              <? }
              } ?>

              <?
              if ($yearnow >= date("Y")) {
                $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and  b.fair_flag = '1' and c.fair_group_status = '1' $ysearch $msearch  and a.fair_pre_end < CURRENT_DATE order by fair_event_start ASC ";
              } else {
                $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and  b.fair_flag = '1' and c.fair_group_status = '1' $ysearch $msearch and a.fair_pre_end < CURRENT_DATE order by fair_event_start DESC ";
              }

              $stmtfair = $mysqli->prepare($sqlfair);
              if ($month > 0) {
                $stmtfair->bind_param('is', $yearnow, $evt);
              } else {
                $stmtfair->bind_param('i', $yearnow);
              }
              $stmtfair->execute();
              $resultfair = $stmtfair->get_result();
              $numrowfair = $resultfair->num_rows;
              if ($numrowfair > 0) {
                $runno = 0;
                while ($datafair = $resultfair->fetch_assoc()) {
                  $imgfair = "";
                  if ($datafair["fair_path_banner"] != "") {
                    $imgfair = ROOTPATHDOMAIN . $datafair["fair_path_banner"];
                  } else {
                    $croppath = str_replace('/main', '/resize', $datafair["fair_group_image_path"]);
                    $imgfair = ROOTPATHDOMAIN . $croppath;
                  }
              ?>
                  <div class="row fair-calendar-list mb-3">
                    <div class="col-12 col-sm-6 bg-img" style="display: flex;align-items: center;justify-content: center;flex-direction: column;padding: 0;background: radial-gradient(circle, rgba(255,255,255,1) 0%, rgb(149 143 143) 80%); cursor: pointer;" onclick="window.location='<?= ROOTPATHDOMAIN ?>fair/<?= $datafair["fair_id"] ?>/<?= urlencode($datafair["fair_name"]) ?>/';">
                      <img class="img_cover" style="width: -webkit-fill-available;" src="<?= $imgfair ?>" alt="">
                    </div>
                    <div class="col-12 col-sm-6 p-3">
                      <a href="<?= ROOTPATHDOMAIN ?>fair/<?= $datafair["fair_id"] ?>/<?= urlencode($datafair["fair_name"]) ?>/">
                        <h3 class="title"><?= $datafair["fair_name"] ?></h3>
                      </a>

                      <div class="d-block p">
                        <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-calendar.png" class="icon-p" /><b>Trade:</b>
                        <?php
                        if (date("m", strtotime($datafair["fair_trade_end"])) != date("m", strtotime($datafair["fair_trade_start"]))) {
                          echo getMonthEng(date("m", strtotime($datafair["fair_trade_start"])));
                        } else {
                          echo getMonthEng(date("m", strtotime($datafair["fair_trade_end"])));
                        }
                        ?>
                        <?php if ($datafair["fair_trade_end"] != $datafair["fair_trade_start"]) {
                          echo date("d", strtotime($datafair["fair_trade_start"]));
                        ?> -<?php } ?>
                          <?php
                          if (date("m", strtotime($datafair["fair_trade_end"])) != date("m", strtotime($datafair["fair_trade_start"]))) {
                            echo getMonthEng(date("m", strtotime($datafair["fair_trade_end"])));
                          }
                          ?> <?php echo date("d", strtotime($datafair["fair_trade_end"])) ?>, <?= date("Y", strtotime($datafair["fair_trade_end"])) ?>
                      </div>
                      <div class="d-block p">
                        <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-calendar.png" class="icon-p" /><b>Public:</b>
                        <?php
                        if (date("m", strtotime($datafair["fair_public_end"])) != date("m", strtotime($datafair["fair_public_start"]))) {
                          echo getMonthEng(date("m", strtotime($datafair["fair_public_start"])));
                        } else {
                          echo getMonthEng(date("m", strtotime($datafair["fair_public_end"])));
                        }
                        ?>
                        <?php if ($datafair["fair_public_end"] != $datafair["fair_public_start"]) {
                          echo date("d", strtotime($datafair["fair_public_start"]));
                        ?> -<?php } ?>
                          <?php
                          if (date("m", strtotime($datafair["fair_public_end"])) != date("m", strtotime($datafair["fair_public_start"]))) {
                            echo getMonthEng(date("m", strtotime($datafair["fair_public_end"])));
                          }
                          ?> <?php echo date("d", strtotime($datafair["fair_public_end"])) ?>, <?= date("Y", strtotime($datafair["fair_public_end"])) ?>
                      </div>
                      <div class="d-block p">
                        <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-pin-drop-material.png" class="icon-p" /><b>Venue:</b>
                        <?= $datafair["fair_vanue"] ?>
                      </div>
                      <div class="row row-cols-1 row-cols-lg-2  mt-3">

                        <?
                        $hotel = getFairHotel($datafair["fair_id"]);
                        if ($hotel > 0) { ?>
                          <div class="col mb-2 mb-lg-0">
                            <a href="<?= ROOTPATHDOMAIN ?>fair-content/<?= $datafair["fair_id"] ?>/<?= urlencode($datafair["fair_name"]) ?>/<?= $hotel ?>/<?= urlencode("Official Hotel") ?>/" class="btn btn-bg-blue w-100 p-1">
                              <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-hotel-material.png" class="icon" /> Fair
                              Hotel
                            </a>
                          </div>
                        <? } ?>

                        <?
                        $todaydatechk = date("Y-m-d");
                        if ($datafair["fair_url_register"] != "" and $todaydatechk >= $datafair["fair_pre_start"]) { ?>
                          <? if ($datafair["fair_pre_end"] <= $todaydatechk) { ?>
                            <div class="col">
                              <a class="btn btn-bg-blue  w-100 p-1">
                                Pre-Register is Closed
                              </a>
                            </div>
                          <? } else { ?>
                            <div class="col">
                              <a href="<?= $datafair["fair_url_register"] ?>" target="_blank" class="btn btn-bg-blue  w-100 p-1">
                                <img src="<?= ROOTPATHDOMAIN ?>assets/images/anticon-edit.png" class="icon" /> Pre-Register
                              </a>
                            </div>

                          <? } ?>
                        <? } ?>
                      </div>
                    </div>
                  </div>
              <? }
              } ?>


            </div>
          </div>
        </div>
      </div>

    </main>
    <?php include('components/footer.php') ?>
  </div>
  <script src="<?= ROOTPATHDOMAIN ?>assets/js/script.js" type="text/javascript"></script>

  <script type="text/javascript">
    function loadData() {
      var m = $('#m_search').val();
      var y = $('#y_search').val();
      var o = $('#o_search').val();

      $.ajax({
        type: "GET",
        url: "<?= ROOTPATHDOMAIN ?>/fair-calendar.php?method=load&m=" + m + "&y=" + y + "&o=" + o,
        dataType: "text",
        success: function(data) {
          $('._datashow').empty();
          $("._datashow").html(data);
        }
      });


    }
  </script>


  <style>
    @media (min-width: 1200px) {
      .img_cover {
        border-top-left-radius: 30px;
        border-bottom-left-radius: 30px;
      }
    }

    @media (max-width: 1199px) {
      .img_cover {
        max-height: 200px;
      }

    }

    .combined-shape {
      height: auto;
    }

    #fair-calendar-title {
      border-radius: 15px;
      background-blend-mode: multiply;
      border-radius: 0.75rem;
      box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.28);
      background-blend-mode: multiply, normal;
      background: url('<?= ROOTPATHDOMAIN ?>assets/images/bg-search-exhibit-export.png') no-repeat center center;
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

      .img_cover {
        max-height: 210px;
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
  </style>
</body>


</html>