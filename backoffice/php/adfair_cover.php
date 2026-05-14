<?php
$menu_name = "Cover Images";
$save_link = "adfair_cover";
$back_link = "adfair_list";

if($_REQUEST["method"]=="chnagetpye") {
  include_once("../connect.php");

  $fair_id = $_GET["id"];
  $type = $_GET["type"];

  $sql = " update tt_fair_list set fair_cover_type = ? where fair_id = ? ";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('ii',$type,$fair_id);
  $stmt->execute();

  exit();
}

if($_REQUEST["method"]=="delete") {
  include_once("../connect.php");

  $cover_id = $_GET["cover_id"];
  $sql = "delete from  tt_fair_cover where cover_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$cover_id);
    $stmt->execute();

    if($cover_id>0) {
      $rmdirs =ROOTPATH."/data/faircover/".$cover_id;
      deleteDirectory($rmdirs);
    }

  }
  exit();
}

if($_REQUEST["method"]=="chgstatus") {
  include_once("../connect.php");

  $cover_id = $_GET["cover_id"];
  $status = $_GET["status"];
  $sql = "select * from tt_fair_cover where cover_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$cover_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if($numrow>0) {
      $runno = 1;
      $data = $result->fetch_assoc();

      if($data["cover_status"]==1) {
        $newsta = 2;
      } else {
        $newsta = 1;
      }

      $sqlup = "update tt_fair_cover set cover_status = ? where cover_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('ii',$newsta,$cover_id);
        $stmtup->execute();
      }

      if($newsta==1) {
        ?>
        <img id="s_<?=$data["cover_id"]?>" src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["cover_id"]?>');" >
        <?
      } else {
        ?>
        <img id="s_<?=$data["cover_id"]?>" src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["cover_id"]?>');" >
        <?
      }
      exit();

    }
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
    $fair_cover_type = $data["fair_cover_type"];
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

<link href="js/jquery.dataTables.min.css" rel="stylesheet">
<script  src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('#deferRenderTable').DataTable( {
        "deferRender": true
    } );

} );
</script>
<div class="row wrapper page-heading">
     <div class="col-xs-12">
       <br>
       <ol class="breadcrumb">
           <li>
               <a href="home.php?show=<?=$back_link?>"><h3><i class="fa fa-angle-left backnav-size " aria-hidden="true"></i> <span class="backnav-size-txt">BACK</span></h3></a>
           </li>
           <div class="div-right text-right">
             Banner Animation :
             &nbsp;&nbsp;&nbsp;
             <? if($fair_cover_type==1) { ?>
               <div class="radio radio-info radio-inline">
                   <input type="radio" id="inlineRadio2" value="1" name="fair_cover_type" <? if($fair_cover_type==1) { echo "checked"; } ?>  >
                   <label for="inlineRadio2"> Auto Slide</label>
               </div>
               <div class="radio radio-info radio-inline">
                   <input type="radio" id="inlineRadio3" value="2" name="fair_cover_type" <? if($fair_cover_type==2) { echo "checked"; } ?>  onclick="changeTypeCover('2','Random');" >
                   <label for="inlineRadio3"> Random</label>
               </div>
             <? } ?>

             <? if($fair_cover_type==2) { ?>
               <div class="radio radio-info radio-inline">
                   <input type="radio" id="inlineRadio2" value="1" name="fair_cover_type" <? if($fair_cover_type==1) { echo "checked"; } ?>  onclick="changeTypeCover('1','Auto Slide');" >
                   <label for="inlineRadio2"> Auto Slide</label>
               </div>
               <div class="radio radio-info radio-inline">
                   <input type="radio" id="inlineRadio3" value="2" name="fair_cover_type" <? if($fair_cover_type==2) { echo "checked"; } ?>   >
                   <label for="inlineRadio3"> Random</label>
               </div>
             <? } ?>
             &nbsp;&nbsp;&nbsp;
             <a href="home.php?show=adfair_cover_add&id=<?=$fair_id?>" class="btn btn-success">+ Add <?=$menu_name?></a>

           </div>
       </ol>
       <div class="bottom-blue"></div>
    </div>

</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        	<div class="ibox float-e-margins">
            <h3><?=$menu_name?></h3>
            <div class="ibox-content">
              <div class="table-responsive">
                 <table class="table table-stripped table-hover table-bordered" id="deferRenderTable">
                    <thead>
                    <tr>
                    	<th class="text-center tr-head">#</th>
                      <th class="text-center tr-head">Cover Image</th>
                      <th class="text-center tr-head">URL</th>
                      <th class="text-center tr-head">Create Date</th>
                      <th class="text-center tr-head">Inactive / Active</th>
                      <th class="text-center tr-head">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?
                      $sql = "select * from tt_fair_cover where fair_id = ? order by cover_pos ASC ";
                      $stmt = $mysqli->prepare($sql);
                      if($stmt) {
                        $stmt->bind_param('i',$fair_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $numrow = $result->num_rows;
                        if($numrow>0) {
                          $runno = 1;
                          while($data = $result->fetch_assoc()) {
                            ?>
                            <tr>
                            	<td class="text-center vmiddle"><?=$runno?></td>
                              <td class="text-center vmiddle">
                                <? if($data["cover_path"]=="") { ?>
                                  <img src="../backoffice/img/noimage.jpg" width="150">
                                <? } else { ?>
                                  <img src="<?=ROOTPATHDOMAIN?><?=$data["cover_path"]?>" width="150">
                                <? } ?>
                              </td>
                              <td class="text-center vmiddle"><?=$data["cover_url"]?></td>
                              <td class="text-center vmiddle"><?=$data["cover_create_date"]?></td>
                              <td class="text-center vmiddle s_<?=$data["cover_id"]?>">
                                <? if($data["cover_status"]==2) { ?>
                                  <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["cover_id"]?>');" >
                                <? } else { ?>
                                  <img src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["cover_id"]?>');" >
                                <? } ?>
                              </td>
                              <td class="text-center vmiddle">

                                <span><img id="e_<?=$data["cover_id"]?>" onmouseover="actionOver('e_<?=$data["cover_id"]?>','1');" onmouseout="actionOut('e_<?=$data["cover_id"]?>','1')" src="../backoffice/asset/icon_edit_null.png" height="35" onclick="window.location='home.php?show=adfair_cover_edit&id=<?=$fair_id?>&cover_id=<?=$data["cover_id"]?>';" ></span>
                                &nbsp;
                                <span><img id="d_<?=$data["cover_id"]?>" onmouseover="actionOver('d_<?=$data["cover_id"]?>','3');" onmouseout="actionOut('d_<?=$data["cover_id"]?>','3')" src="../backoffice/asset/icon_del_null.png" height="35" onclick="confirmDelete('<?=$data["cover_id"]?>');"></span>
                                &nbsp;
                              </td>
                            </tr>
                            <?
                            $runno++;
                          }
                        }
                      }
                      ?>
                    </tbody>
                </table>
                </div>

            </div>

          </div>
        </div>
	</div>
</div>

<script type="text/javascript">
function changeStatus(id,type) {
  $.ajax({
      type: "GET",
      url: "php/adfair_cover.php?method=chgstatus&id=<?=$fair_id?>&cover_id="+id+"&status="+type,
      dataType: "text",
      success : function(data) {
        $('.s_'+id).empty();
        $('.s_'+id).html(data);
      }
  });
}

function actionOver(id,type) {
  if(type==1) {
    $('#'+id).attr('src','../backoffice/asset/icon_edit_act.png');
  }
  if(type==2) {
    $('#'+id).attr('src','../backoffice/asset/icon_m_act.png');
  }
  if(type==3) {
    $('#'+id).attr('src','../backoffice/asset/icon_del_act.png');
  }
  if(type==4) {
    $('#'+id).attr('src','../backoffice/asset/icon_img_act.png');
  }
  if(type==5) {
    $('#'+id).attr('src','../backoffice/asset/icon_inf_act.png');
  }
  if(type==6) {
    $('#'+id).attr('src','../backoffice/asset/icon_cat_act.png');
  }
}

function actionOut(id,type) {
  if(type==1) {
    $('#'+id).attr('src','../backoffice/asset/icon_edit_null.png');
  }
  if(type==2) {
    $('#'+id).attr('src','../backoffice/asset/icon_m_null.png');
  }
  if(type==3) {
    $('#'+id).attr('src','../backoffice/asset/icon_del_null.png');
  }
  if(type==4) {
    $('#'+id).attr('src','../backoffice/asset/icon_img_null.png');
  }
  if(type==5) {
    $('#'+id).attr('src','../backoffice/asset/icon_inf_null.png');
  }
  if(type==6) {
    $('#'+id).attr('src','../backoffice/asset/icon_cat_null.png');
  }
}

function confirmDelete(id) {
  swal({
      title: "ยืนยันการลบรายการ",
      text: "ยืนยันการลบรายการ",
     type: "error",
      showCancelButton: true,
      confirmButtonColor: "#F27474",
      confirmButtonText: "ตกลง",
  cancelButtonText: "ยกเลิก",
      closeOnConfirm: false
    }, function (isConfirm) {
      if (isConfirm) {
          confirmDeleteAction(id);
      }
    });
}

function confirmDeleteAction(id) {
  $.ajax({
      type: "GET",
      url: "php/adfair_cover.php?method=delete&id=<?=$fair_id?>&cover_id="+id,
      dataType: "text",
      success : function(data) {
        alertpopup('1','ลบรายการเรียบร้อยแล้ว');
        setTimeout(function () {top.window.location="home.php?show=adfair_cover&id=<?=$fair_id?>";},500);
      }
  });
}

function changeTypeCover(id,type) {
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
          changeTypeCoverAction(id);
      } else {
        $("input[name=fair_cover_type][value=<?=$fair_cover_type?>]").prop('checked', true);
      }
    });
}

function changeTypeCoverAction(id) {
  $.ajax({
      type: "GET",
      url: "php/adfair_cover.php?method=chnagetpye&type="+id+'&id=<?=$fair_id?>',
      dataType: "text",
      success : function(data) {
        setTimeout(function () {top.window.location="home.php?show=adfair_cover&id=<?=$fair_id?>";},100);
      }
  });
}
</script>
