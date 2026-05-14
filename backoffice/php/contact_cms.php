<?php
$menu_name = "Contact CMS";
$save_link = "contact_cms";
$back_link = "contact_list";


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



if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $fc_id = $_POST["fc_id"];
  $fc_detail_th = $_POST["fc_detail_th"];
  $fc_detail_en = $_POST["fc_detail_en"];
  $fc_status = $_POST["fc_status"];

  $fc_map_status = $_POST["fc_map_status"];
  $fc_map_title = $_POST["maptitle"];
  $fc_lat = $_POST["maplat"];
  $fc_lng = $_POST["maplng"];
  $fc_address = $_POST["mapdesc"];

  $sql = "update tt_contact_content set fc_detail_th = ?, fc_detail_en = ?, fc_status = ?, fc_update_date = now(), fc_update_by = ?, fc_map_status = ?, fc_map_title = ?, fc_lat = ?, fc_lng = ? , fc_address = ? where fc_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('ssiiissssi',$fc_detail_th,$fc_detail_en,$fc_status,$adminid,$fc_map_status,$fc_map_title,$fc_lat,$fc_lng,$fc_address,$fc_id);
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

<?

$adminid = $_SESSION["id"];
$sql = "select * from tt_contact_content where fc_id = 1 limit 1 ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow==0) {
    $sqlup = " insert into tt_contact_content (fc_create_date,fc_create_by,fc_update_date,fc_update_by,fc_status,fc_map_status) values (now(),?,now(),?,'1','2') ";
    $stmtup = $mysqli->prepare($sqlup);
    $stmtup->bind_param('ii',$adminid,$adminid);
    $stmtup->execute();
  }
}

$sql = "select * from tt_contact_content where fc_id = 1 limit 1 ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();

    if($data["pid"]=="") {
      $pid = uniqid();
      $sqlup = "update tt_contact_content set pid = ? where fc_id = ? ";
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
                      <div class="col-xs-6">
                        <div class="form-group">
                          <label class="col-lg-4 control-label" style="padding-top:39px;">Map Status</label>
                          <div class="col-lg-8 text-right">
                            <label class="col-lg-3 control-label">
                              <a onclick="showMap();"><img src="../backoffice/asset/icon_mapgoogle.png" ></a>
                            </label>
                            <div class="col-xs-9">
                              <div class="sm_" style="padding-top:35px; float:left;">
                                <? if($data["fc_map_status"]==1) { ?>
                                  <img src="../backoffice/asset/icon_active.png" onclick="changeStatusMap();" >
                                <? } else { ?>
                                  <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatusMap();" >
                                <? } ?>
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>

                      <div class="col-xs-6">
                        <div class="form-group">
                          <label class="col-lg-8 control-label" style="padding-top:39px; text-align:right !important;">การแสดงผล</label>
                          <div class="col-lg-4 text-right">
                              <div class="s_" style="padding-top:39px;">
                                <? if($data["fc_status"]==1) { ?>
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
      url: "php/contact_cms.php?method=chgstatus&status="+st,
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
      url: "php/contact_cms.php?method=chgstatusmap&status="+st,
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
