<?
include_once("backoffice/connect.php");

$fct_id = (int)$_GET["fct_id"];
$fair_id = $_GET["id"];
$sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_id = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start DESC ";
$stmtfair = $mysqli->prepare($sqlfair);
$stmtfair->bind_param('i', $fair_id);
$stmtfair->execute();
$resultfair = $stmtfair->get_result();
$numrowfair = $resultfair->num_rows;
if ($numrowfair > 0) {
  $datafair = $resultfair->fetch_assoc();
} else {
?>
  <script type="text/javascript">
    window.location = '<?= ROOTPATHDOMAIN ?>';
  </script>
<?
  exit();
}

?>

<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair : <?= $datafair["fair_name"] ?> - <?= $datafair["fair_group_name_th"] ?></title>
  <meta name="description" content="<?= $datafair["fair_name"] ?> - <?= $datafair["fair_group_name_th"] ?>">
  <?php include('components/header.php') ?>

  <!-- Custom styles for this template -->
  <link href="<?= ROOTPATHDOMAIN ?>assets/css/carousel.css" rel="stylesheet">
  <link href="<?= ROOTPATHDOMAIN ?>assets/css/fair.css" rel="stylesheet">

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
              <?= $datafair["fair_name"] ?> ,
              <? if (date("m", strtotime($datafair["fair_event_end"])) != date("m", strtotime($datafair["fair_event_start"]))) {
                echo getMonthEng(date("m", strtotime($datafair["fair_event_start"])));
              } else {
                echo getMonthEng(date("m", strtotime($datafair["fair_event_end"])));
              } ?>
              <?php if ($datafair["fair_event_end"] != $datafair["fair_event_start"]) {
                echo date("d", strtotime($datafair["fair_event_start"]));
              ?> -<?php } ?>
                <?php
                if (date("m", strtotime($datafair["fair_event_end"])) != date("m", strtotime($datafair["fair_event_start"]))) {
                  echo getMonthEng(date("m", strtotime($datafair["fair_event_end"])));
                }
                ?> <?php echo date("d", strtotime($datafair["fair_event_end"])) ?> <?= date("Y", strtotime($datafair["fair_event_end"])) ?> /
                <? if (getFairMenuName($fct_id) != "") { ?>
                  <?= getFairMasterMenuName($fct_id) ?>
                  / <span class="active"><?= getFairMenuName($fct_id) ?></span>
                  <? $namemenufair = getFairMenuName($fct_id); ?>
                <? } else { ?>
                  <span class="active"><?= getFairMasterMenuName($fct_id) ?></span>
                  <? $namemenufair = getFairMasterMenuName($fct_id); ?>
                <? } ?>

            </div>

            <div class="col-12 col-md-5">
              <h1 class="title p-0 m-0 text-end text-uppercase"><?= getFairMasterMenuName($fct_id) ?></h1>
            </div>
          </div>

          <div class="row mt-4 px-4 mb-5 _faircontentblog">
            <div class="col-12 mb-2">
              <h2 class="title p-0 m-0"><?= $namemenufair ?></h2>
            </div>
            <div class="col-12 mb-2">
              <?
              $fairtype = getFairMenuType($fct_id);

              if ($fairtype == 1) {
                include("fair-content-onepage.php");
              }

              if ($fairtype == 2) {
                include("fair-content-list.php");
              }

              if ($fairtype == 3) {
                include("fair-content-thumbnail.php");
              }

              if ($fairtype == 4) {
                include("fair-content-gallery.php");
              }

              if ($fairtype == 5) {
                include("fair-content-exhibitor.php");
              }

              ?>
            </div>

          </div>

        </div>
      </div>
    </main>
    <?php include('components/footer.php') ?>
  </div>
  <script src="<?= ROOTPATHDOMAIN ?>assets/js/script.js" type="text/javascript"></script>


</body>


</html>