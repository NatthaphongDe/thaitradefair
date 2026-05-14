<? include_once ("backoffice/connect.php"); ?>

<div class="row mt-4 px-0 px-md-5">
    <div class="col px-0">
        <ul class="col-12 nav justify-content-end" id="menu-list">

          <?
          $sqlwhy = "select * from tt_why_content where why_status = '1' order by why_id ASC ";
          $stmtwhy = $mysqli->prepare($sqlwhy);
          $stmtwhy->execute();
          $resultwhy = $stmtwhy->get_result();
          $numrowwhy = $resultwhy->num_rows;
          if($numrowwhy>0) {
            while($datawhy = $resultwhy->fetch_assoc()) {
              $titlewhy_show = "";
              $titlewhy = str_replace('and','& and',$datawhy["why_title"]);
              $titlewhy = explode('& ',$titlewhy);
              for($t=0;$t<count($titlewhy);$t++) {
                if($titlewhy_show=="") {
                  $titlewhy_show = $titlewhy[$t];
                } else {
                  if (str_contains($titlewhy[$t], 'and')) {
                    $titlewhy_show = $titlewhy_show."<br>".$titlewhy[$t];
                  } else {
                    $titlewhy_show = $titlewhy_show." & ".$titlewhy[$t];
                  }
                }
              }
          ?>
          <li class="col col-sm-3 nav-item">
              <a class="nav-link px-3 pb-0 text-uppercase text-center position-relative <? echo ($why_id ==$datawhy["why_id"])?'active':''?>"
                  href="<?=ROOTPATHDOMAIN?>what-industry/<?=$datawhy["why_id"]?>/<?=urlencode($datawhy["why_title"])?>/">
                  <?=$titlewhy_show?>
              </a>
              <?php
                  if($why_id==$datawhy["why_id"]){
                      ?>
                      <div class="border-bottom-active"></div>
                      <?php
                  }
                  ?>
          </li>
          <? } } ?>


        </ul>
    </div>
</div>
