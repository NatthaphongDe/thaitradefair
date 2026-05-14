<?php
$menu_name = "Create Fair Names";
$save_link = "fair_add";
$back_link = "fair_list";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $fair_group_id = $_POST["fair_group_id"];
  $fair_name = $_POST["fair_name"];
  $fair_year = $_POST["fair_year"];
  $fair_trade_start = $_POST["fair_trade_start"];
  $fair_trade_end = $_POST["fair_trade_end"];
  $fair_public_start = $_POST["fair_public_start"];
  $fair_public_end = $_POST["fair_public_end"];
  $fair_event_start = $_POST["fair_event_start"];
  $fair_event_end = $_POST["fair_event_end"];
  $fair_vanue = $_POST["fair_vanue"];
  $fair_url = $_POST["fair_url"];
  $fair_status = $_POST["fair_status"];
  $fair_url_register = $_POST["fair_url_register"];
  $fair_pre_start = $_POST["fair_pre_start"];
  $fair_pre_end = $_POST["fair_pre_end"];


  $fair_map_status = $_POST["fair_map_status"];
  $fair_map_title = $_POST["maptitle"];
  $fair_lat = $_POST["maplat"];
  $fair_lng = $_POST["maplng"];
  $fait_map_address = $_POST["mapdesc"];

  $sql = "insert into tt_fair_list (fair_ditp_id,fair_name,fair_year,fair_trade_start,fair_trade_end,fair_public_start,fair_public_end,fair_event_start,fair_event_end,fair_vanue,fair_url,fair_status,fair_create_date,fair_create_by,fair_update_date,fair_update_by,fair_map_status,fair_map_title,fair_lat,fair_lng,fait_map_address,fair_url_register,fair_pre_start,fair_pre_end) values ('0',?,?,?,?,?,?,?,?,?,?,?,now(),?,now(),?,?,?,?,?,?,?,?,?) ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('sissssssssiiiisssssss',$fair_name,$fair_year,$fair_trade_start,$fair_trade_end,$fair_public_start,$fair_public_end,$fair_event_start,$fair_event_end,$fair_vanue,$fair_url,$fair_status,$adminid,$adminid,$fair_map_status,$fair_map_title,$fair_lat,$fair_lng,$fait_map_address,$fair_url_register,$fair_pre_start,$fair_pre_end);
    $stmt->execute();
    $fair_id = $stmt->insert_id;

    saveLogActivity(3,$_SESSION["id"],$fair_id,"Create");


    $sqldel = " delete from tt_fair_group_list where fair_id = ? ";
    $stmtdel = $mysqli->prepare($sqldel);
    $stmtdel->bind_param('i',$fair_id);
    $stmtdel->execute();

    $sql2 = "insert into tt_fair_group_list (fair_group_id,fair_id,fair_flag) values (?,?,'1') ";
    $stmt2 = $mysqli->prepare($sql2);
    $stmt2->bind_param('ii',$fair_group_id,$fair_id);
    $stmt2->execute();

    $sqlchk = "select * from tt_fair_category order by fcat_type ASC, fcat_pos ASC ";
    $stmtchk = $mysqli->prepare($sqlchk);
    $stmtchk->execute();
    $resultchk = $stmtchk->get_result();
    $numrowchk = $resultchk->num_rows;
    if($numrowchk>0) {
      while($datachk = $resultchk->fetch_assoc()) {
        $sql2 = "insert into tt_fair_list_cat (fair_id,fcat_id,fct_pos,fct_status,fct_flag,fct_cms_type) values (?,?,?,'1','1',?) ";
        $stmt2 = $mysqli->prepare($sql2);
        $stmt2->bind_param('iiii',$fair_id,$datachk["fcat_id"],$datachk["fcat_pos"],$datachk["fcat_cms_type"]);
        $stmt2->execute();
      }
    }

    $filedata = file_get_contents($_FILES["upload"]["tmp_name"]);
    $path_parts = pathinfo($_FILES['upload']['name']);
    $extension = $path_parts['extension'];
    $path_namepic = alphanumeric_random_wms(10);
    $path_mini = ROOTPATH."/data/fairlist/$fair_id/banner/$path_namepic.$extension";
    $pathdb = "/data/fairlist/$fair_id/banner/$path_namepic.$extension";
    if (!is_dir(ROOTPATH."/data")){
      @mkdir(ROOTPATH."/data");
    }
    if (!is_dir(ROOTPATH."/data/fairlist")){
      @mkdir(ROOTPATH."/data/fairlist");
    }
    if (!is_dir(ROOTPATH."/data/fairlist/".$fair_id)){
      @mkdir(ROOTPATH."/data/fairlist/".$fair_id);
    }
    if (!is_dir(ROOTPATH."/data/fairlist/".$fair_id."/banner")){
      @mkdir(ROOTPATH."/data/fairlist/".$fair_id."/banner");
    }
    if (!is_dir(ROOTPATH."/data/fairlist/".$fair_id."/banner/resize")){
      @mkdir(ROOTPATH."/data/fairlist/".$fair_id."/banner/resize");
    }
    if (!is_dir(ROOTPATH."/data/fairlist/".$fair_id."/banner/crop")){
      @mkdir(ROOTPATH."/data/fairlist/".$fair_id."/banner/crop");
    }
    if (!is_dir(ROOTPATH."/data/fairlist/".$fair_id."/banner/cropinner")){
      @mkdir(ROOTPATH."/data/fairlist/".$fair_id."/banner/cropinner");
    }
    if(move_uploaded_file($_FILES["upload"]["tmp_name"], $path_mini)) {
      $sqlup = "update tt_fair_list set fair_path_banner = ? where fair_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('si',$pathdb,$fair_id);
        $stmtup->execute();
      }

      $path_resize = ROOTPATH."/data/fairlist/$fair_id/banner/resize/$path_namepic.$extension";
      $path_crop = ROOTPATH."/data/fairlist/$fair_id/banner/crop/$path_namepic.$extension";

      $path_cropinner = ROOTPATH."/data/fairlist/$fair_id/banner/cropinner/$path_namepic.$extension";
      $wid = 1600;
      imageresize($filedata,$path_resize,$wid,0);

      $wid = 1200;
      imageresize($filedata,$path_crop,$wid,600,true,true);

      $wid = 1200;
      imageresize($filedata,$path_cropinner,$wid,380,true,true);

    }

  }

  ?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.alertpopup("1","บันทึกรายการเรียบร้อย");
    setTimeout(function () {top.window.location="../home.php?show=fair_list";},500);
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
                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Fair Name *</label>
                      <div class="col-lg-12">
                        <input type="text" class="form-control" name="fair_name" required autocomplete="off" />
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">ABB *</label>
                      <div class="col-lg-12">
                        <select class="form-control" name="fair_group_id" required>
                          <option value="">- Select ABB -</option>
                          <?
                          $sqlf = "select fair_group_id,fair_group_abb from tt_fair_group where fair_group_status != '9' group by fair_group_abb order by fair_group_abb ASC ";
                          $stmtf = $mysqli->prepare($sqlf);
                          if($stmtf) {
                            $stmtf->execute();
                            $resultf = $stmtf->get_result();
                            $numrowf = $resultf->num_rows;
                            if($numrowf>0) {
                              while($dataf = $resultf->fetch_assoc()) {
                                ?>
                                <option value="<?=$dataf["fair_group_id"]?>"><?=$dataf["fair_group_abb"]?></option>
                                <?
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <?
                  $yearstart = date("Y");
                  $yearstart = $yearstart+3;
                  $yearstop = date("Y");
                  $yearstop = $yearstop-10;
                  ?>
                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Year *</label>
                      <div class="col-lg-12">
                        <select class="form-control" name="fair_year" required>
                          <option value="">- Select Year -</option>
                          <? for($y=$yearstart;$y>=$yearstop;$y--) { ?>
                            <option value="<?=$y?>"><?=$y?></option>
                          <? } ?>
                        </select>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">
                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Trade Start *</label>
                      <div class="col-lg-12">
                        <div class='input-group date datepicker_box'>
                            <input type="text" class="form-control" name="fair_trade_start" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Trade End *</label>
                      <div class="col-lg-12">
                        <div class='input-group date datepicker_box'>
                            <input type="text" class="form-control" name="fair_trade_end" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Public Start *</label>
                      <div class="col-lg-12">
                        <div class='input-group date datepicker_box'>
                            <input type="text" class="form-control" name="fair_public_start" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Public End *</label>
                      <div class="col-lg-12">
                        <div class='input-group date datepicker_box'>
                            <input type="text" class="form-control" name="fair_public_end" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Event Start *</label>
                      <div class="col-lg-12">
                        <div class='input-group date datepicker_box'>
                            <input type="text" class="form-control" name="fair_event_start" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Event End *</label>
                      <div class="col-lg-12">
                        <div class='input-group date datepicker_box'>
                            <input type="text" class="form-control" name="fair_event_end" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>



                <div class="row">
                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Venue</label>
                      <div class="col-lg-12">
                        <input type="text" class="form-control" name="fair_vanue"  />
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-4 control-label">
                        <a onclick="showMap();"><img src="../backoffice/asset/icon_mapgoogle.png" ></a>
                      </label>
                      <div class="col-lg-5 text-left sm_" style="padding-top:35px;">
                          <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatusMap();" >
                      </div>
                    </div>
                  </div>

                </div>




                <div class="row">
                  <div class="col-xs-4 col-lg-4">
                        <div class="form-group">
                          <label class="col-lg-12 control-label">Banner Image/Video *</label>
                          <div class="col-lg-12">
                            <input type="file" name="upload" required  >
                            <span class="text-danger " style="font-size:90%;">
                              *กรุณาเลือกไฟล์ JPG,JPEG,PNG ที่มีขนาดมากกว่า 1600*800 px ขึ้นไปเพื่อความสวยงามของการแสดงผล
                            </span>
                          </div>
                        </div>
                    </div>

                    <div class="col-xs-4 col-lg-4">
                      <div class="form-group">
                        <label class="col-lg-4 control-label">การแสดงผล</label>
                        <div class="col-lg-5 text-left s_" style="padding-top:7px;">
                          <img src="../backoffice/asset/icon_active.png" onclick="changeStatus();" >
                        </div>
                      </div>
                    </div>
                </div>

                <div class="row">
                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">URL</label>
                      <div class="col-lg-12">
                        <input type="text" class="form-control" name="fair_url"  />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Pre Register URL</label>
                      <div class="col-lg-12">
                        <input type="text" class="form-control" name="fair_url_register"  />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Pre Register Start Date *</label>
                      <div class="col-lg-12">
                        <div class='input-group date datepicker_box'>
                            <input type="text" class="form-control" name="fair_pre_start" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-4 col-lg-4">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Pre Register End Date *</label>
                      <div class="col-lg-12">
                        <div class='input-group date datepicker_box'>
                            <input type="text" class="form-control" name="fair_pre_end" required />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
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


            <input type="hidden" name="fair_status" id="fair_status" value="1">

            <input type="hidden" id="maptitle" name="maptitle" value="">
            <input type="hidden" id="maplat" name="maplat" value="">
            <input type="hidden" id="maplng" name="maplng" value="">
            <input type="hidden" id="mapdesc" name="mapdesc" value="">
            <input type="hidden" name="fair_map_status" id="fair_map_status" value="1">
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
  var st = $('#fair_status').val();
  $.ajax({
      type: "GET",
      url: "php/fair_add.php?method=chgstatus&status="+st,
      dataType: "text",
      success : function(data) {
        $('.s_').empty();
        $('.s_').html(data);
        if(st==1) {
          $('#fair_status').val(2);
        } else {
          $('#fair_status').val(1);
        }
      }
  });
}

function changeStatusMap() {
  var st = $('#fair_map_status').val();
  $.ajax({
      type: "GET",
      url: "php/fair_add.php?method=chgstatusmap&status="+st,
      dataType: "text",
      success : function(data) {
        $('.sm_').empty();
        $('.sm_').html(data);
        if(st==1) {
          $('#fair_map_status').val(2);
        } else {
          $('#fair_map_status').val(1);
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
