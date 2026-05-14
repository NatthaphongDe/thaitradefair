<? include_once ("backoffice/connect.php"); ?>
<?
$sqlcover_id = "select * from tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id left join tt_fair_group_list c on a.fair_id=c.fair_id left join tt_fair_group d on c.fair_group_id=d.fair_group_id where a.fcat_id = 26 and a.fca_pubish = 1 and a.fca_status = 1 and b.fair_status = '1' and d.fair_group_id = ?  group by b.fair_id order by a.fct_id DESC limit 1";
$stmtcover_id = $mysqli->prepare($sqlcover_id);
$stmtcover_id->bind_param('i',$_GET["id"]);
$stmtcover_id->execute();
$resultcover_id = $stmtcover_id->get_result();
$raw_id = $resultcover_id->fetch_assoc();
if($datafair["fair_cover_type"]==1) {
  $sqlcover = "select * from tt_fair_cover where fair_id = ? order by cover_pos ASC ";
} else {
  $sqlcover = "select * from tt_fair_cover where fair_id = ? order by RAND() limit 1 ";
}
$stmtcover = $mysqli->prepare($sqlcover);
$stmtcover->bind_param('i',$raw_id['fair_id']);
$stmtcover->execute();
$resultcover = $stmtcover->get_result();
$numrowcover = $resultcover->num_rows;
if($numrowcover>0) {
?>
  <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner" id="fair-calendar">
      <div class="col _homebannercol">
          <div id="aboutFairCarousel" class="carousel slide m-0" data-bs-ride="carousel">
              <div class="carousel-indicators">
                <?
                $runcover = 0;
                for($runi=0;$runi<$numrowcover;$runi++) {
                ?>
                  <button type="button" data-bs-target="#aboutFairCarousel" data-bs-slide-to="<?=$runcover?>"
                      class="rounded-circle <? if($runcover==0) { echo "active"; } ?>" aria-current="true"></button>
                <? $runcover++; } ?>

              </div>
              <div class="carousel-inner">
                <?
                $runcover = 0;
                while($datacover = $resultcover->fetch_assoc()) {
                  $imgcover = "";
                  $croppath = str_replace('/banner','/resize',$datacover["cover_path"]);
                  $imgcover = ROOTPATHDOMAIN.$croppath;
                ?>
                <div class="carousel-item <? if($runcover==0) { echo "active"; } ?>" >

                  <div class="_banenrinertopblog">
                    <? if($datacover["cover_url"]=="") { ?>
                      <img class="bd-placeholder-img " style="height: 100%;" src="<?=$imgcover?>" />
                    <? } else { ?>
                      <a target="_blank" href="<?=$datacover["cover_url"]?>"><img class="bd-placeholder-img " src="<?=$imgcover?>" /></a>
                    <? } ?>

                      <? if($datafair["fair_url"]!="") { ?>
                        <a target="_blank" href="<?=$datafair["fair_url"]?>" class="btn py-0 py-md-1 btn-visit-official-website">Visit Official Website</a>
                      <?  } ?>
                  </div>


                </div>
                <? $runcover++; } ?>
              </div>
          </div>
      </div>
  </div>
<?
} else {
  $imgfair = "";
  if($datafair["fair_path_banner"]!="") {
    $imgfair = ROOTPATHDOMAIN.$datafair["fair_path_banner"];
    $croppath = str_replace('/banner','/banner/resize',$datafair["fair_path_banner"]);
    $imgfair = ROOTPATHDOMAIN.$croppath;
  } else {
    $croppath = str_replace('/main','/resize',$datafair["fair_group_image_path"]);
    $imgfair = ROOTPATHDOMAIN.$croppath;
  }
  ?>
  <div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner" id="fair-calendar">
      <div class="col _homebannercol">
          <div id="aboutFairCarousel" class="carousel slide m-0" data-bs-ride="carousel">
              <div class="carousel-indicators">
                  <button type="button" data-bs-target="#aboutFairCarousel" data-bs-slide-to="0"
                      class="rounded-circle active" aria-current="true"></button>
              </div>
              <div class="carousel-inner">
                  <div class="carousel-item active" >

                    <div class="_banenrinertopblog" >
                      <img class="bd-placeholder-img " style="height: 380px;" src="<?=$imgfair?>" />
                      <? if($datafair["fair_url"]!="") { ?>
                        <a target="_blank" href="<?=$datafair["fair_url"]?>" class="btn py-0 py-md-1 btn-visit-official-website">Visit Official Website</a>
                      <? } ?>
                    </div>


                  </div>
              </div>
          </div>
      </div>
  </div>
  <?
}
?>
