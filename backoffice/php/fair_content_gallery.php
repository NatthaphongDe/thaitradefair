<?php
if($_REQUEST["method"]=="delete") {
  include_once("../connect.php");

  $id = $_GET["id"];
  $sql = "delete from tt_fair_content_gallery where fcg_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();

    saveLogActivity(6,$_SESSION["id"],$id,"Delete");

    $sqlphoto = "select * from  tt_fair_content_gallery_file where fcg_id = ? ";
    $stmtphoto = $mysqli->prepare($sqlphoto);
    $stmtphoto->bind_param('i',$id);
    $stmtphoto->execute();
    $resultphoto = $stmtphoto->get_result();
    $numrowphoto = $resultphoto->num_rows;
    if($numrowphoto>0) {
      while($dataphoto = $resultphoto->fetch_assoc()) {
        if($dataphoto["gall_file_id"]>0) {
          $rmdirs =ROOTPATH."/data/fairgallery/".$dataphoto["gall_file_id"];
          deleteDirectory($rmdirs);
        }
      }
    }
  }

  $sql = "delete from tt_fair_content_gallery_file where fcg_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();
  }

  exit();
}

if($_REQUEST["method"]=="chgstatus") {
  include_once("../connect.php");

  $id = $_GET["id"];
  $status = $_GET["status"];
  $sql = "select * from tt_fair_content_gallery where fcg_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if($numrow>0) {
      $runno = 1;
      $data = $result->fetch_assoc();

      if($data["fcg_status"]==1) {
        $newsta = 2;
      } else {
        $newsta = 1;
      }

      $sqlup = "update tt_fair_content_gallery set fcg_status = ? where fcg_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('ii',$newsta,$id);
        $stmtup->execute();
      }

      if($newsta==1) {
        ?>
        <img id="s_<?=$data["fcg_id"]?>" src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["fcg_id"]?>');" >
        <?
      } else {
        ?>
        <img id="s_<?=$data["fcg_id"]?>" src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["fcg_id"]?>');" >
        <?
      }
      exit();

    }
  }

  exit();
}

$menu_head = $datac["fcat_name"];
$save_link = "fair_content_gallery";

?>

<div class="row wrapper page-heading">
     <div class="col-xs-12">
       <br>
       <ol class="breadcrumb">
           <li>
               <h3><span class="backnav-size-txt" style="padding-left:0;"><?=$menu_head?></span></h3>
           </li>
            <div class="div-right text-right">
              <a href="home.php?show=fair_content_gallery_add&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>" class="btn btn-success">+ Add <?=$menu_head?></a>

            </div>
          </li>
       </ol>
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
                    <th class="text-center tr-head">Gallery Cover</th>
                    <th class="text-center tr-head">Gallery Title</th>
                    <th class="text-center tr-head">Fair Name</th>
                    <th class="text-center tr-head">Tags Fair</th>
                    <th class="text-center tr-head">Tags Master</th>
                    <th class="text-center tr-head">Create Date</th>
                    <th class="text-center tr-head">Inactive / Active</th>
                    <th width="120" class="text-center tr-head">Action</th>
                    <th class="text-center tr-head">Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?
                    $sql = "select * from  tt_fair_content_gallery a left join tt_fair_list b on a.fair_id=b.fair_id where a.fct_id = ? order by a.fcg_id DESC ";
                    $stmt = $mysqli->prepare($sql);
                    if($stmt) {
                      $stmt->bind_param('i',$fct_id);
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $numrow = $result->num_rows;
                      if($numrow>0) {
                        $runno = 1;
                        while($data = $result->fetch_assoc()) {

                          $cover = "../backoffice/img/noimage.jpg";

                          $sqlphoto = "select * from  tt_fair_content_gallery_file where fcg_id = ? order by gall_file_pos ASC limit 1 ";
                          $stmtphoto = $mysqli->prepare($sqlphoto);
                          $stmtphoto->bind_param('i',$data["fcg_id"]);
                          $stmtphoto->execute();
                          $resultphoto = $stmtphoto->get_result();
                          $numrowphoto = $resultphoto->num_rows;
                          if($numrowphoto>0) {
                            $dataphoto = $resultphoto->fetch_assoc();
                            $croppath = str_replace('/cover','/cover/resize',$dataphoto["gall_file_path"]);
                            $cover = ROOTPATHDOMAIN.$croppath;
                          }

                          ?>
                          <tr>
                          	<td class="text-center vmiddle"><?=$runno?></td>
                            <td class="text-center vmiddle">
                              <img src="<?=$cover?>" width="150">
                            </td>
                            <td class="text-center vmiddle"><?=$data["fcg_title_en"]?></td>
                            <td class="text-center vmiddle"><?=$data["fair_name"]?></td>
                            <td class="text-center vmiddle"><?=getTagFairShow($data["fcg_tag_fair_id"])?></td>
                            <td class="text-center vmiddle"><?=getTagMaster($data["fcg_tag_master_id"])?></td>
                            <td class="text-center vmiddle"><?=$data["fcg_create_date"]?></td>
                            <td class="text-center vmiddle s_<?=$data["fcg_id"]?>">
                              <? if($data["fcg_status"]==2) { ?>
                                <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["fcg_id"]?>');" >
                              <? } else { ?>
                                <img src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["fcg_id"]?>');" >
                              <? } ?>
                            </td>
                            <td class="text-center vmiddle">

                              <span><img id="e_<?=$data["fcg_id"]?>" onmouseover="actionOver('e_<?=$data["fcg_id"]?>','1');" onmouseout="actionOut('e_<?=$data["fcg_id"]?>','1')" src="../backoffice/asset/icon_edit_null.png" height="35" onclick="window.location='home.php?show=fair_content_gallery_edit&id=<?=$data["fcg_id"]?>&fair_id=<?=$data["fair_id"]?>&fct_id=<?=$data["fct_id"]?>';" ></span>
                              &nbsp;
                              <span><img id="d_<?=$data["fcg_id"]?>" onmouseover="actionOver('d_<?=$data["fcg_id"]?>','3');" onmouseout="actionOut('d_<?=$data["fcg_id"]?>','3')" src="../backoffice/asset/icon_del_null.png" height="35" onclick="confirmDelete('<?=$data["fcg_id"]?>');"></span>
                              &nbsp;
                            </td>

                            <td class="text-center vmiddle">
                              <? if($data["fcg_pubish"]==1) { ?>
                                <button type="button" class="btn btn-publish">Approved</a>
                              <? } else { ?>
                                <button type="button" class="btn btn-unpublish">Pending</a>
                              <? } ?>
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

<script type="text/javascript">
function changeStatus(id,type) {
  $.ajax({
      type: "GET",
      url: "php/fair_content_gallery.php?method=chgstatus&id="+id+"&status="+type,
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
  if(type==3) {
    $('#'+id).attr('src','../backoffice/asset/icon_del_act.png');
  }
}

function actionOut(id,type) {
  if(type==1) {
    $('#'+id).attr('src','../backoffice/asset/icon_edit_null.png');
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
      url: "php/fair_content_gallery.php?method=delete&id="+id+'&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>',
      dataType: "text",
      success : function(data) {
        alertpopup('1','ลบรายการเรียบร้อยแล้ว');
        setTimeout(function () {top.window.location="home.php?show=fair_content_portal&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>";},100);
      }
  });
}


</script>
