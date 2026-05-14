<?
include_once ("backoffice/connect.php");
$yearnow = (int)$_GET["y"];
$array_m = array("JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"); ?>
  <?
  $mt = 1;
  for($mm=0;$mm<count($array_m);$mm++) { ?>
  <li class="nav-item">
      <a class="nav-link position-relative <? if(getFairListMonth($yearnow,$mt)>0) { ?> active <? } ?>" <? if(getFairListMonth($yearnow,$mt)>0) { ?> href="<?=ROOTPATHDOMAIN?>fair-calendar/?year=<?=$yearnow?>&month=<?=$mt?>" <? } ?>>
          <?=$array_m[$mm]?>
          <? if(getFairListMonth($yearnow,$mt)>0) { ?>
          <span
              class="position-absolute top-10 end-10 translate-middle badge rounded-pill bg-danger badgeCalerdarHome">
              <?=getFairListMonth($yearnow,$mt)?>
          </span>
          <? } ?>
      </a>
  </li>
  <? $mt++; } ?>
</ul>
