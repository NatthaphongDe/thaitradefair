<? include_once ("backoffice/connect.php"); ?>
<div class="row mt-4 px-0 px-md-5">
    <div class="col px-0">
        <ul class="nav justify-content-end" id="menu-list">

          <?
          if($fct_id<=0) {
            $fct_id = getFairFirstMenu($fair_id);
          }
          $mastermenu = getMasterMenu($fct_id);
          $sqlc = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_type = '1' and b.fcat_status = '1' and a.fct_status = '1' order by a.fct_pos ASC ";
          $stmtc = $mysqli->prepare($sqlc);
          $stmtc->bind_param('i',$fair_id);
          $stmtc->execute();
          $resultc = $stmtc->get_result();
          $numrowc = $resultc->num_rows;
          if($numrowc>0) {
            while($datac = $resultc->fetch_assoc()) {
              ?>
              <? if($datac["fct_cms_type"]==0) { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link px-2 pb-0 text-uppercase <? echo ($mastermenu==$datac["fcat_id"])?'active':''?>"
                        href="javascript:void(0);"><?=$datac["fcat_name"]?></a>
                        <?php
                        if($mastermenu==$datac["fcat_id"]){
                            ?>
                            <div class="border-bottom-active"></div>
                            <?php
                        }
                        ?>
                    <div class="dropdown-content py-1 rounded-3">
                      <?
                      $sqlc2 = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_master = ? and b.fcat_type = '2' and b.fcat_status = '1' and a.fct_status = '1' order by a.fct_pos ASC ";
                      $stmtc2 = $mysqli->prepare($sqlc2);
                      $stmtc2->bind_param('ii',$fair_id,$datac["fcat_id"]);
                      $stmtc2->execute();
                      $resultc2 = $stmtc2->get_result();
                      $numrowc2 = $resultc2->num_rows;
                      if($numrowc2>0) {
                        while($datac2 = $resultc2->fetch_assoc()) {
                          ?>
                          <a href="<?=ROOTPATHDOMAIN?>fair-content/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$datac2["fct_id"]?>/<?=urlencode($datac2["fcat_name"])?>/" class="px-2 py-1 <? echo ($fct_id==$datac2["fct_id"])?'active':''?>"><?=$datac2["fcat_name"]?></a>
                          <?
                        }
                      }
                      ?>
                    </div>
                </li>
              <? } else { ?>
                <li class="nav-item">
                    <a class="nav-link px-2 pb-0 text-uppercase position-relative <? echo ($mastermenu==$datac["fcat_id"])?'active':''?>"
                        href="<?=ROOTPATHDOMAIN?>fair-content/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$datac["fct_id"]?>/<?=urlencode($datac["fcat_name"])?>/">
                        <?=$datac["fcat_name"]?>
                    </a>
                    <?php
                      if($mastermenu==$datac["fcat_id"]){
                            ?>
                            <div class="border-bottom-active"></div>
                            <?php
                        }
                        ?>
                </li>
              <? } ?>
              <?
            }
          }
          ?>

            <!-- <li class="nav-item">
                <a class="nav-link px-2 pb-0 text-uppercase position-relative <? echo ($top_menu_active =='about-fair')?'active':''?>"
                    href="<?=ROOTPATHDOMAIN?>about-fair.php">
                    About
                </a>
                <?php
                    if($top_menu_active =='about-fair'){
                        ?>
                        <div class="border-bottom-active"></div>
                        <?php
                    }
                    ?>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link px-2 pb-0 text-uppercase <? echo ($top_menu_active =='why-visit' || $top_menu_active =='exhibitor-list')?'active':''?>"
                    href="javascript:void(0);">For Visitor</a>
                    <?php
                    if($top_menu_active =='why-visit'){
                        ?>
                        <div class="border-bottom-active"></div>
                        <?php
                    }
                    ?>
                <div class="dropdown-content py-1 rounded-3">
                    <a href="<?=ROOTPATHDOMAIN?>why-visit.php" class="px-2 py-1 <? echo ($top_menu_active =='why-visit')?'active':''?>">Why
                        Visit </a>
                    <a href="<?=ROOTPATHDOMAIN?>exhibitor-list.php"
                        class="px-2 py-1 <? echo ($top_menu_active =='exhibitor-list')?'active':''?>">Exhibitor
                        List</a>
                    <a href="<?=ROOTPATHDOMAIN?>floor-plan" class="px-2 py-1 ">Floor Plan</a>
                    <a href="<?=ROOTPATHDOMAIN?>admission.php" class="px-2 py-1">Admission</a>
                    <a href="<?=ROOTPATHDOMAIN?>visitor-guide.php" class="px-2 py-1">Visitor Guide</a>
                    <a href="<?=ROOTPATHDOMAIN?>business-matching.php" class="px-2 py-1">Business Matching</a>
                    <a href="<?=ROOTPATHDOMAIN?>official-hotel.php" class="px-2 py-1">Official Hotel</a>
                    <a href="<?=ROOTPATHDOMAIN?>shuttle-service.php" class="px-2 py-1">Shuttle Service</a>
                    <a href="<?=ROOTPATHDOMAIN?>map-transportation.php" class="px-2 py-1">Map & Transportation</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2 pb-0 text-uppercase <? echo ($top_menu_active =='why-exhibit')?'active':''?>"
                    href="<?=ROOTPATHDOMAIN?>why-exhibit.php">For Exhibitor</a>
                    <?php
                    if($top_menu_active =='why-exhibit'){
                        ?>
                        <div class="border-bottom-active"></div>
                        <?php
                    }
                    ?>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2 pb-0 text-uppercase <? echo ($top_menu_active =='press')?'active':''?>"
                    href="<?=ROOTPATHDOMAIN?>press.php">Press</a>
                    <?php
                    if($top_menu_active =='press'){
                        ?>
                        <div class="border-bottom-active"></div>
                        <?php
                    }
                    ?>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2 pb-0 text-uppercase <? echo ($top_menu_active =='activities')?'active':''?>"
                    href="<?=ROOTPATHDOMAIN?>activities.php">Activities</a>
                    <?php
                    if($top_menu_active =='activities'){
                        ?>
                        <div class="border-bottom-active"></div>
                        <?php
                    }
                    ?>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2 pb-0 text-uppercase <? echo ($top_menu_active =='gallery')?'active':''?>"
                    href="<?=ROOTPATHDOMAIN?>gallery.php">Gallery</a>
                    <?php
                    if($top_menu_active =='gallery'){
                        ?>
                        <div class="border-bottom-active"></div>
                        <?php
                    }
                    ?>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2 pb-0 text-uppercase <? echo ($top_menu_active =='contact-us')?'active':''?>"
                    href="<?=ROOTPATHDOMAIN?>contact-us.php">Contact Us</a>
                    <?php
                    if($top_menu_active =='contact-us'){
                        ?>
                        <div class="border-bottom-active"></div>
                        <?php
                    }
                    ?>
            </li> -->
        </ul>
    </div>
</div>
