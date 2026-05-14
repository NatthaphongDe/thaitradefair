<?
include_once ("backoffice/connect.php");

$why_id = $_GET["id"];
$sqlwhycontent ="select * from tt_why_content where why_status = '1' and  why_id = ? ";
$stmtwhycontent = $mysqli->prepare($sqlwhycontent);
$stmtwhycontent->bind_param('i',$why_id);
$stmtwhycontent->execute();
$resultwhycontent = $stmtwhycontent->get_result();
$numrowwhycontent = $resultwhycontent->num_rows;
if($numrowwhycontent>0) {
  $datawhycontent = $resultwhycontent->fetch_assoc();
  $imgwhy_a = ROOTPATHDOMAIN.$datawhycontent["why_logo_act_path"];
  $imgwhy_n = ROOTPATHDOMAIN.$datawhycontent["why_logo_null_path"];
  $imgwhy_banner = ROOTPATHDOMAIN.$datawhycontent["why_banner_path"];

  $contentdata = str_replace('..//data','../data',$datawhycontent["why_detail"]);
  $contentdata = str_replace('../data',ROOTPATHDOMAIN.'data',$contentdata);
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
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?></title>
  <meta name="description" content="<?php echo $datawhycontent["why_title"]?>">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/calendar.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/what-industry.css" rel="stylesheet">
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
                          <div id="what-industry-title" class="row _homebanner_bgheight" style="background:url('<?=$imgwhy_banner?>');">
                            <div class="col-12 align-self-center">
                                <h1 class="title"><?php echo $datawhycontent["why_title"]?></h1>
                                <h2 class="sub-title">INDUSTRY</h2>
                            </div>
                          </div>
                        </div>
                    </div>

                </div>
            </div>
        </header>


        <main>
            <div class="container-fluid">
                <div class="container">
                    <?php include('components/top_menu_industry.php'); ?>

                    <div class="row mt-4 mb-3 px-4 event-type _faircontentblog ">
                        <div class="col-12">
                            <span class="event-name h-100 float-start pt-0"><?php echo $datawhycontent["why_title"]?></span>
                        </div>
                    </div>

                    <div class="row mt-4 mb-5 px-4 content-cms _faircontentblogwhicht">
                      <?=$contentdata?>
                    </div>

                </div>
            </div>
        </main>
        <?php include('components/footer.php') ?>
    </div>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>


</body>


</html>
