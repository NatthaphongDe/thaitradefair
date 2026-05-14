<?php
$header_name = "Setting Business Matching";
$menu_name = "Setting Business Matching";
$back_link = "bu_item";

if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $bu_url = $_POST["bu_url"];
  $bu_id = $_POST["bu_id"];

  $sql = "update tt_business_link set bu_url = ?, bu_update_date = now(), bu_update_by = ? where bu_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('sii',$bu_url,$adminid,$bu_id);
    $stmt->execute();
  }

  ?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.alertpopup("1","บันทึกรายการเรียบร้อย");
    setTimeout(function () {top.window.location="../home.php?show=<?=$back_link?>";},500);
  </script>
  <?php
  exit();

  exit();
}
?>

<div class="row wrapper page-heading">
     <div class="col-xs-4 col-lg-6">
       <br>
        <ol class="breadcrumb">
            <li>
                <a><h2><?=$header_name?></h2></a>
            </li>
        </ol>
    </div>

    <div class="col-xs-8 col-lg-6 text-right"><h3>&nbsp;</h3>
    </div>

</div>

<?
$adminid = $_SESSION["id"];
$sql = "select * from tt_business_link where bu_id = '1' limit 1 ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow==0) {
    $sqlup = " insert into tt_business_link (bu_id,bu_create_date,bu_create_by,bu_update_date,bu_update_by) values ('1',now(),?,now(),?) ";
    $stmtup = $mysqli->prepare($sqlup);
    $stmtup->bind_param('ii',$adminid,$adminid);
    $stmtup->execute();
  }
}

$sql = "select * from tt_business_link where bu_id = '1' limit 1 ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();
  } else {
    ?>
    <script type="text/javascript">
      top.window.location='home.php';
    </script>
    <?
    exit();
  }
} else {
  ?>
  <script type="text/javascript">
    top.window.location='home.php';
  </script>
  <?
  exit();
}
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        	<div class="ibox float-e-margins">
            <form class="form-horizontal" method="post" name="form_Suppliers_add" enctype="multipart/form-data" id="form_Suppliers_add" action="php/<?=$back_link?>.php?method=add" target="com_m" onSubmit="pc_overlay(1);">

            <div class="ibox-content">

              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Business Matching URL</label>
                    <div class="col-lg-12">
                      <input type="text" class="form-control" name="bu_url" value="<?=$data["bu_url"]?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <br>
            <div class="row">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn-success" style="width:150px;">Save</button>
                    &nbsp;&nbsp;&nbsp;
                  <a href="home.php?show=<?=$back_link?>" class="btn btn-default" style="width:150px;">Cancel</a>
                </div>
            </div>

            <input type="hidden" name="bu_id" value="<?=$data["bu_id"]?>">
            </form>
          </div>
        </div>
	</div>
</div>
