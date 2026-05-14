<?php
$menu_name = "Create Gallery";
$save_link = "gallery_add";
$back_link = "gallery_list";

if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $fair_id = $_POST["fair_id"];
  $fcat_id = 6;

  $fcg_title_th = $_POST["fcg_title_th"];
  $fcg_title_en = $_POST["fcg_title_en"];
  $fcg_detail_en = $_POST["fcg_detail_en"];
  $fcg_status = $_POST["fcg_status"];
  $fcg_pubish = $_POST["fcg_pubish"];

  $fcg_tag_fair_id = $_POST["tag_fair_id"];
  $fcg_tag_master_id = $_POST["tag_master_id"];

  $fct_id = 0;
  $sqlf = "select * from tt_fair_list_cat where fair_id = ? and fcat_id = ? limit 1 ";
  $stmtf = $mysqli->prepare($sqlf);
  if($stmtf) {
    $stmtf->bind_param('ii',$fair_id,$fcat_id);
    $stmtf->execute();
    $resultf = $stmtf->get_result();
    $numrowf = $resultf->num_rows;
    if($numrowf>0) {
      $dataf = $resultf->fetch_assoc();
      $fct_id = $dataf["fct_id"];
    }
  }

  $sql = "insert into tt_fair_content_gallery (fair_id,fct_id,fcat_id,fcg_status,fcg_create_date,fcg_create_by,fcg_update_date,fcg_update_by,fcg_title_th,fcg_title_en,fcg_detail_en,fcg_pubish,fcg_tag_fair_id,fcg_tag_master_id) values (?,?,?,?,now(),?,now(),?,?,?,?,?,?,?) ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('iiiiiisssiss',$fair_id,$fct_id,$fcat_id,$fcg_status,$adminid,$adminid,$fcg_title_th,$fcg_title_en,$fcg_detail_en,$fcg_pubish,$fcg_tag_fair_id,$fcg_tag_master_id);
    $stmt->execute();
    $fcg_id = $stmt->insert_id;

    saveLogActivity(6,$_SESSION["id"],$fcg_id,"Create");

    $posfile = 1;
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

if($_REQUEST["method"]=="changefair") {
  include_once("../connect.php");

  $gid = $_GET["gid"];
  ?>
  <select class="form-control" name="fair_id" required>
    <option value="">- Select Fair List -</option>
    <?
    $sqlf = "select * from tt_fair_list where fair_status != '9' and fair_id in (select fair_id from tt_fair_group_list where fair_group_id = ? and fair_flag = 1) order by fair_name ASC ";
    $stmtf = $mysqli->prepare($sqlf);
    if($stmtf) {
      $stmtf->bind_param('i',$gid);
      $stmtf->execute();
      $resultf = $stmtf->get_result();
      $numrowf = $resultf->num_rows;
      if($numrowf>0) {
        while($dataf = $resultf->fetch_assoc()) {
          ?>
          <option value="<?=$dataf["fair_id"]?>"><?=$dataf["fair_name"]?></option>
          <?
        }
      }
    }
    ?>
  </select>
  <?
  exit();
}
?>

<?
$pulishdata = 1;
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

            <div class="row">
              <div class="col-xs-6">
                <h3><?=$menu_name?></h3>
              </div>
              <div class="col-xs-6 text-right">
                <span class="st-grey-color">Status&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>
                <span class="_pp">
                  <? if($pulishdata==1) { ?>
                    <button type="button" onclick="changePublish();" class="btn btn-publish">Approved</a>
                  <? } else { ?>
                    <button type="button" onclick="changePublish();" class="btn btn-unpublish">Pending</a>
                  <? } ?>
                </span>
              </div>
            </div>

            <div class="ibox-content">

              <div class="row">

                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Fair Group</label>
                    <div class="col-lg-12">
                      <select class="form-control" name="fair_group_id" required onchange="changeTagFair(this.value);changeSeleteFair(this.value);">
                        <option value="">- Select Fair Group -</option>
                        <?
                        $sqlf = "select * from tt_fair_group where fair_group_status != '9' order by fair_group_name_th ASC ";
                        $stmtf = $mysqli->prepare($sqlf);
                        if($stmtf) {
                          $stmtf->execute();
                          $resultf = $stmtf->get_result();
                          $numrowf = $resultf->num_rows;
                          if($numrowf>0) {
                            while($dataf = $resultf->fetch_assoc()) {
                              ?>
                              <option value="<?=$dataf["fair_group_id"]?>" <? if($data["fair_group_id"]==$dataf["fair_group_id"]) { echo "selected"; } ?> ><?=$dataf["fair_group_name_th"]?></option>
                              <?
                            }
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Fair List</label>
                    <div class="col-lg-12 _fairdiv">
                      <select class="form-control" name="fair_id" required>
                        <option value="">- Select Fair List -</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <hr>

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Title [EN]*</label>
                    <div class="col-lg-12">
                      <input type="text" class="form-control" name="fcg_title_en" required  />
                    </div>
                  </div>
                </div>

                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Title [TH]</label>
                    <div class="col-lg-12">
                      <input type="text" class="form-control" name="fcg_title_th"  />
                    </div>
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Description</label>
                    <div class="col-lg-12">
                      <textarea name="fcg_detail_en" class="form-control" rows="5"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Image</label>
                    <div class="col-lg-12">
                      <input type="file" name="filebanner[]" class="form-control" multiple accept="image/*" required  >
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
                          <img src="../backoffice/asset/icon_active.png" onclick="changeStatus();" >
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
                                    <button onclick="changeTagFair('<?=$dataf["fair_group_id"]?>');" id="tagf_<?=$dataf["fair_group_id"]?>" type="button" class="tagf btn btn-tag <? if($fgid==$dataf["fair_group_id"]) { ?>btn-tag-active<? } ?>"><?=$dataf["fair_group_abb"]?></button>
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
                            $sqlf = "select * from tt_tag_master where tag_status != '9' order by tag_name ASC ";
                            $stmtf = $mysqli->prepare($sqlf);
                            if($stmtf) {
                              $stmtf->execute();
                              $resultf = $stmtf->get_result();
                              $numrowf = $resultf->num_rows;
                              if($numrowf>0) {
                                while($dataf = $resultf->fetch_assoc()) {
                                  ?>
                                  <span>
                                    <button data-val="0" data-id="<?=$dataf["tag_id"]?>" onclick="changeTagMaster('<?=$dataf["tag_id"]?>');" id="tagm_<?=$dataf["tag_id"]?>" type="button" class="tagm_ btn btn-tag"><?=$dataf["tag_name"]?></button>
                                  </span>
                                  <?
                                }
                              }
                            }
                            $datatag = "|";
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
                  <a href="home.php?show=<?=$back_link?>" class="btn btn-default" style="width:150px;">Cancel</a>
                </div>
            </div>

            <input type="hidden" name="fcg_status" id="fcg_status" value="1">
            <input type="hidden" name="fcg_pubish" id="fcg_pubish" value="<?=$pulishdata?>">

            <input type="hidden" name="tag_master_id" id="tag_master_id" value="<?=$datatag?>">
            <input type="hidden" name="tag_fair_id" id="tag_fair_id" value="<?=$fgid?>">
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
      url: "php/gallery_add.php?method=chgstatus&status="+st,
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
      url: "php/gallery_add.php?method=chgpublish&status="+st,
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

function changeSeleteFair(id) {
  $('._fairdiv').empty();
  $.ajax({
      type: "GET",
      url: "php/gallery_add.php?method=changefair&gid="+id,
      dataType: "text",
      success : function(data) {
        $('._fairdiv').html(data);
      }
  });
}
</script>
