<?
$sqlcontent = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id where a.fair_id = ? and a.fct_id = ?  and a.fca_pubish = '1' and a.fca_status = '1'  order by a.fca_create_date DESC, a.fca_id DESC ";
$stmtcontent = $mysqli->prepare($sqlcontent);
$stmtcontent->bind_param('ii',$fair_id,$fct_id);
$stmtcontent->execute();
$resultcontent = $stmtcontent->get_result();
$numrowcontent = $resultcontent->num_rows;
if($numrowcontent>0) {
  ?>
  <div id="news"
      class="row row-cols-1 row-cols-md-2 row-cols-lg-3 mt-4 px-4  continer-content-list">

      <? while($datacontent = $resultcontent->fetch_assoc()) {
              if($datacontent["fca_title_en"]!="") {
                $contentdata_title = $datacontent["fca_title_en"];
              } else {
                $contentdata_title = $datacontent["fca_title_th"];
              }

              $imgdata = "";
              if($datacontent["fca_banner_path"]!="") {
                $imgdata = ROOTPATHDOMAIN.$datacontent["fca_banner_path"];
              } else {
                $imgdata = ROOTPATHDOMAIN.$datafair["fair_path_banner"];
              }

              $croppath = str_replace('logo/','logo/crop/',$datafair["fair_group_logo_path"]);
              $imgdatalogo = ROOTPATHDOMAIN.$croppath;
        ?>
        <div class="col px-2 mb-3">
            <div class="card border-0 rounded-4 p-2 homenewsitem homenewsitem_bt">
                <div class="card-image rounded-3">
                    <a href="<?=ROOTPATHDOMAIN?>fair-detail/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$datacontent["fca_id"]?>/<?=urlencode($contentdata_title)?>/">
                        <img src="<?=$imgdata?>" />
                        <span class="label-txt-press-con px-2 py-0"><?=getTagMasterNews($datacontent["fca_tag_master_id"])?></span>
                    </a>
                </div>
                <div class="card-body pb-0">
                    <h5 class="card-title"><a href="<?=ROOTPATHDOMAIN?>fair-detail/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$datacontent["fca_id"]?>/<?=urlencode($contentdata_title)?>/"><?=$contentdata_title?></a></h5>
                    <div class="event-area row row-cols-2">
                        <div class="col-2 pt-1">
                            <img src="<?=$imgdatalogo?>" style="border-radius:50%;" />
                        </div>
                        <div class="col-10 pt-2">
                            <span class="event-name d-block"><?=$datafair["fair_name"]?></span>
                            <span class="event-date d-block"><?=getDateContent($datacontent["fca_create_date"])?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <? } ?>

  </div>
  <?

} else {
?>
<p class="text-center">
  No data available.</p>
<? } ?>

<style media="screen">
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
    height: 100%;
    object-fit: cover;
}

.continer-content-list .card .card-title {
    height: 55px;
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
