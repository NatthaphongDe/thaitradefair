<?php
$header_name = "Fair Management";
$menu_name = "Fair Master";
$back_link = "fair_master_list";

if($_REQUEST["method"]=="delete") {
  include_once("../connect.php");

  $id = $_GET["id"];
  $sql = "update tt_fair_group set fair_group_status = 9 where fair_group_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();
  }

  $sql = "update tt_fair_group_list set fair_flag = 9 where fair_group_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();
  }

  saveLogActivity(2,$_SESSION["id"],$id,"Delete");
  exit();
}

if($_REQUEST["method"]=="chgstatus") {
  include_once("../connect.php");

  $id = $_GET["id"];
  $status = $_GET["status"];
  $sql = "select * from tt_fair_group where fair_group_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if($numrow>0) {
      $runno = 1;
      $data = $result->fetch_assoc();

      if($data["fair_group_status"]==1) {
        $newsta = 2;
      } else {
        $newsta = 1;
      }

      $sqlup = "update tt_fair_group set fair_group_status = ? where fair_group_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('ii',$newsta,$id);
        $stmtup->execute();
      }

      if($newsta==1) {
        ?>
        <img id="s_<?=$data["fair_group_id"]?>" src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["fair_group_id"]?>');" >
        <?
      } else {
        ?>
        <img id="s_<?=$data["fair_group_id"]?>" src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["fair_group_id"]?>');" >
        <?
      }
      exit();

    }
  }

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
            <li class="active">
                <strong class="menu_n_menu"><?=$menu_name?></strong>
            </li>
        </ol>
    </div>

    <div class="col-xs-8 col-lg-6 text-right"><h3>&nbsp;</h3>
      <a href="home.php?show=fair_master_add" class="btn btn-success">+ Add Fair Group</a>
    </div>

</div>
<link href="js/jquery.dataTables.min.css" rel="stylesheet">
<script  src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('#deferRenderTable').DataTable( {
        "deferRender": true
    } );

} );
</script>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        	<div class="ibox float-e-margins">
          	<div class="table-responsive">
               <table class="table table-stripped table-hover table-bordered" id="deferRenderTable">
                  <thead>
                  <tr>
                  	<th class="text-center tr-head">#</th>
                    <th class="text-left tr-head">Fair Group</th>
                    <th class="text-center tr-head">Fair ABB</th>
                    <th class="text-center tr-head">Inactive / Active</th>
                    <th class="text-center tr-head">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?
                    $sql = "select * from tt_fair_group where fair_group_status != '9' order by fair_group_id DESC ";
                    $stmt = $mysqli->prepare($sql);
                    if($stmt) {
                      //$stmt->bind_param();
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $numrow = $result->num_rows;
                      if($numrow>0) {
                        $runno = 1;
                        while($data = $result->fetch_assoc()) {
                          ?>
                          <tr>
                          	<td class="text-center vmiddle"><?=$runno?></td>
                            <td class="text-left vmiddle"><?=$data["fair_group_name_th"]?></td>
                            <td class="text-center vmiddle"><?=$data["fair_group_abb"]?></td>
                            <td class="text-center vmiddle s_<?=$data["fair_group_id"]?>">
                              <? if($data["fair_group_status"]==2) { ?>
                                <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["fair_group_id"]?>');" >
                              <? } else { ?>
                                <img src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["fair_group_id"]?>');" >
                              <? } ?>
                            </td>
                            <td class="text-center vmiddle">

                              <span><img id="e_<?=$data["fair_group_id"]?>" onmouseover="actionOver('e_<?=$data["fair_group_id"]?>','1');" onmouseout="actionOut('e_<?=$data["fair_group_id"]?>','1')" src="../backoffice/asset/icon_edit_null.png" height="35" onclick="window.location='home.php?show=fair_master_edit&id=<?=$data["fair_group_id"]?>';" ></span>
                              &nbsp;
                              <span><img id="m_<?=$data["fair_group_id"]?>" onmouseover="actionOver('m_<?=$data["fair_group_id"]?>','2');" onmouseout="actionOut('m_<?=$data["fair_group_id"]?>','2')" src="../backoffice/asset/icon_m_null.png" height="35" onclick="showAdmin('<?=$data["fair_group_id"]?>');" ></span>
                              &nbsp;
                              <span><img id="d_<?=$data["fair_group_id"]?>" onmouseover="actionOver('d_<?=$data["fair_group_id"]?>','3');" onmouseout="actionOut('d_<?=$data["fair_group_id"]?>','3')" src="../backoffice/asset/icon_del_null.png" height="35" onclick="confirmDelete('<?=$data["fair_group_id"]?>');"></span>
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

<div class="modal fade text-left w-100 bg-modal" id="full-scrn_edit" tabindex="-1" aria-labelledby="myModalLabel20" aria-hidden="true">
  <div  class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full _modelinv" role="document"></div>

</div>

<script type="text/javascript">
function changeStatus(id,type) {
  $.ajax({
      type: "GET",
      url: "php/fair_master_list.php?method=chgstatus&id="+id+"&status="+type,
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
      url: "php/fair_master_list.php?method=delete&id="+id,
      dataType: "text",
      success : function(data) {
        alertpopup('1','ลบรายการเรียบร้อยแล้ว');
        setTimeout(function () {top.window.location="home.php?show=fair_master_list";},500);
      }
  });
}

function showAdmin(id) {
  $('#full-scrn_edit').modal('show');
  $('._modelinv').empty();
  $.ajax({
      type: "GET",
      url: "php/fair_master_admin.php?id="+id,
      dataType: "text",
      success : function(data) {
        $('._modelinv').html(data);

        $('#deferRenderTableAdmin').DataTable( {
            "deferRender": true,
            "bPaginate": false
        });
      }
  });

}
</script>
