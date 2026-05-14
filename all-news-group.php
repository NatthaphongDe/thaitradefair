<?
include_once ("backoffice/connect.php");
$top_menu_active = "about-fair";

if($_GET["method"]=="loadnews") {
  $gid = $mysqli->real_escape_string($_GET["id"]);
  $kw = $mysqli->real_escape_string($_GET["kw"]);
  $csrf_token = $mysqli->real_escape_string($_GET['csrf_token']);
  if (!isset($_SESSION['csrf_token'], $csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) { ?>
    <script type="text/javascript">
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
    </script>
  <?exit();}
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

      $kw_search = " ";
      if($kw_search!="") {
        $kw_search = " and (a.fca_title_th like '%".$kw."%' or a.fca_title_en like '%".$kw."%' or a.fca_detail_th like '%".$kw."%' or a.fca_detail_en like '%".$kw."%') ";
      }



      $sqlnews = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 and b.fair_status = '1' and d.fair_group_id = ? $kw_search  order by a.fca_create_date DESC  ";
      $stmtnews= $mysqli->prepare($sqlnews);
      $stmtnews->bind_param('i',$datanewsg["fair_group_id"]);
      $stmtnews->execute();
      $resultnews = $stmtnews->get_result();
      $numrownews = $resultnews->num_rows;
      if($numrownews>0) {
        ?>
        <div class="row mb-3 px-4 event-type _faircontentblog">
            <div class="col-12 col-md-10">
                <div class="row event-area align-items-center">
                    <div class="col-12 pt-1">
                        <img class="float-start" src="<?=$fgimg?>" style="border-radius:50%;">
                        <span class="event-name h-100 float-start pt-0">
                            <?=$datanewsg["fair_group_name_th"]?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-3 mb-5 px-4 continer-content-list px-4">
        <?
        while($datanews = $resultnews->fetch_assoc()) {

          $fgimg = "";
          $croppath = str_replace('logo/','logo/crop/',$datanews["fair_group_logo_path"]);
          $fgimg = ROOTPATHDOMAIN.$croppath;

          $newsimg = "";
          //$newsimg = ROOTPATHDOMAIN.$datanews["news_banner_path"];

          if($datanews["fca_banner_path"]!="") {
            $newsimgx = "";
            $newsimg = "";
            $croppath = str_replace('banner/','banner/resize/',$datanews["fca_banner_path"]);
            $newsimg = ROOTPATHDOMAIN.$croppath;
            $newsimgx = ROOTPATH.$croppath;
            if(!file_exists($newsimgx)) {
              $newsimg = ROOTPATHDOMAIN.$datanews["fca_banner_path"];
            }
          } else {

            if($datanews["fair_path_banner"]!="") {
              $croppath = str_replace('/banner','/banner/crop',$datanews["fair_path_banner"]);
              $newsimg = ROOTPATHDOMAIN.$croppath;
            } else {
              $croppath = str_replace('/main','/crop',$datanews["fair_group_image_path"]);
              $newsimg = ROOTPATHDOMAIN.$croppath;
            }
          }

          if($datanews["fca_title_en"]!="") {
            $newstitle = $datanews["fca_title_en"];
          } else {
            $newstitle = $datanews["fca_title_th"];
          }
        ?>
        <div class="col px-1">
            <div class="card border-0 rounded-4 p-2 homenewsitem homenewsitem_bt">
                <div class="card-image rounded-3">
                  <a href="<?=ROOTPATHDOMAIN?>news-detail/<?=$datanews["fca_id"]?>/<?=urlencode($newstitle)?>/"><img src="<?=$newsimg?>"/>  <span class="label-txt-press-con px-2 py-0"><?=getTagMasterNews($datanews["fca_tag_master_id"])?></span></a>
                </div>
                <div class="card-body pb-0">
                    <h5 class="card-title"><a href="<?=ROOTPATHDOMAIN?>news-detail/<?=$datanews["fca_id"]?>/<?=urlencode($newstitle)?>/"><?=$newstitle?></a></h5>
                    <div class="event-area row row-cols-2">
                        <div class="col-2 pt-1">
                            <img src="<?=$fgimg?>" style="border-radius:50%; border:" />
                        </div>
                        <div class="col-10 pt-2">
                            <span class="event-name d-block"><?=$datanews["fair_name"]?></span>
                            <span class="event-date d-block"><?=getDateContent($datanews["fca_create_date"])?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?
        }
        ?>
        </div>
        <?
      } else {
        ?>
        <div class="row">
          <div class="col-12 text-center pt-5">
            Data not found.
          </div>
        </div>
        <?
      }

    }
  }
  ?>

  <?
  exit();
}

if($_GET["method"]=="changef") {
  $csrf_token = $mysqli->real_escape_string($_GET['csrf_token']);
  if (!isset($_SESSION['csrf_token'], $csrf_token ) || !hash_equals($_SESSION['csrf_token'], $csrf_token )) { ?>
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
  <?exit();}
  $gid = $mysqli->real_escape_string($_GET['id']);
  $fid = $mysqli->real_escape_string($_GET['fid']);
  $sqlnewsg = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_category c on a.fcat_id=c.fcat_id where a.fcat_id = 26 and b.fair_id = ? limit 1 ";
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
  <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : News</title>
  <meta name="description" content="Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?> : News">
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
                    
                </div>
            </div>
        </header>

        <br>
        <main>
            <div class="container-fluid">
                <div class="container ">
                    <div class="row mb-3 px-4 pt-md-0 _faircontentblog">
                        <div class="col-12 col-md-7">
                            <h2 class="title p-0 m-0">NEWS</h2>
                        </div>
                        <div class="col-12 col-md-5">
                          <form method="get" onsubmit="loadnews(); return false;">
                            <div id="search" class="input-group mt-3">
                              <select type="text" class="form-select type-exhibitors w-20 _eenews" onchange="changeMore(this.value)">
                                  <option value="">All Event</option>
                                  <?
                                  $gid = $mysqli->real_escape_string($_GET['id']);
                                  $sqlnewsg = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 and b.fair_status = '1' and d.fair_group_id = ?  group by b.fair_id order by b.fair_name ASC ";
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
                    $gid = $mysqli->real_escape_string($_GET['id']);
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

                        $kw_search = " ";
                        if($kw_search!="") {
                          $kw_search = " and (a.fca_title_th like '%".$kw."%' or a.fca_title_en like '%".$kw."%' or a.fca_detail_th like '%".$kw."%' or a.fca_detail_en like '%".$kw."%') ";
                        }



                        $sqlnews = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 and b.fair_status = '1' and d.fair_group_id = ? $kw_search  order by a.fca_create_date DESC  ";
                        $stmtnews= $mysqli->prepare($sqlnews);
                        $stmtnews->bind_param('i',$datanewsg["fair_group_id"]);
                        $stmtnews->execute();
                        $resultnews = $stmtnews->get_result();
                        $numrownews = $resultnews->num_rows;
                        if($numrownews>0) {
                          ?>
                          <div class="row mb-3 px-4 event-type _faircontentblog">
                              <div class="col-12 col-md-10">
                                  <div class="row event-area align-items-center">
                                      <div class="col-12 pt-1">
                                          <img class="float-start" src="<?=$fgimg?>" style="border-radius:50%;">
                                          <span class="event-name h-100 float-start pt-0">
                                              <?=$datanewsg["fair_group_name_th"]?>
                                          </span>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-3 mb-5 px-4 continer-content-list px-4">
                          <?
                          while($datanews = $resultnews->fetch_assoc()) {

                            $fgimg = "";
                            $croppath = str_replace('logo/','logo/crop/',$datanews["fair_group_logo_path"]);
                            $fgimg = ROOTPATHDOMAIN.$croppath;

                            $newsimg = "";
                            //$newsimg = ROOTPATHDOMAIN.$datanews["news_banner_path"];

                            if($datanews["fca_banner_path"]!="") {
                              $newsimgx = "";
                              $newsimg = "";
                              $croppath = str_replace('banner/','banner/resize/',$datanews["fca_banner_path"]);
                              $newsimg = ROOTPATHDOMAIN.$croppath;
                              $newsimgx = ROOTPATH.$croppath;
                              if(!file_exists($newsimgx)) {
                                $newsimg = ROOTPATHDOMAIN.$datanews["fca_banner_path"];
                              }
                            } else {

                              if($datanews["fair_path_banner"]!="") {
                                $croppath = str_replace('/banner','/banner/crop',$datanews["fair_path_banner"]);
                                $newsimg = ROOTPATHDOMAIN.$croppath;
                              } else {
                                $croppath = str_replace('/main','/crop',$datanews["fair_group_image_path"]);
                                $newsimg = ROOTPATHDOMAIN.$croppath;
                              }
                            }

                            if($datanews["fca_title_en"]!="") {
                              $newstitle = $datanews["fca_title_en"];
                            } else {
                              $newstitle = $datanews["fca_title_th"];
                            }
                          ?>
                          <div class="col px-1">
                              <div class="card border-0 rounded-4 p-2 homenewsitem homenewsitem_bt">
                                  <div class="card-image rounded-3">
                                    <a href="<?=ROOTPATHDOMAIN?>news-detail/<?=$datanews["fca_id"]?>/<?=urlencode($newstitle)?>/"><img src="<?=$newsimg?>"/>  <span class="label-txt-press-con px-2 py-0"><?=getTagMasterNews($datanews["fca_tag_master_id"])?></span></a>
                                  </div>
                                  <div class="card-body pb-0">
                                      <h5 class="card-title"><a href="<?=ROOTPATHDOMAIN?>news-detail/<?=$datanews["fca_id"]?>/<?=urlencode($newstitle)?>/"><?=$newstitle?></a></h5>
                                      <div class="event-area row row-cols-2">
                                          <div class="col-2 pt-1">
                                              <img src="<?=$fgimg?>" style="border-radius:50%; border:" />
                                          </div>
                                          <div class="col-10 pt-2">
                                              <span class="event-name d-block"><?=$datanews["fair_name"]?></span>
                                              <span class="event-date d-block"><?=getDateContent($datanews["fca_create_date"])?></span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <?
                          }
                          ?>
                          </div>
                          <?
                        }

                      }
                    }
                    ?>

                  </div>

                    </div>
                </div>
            </div>
        </main>
        <?php include('components/footer.php') ?>
    </div>
    <script src="<?=ROOTPATHDOMAIN?>assets/js/script.js" type="text/javascript"></script>

    <script type="text/javascript">
      function changeMore(id) {
        if(id!="") {
          var csrf_token = $('#csrf_token').val();
          $.ajax({
              type: "GET",
              //url: "<?=ROOTPATHDOMAIN?>all-news-group.php?method=changef&id=<?=$gid?>&fid="+id,
              url: "<?=ROOTPATHDOMAIN?>all-news-group.php",
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
            //url: "<?=ROOTPATHDOMAIN?>all-news-group.php?method=loadnews&id=<?=$gid?>&kw="+kww,
            url: "<?=ROOTPATHDOMAIN?>all-news-group.php",
            data: {
              method: 'loadnews', // ส่งค่า method
              id: '<?=$gid?>', // ส่งค่า id
              kw: kww, // ส่งค่า keyword
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
