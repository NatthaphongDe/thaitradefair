<?
include_once ("backoffice/connect.php");
$top_menu_active = "about-fair";

if($_GET["method"]=="loadnews") {
  if ($_SESSION['csrf_token'] != $_GET["csrf_token"]) { ?>
    <script type="text/javascript">
      swal({
        title: "",
        text: "The token provided is invalid. Please try again",
        icon: "error", // เปลี่ยนจาก type เป็น icon ใน SweetAlert2
        buttons: {
            confirm: {
                text: "Close",
                value: true,
                visible: true,
                className: "btn btn-success",
                closeModal: true
            }
        }
      }, function(isConfirm) {
        if (isConfirm) {
          window.location="/";
        }
      });
    </script>
  <?
  exit();}
  $gid = $_GET["id"];
  $kw = $_GET["kw"];

  $gid = $_GET["id"];
  $sqlhy = "select * from tt_fair_group where fair_group_status = 1 and fair_group_id = ? limit 1 ";
  $stmthy= $mysqli->prepare($sqlhy);
  $stmthy->bind_param('i',$gid);
  $stmthy->execute();
  $resulthy = $stmthy->get_result();
  $numrowhy = $resulthy->num_rows;
  if($numrowhy>0) {
    while($datahy = $resulthy->fetch_assoc()) {
      $fgimg = "";
      $croppath = str_replace('logo/','logo/crop/',$datahy["fair_group_logo_path"]);
      $fgimg = ROOTPATHDOMAIN.$croppath;
  ?>
  <div class="row mb-3 px-4 event-type _faircontentblog">
      <div class="col-12 col-md-10">
          <div class="row event-area align-items-center">
              <div class="col-12 pt-1">
                  <img class="float-start" src="<?=$fgimg?>" style="border-radius:50%;">
                  <span class="event-name h-100 float-start pt-0"><?=$datahy["fair_group_name_th"]?></span>
              </div>
          </div>
      </div>
  </div>


  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-3 mb-5 px-4 continer-content-list px-4">
  <?
  $params = [];
  $types = 'i'; // 'i' for $gid
  $params[] = $gid;

  $kw_search = " ";
  if($kw_search!="") {
    $kw_search = " and (b.fcg_title_th like ? or b.fcg_title_en like ? or b.fcg_detail_th like ? or b.fcg_detail_en like ?) ";
    $types .= 'ssss';
    $like_kw = '%' . $kw . '%';
    array_push($params, $like_kw, $like_kw, $like_kw, $like_kw);
  }

  $sqlhy2 = "select * from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id left join tt_fair_category f on b.fcat_id=f.fcat_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 and e.fair_group_id = ? $kw_search group by b.fcg_id order by b.fcg_id DESC ";
  
  $stmthy2= $mysqli->prepare($sqlhy2);
  $stmthy2->bind_param($types, ...$params);
  $stmthy2->execute();
  $resulthy2 = $stmthy2->get_result();
  $numrowhy2 = $resulthy2->num_rows;
  if($numrowhy2>0) {
    while($datahy2 = $resulthy2->fetch_assoc()) {

      $fgimg = "";
      $croppath = str_replace('logo/','logo/crop/',$datahy2["fair_group_logo_path"]);
      $fgimg = ROOTPATHDOMAIN.$croppath;

      $sqlnews = "select * from tt_fair_content_gallery_file where fcg_id = ? order by RAND() limit 1 ";
      $stmtnews= $mysqli->prepare($sqlnews);
      $stmtnews->bind_param('i',$datahy2["fcg_id"]);
      $stmtnews->execute();
      $resultnews = $stmtnews->get_result();
      $numrownews = $resultnews->num_rows;

      if($numrownews>0) {
        $datanews = $resultnews->fetch_assoc();
        $newsimg = "";
        $croppath = str_replace('cover/','cover/croptop/',$datanews["gall_file_path"]);
        $newsimg = ROOTPATHDOMAIN.$croppath;

        if($datahy2["fcg_title_en"]!="") {
          $newstitle = $datahy2["fcg_title_en"];
        } else {
          $newstitle = $datahy2["fcg_title_th"];
        }
  ?>
  <div class="col px-1">
      <div class="card border-0 rounded-4 p-2 homenewsitem homenewsitem_bt">
          <div class="card-image rounded-3" style="background-image: url('<?=$newsimg?>');" onclick="window.location='<?=ROOTPATHDOMAIN?>fair-content/<?=$datahy2["fair_id"]?>/<?=urlencode($datahy2["fair_name"])?>/<?=$datahy2["fct_id"]?>/<?=urlencode($datahy2["fcat_name"])?>/';">

              <!-- <img src="<?=$newsimg?>"/>  -->
              <span class="label-txt-press-con px-2 py-0"><?=getTagMasterNews($datahy2["fcg_tag_master_id"])?></span>
          </div>
          <div class="card-body pb-0">
              <h5 class="card-title"><a href="<?=ROOTPATHDOMAIN?>fair-content/<?=$datahy2["fair_id"]?>/<?=urlencode($datahy2["fair_name"])?>/<?=$datahy2["fct_id"]?>/<?=urlencode($datahy2["fcat_name"])?>/"><?=$newstitle?></a></h5>
              <div class="event-area row row-cols-2">
                  <div class="col-2 pt-1">
                      <img src="<?=$fgimg?>" style="border-radius:50%; border:" />
                  </div>
                  <div class="col-10 pt-2">
                      <span class="event-name d-block"><?=$datahy2["fair_name"]?></span>
                      <span class="event-date d-block"><?=getDateContent($datahy2["fcg_create_date"])?></span>
                  </div>
              </div>
          </div>
      </div>
  </div>
<? } } }  ?>
  </div>

  <? } } ?>

 <?
 exit();
}

if($_GET["method"]=="changef") {
  if ($_SESSION['csrf_token'] != $_GET["csrf_token"]) { ?>
    <script type="text/javascript">
      swal({
        title: "",
        text: "The token provided is invalid. Please try again",
        type: "error",// เปลี่ยนจาก type เป็น icon ใน SweetAlert2
        showCancelButton: false,
        confirmButtonColor: "#5cb85c",
        confirmButtonText: "close",
        closeOnConfirm: false
      }, function(isConfirm) {
        if (isConfirm) {
          window.location="/";
        }
      });
    </script>
  <?exit();}
  $gid = $_GET["id"];
  $fid = $_GET["fid"];
  $sqlnewsg = "select * from tt_fair_content_gallery a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_category c on a.fcat_id=c.fcat_id where a.fcat_id = 6 and b.fair_id = ? limit 1 ";
  $stmtnewsg= $mysqli->prepare($sqlnewsg);
  $stmtnewsg->bind_param('i',$fid);
  $stmtnewsg->execute();
  $resultnewsg = $stmtnewsg->get_result();
  $numrownewsg = $resultnewsg->num_rows;
  $datanewsg = $resultnewsg->fetch_assoc();
  echo $link = ROOTPATHDOMAIN."fair-content/".$fid."/".urlencode($datanewsg["fair_name"])."/".$datanewsg["fct_id"]."/".urlencode($datanewsg["fcat_name"])."/";
  exit();
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : Galleries</title>
  <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : Galleries">
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/calendar.css" rel="stylesheet">
    <link href="<?=ROOTPATHDOMAIN?>assets/css/all-news.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">

            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>
                    <?php include('components/top_slide_new_cover.php'); ?>
                    <!-- <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner" id="fair-calendar">
                        <div class="col _homebannercol">
                            <div id="allNewsCarousel" class="carousel slide m-0" data-bs-ride="carousel">
                                <div class="carousel-inner allnewsbanner">

                                  <?
                                  $runimg = 0;
                                  $gid = $_GET["id"];
                                  $sqlnewsg = "select * from tt_fair_group where fair_group_status = 1 and fair_group_id = ? limit 1 ";
                                  $stmtnewsg= $mysqli->prepare($sqlnewsg);
                                  $stmtnewsg->bind_param('i',$gid);
                                  $stmtnewsg->execute();
                                  $resultnewsg = $stmtnewsg->get_result();
                                  $numrownewsg = $resultnewsg->num_rows;
                                  if($numrownewsg>0) {
                                    while($datanewsg = $resultnewsg->fetch_assoc()) {

                                      $fgimg = "";
                                      $croppath = str_replace('logo/','logo/crop/',$datanewsg["fair_group_logo_path"]);
                                      $fgimg = ROOTPATHDOMAIN.$croppath;


                                      $sqlnews = "select * from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and e.fair_group_id = ?  and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1  order by RAND() limit 10 ";
                                      $stmtnews= $mysqli->prepare($sqlnews);
                                      $stmtnews->bind_param('i',$datanewsg["fair_group_id"]);
                                      $stmtnews->execute();
                                      $resultnews = $stmtnews->get_result();
                                      $numrownews = $resultnews->num_rows;
                                      if($numrownews>0) {
                                        while($datanews = $resultnews->fetch_assoc()) {
                                          $fgimg = "";
                                          $croppath = str_replace('logo/','logo/crop/',$datanews["fair_group_logo_path"]);
                                          $fgimg = ROOTPATHDOMAIN.$croppath;

                                          $newsimg = "";
                                          $croppath = str_replace('cover/','cover/resize/',$datanews["gall_file_path"]);
                                          $newsimg = ROOTPATHDOMAIN.$croppath;


                                          ?>
                                          <div class="carousel-item gallbanner_bg <? if($runimg==0) { echo "active"; } ?> " style="background-image: url('<?=$newsimg?>');">

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
                            <h2 class="title p-0 m-0">GALLERY</h2>
                        </div>
                        <div class="col-12 col-md-5">
                          <form method="get" onsubmit="loadnews(); return false;">
                            <div id="search" class="input-group mt-3">
                              <select type="text" class="form-select type-exhibitors w-20 _eenews" onchange="changeMore(this.value)">
                                  <option value="">All Event</option>
                                  <?
                                  $gid = $_GET["id"];
                                  $sqlnewsg = "select * from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id left join tt_fair_category f on b.fcat_id=f.fcat_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 and e.fair_group_id = ? group by c.fair_id order by c.fair_name ASC ";
                                  $stmtnewsg= $mysqli->prepare($sqlnewsg);
                                  $stmtnewsg->bind_param('i',$gid);
                                  $stmtnewsg->execute();
                                  $resultnewsg = $stmtnewsg->get_result();
                                  $numrownewsg = $resultnewsg->num_rows;
                                  if($numrownewsg>0) {
                                    while($datanewsg = $resultnewsg->fetch_assoc()) {
                                  ?>

                                  <option value="<?=$datanewsg["fair_id"]?>"><?=$datanewsg["fair_name"]?></option>
                                  <? } }  ?>
                                  <input type="text" class="form-control w-25" placeholder="Search" id="kww">
                                  <input type="text" name="csrf_token" id="csrf_token" value="<?=$_SESSION['csrf_token']?>" hidden>
                                  <button class="btn btn-search rounded-end text-center pt-1" type="submit">
                                      <i class="bi bi-search text-white "></i>
                                  </button>

                            </div>
                          </form>
                        </div>
                    </div>

                    <div class="_newsss">
                    <?
                    $gid = $_GET["id"];
                    $sqlhy = "select * from tt_fair_group where fair_group_status = 1 and fair_group_id = ? limit 1 ";
                    $stmthy= $mysqli->prepare($sqlhy);
                    $stmthy->bind_param('i',$gid);
                    $stmthy->execute();
                    $resulthy = $stmthy->get_result();
                    $numrowhy = $resulthy->num_rows;
                    if($numrowhy>0) {
                      while($datahy = $resulthy->fetch_assoc()) {
                        $fgimg = "";
                        $croppath = str_replace('logo/','logo/crop/',$datahy["fair_group_logo_path"]);
                        $fgimg = ROOTPATHDOMAIN.$croppath;
                    ?>
                    <div class="row mb-3 px-4 event-type _faircontentblog">
                        <div class="col-12 col-md-10">
                            <div class="row event-area align-items-center">
                                <div class="col-12 pt-1">
                                    <img class="float-start" src="<?=$fgimg?>" style="border-radius:50%;">
                                    <span class="event-name h-100 float-start pt-0"><?=$datahy["fair_group_name_th"]?></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-3 mb-5 px-4 continer-content-list px-4">
                    <?
                    $sqlhy2 = "select * from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id left join tt_fair_category f on b.fcat_id=f.fcat_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 and e.fair_group_id = ? group by b.fcg_id order by b.fcg_id DESC ";
                    $stmthy2= $mysqli->prepare($sqlhy2);
                    $stmthy2->bind_param('i',$gid);
                    $stmthy2->execute();
                    $resulthy2 = $stmthy2->get_result();
                    $numrowhy2 = $resulthy2->num_rows;
                    if($numrowhy2>0) {
                      while($datahy2 = $resulthy2->fetch_assoc()) {

                        $fgimg = "";
                        $croppath = str_replace('logo/','logo/crop/',$datahy2["fair_group_logo_path"]);
                        $fgimg = ROOTPATHDOMAIN.$croppath;

                        $sqlnews = "select * from tt_fair_content_gallery_file where fcg_id = ? order by RAND() limit 1 ";
                        $stmtnews= $mysqli->prepare($sqlnews);
                        $stmtnews->bind_param('i',$datahy2["fcg_id"]);
                        $stmtnews->execute();
                        $resultnews = $stmtnews->get_result();
                        $numrownews = $resultnews->num_rows;

                        if($numrownews>0) {
                          $datanews = $resultnews->fetch_assoc();
                          $newsimg = "";
                          $croppath = str_replace('cover/','cover/croptop/',$datanews["gall_file_path"]);
                          $newsimg = ROOTPATHDOMAIN.$croppath;

                          if($datahy2["fcg_title_en"]!="") {
                            $newstitle = $datahy2["fcg_title_en"];
                          } else {
                            $newstitle = $datahy2["fcg_title_th"];
                          }
                    ?>
                    <div class="col px-1">
                        <div class="card border-0 rounded-4 p-2 homenewsitem homenewsitem_bt">
                            <div class="card-image rounded-3" style="background-image: url('<?=$newsimg?>');" onclick="window.location='<?=ROOTPATHDOMAIN?>fair-content/<?=$datahy2["fair_id"]?>/<?=urlencode($datahy2["fair_name"])?>/<?=$datahy2["fct_id"]?>/<?=urlencode($datahy2["fcat_name"])?>/';">

                                <!-- <img src="<?=$newsimg?>"/>  -->
                                <span class="label-txt-press-con px-2 py-0"><?=getTagMasterNews($datahy2["fcg_tag_master_id"])?></span>
                            </div>
                            <div class="card-body pb-0">
                                <h5 class="card-title"><a href="<?=ROOTPATHDOMAIN?>fair-content/<?=$datahy2["fair_id"]?>/<?=urlencode($datahy2["fair_name"])?>/<?=$datahy2["fct_id"]?>/<?=urlencode($datahy2["fcat_name"])?>/"><?=$newstitle?></a></h5>
                                <div class="event-area row row-cols-2">
                                    <div class="col-2 pt-1">
                                        <img src="<?=$fgimg?>" style="border-radius:50%; border:" />
                                    </div>
                                    <div class="col-10 pt-2">
                                        <span class="event-name d-block"><?=$datahy2["fair_name"]?></span>
                                        <span class="event-date d-block"><?=getDateContent($datahy2["fcg_create_date"])?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  <? } } }  ?>
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
      function changeMore(id) {
        var csrf_token = $('#csrf_token').val();
        if(id!="") {
          $.ajax({
              type: "GET",
              //url: "<?=ROOTPATHDOMAIN?>all-gallery-group.php?method=changef&id=<?=$gid?>&fid="+id,
              url: "<?=ROOTPATHDOMAIN?>all-gallery-group.php",
              data: {
                method: 'changef', // ส่งค่า method
                id: '<?=$gid?>', // ส่งค่า id
                fid: id, // ส่งค่า keyword
                csrf_token: csrf_token
              },
              dataType: "text",
              success : function(data) {
                window.location=data;
              }
          });
        }
      }

      function loadnews() {
        var kww = $('#kww').val();
        var csrf_token = $('#csrf_token').val();
        $.ajax({
            type: "GET",
            //url: "<?=ROOTPATHDOMAIN?>all-gallery-group.php?method=loadnews&id=<?=$gid?>&kw="+kww,
            url: "<?=ROOTPATHDOMAIN?>all-gallery-group.php",
            data: {
              method: 'loadnews', 
              id: '<?=$gid?>', 
              kw: kww, 
              csrf_token: csrf_token
            },
            dataType: "text",
            success : function(data) {
              $('._newsss').empty();
              $('._newsss').html(data);
            }
        });
      }
    </script>

</body>


</html>
