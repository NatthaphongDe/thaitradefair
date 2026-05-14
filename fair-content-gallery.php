<?
$sqlhy2 = "select * from tt_fair_content_gallery_file a left join tt_fair_content_gallery b on a.fcg_id=b.fcg_id left join tt_fair_list c on b.fair_id=c.fair_id left join tt_fair_group_list d on b.fair_id=d.fair_id left join tt_fair_group e on d.fair_group_id=e.fair_group_id left join tt_fair_category f on b.fcat_id=f.fcat_id where b.fcat_id = 6 and b.fcg_pubish = 1 and b.fcg_status = 1 and c.fair_status = 1 and d.fair_flag = 1 and e.fair_group_status = 1 and b.fair_id = ? and b.fct_id = ?  group by b.fcg_id order by b.fcg_id DESC ";
$stmthy2= $mysqli->prepare($sqlhy2);
$stmthy2->bind_param('ii',$fair_id,$fct_id);
$stmthy2->execute();
$resulthy2 = $stmthy2->get_result();
$numrowhy2 = $resulthy2->num_rows;
if($numrowhy2>0) {
  ?>
  <div id="news"
      class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-4 px-4 mb-5 continer-content-list">

      <? while($datahy2 = $resulthy2->fetch_assoc()) {

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
                <div class="card-image rounded-3" style="background-image: url('<?=$newsimg?>');" onclick="window.location='<?=ROOTPATHDOMAIN?>fair-gallery/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$datahy2["fcg_id"]?>/<?=urlencode($newstitle)?>/';">
                  <!-- <a href="<?=ROOTPATHDOMAIN?>fair-gallery/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$datahy2["fcg_id"]?>/<?=urlencode($newstitle)?>/"><img src="<?=$newsimg?>"/> <span class="label-txt-press-con px-2 py-0"><?=getTagMasterNews($datahy2["fcg_tag_master_id"])?></span></a> -->
                  <span class="label-txt-press-con px-2 py-0"><?=getTagMasterNews($datahy2["fcg_tag_master_id"])?></span>
                </div>
                <div class="card-body pb-0">
                    <h5 class="card-title"><a href="<?=ROOTPATHDOMAIN?>fair-gallery/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$datahy2["fcg_id"]?>/<?=urlencode($newstitle)?>/"><?=$newstitle?></a></h5>
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
      <? } }  ?>

  </div>
  <?

} else {
?>
<p class="text-center">
  No data available.</p>
<? } ?>


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

h2.title {
    font-size: 26px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    /* text-align: justify; */
    color: #111;
}

.continer-content-list .card .card-image {
    height: 217px;
    border-radius: 1rem;
    overflow: hidden;
    background-repeat: no-repeat;
    background-position: center 40%;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    cursor: pointer;
}

.continer-content-list .card .card-image .label-txt-press-con {
    position: absolute;
    top: 20px;
    left: 20px;
    border-radius: 6px;
    background-color: #378dd7;
    font-size: 14px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #fff;
}

.continer-content-list .card .card-image img {
    width: 100%;
}

.continer-content-list .card .card-title {
  height: 40px;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.continer-content-list .card .card-body h5 {
    font-size: 18px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.06;
    letter-spacing: normal;
    max-height: 56px;
}

.continer-content-list .event-area img {
    width: 40px;
    height: 40px;
}

.continer-content-list .event-area .event-name {
    font-size: 14px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    letter-spacing: normal;
    line-height: 1;
}

.continer-content-list .event-area .event-date {
    font-size: 14px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #b2b2b2;
}

.continer-content-list .card .card-image .label-txt-press-con {
    position: absolute;
    top: 20px;
    left: 20px;
    border-radius: 6px;
    background-color: #378dd7;
    font-size: 14px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #fff;
}
</style>
