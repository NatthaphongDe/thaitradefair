<?
include_once("backoffice/connect.php");

$top_menu_active = "about-fair";
$kw = $_POST["kw"];
$kw = htmlspecialchars($kw, ENT_QUOTES, 'UTF-8');
if (!empty($kw)) {
  if ($_SESSION['csrf_token'] != $_POST["csrf_token"]) { ?>
    <script type="text/javascript">
      setTimeout(function() {
        top.alertToken();
      }, 1000);
    </script>
<? exit();
  }
} ?>

<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair | Thailand Exhibition Calendar <?= date("Y") ?> - Bangkok Show <?= date("Y") ?> - Thai Trade Fair <?= date("Y") ?> : Galleries</title>
  <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?= date("Y") ?> - Bangkok Show <?= date("Y") ?> - Thai Trade Fair <?= date("Y") ?> : Galleries">
  <?php include('components/header.php') ?>

  <!-- Custom styles for this template -->
  <link href="<?= ROOTPATHDOMAIN ?>assets/css/carousel.css" rel="stylesheet">
  <link href="<?= ROOTPATHDOMAIN ?>assets/css/calendar.css" rel="stylesheet">
  <link href="<?= ROOTPATHDOMAIN ?>assets/css/all-news.css" rel="stylesheet">


</head>
<?php ?>

<body class="d-flex flex-column h-100">
  <div id="vue-app" class="p-0 bg-header-1">
    <?php include('components/external_menu.php'); ?>
    <header class="d-flex flex-wrap justify-content-center ">

      <div class="container-fluid">
        <div class="container combined-shape-2 ">
          <?php include('components/header_menu.php'); ?>
          <?php include('components/top_slide_cover.php'); ?>
          <!-- <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner" id="fair-calendar">
                        <div class="col _homebannercol">
                            <div id="allNewsCarousel" class="carousel slide m-0" data-bs-ride="carousel">
                                <div class="carousel-inner allnewsbanner">

                                  <?
                                  $runimg = 0;
                                  $sqlnewsg = "select * from tt_fair_group where fair_group_status = 1 order by fair_group_name_th ASC ";

                                  $stmtnewsg = $mysqli->prepare($sqlnewsg);
                                  $stmtnewsg->execute();
                                  $resultnewsg = $stmtnewsg->get_result();
                                  $numrownewsg = $resultnewsg->num_rows;
                                  if ($numrownewsg > 0) {
                                    while ($datanewsg = $resultnewsg->fetch_assoc()) {

                                      $fgimg = "";
                                      $croppath = str_replace('logo/', 'logo/crop/', $datanewsg["fair_group_logo_path"]);
                                      $fgimg = ROOTPATHDOMAIN . $croppath;


                                      $sqlnews = "select * from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and e.fair_group_id = ?  and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 group by a.fcg_id order by RAND() limit 1 ";
                                      $stmtnews = $mysqli->prepare($sqlnews);
                                      $stmtnews->bind_param('i', $datanewsg["fair_group_id"]);
                                      $stmtnews->execute();
                                      $resultnews = $stmtnews->get_result();
                                      $numrownews = $resultnews->num_rows;
                                      if ($numrownews > 0) {
                                        while ($datanews = $resultnews->fetch_assoc()) {
                                          $fgimg = "";
                                          $croppath = str_replace('logo/', 'logo/crop/', $datanews["fair_group_logo_path"]);
                                          $fgimg = ROOTPATHDOMAIN . $croppath;

                                          $newsimg = "";
                                          $croppath = str_replace('cover/', 'cover/resize/', $datanews["gall_file_path"]);
                                          $newsimg = ROOTPATHDOMAIN . $croppath;


                                  ?>
                                          <div class="carousel-item gallbanner_bg <? if ($runimg == 0) {
                                                                                    echo "active";
                                                                                  } ?> " style="background-image: url('<?= $newsimg ?>');">
                                              /* <img class="bd-placeholder-img" src="<?= $newsimg ?>" /> */
                                          </div>
                                          <?
                                          $runimg++;
                                        }
                                      }
                                    }
                                  }
                                          ?>

                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#allNewsCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon">
                                        <i class="bi bi-chevron-left text-dark"></i>
                                    </span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#allNewsCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon">
                                        <i class="bi bi-chevron-right text-dark"></i>
                                    </span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div> -->
        </div>
      </div>
    </header>

    <br>
    <main>
      <div class="container-fluid">
        <div class="container">
          <div class="row mb-3 px-4 pt-md-0 _faircontentblog">
            <div class="col-12 col-md-7">
              <h2 class="title p-0 m-0">GALLERY </h2>
            </div>
            <div class="col-12 col-md-5">
              <form method="post" action="<?= ROOTPATHDOMAIN ?>all-gallery/">
                
                <div id="search" class="input-group mt-3">
                  <select type="text" class="form-select type-exhibitors w-20 _eenews" onchange="changeMore(this.value)">
                    <option value="">All Event</option>
                    <?php
                    $sqlnewsg = "select * from tt_fair_group where fair_group_status = 1 order by fair_group_name_th ASC ";

                    $stmtnewsg = $mysqli->prepare($sqlnewsg);
                    $stmtnewsg->execute();
                    $resultnewsg = $stmtnewsg->get_result();
                    $numrownewsg = $resultnewsg->num_rows;
                    if ($numrownewsg > 0) {
                      while ($datanewsg = $resultnewsg->fetch_assoc()) {

                        $fgimg = "";
                        $croppath = str_replace('logo/', 'logo/crop/', $datanewsg["fair_group_logo_path"]);
                        $fgimg = ROOTPATHDOMAIN . $croppath;


                        $sqlnews = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 and d.fair_group_id = ? order by a.fca_create_date DESC limit 3 ";
                        $stmtnews = $mysqli->prepare($sqlnews);
                        $stmtnews->bind_param('i', $datanewsg["fair_group_id"]);
                        $stmtnews->execute();
                        $resultnews = $stmtnews->get_result();
                        $numrownews = $resultnews->num_rows;
                        if ($numrownews > 0) {
                    ?>

                          <option value="<?= $datanewsg["fair_group_id"] ?>"><?= $datanewsg["fair_group_name_th"] ?></option>
                    <?php }
                      }
                    } ?>
                  </select>
                  <input type="text" class="form-control w-25" placeholder="Search" name="kw" value="<?= $kw ?>">
                  <input type="text" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" id="csrf_token" hidden>
                  <button class="btn btn-search rounded-end text-center pt-1" type="submit">
                    <i class="bi bi-search text-white "></i>
                  </button>

                </div>
                
              </form>
            </div>
          </div>

          <?php
          $sqlhy = "select e.* from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 GROUP by e.fair_group_id order by e.fair_group_name_th ASC ";
          
          $stmthy = $mysqli->prepare($sqlhy);
          $stmthy->execute();
          $resulthy = $stmthy->get_result();
          $numrowhy = $resulthy->num_rows;
          if ($numrowhy > 0) {
            while ($datahy = $resulthy->fetch_assoc()) {
              $fgimg = "";
              $croppath = str_replace('logo/', 'logo/crop/', $datahy["fair_group_logo_path"]);
              $fgimg = ROOTPATHDOMAIN . $croppath;
          ?>
              <div class="row mb-3 px-4 event-type _faircontentblog">

                <div class="d-block d-md-none col-12 col-md-3 view-more pt-2 text-end">
                  <a href="<?= ROOTPATHDOMAIN ?>all-gallery-group/<?= $datahy["fair_group_id"] ?>/<?= urlencode($datahy["fair_group_name_th"]) ?>/">
                    <i class="bi bi-arrow-right-short"></i>
                    <span>View More</span>
                  </a>
                </div>

                <div class="col-12 col-md-9">
                  <div class="row event-area align-items-center">
                    <div class="col-12 pt-1">
                      <img class="float-start" src="<?= $fgimg ?>" style="border-radius:50%;">
                      <span class="event-name h-100 float-start pt-0"><?= $datahy["fair_group_name_th"] ?></span>
                    </div>
                  </div>
                </div>

                <div class="d-none d-md-block col-12 col-md-3 view-more pt-2 text-end">
                  <a href="<?= ROOTPATHDOMAIN ?>all-gallery-group/<?= $datahy["fair_group_id"] ?>/<?= urlencode($datahy["fair_group_name_th"]) ?>/">
                    <i class="bi bi-arrow-right-short"></i>
                    <span>View More</span>
                  </a>
                </div>
              </div>
              <div class="row row-cols-4 mb-5 px-4">
                <?

                $kw_search = " ";
                if ($kw_search != "") {
                  $kw_search = " and (b.fcg_title_th like '%" . $kw . "%' or b.fcg_title_en like '%" . $kw . "%' or b.fcg_detail_th like '%" . $kw . "%' or b.fcg_detail_en like '%" . $kw . "%') ";
                }

                $sqlnews = "select * from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and e.fair_group_id = ?  and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 $kw_search order by a.gall_file_create_date DESC limit 4 ";
                $stmtnews = $mysqli->prepare($sqlnews);
                $stmtnews->bind_param('i', $datahy["fair_group_id"]);
                $stmtnews->execute();
                $resultnews = $stmtnews->get_result();
                $numrownews = $resultnews->num_rows;

                if ($numrownews > 0) {
                  while ($datanews = $resultnews->fetch_assoc()) {
                    $newsimg = "";
                    $croppath = str_replace('cover/', 'cover/cropsq/', $datanews["gall_file_path"]);
                    $newsimg = ROOTPATHDOMAIN . $croppath;
                ?>
                    <div class="col-12 col-md-6 col-lg-3 " style="margin-bottom:20px;">
                      <img src="<?= $newsimg ?>" class="w-100 rounded-4">
                    </div>
                  <? }
                } else { ?>
                  <div class="col-12 text-center pt-10">
                    Data not found.
                  </div>
                <? } ?>
              </div>
          <? }
          } ?>

        </div>
      </div>
    </main>
    <?php include('components/footer.php') ?>
  </div>
  <script src="<?= ROOTPATHDOMAIN ?>assets/js/script.js" type="text/javascript"></script>
  <script src="<?= ROOTPATHDOMAIN ?>backoffice/js/sweetalert/sweetalert.min.js"></script>
  <script type="text/javascript">
    function changeMore(id) {
      if (id != "") {
        var name = $("._eenews option:selected").text();
        name = encodeURIComponent(name);
        window.location = '<?= ROOTPATHDOMAIN ?>all-gallery-group/' + id + '/' + name + '/';
      }
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
          window.location = "/";
        }
      });
    }
  </script>

</body>


</html>