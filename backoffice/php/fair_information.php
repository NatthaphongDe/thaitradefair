<?php
$menu_name = "Infomation Management ";
$save_link = "fair_information";
$back_link = "fair_list";


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

if($_REQUEST["method"]=="chgstatusmap") {
  include_once("../connect.php");

  $status = $_GET["status"];
  if($status==1) {
    $newsta = 2;
  } else {
    $newsta = 1;
  }

  if($newsta==1) {
    ?>
    <img src="../backoffice/asset/icon_active.png" onclick="changeStatusMap();" >
    <?
  } else {
    ?>
    <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatusMap();" >
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


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $fc_id = $_POST["fc_id"];
  $fct_id = $_POST["fct_id"];
  $fair_id = $_POST["fair_id"];
  $fc_date = $_POST["fc_date"];
  $fc_detail_th = $_POST["fc_detail_th"];
  $fc_detail_en = $_POST["fc_detail_en"];
  $fc_url = $_POST["fc_url"];
  $fc_youtube = $_POST["fc_youtube"];
  $fc_status = $_POST["fc_status"];
  $fc_pubish = $_POST["fc_pubish"];

  $fc_tag_fair_id = $_POST["tag_fair_id"];
  $fc_tag_master_id = $_POST["tag_master_id"];

  $fc_map_status = $_POST["fc_map_status"];
  $fc_map_title = $_POST["maptitle"];
  $fc_lat = $_POST["maplat"];
  $fc_lng = $_POST["maplng"];
  $fc_address = $_POST["mapdesc"];

  $sql = "update tt_fair_content_onepage set fc_date = ?, fc_detail_th = ?, fc_detail_en = ?, fc_url = ?, fc_youtube = ?, fc_status = ?, fc_update_date = now(), fc_update_by = ?, fc_pubish = ?, fc_tag_fair_id = ?, fc_tag_master_id = ? , fc_map_status = ?, fc_map_title = ?, fc_lat = ?, fc_lng = ? , fc_address = ? where fc_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('sssssiiiisissssi',$fc_date,$fc_detail_th,$fc_detail_en,$fc_url,$fc_youtube,$fc_status,$adminid,$fc_pubish,$fc_tag_fair_id,$fc_tag_master_id,$fc_map_status,$fc_map_title,$fc_lat,$fc_lng,$fc_address,$fc_id);
    $stmt->execute();

    if($_FILES['filebanner']['name']!="") {

      $filedata = file_get_contents($_FILES["filebanner"]["tmp_name"]);

      if($fc_id>0) {
        $rmdirs =ROOTPATH."/data/faircontent/".$fc_id."/banner";
        deleteDirectory($rmdirs);
      }

      $path_parts = pathinfo($_FILES['filebanner']['name']);
      $extension = $path_parts['extension'];
      $path_namepic = alphanumeric_random_wms(10);
      $path_mini = ROOTPATH."/data/faircontent/$fc_id/banner/$path_namepic.$extension";
      $pathdb = "/data/faircontent/$fc_id/banner/$path_namepic.$extension";
      if (!is_dir(ROOTPATH."/data")){
        @mkdir(ROOTPATH."/data");
      }
      if (!is_dir(ROOTPATH."/data/faircontent")){
        @mkdir(ROOTPATH."/data/faircontent");
      }
      if (!is_dir(ROOTPATH."/data/faircontent/".$fc_id)){
        @mkdir(ROOTPATH."/data/faircontent/".$fc_id);
      }
      if (!is_dir(ROOTPATH."/data/faircontent/".$fc_id."/banner")){
        @mkdir(ROOTPATH."/data/faircontent/".$fc_id."/banner");
      }
      if (!is_dir(ROOTPATH."/data/faircontent/".$fc_id."/banner/resize")){
        @mkdir(ROOTPATH."/data/faircontent/".$fc_id."/banner/resize");
      }
      if (!is_dir(ROOTPATH."/data/faircontent/".$fc_id."/banner/crop")){
        @mkdir(ROOTPATH."/data/faircontent/".$fc_id."/banner/crop");
      }
      if(move_uploaded_file($_FILES["filebanner"]["tmp_name"], $path_mini)) {
        $sqlup = "update tt_fair_content_onepage set fc_banner_path = ? where fc_id = ? ";
        $stmtup = $mysqli->prepare($sqlup);
        if($stmtup) {
          $stmtup->bind_param('si',$pathdb,$fc_id);
          $stmtup->execute();
        }

        $path_resize = ROOTPATH."/data/faircontent/$fc_id/banner/resize/$path_namepic.$extension";
        $path_crop = ROOTPATH."/data/faircontent/$fc_id/banner/crop/$path_namepic.$extension";
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
    setTimeout(function () {top.window.location="../home.php?show=<?=$back_link?>";},500);
  </script>
  <?php
  exit();

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

    $abb = "";
    $sqlg = "select * from tt_fair_group_list a left join tt_fair_group b on a.fair_group_id=b.fair_group_id where a.fair_id = ? and a.fair_flag != '9' limit 1 ";
    $stmtg = $mysqli->prepare($sqlg);
    $stmtg->bind_param('i',$data["fair_id"]);
    $stmtg->execute();
    $resultg = $stmtg->get_result();
    $numrowg = $resultg->num_rows;
    if($numrowg>0) {
      $datag = $resultg->fetch_assoc();
      $fairname = $data["fair_name"];
      $abb = $datag["fair_group_abb"];
    }
  }
}

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

$fct_id = 0;
$fcat_id = 7;

$sqlc = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and a.fcat_id = ? limit 1 ";
$stmtc = $mysqli->prepare($sqlc);
if($stmtc) {
  $stmtc->bind_param('ii',$fair_id,$fcat_id);
  $stmtc->execute();
  $resultc = $stmtc->get_result();
  $numrowc = $resultc->num_rows;
  if($numrowc>0) {
    $datac = $resultc->fetch_assoc();
  }
}



$sql = " select * from tt_fair_list_cat where fair_id = ? and fcat_id = ? limit 1  ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('ii',$fair_id,$fcat_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();
    $fct_id = $data["fct_id"];
  }
}


$adminid = $_SESSION["id"];
$sql = "select * from tt_fair_content_onepage where fair_id = ? and fcat_id = ? limit 1 ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('ii',$fair_id,$fct_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow==0) {
    $sqlup = " insert into tt_fair_content_onepage (fair_id,fct_id,fcat_id,fc_create_date,fc_create_by,fc_update_date,fc_update_by,fc_status,fc_pubish,fc_map_status,fc_tag_fair_id) values (?,?,?,now(),?,now(),?,'2',?,'2',?) ";
    $stmtup = $mysqli->prepare($sqlup);
    $stmtup->bind_param('iiiiiii',$fair_id,$fct_id,$datac["fcat_id"],$adminid,$adminid,$datac["fct_flag"],$fgid);
    $stmtup->execute();
  }
}

$sql = "select * from tt_fair_content_onepage where fair_id = ? and fct_id = ? limit 1 ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('ii',$fair_id,$fct_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();

    if($data["pid"]=="") {
      $pid = uniqid();
      $sqlup = "update tt_fair_content_onepage set pid = ? where fc_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      $stmtup->bind_param('si',$pid,$data["fc_id"]);
      $stmtup->execute();
    } else {
      $pid = $data["pid"];
    }
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
if($data["fc_pubish"]==1) {
  $pulishdata = 1;
} else {
  $pulishdata = 2;
}




/*
$id = $_GET["id"];
$sql = "select * from tt_fair_list where fair_id = ? ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('i',$id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();

    $abb = "";
    $sqlg = "select * from tt_fair_group_list a left join tt_fair_group b on a.fair_group_id=b.fair_group_id where a.fair_id = ? and a.fair_flag != '9' limit 1 ";
    $stmtg = $mysqli->prepare($sqlg);
    $stmtg->bind_param('i',$data["fair_id"]);
    $stmtg->execute();
    $resultg = $stmtg->get_result();
    $numrowg = $resultg->num_rows;
    if($numrowg>0) {
      $datag = $resultg->fetch_assoc();
      $abb = $datag["fair_group_abb"];
    }
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
*/
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
                <div class="col-xs-6 col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Fair Name </label>
                    <div class="col-lg-12">
                      <input type="text" class="form-control" disabled value="<?=$fairname?>" />
                    </div>
                  </div>
                </div>

                <div class="col-xs-6 col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">ABB </label>
                    <div class="col-lg-12">
                      <input type="text" class="form-control" disabled value="<?=$abb?>" />
                    </div>
                  </div>
                </div>
              </div>

              <!-- <div class="row">
                <div class="col-xs-6 col-lg-6">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Contact Us</label>
                    <div class="col-lg-12">
                      <textarea name="fair_information" class="tinyclass"><?=$data["fair_information"]?></textarea>
                    </div>
                  </div>
                </div>
              </div> -->

              <hr>
              <div class="row">
                <div class="col-xs-6 col-lg-6">


                    <div class="row">
                      <div class="col-xs-12">
                        <div class="form-group">
                          <label class="col-lg-8 control-label" style="padding-top:39px;">Create Date</label>
                          <div class="col-lg-4 text-right">
                            <label class="col-lg-6 control-label">
                              <a onclick="showMap();"><img src="../backoffice/asset/icon_mapgoogle.png" ></a>
                            </label>
                            <div class="sm_" style="padding-top:35px;">
                              <? if($data["fc_map_status"]==1) { ?>
                                <img src="../backoffice/asset/icon_active.png" onclick="changeStatusMap();" >
                              <? } else { ?>
                                <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatusMap();" >
                              <? } ?>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class='input-group date datepicker_box'>
                                <input type="text" class="form-control" name="fc_date" required value="<?=$data["fc_date"]?>" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>


                    <!-- <div class="row">
                      <div class="col-xs-12">
                        <div class="form-group">
                          <label class="col-lg-12 control-label">Add URL</label>
                          <div class="col-lg-12">
                            <input type="text" class="form-control" name="fc_url" value="<?=$data["fc_url"]?>">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-12">
                        <div class="form-group">
                          <label class="col-lg-12 control-label">Add Youtube VDO URL</label>
                          <div class="col-lg-12">
                            <input type="text" class="form-control" name="fc_youtube" value="<?=$data["fc_youtube"]?>">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-12">
                        <div class="form-group">
                          <label class="col-lg-12 control-label">Add Attachment File</label>
                          <div class="col-lg-12">
                            <input type="file" name="filedoc" class="form-control"  >
                          </div>
                        </div>
                      </div>
                    </div> -->



                  </div>

                  <div class="col-xs-6 col-lg-6">

                      <div class="row">
                        <div class="col-xs-12">
                          <div class="form-group">
                            <label class="col-lg-6 control-label">Title Image</label>
                            <div class="col-lg-6 text-right">
                              <label class="col-lg-6 control-label">การแสดงผล</label>
                              <div class="s_">
                                <? if($data["fc_status"]==1) { ?>
                                  <img src="../backoffice/asset/icon_active.png" onclick="changeStatus();" >
                                <? } else { ?>
                                  <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatus();" >
                                <? } ?>
                              </div>
                            </div>
                            <? if($data["fc_banner_path"]!="") { ?>
                              <div class="col-lg-12">
                                <img src="<?=ROOTPATHDOMAIN?><?=$data["fc_banner_path"]?>" width="100%">
                              </div>
                            <? } ?>
                            <div class="col-lg-12">
                              <? if($data["fc_banner_path"]!="") { ?>
                              <br>
                              <? } ?>
                              <input type="file" name="filebanner" class="form-control"  >
                              <span class="text-danger " style="font-size:90%;">
                                *กรุณาเลือกไฟล์ JPG,JPEG,PNG ที่มีขนาดมากกว่า 1600*540 px ขึ้นไปเพื่อความสวยงามของการแสดงผล
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>

              </div>

              <div class="row">
                <div class="col-xs-9">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Content [EN]</label>
                    <div class="col-lg-12">
                      <textarea name="fc_detail_en" class="tinyclass"><?=$data["fc_detail_en"]?></textarea>
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

              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="col-lg-12 control-label">Content [TH]</label>
                    <div class="col-lg-12">
                      <textarea name="fc_detail_th" class="tinyclass"><?=$data["fc_detail_th"]?></textarea>
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
                              <button onclick="changeTagFair('<?=$dataf["fair_group_id"]?>');" id="tagf_<?=$dataf["fair_group_id"]?>" type="button" class="tagf btn btn-tag <? if($data["fc_tag_fair_id"]==$dataf["fair_group_id"]) { ?>btn-tag-active<? } ?>"><?=$dataf["fair_group_abb"]?></button>
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
                      $datatag = $data["fc_tag_master_id"];
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
                  <a href="home.php?show=<?=$back_link?>" class="btn btn-default" style="width:150px;">Cancel</a>
                </div>
            </div>

            <input type="hidden" name="fc_status" id="fc_status" value="<?=$data["fc_status"]?>">
            <input type="hidden" name="fc_map_status" id="fc_map_status" value="<?=$data["fc_map_status"]?>">
            <input type="hidden" name="fc_id" value="<?=$data["fc_id"]?>">
            <input type="hidden" name="fair_id" value="<?=$data["fair_id"]?>">
            <input type="hidden" name="fct_id" value="<?=$data["fct_id"]?>">
            <input type="hidden" name="fc_pubish" id="fc_pubish" value="<?=$pulishdata?>">

            <input type="hidden" name="tag_master_id" id="tag_master_id" value="<?=$datatag?>">
            <input type="hidden" name="tag_fair_id" id="tag_fair_id" value="<?=$data["fc_tag_fair_id"]?>">


            <input type="hidden" id="maptitle" name="maptitle" value="<?=$data["fc_map_title"]?>">
            <input type="hidden" id="maplat" name="maplat" value="<?=$data["fc_lat"]?>">
            <input type="hidden" id="maplng" name="maplng" value="<?=$data["fc_lng"]?>">
            <input type="hidden" id="mapdesc" name="mapdesc" value="<?=$data["fc_address"]?>">

            </form>
          </div>
        </div>
	</div>
</div>

<div class="modal fade text-left w-100 bg-modal" id="full-scrn_edit" tabindex="-1" aria-labelledby="myModalLabel20" aria-hidden="true">
  <div  class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full _modelinv" role="document"></div>

</div>

<script type="text/javascript">
function changeStatus() {
  var st = $('#fc_status').val();
  $.ajax({
      type: "GET",
      url: "php/fair_information.php?method=chgstatus&status="+st,
      dataType: "text",
      success : function(data) {
        $('.s_').empty();
        $('.s_').html(data);
        if(st==1) {
          $('#fc_status').val(2);
        } else {
          $('#fc_status').val(1);
        }
      }
  });
}

function changeStatusMap() {
  var st = $('#fc_map_status').val();
  $.ajax({
      type: "GET",
      url: "php/fair_information.php?method=chgstatusmap&status="+st,
      dataType: "text",
      success : function(data) {
        $('.sm_').empty();
        $('.sm_').html(data);
        if(st==1) {
          $('#fc_map_status').val(2);
        } else {
          $('#fc_map_status').val(1);
        }
      }
  });
}

function changeTypeCMS(id,type) {
  swal({
      title: "ยืนยันการเปลี่ยนข้อมูล",
      text: "ยืนยันการเปลี่ยนประเภทการแสดงผลข้อมูลเป็นแบบ "+type,
     type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#5cb85c",
      confirmButtonText: "ตกลง",
  cancelButtonText: "ยกเลิก",
      closeOnConfirm: false
    }, function (isConfirm) {
      if (isConfirm) {
          changeTypeCMSAction(id);
      } else {
        $("input[name=fct_cms_type][value=<?=$datac["fct_cms_type"]?>]").prop('checked', true);
      }
    });
}

function changeTypeCMSAction(id) {
  $.ajax({
      type: "GET",
      url: "php/fair_information.php?method=chnagetpye&type="+id+'&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>',
      dataType: "text",
      success : function(data) {
        setTimeout(function () {top.window.location="home.php?show=fair_content_portal&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>";},100);
      }
  });
}

function changePublish() {
  var st = $('#fc_pubish').val();
  $.ajax({
      type: "GET",
      url: "php/fair_information.php?method=chgpublish&status="+st,
      dataType: "text",
      success : function(data) {
        $('._pp').empty();
        $('._pp').html(data);
        if(st==1) {
          $('#fc_pubish').val(2);
        } else {
          $('#fc_pubish').val(1);
        }
      }
  });
}

function showMap() {

  var t_map = $('#maptitle').val();
  var t_lat = $('#maplat').val();
  var t_lng = $('#maplng').val();
  var t_desc = $('#mapdesc').val();
  $('#full-scrn_edit').modal('show');
  $('._modelinv').empty();
  $.ajax({
      type: "GET",
      url: "php/content_map.php?t_map="+t_map+"&t_lat="+t_lat+"&t_lng="+t_lng+"&t_desc="+t_desc,
      dataType: "text",
      success : function(data) {
        $('._modelinv').html(data);
      }
  });

}

function closeMap() {
  $('._modelinv').empty();
  $('#full-scrn_edit').modal('hide');
}


</script>
