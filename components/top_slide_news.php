<? include_once ("backoffice/connect.php"); ?>
<div class="row px-0 px-md-2 pt-2 pt-md-4 _homebanner" id="fair-calendar">
    <div class="col _homebannercol">
        <div id="aboutFairCarousel" class="carousel slide m-0" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <?
              $imgfair = "";
              $imgfair = ROOTPATHDOMAIN.$datafair["fca_banner_path"];

              if($datafair["fca_banner_path"]!="") {
                $imgfairx = "";
                $imgfair = "";
                $croppath = str_replace('banner/','banner/resize/',$datafair["fca_banner_path"]);
                $imgfair = ROOTPATHDOMAIN.$croppath;
                $imgfairx = ROOTPATH.$croppath;
                if(!file_exists($imgfairx)) {
                  $imgfair = ROOTPATHDOMAIN.$datafair["fca_banner_path"];
                }
              }
              ?>
                <!-- <button type="button" data-bs-target="#aboutFairCarousel" data-bs-slide-to="0"
                    class="rounded-circle active" aria-current="true"></button> -->
            </div>
            <div class="carousel-inner newsbanner_bgblog">
                <div class="carousel-item active newsbanner_bg" style="background-image: url('<?=$imgfair?>');" >
                    <!-- <img class="bd-placeholder-img" style="border-radius:30px;" src="<?=$imgfair?>" width="100%" /> -->
                </div>
            </div>
        </div>
    </div>
</div>
