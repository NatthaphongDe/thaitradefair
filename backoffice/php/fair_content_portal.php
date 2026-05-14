<?
$fct_id = $_GET["fct_id"];
$fair_id = $_GET["fair_id"];

$sqlc = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and a.fct_id = ? limit 1 ";
$stmtc = $mysqli->prepare($sqlc);
if($stmtc) {
  $stmtc->bind_param('ii',$fair_id,$fct_id);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;
  if($numrowc>0) {
    $datac = $resultc->fetch_assoc();
  } else {
    ?>
    <script type="text/javascript">
      top.window.location='home.php?show=fair_menu_list&id=<?=$fair_id?>';
    </script>
    <?
    exit();
  }
} else {
  ?>
  <script type="text/javascript">
    top.window.location='home.php?show=fair_menu_list&id=<?=$fair_id?>';
  </script>
  <?
  exit();
}

if($datac["fct_cms_type"]==1) {
  include "fair_content_onepage.php";
}

if($datac["fct_cms_type"]==2) {
  include "fair_content_aricle.php";
}

if($datac["fct_cms_type"]==3) {
  include "fair_content_aricle.php";
}
if($datac["fct_cms_type"]==4) {
  include "fair_content_gallery.php";
}
if($datac["fct_cms_type"]==5) {
  include "fair_exhibitor_list.php";
}
?>
