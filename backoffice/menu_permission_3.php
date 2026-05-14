<?
$sqlrl = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_type = '1' order by a.fct_pos ASC ";
$stmtrl = $mysqli->prepare($sqlrl);
$stmtrl->bind_param('i',$_SESSION["frlid"]);
$stmtrl->execute();
$resultrl = $stmtrl->get_result();
$numrowrl = $resultrl->num_rows;
if($numrowrl>0) {
  while($datarl = $resultrl->fetch_assoc()) {
    ?>

    <? if($datarl["fct_cms_type"]==0) { ?>
      <li class="active">
        <a><img src="asset/icon_report.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label"><?=$datarl["fcat_name"]?></span>  <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">

          <?
          $runno++;

          $sqlc2 = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_master = ? and b.fcat_type = '2' order by a.fct_pos ASC ";
          $stmtc2 = $mysqli->prepare($sqlc2);
          if($stmtc2) {
            $stmtc2->bind_param('ii',$_SESSION["frlid"],$datarl["fcat_id"]);
            $stmtc2->execute();
            $resultc2 = $stmtc2->get_result();
            $numrowc2 = $resultc2->num_rows;
            if($numrowc2>0) {
              while($datac2 = $resultc2->fetch_assoc()) {

              $actionlink = "";
              if($datac2["fct_cms_type"]==1 or $datac2["fct_cms_type"]==2 or $datac2["fct_cms_type"]==3 or $datac2["fct_cms_type"]==4) {
                $actionlink = "admin_fair_content_portal";
              }
              ?>
              <li <?php if(($_REQUEST['show']==$actionlink or $_REQUEST['show']=="admin_fair_content_aricle_add" or $_REQUEST['show']=="admin_fair_content_aricle_edit") and  $_REQUEST['fct_id']==$datac2["fct_id"]){echo "class='active'";}?>><a href="home.php?show=<?=$actionlink?>&fct_id=<?=$datac2["fct_id"]?>">• <?=$datac2["fcat_name"]?></a></li>
          <? } } } ?>



        </ul>
      </li>
    <? } else { ?>
      <?
      $actionlink = "";
      if($datarl["fct_cms_type"]==1 or $datarl["fct_cms_type"]==2 or $datarl["fct_cms_type"]==3  or $datarl["fct_cms_type"]==4) {
        $actionlink = "admin_fair_content_portal";
      }
      ?>
      <li class="active">
        <a href="home.php?show=<?=$actionlink?>&fct_id=<?=$datarl["fct_id"]?>"><img src="asset/icon_report.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label"><?=$datarl["fcat_name"]?></span> </a>
      </li>
    <? } ?>

    <?
  }
}
?>

<li class="active" style="position:relative;">
  <div style="position:absolute; z-index:1; top:8px; left:35px; display:none;">
    <span class="text-danger"><i class="fa fa-bell" aria-hidden="true"></i></span>
  </div>
  <a><span><img src="asset/icon_log.png" height="12"></span>&nbsp;&nbsp;&nbsp;<span class="nav-label">Log History</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

          <li><a>• Online Meeting Log</a></li>
          <li><a>• Live Chat Log</a></li>


  </ul>
</li>
