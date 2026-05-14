<?php
$menu_name = "Create Tag Master";
$save_link = "tag_add";
$back_link = "tag_list";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $tag_name = $_POST["tag_name"];
  $tag_status = 1;

  $sql = "insert into tt_tag_master (tag_name,tag_status,tag_create_date,tag_create_by,tag_update_date,tag_update_by) values (?,?,now(),?,now(),?) ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('siii',$tag_name,$tag_status,$adminid,$adminid);
    $stmt->execute();

    ?>
    <script type="text/javascript">
      top.pc_overlay(2);
      top.alertpopup("1","บันทึกรายการเรียบร้อย");
      setTimeout(function () {top.window.location="../home.php?show=<?=$back_link?>";},500);
    </script>
    <?php
    exit();

  } else {
    ?>
    <script type="text/javascript">
      top.pc_overlay(2);
      top.alertpopup("2","ไม่สามารถบันทึกรายการได้ กรุณาลองใหม่");
    </script>
    <?php
    exit();
  }

  exit();
}
?>

<div class="row wrapper page-heading">
     <div class="col-xs-12">
       <br>
       <ol class="breadcrumb">
           <li>
               <a href="home.php?show=<?=$back_link?>"><h3><i class="fa fa-angle-left backnav-size " aria-hidden="true"></i> <span class="backnav-size-txt">BACK</span></h3></a>
           </li>
       </ol>
       <div class="bottom-blue"></div>
    </div>

</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        	<div class="ibox float-e-margins">
            <form class="form-horizontal" method="post" name="form_Suppliers_add" enctype="multipart/form-data" id="form_Suppliers_add" action="php/<?=$save_link?>.php?method=add" target="com_m" onSubmit="pc_overlay(1);">

            <h3><?=$menu_name?></h3>
            <div class="ibox-content">


                <div class="row">
                  <div class="col-xs-12">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Tag Name*</label>
                      <div class="col-lg-12">
                        <input type="text" class="form-control" name="tag_name" required autocomplete="off" />
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

            </form>
          </div>
        </div>
	</div>
</div>
