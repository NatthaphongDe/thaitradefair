<?
$sqlcontent = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id where a.fair_id = ? and a.fct_id = ?  and a.fca_pubish = '1' and a.fca_status = '1'  order by a.fca_create_date DESC, a.fca_id DESC ";
$stmtcontent = $mysqli->prepare($sqlcontent);
$stmtcontent->bind_param('ii',$fair_id,$fct_id);
$stmtcontent->execute();
$resultcontent = $stmtcontent->get_result();
$numrowcontent = $resultcontent->num_rows;
if($numrowcontent>0) {
  ?>
  <div id="news" class="row continer-content-list">

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
        <div class="col-12">
          <div class="new-list-blog" onclick="window.location='<?=ROOTPATHDOMAIN?>fair-detail/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$datacontent["fca_id"]?>/<?=urlencode($contentdata_title)?>/';">
              <?=$contentdata_title?>
              <span class="new-list-blog-readmore">...Readmore</span>
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

.continer-content-list .card {
    background: none;
}

.continer-content-list .card .card-image {
    height: 217px;
    border-radius: 1rem;
    overflow: hidden;
    position: relative;
}

.continer-content-list .card .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.continer-content-list .card .card-image .label-txt-press-con {
    position: absolute;
    top: 10px;
    left: 10px;
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

.continer-content-list .card .card-image .label-txt-update {
    position: absolute;
    top: 10px;
    left: 10px;
    border-radius: 6px;
    background-color: #339f48;
    font-size: 14px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #fff;
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
</style>
