<?php
$header_name = "Fair Management";
$menu_name = "Fair Manager";
$back_link = "adfair_list";

if($_REQUEST["method"]=="delete") {
  include_once("../connect.php");

  $id = $_GET["id"];
  $sql = "update tt_fair_list set fair_status = 9 where fair_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();
  }

  saveLogActivity(3,$_SESSION["id"],$id,"Delete");
  exit();
}

if($_REQUEST["method"]=="chgstatus") {
  include_once("../connect.php");

  $id = $_GET["id"];
  $status = $_GET["status"];
  $sql = "select * from tt_fair_list where fair_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if($numrow>0) {
      $runno = 1;
      $data = $result->fetch_assoc();

      if($data["fair_status"]==1) {
        $newsta = 2;
      } else {
        $newsta = 1;
      }

      $sqlup = "update tt_fair_list set fair_status = ? where fair_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('ii',$newsta,$id);
        $stmtup->execute();
      }

      if($newsta==1) {
        ?>
        <img id="s_<?=$data["fair_id"]?>" src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["fair_id"]?>');" >
        <?
      } else {
        ?>
        <img id="s_<?=$data["fair_id"]?>" src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["fair_id"]?>');" >
        <?
      }
      exit();

    }
  }

  exit();
}


$sql = "select * from tt_fair_list where fair_token = '' ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    while($data = $result->fetch_assoc()) {
      $tokenfair = date("YmdHis")."-".$data["fair_id"];
      $tokenfair = base64_encode(md5($tokenfair));

      $sqlup = "update tt_fair_list set fair_token = ? where fair_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('si',$tokenfair,$data["fair_id"]);
        $stmtup->execute();
      }
    }
  }
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
      <a href="home.php?show=adfair_add" class="btn btn-success">+ Create Fair Name</a>
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
                    <th class="text-left tr-head">Fair Name</th>
                    <th class="text-center tr-head">Fair ABB</th>
                    <th class="text-center tr-head">Update Date</th>
                    <th class="text-center tr-head">Years</th>
                    <th class="text-center tr-head">Inactive / Active</th>
                    <th width="30%" class="text-center tr-head">Action</th>
                    <th class="text-center tr-head">Sources </th>
                  </tr>
                  </thead>
                  <tbody>
                    <?
                    $sql = "select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id where a.fair_status != '9' and b.fair_flag != '9' and b.fair_group_id = ?  order by a.fair_id DESC ";
                    $stmt = $mysqli->prepare($sql);
                    if($stmt) {
                      $stmt->bind_param('i',$_SESSION["fgid"]);
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $numrow = $result->num_rows;
                      if($numrow>0) {
                        $runno = 1;
                        while($data = $result->fetch_assoc()) {
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
                          ?>
                          <tr>
                          	<td class="text-center vmiddle"><?=$runno?></td>
                            <td class="text-left vmiddle"><?=$data["fair_name"]?></td>
                            <td class="text-center vmiddle"><?=$abb?></td>
                            <td class="text-center vmiddle"><?=$data["fair_update_date"]?></td>
                            <td class="text-center vmiddle"><?=$data["fair_year"]?></td>
                            <td class="text-center vmiddle s_<?=$data["fair_id"]?>">
                              <? if($data["fair_status"]==2) { ?>
                                <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["fair_id"]?>');" >
                              <? } else { ?>
                                <img src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["fair_id"]?>');" >
                              <? } ?>
                            </td>
                            <td class="text-center vmiddle">

                              <span><img id="e_<?=$data["fair_id"]?>" onmouseover="actionOver('e_<?=$data["fair_id"]?>','1');" onmouseout="actionOut('e_<?=$data["fair_id"]?>','1')" src="../backoffice/asset/icon_edit_null.png" height="35" onclick="window.location='home.php?show=adfair_edit&id=<?=$data["fair_id"]?>';" ></span>
                              &nbsp;
                              <span><img id="m_<?=$data["fair_id"]?>" onmouseover="actionOver('m_<?=$data["fair_id"]?>','2');" onmouseout="actionOut('m_<?=$data["fair_id"]?>','2')" src="../backoffice/asset/icon_m_null.png" height="35" onclick="showAdminFair('<?=$data["fair_id"]?>');" ></span>
                              &nbsp;
                              <span><img id="p_<?=$data["fair_id"]?>" onmouseover="actionOver('p_<?=$data["fair_id"]?>','4');" onmouseout="actionOut('p_<?=$data["fair_id"]?>','4')" src="../backoffice/asset/icon_img_null.png" height="35" onclick="window.location='home.php?show=adfair_cover&id=<?=$data["fair_id"]?>';"></span>
                              &nbsp;
                              <span><img id="i_<?=$data["fair_id"]?>" onmouseover="actionOver('i_<?=$data["fair_id"]?>','5');" onmouseout="actionOut('i_<?=$data["fair_id"]?>','5')" src="../backoffice/asset/icon_inf_null.png" height="35" onclick="window.location='home.php?show=adfair_information&id=<?=$data["fair_id"]?>';" ></span>
                              &nbsp;
                              <span><img id="c_<?=$data["fair_id"]?>" onmouseover="actionOver('c_<?=$data["fair_id"]?>','6');" onmouseout="actionOut('c_<?=$data["fair_id"]?>','6')" src="../backoffice/asset/icon_cat_null.png" height="35" onclick="window.location='home.php?show=adfair_menu_list&id=<?=$data["fair_id"]?>';" ></span>
                              &nbsp;
                              <span><img id="t_<?=$data["fair_id"]?>" onmouseover="actionOver('t_<?=$data["fair_id"]?>','7');" onmouseout="actionOut('t_<?=$data["fair_id"]?>','7')" src="../backoffice/asset/icon_token_null.png" height="35" onclick="showGetToken('<?=$data["fair_id"]?>');" ></span>
                              &nbsp;
                              <span><img id="d_<?=$data["fair_id"]?>" onmouseover="actionOver('d_<?=$data["fair_id"]?>','3');" onmouseout="actionOut('d_<?=$data["fair_id"]?>','3')" src="../backoffice/asset/icon_del_null.png" height="35" onclick="confirmDelete('<?=$data["fair_id"]?>');"></span>
                              &nbsp;
                            </td>
                            <td class="text-center vmiddle">
                              <? if($data["fair_ditp_id"]>0) { ?>
                                <img src="../backoffice/asset/icon_fair_ditp.png" height="35">
                              <? } else { ?>
                                MANUAL
                              <? }  ?>
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
function changeStatus(id) {
  $.ajax({
      type: "GET",
      url: "php/adfair_list.php?method=chgstatus&id="+id,
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
  if(type==7) {
    $('#'+id).attr('src','../backoffice/asset/icon_token_act.png');
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
  if(type==7) {
    $('#'+id).attr('src','../backoffice/asset/icon_token_null.png');
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
      url: "php/adfair_list.php?method=delete&id="+id,
      dataType: "text",
      success : function(data) {
        alertpopup('1','ลบรายการเรียบร้อยแล้ว');
        setTimeout(function () {top.window.location="home.php?show=adfair_list";},500);
      }
  });
}

function showAdminFair(id) {
  $('#full-scrn_edit').modal('show');
  $('._modelinv').empty();
  $.ajax({
      type: "GET",
      url: "php/adfair_list_admin.php?id="+id,
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

function showGetToken(id) {
  $('#full-scrn_edit').modal('show');
  $('._modelinv').empty();
  $.ajax({
      type: "GET",
      url: "php/adfair_token_pass.php?id="+id,
      dataType: "text",
      success : function(data) {
        $('._modelinv').html(data);
      }
  });

}
</script>
