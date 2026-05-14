<?
include_once("../connect.php");
if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $idaddmin = $_SESSION["id"];
  $passwordt = $_POST["passwordt"];
  $fair_id = $_POST["fair_id"];

	$sql = "select * from tt_admin where id = ? and password = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('is',$idaddmin,$passwordt);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if($numrow>0) {
      ?>
      <script type="text/javascript">
        setTimeout(function () {top.window.location="../home.php?show=fair_token&fair_id=<?=$fair_id?>";},500);
      </script>
      <?php
      exit();
    } else {
      ?>
      <script type="text/javascript">
        top.pc_overlay(2);
        top.alertpopup("2","รหัสผ่านไม่ถูกต้อง");
      </script>
      <?php
      exit();
    }
  }
  exit();
}

$fair_id = $_GET["id"];
?>
<form class="form-horizontal" method="post" name="form_Suppliers_add" enctype="multipart/form-data" id="form_Suppliers_add" action="php/fair_token_pass.php?method=add" target="com_m" onSubmit="pc_overlay(1);" >
<div class="modal-content">

    <div class="modal-body">
      <div class="row">
        <div class="col-xs-12">
          <strong class="menu_n_menu">Admin Password</strong>
        </div>
      </div>
      <br>

      <div class="row">
        <div class="col-xs-6 col-lg-6">
          <div class="form-group">
            <label class="col-lg-12 control-label">Password </label>
            <div class="col-lg-12">
              <input type="password" class="form-control" name="passwordt" required  autocomplete="off">
            </div>
          </div>
        </div>
      </div>

      <br>
      <div class="row">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-success" style="width:150px;">Continue</button>
              &nbsp;&nbsp;&nbsp;
            <a href="home.php?show=fair_list" class="btn btn-default" style="width:150px;">Cancel</a>
          </div>
      </div>


    </div>

</div>

<input type="hidden" name="fair_id" value="<?=$fair_id?>">
</form>
