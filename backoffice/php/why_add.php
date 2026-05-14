<?php
$menu_name = "Create";
$save_link = "why_add";
$back_link = "why_list";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $why_date = $_POST["why_date"];
  $why_detail = $_POST["why_detail"];
  $why_title = $_POST["why_title"];
  $pid = $_POST["pid"];

  $sql = "insert into tt_why_content (why_date,why_detail,why_status,why_create_date,why_create_by,why_update_date,why_update_by,why_title,pid) values (?,?,'1',now(),?,now(),?,?,?) ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('ssiiss',$why_date,$why_detail,$adminid,$adminid,$why_title,$pid);
    $stmt->execute();
    $why_id = $stmt->insert_id;

    if($_FILES['filebanner']['name']!="") {

      if($why_id>0) {
        $rmdirs =ROOTPATH."/data/whythailand/".$why_id."/banner";
        deleteDirectory($rmdirs);
      }

      $filedata = file_get_contents($_FILES["filebanner"]["tmp_name"]);

      $path_parts = pathinfo($_FILES['filebanner']['name']);
      $extension = $path_parts['extension'];
      $path_namepic = alphanumeric_random_wms(10);
      $path_mini = ROOTPATH."/data/whythailand/$why_id/banner/$path_namepic.$extension";
      $pathdb = "/data/whythailand/$why_id/banner/$path_namepic.$extension";
      if (!is_dir(ROOTPATH."/data")){
        @mkdir(ROOTPATH."/data");
      }
      if (!is_dir(ROOTPATH."/data/whythailand")){
        @mkdir(ROOTPATH."/data/whythailand");
      }
      if (!is_dir(ROOTPATH."/data/whythailand/".$why_id)){
        @mkdir(ROOTPATH."/data/whythailand/".$why_id);
      }
      if (!is_dir(ROOTPATH."/data/whythailand/".$why_id."/banner")){
        @mkdir(ROOTPATH."/data/whythailand/".$why_id."/banner");
      }

      $sqlup = "update tt_why_content set why_banner_path = ? where why_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('si',$pathdb,$why_id);
        $stmtup->execute();
      }

      $wid = 1900;
      imageresize($filedata,$path_mini,$wid,0);
      /*
      if(move_uploaded_file($_FILES["filebanner"]["tmp_name"], $path_mini)) {
        $sqlup = "update tt_why_content set why_banner_path = ? where why_id = ? ";
        $stmtup = $mysqli->prepare($sqlup);
        if($stmtup) {
          $stmtup->bind_param('si',$pathdb,$why_id);
          $stmtup->execute();
        }
      }
      */
    }

    if($_FILES['filelogoa']['name']!="") {

      if($why_id>0) {
        $rmdirs =ROOTPATH."/data/whythailand/".$why_id."/logoactive";
        deleteDirectory($rmdirs);
      }

      $path_parts = pathinfo($_FILES['filelogoa']['name']);
      $extension = $path_parts['extension'];
      $path_namepic = alphanumeric_random_wms(10);
      $path_mini = ROOTPATH."/data/whythailand/$why_id/logoactive/$path_namepic.$extension";
      $pathdb = "/data/whythailand/$why_id/logoactive/$path_namepic.$extension";
      if (!is_dir(ROOTPATH."/data")){
        @mkdir(ROOTPATH."/data");
      }
      if (!is_dir(ROOTPATH."/data/whythailand")){
        @mkdir(ROOTPATH."/data/whythailand");
      }
      if (!is_dir(ROOTPATH."/data/whythailand/".$why_id)){
        @mkdir(ROOTPATH."/data/whythailand/".$why_id);
      }
      if (!is_dir(ROOTPATH."/data/whythailand/".$why_id."/logoactive")){
        @mkdir(ROOTPATH."/data/whythailand/".$why_id."/logoactive");
      }
      if(move_uploaded_file($_FILES["filelogoa"]["tmp_name"], $path_mini)) {
        $sqlup = "update tt_why_content set why_logo_act_path = ? where why_id = ? ";
        $stmtup = $mysqli->prepare($sqlup);
        if($stmtup) {
          $stmtup->bind_param('si',$pathdb,$why_id);
          $stmtup->execute();
        }
      }
    }

    if($_FILES['filelogoan']['name']!="") {

      if($why_id>0) {
        $rmdirs =ROOTPATH."/data/whythailand/".$why_id."/logoinactive";
        deleteDirectory($rmdirs);
      }

      $path_parts = pathinfo($_FILES['filelogoan']['name']);
      $extension = $path_parts['extension'];
      $path_namepic = alphanumeric_random_wms(10);
      $path_mini = ROOTPATH."/data/whythailand/$why_id/logoinactive/$path_namepic.$extension";
      $pathdb = "/data/whythailand/$why_id/logoinactive/$path_namepic.$extension";
      if (!is_dir(ROOTPATH."/data")){
        @mkdir(ROOTPATH."/data");
      }
      if (!is_dir(ROOTPATH."/data/whythailand")){
        @mkdir(ROOTPATH."/data/whythailand");
      }
      if (!is_dir(ROOTPATH."/data/whythailand/".$why_id)){
        @mkdir(ROOTPATH."/data/whythailand/".$why_id);
      }
      if (!is_dir(ROOTPATH."/data/whythailand/".$why_id."/logoinactive")){
        @mkdir(ROOTPATH."/data/whythailand/".$why_id."/logoinactive");
      }
      if(move_uploaded_file($_FILES["filelogoan"]["tmp_name"], $path_mini)) {
        $sqlup = "update tt_why_content set why_logo_null_path = ? where why_id = ? ";
        $stmtup = $mysqli->prepare($sqlup);
        if($stmtup) {
          $stmtup->bind_param('si',$pathdb,$why_id);
          $stmtup->execute();
        }
      }
    }



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

$pid = uniqid();
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
                <div class="col-xs-3">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Logo Active</label>
                    <div class="col-lg-12">
                      <input type="file" name="filelogoa" class="form-control" required  >
                    </div>
                  </div>
                </div>

                <div class="col-xs-3">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Logo Inactive</label>
                    <div class="col-lg-12">
                      <input type="file" name="filelogoan" class="form-control" required  >
                    </div>
                  </div>
                </div>

                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Banner Image</label>
                    <div class="col-lg-12">
                      <input type="file" name="filebanner" class="form-control" required  >
                    </div>
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Create Date</label>
                    <div class="col-lg-12">
                      <div class='input-group date datepicker_box'>
                          <input type="text" class="form-control" name="why_date" required />
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Subject</label>
                    <div class="col-lg-12">
                      <input type="text" class="form-control" name="why_title" required  />
                    </div>
                  </div>
                </div>

              </div>


              <div class="row">
                <div class="col-xs-9">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Content </label>
                    <div class="col-lg-12">
                      <textarea name="why_detail" class="tinyclass"></textarea>
                    </div>
                  </div>
                </div>

                <div class="col-xs-3">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Upload CMS Photo </label>
                    <div class="col-lg-12">
                      <iframe src="php/cms_photo.php?pid=<?=$pid?>" frameborder="0" width="100%" height="500" style="overflow-x:hidden; overflow-y:auto"></iframe>
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

            <input type="hidden" name="pid" value="<?=$pid?>">
            </form>
          </div>
        </div>
	</div>
</div>
