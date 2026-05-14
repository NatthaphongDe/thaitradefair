<?php
$menu_name = "Create Cover Image";
$save_link = "adfair_cover_add";
$back_link = "adfair_cover";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $fair_id = $_POST["fair_id"];
  $cover_url = $_POST["cover_url"];
  $cover_status = $_POST["cover_status"];

  $sql = "select * from tt_fair_cover where fair_id = ? order by cover_pos DESC limit 1 ";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('i',$fair_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();
    $positem = (int)$data["cover_pos"];
    $positem = $positem+1;
  } else {
    $positem = 1;
  }



  $sql = "insert into tt_fair_cover (fair_id,cover_url,cover_status,cover_pos,cover_create_date,cover_create_by,cover_update_date,cover_update_by) values (?,?,?,?,now(),?,now(),?) ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('isiiii',$fair_id,$cover_url,$cover_status,$positem,$adminid,$adminid);
    $stmt->execute();
    $cover_id = $stmt->insert_id;

    if($_FILES['filecover']['name']!="") {

      $filedata = file_get_contents($_FILES["filecover"]["tmp_name"]);

      if($cover_id>0) {
        $rmdirs =ROOTPATH."/data/faircover/".$cover_id;
        deleteDirectory($rmdirs);
      }

      $path_parts = pathinfo($_FILES['filecover']['name']);
      $extension = $path_parts['extension'];
      $path_namepic = alphanumeric_random_wms(10);
      $path_mini = ROOTPATH."/data/faircover/$cover_id/banner/$path_namepic.$extension";
      $pathdb = "/data/faircover/$cover_id/banner/$path_namepic.$extension";
      if (!is_dir(ROOTPATH."/data")){
        @mkdir(ROOTPATH."/data");
      }
      if (!is_dir(ROOTPATH."/data/faircover")){
        @mkdir(ROOTPATH."/data/faircover");
      }
      if (!is_dir(ROOTPATH."/data/faircover/".$cover_id)){
        @mkdir(ROOTPATH."/data/faircover/".$cover_id);
      }
      if (!is_dir(ROOTPATH."/data/faircover/".$cover_id."/banner")){
        @mkdir(ROOTPATH."/data/faircover/".$cover_id."/banner");
      }
      if (!is_dir(ROOTPATH."/data/faircover/".$cover_id."/resize")){
        @mkdir(ROOTPATH."/data/faircover/".$cover_id."/resize");
      }
      if (!is_dir(ROOTPATH."/data/faircover/".$cover_id."/crop")){
        @mkdir(ROOTPATH."/data/faircover/".$cover_id."/crop");
      }
      if(move_uploaded_file($_FILES["filecover"]["tmp_name"], $path_mini)) {
        $sqlup = "update tt_fair_cover set cover_path = ? where cover_id = ? ";
        $stmtup = $mysqli->prepare($sqlup);
        if($stmtup) {
          $stmtup->bind_param('si',$pathdb,$cover_id);
          $stmtup->execute();
        }

        $path_resize = ROOTPATH."/data/faircover/$cover_id/resize/$path_namepic.$extension";
        $path_crop = ROOTPATH."/data/faircover/$cover_id/crop/$path_namepic.$extension";
        $wid = 1600;
        imageresize($filedata,$path_resize,$wid,0);

        $wid = 1200;
        imageresize($filedata,$path_crop,$wid,380,true,true);

      }
    }

  }

  ?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.alertpopup("1","บันทึกรายการเรียบร้อย");
    setTimeout(function () {top.window.location="../home.php?show=<?=$back_link?>&id=<?=$fair_id?>";},500);
  </script>
  <?php
  exit();

  exit();
}

if($_REQUEST["method"]=="chgstatus") {
  include_once("../connect.php");

  $status = $_GET["status"];
  if($status==1) {
    $newsta = 2;
  } else {
    $newsta = 1;
  }

  if($newsta==1) {
    ?>
    <img src="../backoffice/asset/icon_active.png" onclick="changeStatus();" >
    <?
  } else {
    ?>
    <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatus();" >
    <?
  }
  exit();

}
?>

<?
$fair_id = $_GET["id"];
$sql = "select * from tt_fair_list where fair_id = ? ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('i',$fair_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();
  } else {
    ?>
    <script type="text/javascript">
      top.window.location='home.php?show=<?=$back_link?>';
    </script>
    <?
    exit();
  }
} else {
    ?>
    <script type="text/javascript">
      top.window.location='home.php?show=<?=$back_link?>';
    </script>
    <?
    exit();
}
?>

<div class="row wrapper page-heading">
     <div class="col-xs-12">
       <br>
       <ol class="breadcrumb">
           <li>
               <a href="home.php?show=<?=$back_link?>&id=<?=$fair_id?>"><h3><i class="fa fa-angle-left backnav-size " aria-hidden="true"></i> <span class="backnav-size-txt">BACK</span></h3></a>
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
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-6 control-label">Cover Image/VDO</label>
                    <div class="col-lg-12">
                      <input type="file" name="filecover" class="form-control" required  >
                      <span class="text-danger " style="font-size:90%;">
                        *กรุณาเลือกไฟล์ JPG,JPEG,PNG ที่มีขนาดมากกว่า 1600*540 px ขึ้นไปเพื่อความสวยงามของการแสดงผล
                      </span>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6">
                  <div class="form-group">
                    <div class="col-lg-12 text-right">
                      <label class="col-lg-9 text-right control-label" style="text-align:right !important;">การแสดงผล</label>
                      <div class="col-lg-3">
                        <div class="s_">
                          <img src="../backoffice/asset/icon_active.png" onclick="changeStatus();" >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12 col-sm-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">URL</label>
                    <div class="col-lg-12">
                      <input type="text" class="form-control" name="cover_url"  />
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
                  <a href="home.php?show=<?=$back_link?>&id=<?=$fair_id?>" class="btn btn-default" style="width:150px;">Cancel</a>
                </div>
            </div>

            <input type="hidden" name="fair_id" id="fair_id" value="<?=$fair_id?>">
            <input type="hidden" name="cover_status" id="cover_status" value="1">
            </form>
          </div>
        </div>
	</div>
</div>


<script type="text/javascript">
function changeStatus() {
  var st = $('#cover_status').val();
  $.ajax({
      type: "GET",
      url: "php/adfair_cover_add.php?method=chgstatus&status="+st,
      dataType: "text",
      success : function(data) {
        $('.s_').empty();
        $('.s_').html(data);
        if(st==1) {
          $('#cover_status').val(2);
        } else {
          $('#cover_status').val(1);
        }
      }
  });
}
</script>
