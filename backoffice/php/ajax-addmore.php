<?
include_once("../connect.php");

if($_REQUEST["method"]=="addurl") {
  ?>
  <div >
    <div class="col-lg-9">
      <input type="text" class="form-control" style="margin-bottom:5px;" name="fc_url[]" value="">
    </div>
  </div>
  <?
  exit();
}

if($_REQUEST["method"]=="addyoutube") {
  ?>
  <div >
    <div class="col-lg-9">
      <input type="text" class="form-control" style="margin-bottom:5px;" name="fc_youtube[]" value="">
    </div>
  </div>
  <?
  exit();
}

?>
