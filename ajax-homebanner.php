<?
include_once ("backoffice/connect.php");
$yearnow = (int)$_GET["y"];
?>
<div class="col-12 col-lg-3 mb-4 mb-lg-0 d-none d-md-block">
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-12 _home-calendar-logo-padding">
            <div class="card _homelogoblog">
                <?
                $earlyFair = getEarlyFair($yearnow);
                $runno = 0;
                $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start ASC ";
                $stmtfair = $mysqli->prepare($sqlfair);
                $stmtfair->bind_param('i',$yearnow);
                $stmtfair->execute();
                $resultfair = $stmtfair->get_result();
                $numrowfair = $resultfair->num_rows;
                if($numrowfair>0) {
                  while($datafair = $resultfair->fetch_assoc()) {
                    $imgfair = "";
                    $croppath = str_replace('/main','/crop',$datafair["fair_group_image_path"]);
                    $imgfair = ROOTPATHDOMAIN.$croppath;


                    $thisactive = "";
                    if($earlyFair<=0) {
                      if($runno!=0) {
                        $thisactive = "homelogotopdis";
                      }
                    } else {
                      if($datafair["fair_id"]!=$earlyFair) {
                        $thisactive = "homelogotopdis";
                      }
                    }

                ?>
                <img class="bd-placeholder-img card-img-top _logogtopall _logogtop_<?=$runno?> <?=$thisactive?> "
                    src="<?=$imgfair?>" alt="<?=$datafair["fair_group_name_th"]?>" height="140" />
                <? $runno++; } } ?>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-12 pt-0 mt-3 mt-sm-0 pt-0 pt-lg-3 _home-calendar-padding">
            <div class="h-100 _home-calendar-bg">

              <div class="homeCalendarSlick">

                <div id="homeCalendarSlickBlog" class="carousel slide m-0">
                      <?
                      $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start ASC ";
                      $stmtfair = $mysqli->prepare($sqlfair);
                      $stmtfair->bind_param('i',$yearnow);
                      $stmtfair->execute();
                      $resultfair = $stmtfair->get_result();
                      $numrowfair = $resultfair->num_rows;
                      if($numrowfair>0) {
                        $runno = 0;
                        while($datafair = $resultfair->fetch_assoc()) {

                          $thisactive = "";
                          if($earlyFair<=0) {
                            if($runno==0) {
                              $thisactive = "active";
                            }
                          } else {
                            if($datafair["fair_id"]==$earlyFair) {
                              $thisactive = "active";
                            }
                          }

                        ?>
                          <div class="carousel-item <?=$thisactive?>">
                            <div class="row">
                              <div class="col-12 text-center">
                                <div class="homeCalendarSlickBlogTitle">EVENT DATE</div>
                                <?php if (date("M",strtotime($datafair["fair_event_start"])) != date("M",strtotime($datafair["fair_event_end"]))) {?>
                                  <div class="homeCalendarSlickBlogDate" style="font-size: 32px; padding-top: 32px;">
                                    <?=date("d",strtotime($datafair["fair_event_start"]))?> <?=mb_strtoupper(date("M",strtotime($datafair["fair_event_start"])))?>  - <?=date("d",strtotime($datafair["fair_event_end"]))?> <?=mb_strtoupper(date("M",strtotime($datafair["fair_event_end"])))?>&nbsp;&nbsp;
                                    
                                  </div>
                                  <div class="homeCalendarSlickBlogDateMonth" style="font-size: 32px; padding-top: 30px;">
                                  <?=date("Y",strtotime($datafair["fair_event_start"]))?>
                                </div>
                                <?}else{?>
                                  <div class="homeCalendarSlickBlogDate">
                                    <?=date("d",strtotime($datafair["fair_event_start"]))?> - <?=date("d",strtotime($datafair["fair_event_end"]))?>
                                  </div>
                                  <div class="homeCalendarSlickBlogDateMonth">
                                    <?=mb_strtoupper(date("M",strtotime($datafair["fair_event_start"])))?>
                                    &nbsp;&nbsp;
                                    <?=date("Y",strtotime($datafair["fair_event_start"]))?>
                                  </div>
                                <?}?>
                              </div>
                            </div>
                          </div>
                      <? $runno++; } } ?>


                    </div>

              </div>



            </div>
        </div>
    </div>
</div>

<div class="col-12 col-lg-9 _fairbannerSlide" style="padding-left:0;">
    <div id="indexCarousel" class="carousel slide m-0" data-bs-ride="carousel">
        <div class="carousel-inner _banner_carousel-inner">
          <?
          $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_year = ? and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start ASC ";
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
                $croppath = str_replace('/banner','/banner/crop',$datafair["fair_path_banner"]);
                $imgfair = ROOTPATHDOMAIN.$croppath;
              } else {
                $croppath = str_replace('/main','/crop',$datafair["fair_group_image_path"]);
                $imgfair = ROOTPATHDOMAIN.$croppath;
              }

              $thisactive = "";
              if($earlyFair<=0) {
                if($runno==0) {
                  $thisactive = "active";
                }
              } else {
                if($datafair["fair_id"]==$earlyFair) {
                  $thisactive = "active";
                }
              }

              ?>
              <div class="carousel-item <?=$thisactive?>">
                  <a href="<?=ROOTPATHDOMAIN?>fair/<?=$datafair["fair_id"]?>/<?=urlencode($datafair["fair_name"])?>/"><img src="<?=$imgfair?>" alt="<?=$datafair["fair_name"]?>" /></a>
              </div>
              <?
              $runno++;
            }
          }
          ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#indexCarousel"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon">
                <i class="bi bi-chevron-left text-dark"></i>
            </span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#indexCarousel"
            data-bs-slide="next">
            <span class="carousel-control-next-icon">
                <i class="bi bi-chevron-right text-dark"></i>
            </span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#indexCarousel').bind('slide.bs.carousel', function (e) {
      var slideFrom = $(this).find('.active').index();
      var slideTo = $(e.relatedTarget).index();

      setTimeout(function () {
        $('#homeCalendarSlickBlog').carousel(slideTo);
        $('._logogtopall').addClass('homelogotopdis');
        $('._logogtop_'+slideTo).removeClass('homelogotopdis');
      },50);

    });
});
</script>
