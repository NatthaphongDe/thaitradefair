<?php
$save_link = "admin_fair_content_gallery_edit";
$back_link = "admin_fair_content_portal";

if($_REQUEST["method"]=="delete") {
  include_once("../connect.php");

  $id = (int)$_GET["id"];


  $sql = "delete from tt_fair_content_gallery_file where gall_file_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();

    if($id>0) {
      $rmdirs =ROOTPATH."/data/fairgallery/".$id;
      deleteDirectory($rmdirs);
    }

  }

  exit();
}

if($_REQUEST["method"]=="add") {
  include_once("../connect.php");
  ini_set('display_errors', '1');

  $adminid = $_SESSION["id"];
  $fct_id = $_POST["fct_id"];
  $fair_id = $_POST["fair_id"];
  $fcat_id = $_POST["fcat_id"];
  $fcg_id = $_POST["fcg_id"];

  $fcg_title_th = $_POST["fcg_title_th"];
  $fcg_title_en = $_POST["fcg_title_en"];
  $fcg_detail_en = $_POST["fcg_detail_en"];
  $fcg_status = $_POST["fcg_status"];
  $fcg_pubish = $_POST["fcg_pubish"];

  $fcg_tag_fair_id = $_POST["tag_fair_id"];
  $fcg_tag_master_id = $_POST["tag_master_id"];

  $sql = "update tt_fair_content_gallery set
          fcg_status = ?,
          fcg_title_th = ?,
          fcg_title_en = ?,
          fcg_detail_en = ?,
          fcg_pubish = ?,
          fcg_tag_fair_id = ?,
          fcg_tag_master_id = ?,
          fcg_update_date = now(),
          fcg_update_by = ?
          where fcg_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('isssissii',$fcg_status,$fcg_title_th,$fcg_title_en,$fcg_detail_en,$fcg_pubish,$fcg_tag_fair_id,$fcg_tag_master_id,$adminid,$fcg_id);
    $stmt->execute();

    saveLogActivity(6,$_SESSION["id"],$fcg_id,"Edit");

    $sqlphoto = "select * from  tt_fair_content_gallery_file where fcg_id = ? order by gall_file_pos DESC limit 1 ";
    $stmtphoto = $mysqli->prepare($sqlphoto);
    $stmtphoto->bind_param('i',$fcg_id);
    $stmtphoto->execute();
    $resultphoto = $stmtphoto->get_result();
    $numrowphoto = $resultphoto->num_rows;
    if($numrowphoto>0) {
      $dataphoto = $resultphoto->fetch_assoc();
      $posfile = (int)$dataphoto["gall_file_pos"];
      $posfile = $posfile+1;
    } else {
      $posfile = 1;
    }


    $countfile = count(array_filter($_FILES['filebanner']['name']));
    if($countfile>0) {
      for($i=0;$i<$countfile;$i++ ) {

        $sqlgl = "insert into tt_fair_content_gallery_file (fcg_id,gall_file_pos,gall_file_create_date,gall_file_create_by) values (?,?,now(),?) ";
        $stmtgl = $mysqli->prepare($sqlgl);
        if($stmtgl) {
          $stmtgl->bind_param('iii',$fcg_id,$posfile,$adminid);
          $stmtgl->execute();
          $gall_file_id = $stmtgl->insert_id;

          $filedata = file_get_contents($_FILES["filebanner"]["tmp_name"][$i]);

          $path_parts = pathinfo($_FILES['filebanner']['name'][$i]);
          $extension = $path_parts['extension'];
          $path_namepic = alphanumeric_random_wms(10);
          $path_mini = ROOTPATH."/data/fairgallery/$gall_file_id/cover/$path_namepic.$extension";
          $pathdb = "/data/fairgallery/$gall_file_id/cover/$path_namepic.$extension";
          if (!is_dir(ROOTPATH."/data")){
            @mkdir(ROOTPATH."/data");
          }
          if (!is_dir(ROOTPATH."/data/fairgallery")){
            @mkdir(ROOTPATH."/data/fairgallery");
          }
          if (!is_dir(ROOTPATH."/data/fairgallery/".$gall_file_id)){
            @mkdir(ROOTPATH."/data/fairgallery/".$gall_file_id);
          }
          if (!is_dir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover")){
            @mkdir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover");
          }
          if (!is_dir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover/resize")){
            @mkdir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover/resize");
          }
          if (!is_dir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover/cropcenter")){
            @mkdir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover/cropcenter");
          }
          if (!is_dir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover/croptop")){
            @mkdir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover/croptop");
          }
          if (!is_dir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover/cropsq")){
            @mkdir(ROOTPATH."/data/fairgallery/".$gall_file_id."/cover/cropsq");
          }
          if(move_uploaded_file($_FILES["filebanner"]["tmp_name"][$i], $path_mini)) {
            $sqlup = "update tt_fair_content_gallery_file set gall_file_path = ? where gall_file_id = ? ";
            $stmtup = $mysqli->prepare($sqlup);
            if($stmtup) {
              $stmtup->bind_param('si',$pathdb,$gall_file_id);
              $stmtup->execute();
            }
            $posfile++;

            $path_resize = ROOTPATH."/data/fairgallery/$gall_file_id/cover/resize/$path_namepic.$extension";
            $path_cropcenter = ROOTPATH."/data/fairgallery/$gall_file_id/cover/cropcenter/$path_namepic.$extension";
            $path_croptop = ROOTPATH."/data/fairgallery/$gall_file_id/cover/croptop/$path_namepic.$extension";
            $path_cropsq = ROOTPATH."/data/fairgallery/$gall_file_id/cover/cropsq/$path_namepic.$extension";
            $wid = 1600;
            imageresize($filedata,$path_resize,$wid,0);

            $wid = 800;
            imageresize($filedata,$path_cropcenter,$wid,800,true,true);


            $wid = 600;
            imageresize($filedata,$path_cropsq,$wid,600,true,false);

            $wid = 500;
            imageresize($filedata,$path_croptop,$wid,600,true,false);

          }

        }

      }
    }

  }

  ?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.alertpopup("1","บันทึกรายการเรียบร้อย");
    setTimeout(function () {top.window.location="../home.php?show=<?=$back_link?>&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>";},500);
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

if($_REQUEST["method"]=="chgpublish") {
  include_once("../connect.php");

  $status = $_GET["status"];
  if($status==1) {
    $newsta = 2;
  } else {
    $newsta = 1;
  }

  if($newsta==1) {
    ?>
    <button type="button" onclick="changePublish();" class="btn btn-publish">Approved</a>
    <?
  } else {
    ?>
    <button type="button" onclick="changePublish();" class="btn btn-unpublish">Pending</a>
    <?
  }
  exit();
}

$fct_id = $_GET["fct_id"];
$fair_id = $_GET["fair_id"];

$fgid = 0;
$sql = " select * from tt_fair_group_list where fair_id = ? and fair_flag = '1' limit 1  ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('i',$fair_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();
    $fgid = $data["fair_group_id"];
  }
}

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

$menu_name = "Edit ".$datac["fcat_name"];

$id = $_GET["id"];
$sql = "select * from  tt_fair_content_gallery a left join tt_fair_list b on a.fair_id=b.fair_id where a.fcg_id = ?  ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('i',$id);
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

$pulishdata = 0;
if($data["fcg_status"]==1) {
  $pulishdata = 1;
} else {
  $pulishdata = 2;
}

?>

<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.12/css/lightgallery.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/lightgallery@1.6.12/dist/js/lightgallery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-thumbnail/1.1.0/lg-thumbnail.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-fullscreen/1.1.0/lg-fullscreen.min.js"></script> -->
<div class="row wrapper page-heading">
     <div class="col-xs-12">
       <br>
       <ol class="breadcrumb">
           <li>
               <a href="home.php?show=<?=$back_link?>&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>"><h3><i class="fa fa-angle-left backnav-size " aria-hidden="true"></i> <span class="backnav-size-txt">BACK</span></h3></a>
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

            <div class="row">
              <div class="col-xs-6">
                <h3><?=$menu_name?></h3>
              </div>
              <div class="col-xs-6 text-right">
                <span class="st-grey-color">Status&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>
                <span class="_pp">
                  <? if($pulishdata==1) { ?>
                    <button type="button"  class="btn btn-publish">Approved</a>
                  <? } else { ?>
                    <button type="button"  class="btn btn-unpublish">Pending</a>
                  <? } ?>
                </span>
              </div>
            </div>

            <div class="ibox-content">

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Title [EN]*</label>
                    <div class="col-lg-12">
                      <input type="text" class="form-control" name="fcg_title_en" required value="<?=$data["fcg_title_en"]?>"  />
                    </div>
                  </div>
                </div>

                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Title [TH]</label>
                    <div class="col-lg-12">
                      <input type="text" class="form-control" name="fcg_title_th" value="<?=$data["fcg_title_th"]?>" />
                    </div>
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Description</label>
                    <div class="col-lg-12">
                      <textarea name="fcg_detail_en" class="form-control" rows="5"><?=$data["fcg_detail_en"]?></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Image</label>

                    <div class="col-lg-12">
                      <div class="row" id="lightgallery">
                      <?
                      $sqlphoto = "select * from  tt_fair_content_gallery_file where fcg_id = ? order by gall_file_pos ASC ";
                      $stmtphoto = $mysqli->prepare($sqlphoto);
                      $stmtphoto->bind_param('i',$data["fcg_id"]);
                      $stmtphoto->execute();
                      $resultphoto = $stmtphoto->get_result();
                      $numrowphoto = $resultphoto->num_rows;
                      if($numrowphoto>0) {
                        while($dataphoto = $resultphoto->fetch_assoc()) {
                          $croppath = str_replace('/cover','/cover/cropcenter',$dataphoto["gall_file_path"]);
                          $cover = ROOTPATHDOMAIN.$croppath;
                          ?>
                          <div class="col-xs-4 _filebolg_<?=$dataphoto["gall_file_id"]?>" style="position:relative; overflow:hidden; ">
                            <div style="position: absolute; z-index:1; right:5px; top:0;">
                              <img src="asset/icon_x.png" onclick="removeFile('<?=$dataphoto["gall_file_id"]?>');" >
                            </div>

                            <div style="width:100%; height:100px; margin:auto; margin-top: 5px; margin-bottom: 5px; overflow:hidden; border:1px solid #ccc; border-radius:10px;">
                              <img src="<?=$cover?>" width="100%">
                            </div>

                          </div>
                          <?
                        }
                      }
                      ?>
                      </div>
                    </div>



                    <div class="col-lg-12">
                      <br>
                      <input type="file" name="filebanner[]" class="form-control" multiple accept="image/*"  >
                      <span class="text-danger " style="font-size:90%;">
                        *กรุณาเลือกไฟล์ JPG,JPEG,PNG ที่มีขนาดมากกว่า 1600*900 px ขึ้นไปเพื่อความสวยงามของการแสดงผล
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Status การแสดงผล</label>
                    <div class="col-lg-9">
                      <div class="s_" style="padding-top:7px;">
                        <? if($data["fcg_status"]==1) { ?>
                          <img src="../backoffice/asset/icon_active.png" onclick="changeStatus();" >
                        <? } else { ?>
                          <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatus();" >
                        <? } ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Tag Fair</label>
                    <div class="col-lg-12">
                      <?
                      $sqlf = "select * from tt_fair_group where fair_group_status != '9' order by fair_group_abb ASC ";
                      $stmtf = $mysqli->prepare($sqlf);
                      if($stmtf) {
                        $stmtf->execute();
                        $resultf = $stmtf->get_result();
                        $numrowf = $resultf->num_rows;
                        if($numrowf>0) {
                          while($dataf = $resultf->fetch_assoc()) {
                            ?>
                            <span>
                              <button onclick="changeTagFair('<?=$dataf["fair_group_id"]?>');" id="tagf_<?=$dataf["fair_group_id"]?>" type="button" class="tagf btn btn-tag <? if($data["fcg_tag_fair_id"]==$dataf["fair_group_id"]) { ?>btn-tag-active<? } ?>"><?=$dataf["fair_group_abb"]?></button>
                            </span>
                            <?
                          }
                        }
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Tags Master</label>
                    <div class="col-lg-12 _showtagmaster">
                      <?
                      $datatag = $data["fcg_tag_master_id"];
                      $tagitem = explode('|',$datatag);
                      $sqlf = "select * from tt_tag_master where tag_status != '9' order by tag_name ASC ";
                      $stmtf = $mysqli->prepare($sqlf);
                      if($stmtf) {
                        $stmtf->execute();
                        $resultf = $stmtf->get_result();
                        $numrowf = $resultf->num_rows;
                        if($numrowf>0) {
                          while($dataf = $resultf->fetch_assoc()) {
                            $haveteg = 0;
                            for($tt=0;$tt<count($tagitem);$tt++) {
                              if($dataf["tag_id"]==$tagitem[$tt]) {
                                $haveteg = 1;
                                break;
                              }
                            }
                            ?>
                            <span>
                              <button data-val="<?=$haveteg?>" data-id="<?=$dataf["tag_id"]?>" onclick="changeTagMaster('<?=$dataf["tag_id"]?>');" id="tagm_<?=$dataf["tag_id"]?>" type="button" class="tagm_ btn btn-tag <? if($haveteg==1) { ?>btn-tag-active<? } ?>"><?=$dataf["tag_name"]?></button>
                            </span>
                            <?
                          }
                        }
                      }

                      ?>
                      <span>
                        <button onclick="addTag();" type="button" class="btn btn-addtag">+ Add Tags</button>
                      </span>
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
                  <a href="home.php?show=<?=$back_link?>&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>" class="btn btn-default" style="width:150px;">Cancel</a>
                </div>
            </div>

            <input type="hidden" name="fcg_id" value="<?=$data["fcg_id"]?>">
            <input type="hidden" name="fair_id" value="<?=$fair_id?>">
            <input type="hidden" name="fct_id" value="<?=$fct_id?>">
            <input type="hidden" name="fcat_id" value="<?=$datac["fcat_id"]?>">
            <input type="hidden" name="fcg_status" id="fcg_status" value="<?=$data["fcg_status"]?>">
            <input type="hidden" name="fcg_pubish" id="fcg_pubish" value="<?=$pulishdata?>">

            <input type="hidden" name="tag_master_id" id="tag_master_id" value="<?=$datatag?>">
            <input type="hidden" name="tag_fair_id" id="tag_fair_id" value="<?=$data["fcg_tag_fair_id"]?>">
            </form>
          </div>
        </div>
	</div>
</div>



<script type="text/javascript">
function changeStatus() {
  var st = $('#fcg_status').val();
  $.ajax({
      type: "GET",
      url: "php/admin_fair_content_gallery_edit.php?method=chgstatus&status="+st,
      dataType: "text",
      success : function(data) {
        $('.s_').empty();
        $('.s_').html(data);
        if(st==1) {
          $('#fcg_status').val(2);
        } else {
          $('#fcg_status').val(1);
        }
      }
  });
}

function changePublish() {
  var st = $('#fcg_pubish').val();
  $.ajax({
      type: "GET",
      url: "php/admin_fair_content_gallery_edit.php?method=chgpublish&status="+st,
      dataType: "text",
      success : function(data) {
        $('._pp').empty();
        $('._pp').html(data);
        if(st==1) {
          $('#fcg_pubish').val(2);
        } else {
          $('#fcg_pubish').val(1);
        }
      }
  });
}

function removeFile(id) {
  swal({
      title: "ยืนยันการลบรายการ",
      text: "ยืนยันการลบรายการ",
     type: "error",
      showCancelButton: true,
      confirmButtonColor: "#F27474",
      confirmButtonText: "ตกลง",
  cancelButtonText: "ยกเลิก",
      closeOnConfirm: true
    }, function (isConfirm) {
      if (isConfirm) {
          removeFileAction(id);
      }
    });
}

function removeFileAction(id) {
  $.ajax({
      type: "GET",
      url: "php/admin_fair_content_gallery_edit.php?method=delete&id="+id,
      dataType: "text",
      success : function(data) {
        $('._filebolg_'+id).remove();
      }
  });
}


/*
$(document).ready(function() {
  $("#lightgallery").lightGallery();
});
*/

</script>
