<?php
if($_REQUEST["method"]=="chnagetpye") {
  include_once("../connect.php");

  $fct_id = $_GET["fct_id"];
  $fair_id = $_GET["fair_id"];
  $type = $_GET["type"];

  $sql = " update tt_fair_list_cat set fct_cms_type = ? where fct_id = ? ";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('ii',$type,$fct_id);
  $stmt->execute();

  exit();
}

if($_REQUEST["method"]=="delete") {
  include_once("../connect.php");

  $id = $_GET["id"];
  $sql = "delete from tt_fair_content_article where fca_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();

    saveLogActivity(5,$_SESSION["id"],$id,"Delete");

    if($id>0) {
      $rmdirs =ROOTPATH."/data/fairarticle/".$id;
      deleteDirectory($rmdirs);
    }

    $sqlphoto = "select * from  tt_fair_content_file where file_type = '2' and content_id = ? ";
    $stmtphoto = $mysqli->prepare($sqlphoto);
    $stmtphoto->bind_param('i',$id);
    $stmtphoto->execute();
    $resultphoto = $stmtphoto->get_result();
    $numrowphoto = $resultphoto->num_rows;
    if($numrowphoto>0) {
      while($dataphoto = $resultphoto->fetch_assoc()) {
        if($dataphoto["file_id"]>0) {
          $rmdirs =ROOTPATH."/data/fairattachfile/".$dataphoto["file_id"];
          deleteDirectory($rmdirs);
        }
      }
    }

    $sql = "delete from tt_fair_content_file where file_type = '2' and content_id = ? ";
    $stmt = $mysqli->prepare($sql);
    if($stmt) {
      $stmt->bind_param('i',$id);
      $stmt->execute();
    }

  }
  exit();
}

if($_REQUEST["method"]=="chgstatus") {
  include_once("../connect.php");

  $id = $_GET["id"];
  $status = $_GET["status"];
  $sql = "select * from tt_fair_content_article where fca_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if($numrow>0) {
      $runno = 1;
      $data = $result->fetch_assoc();

      if($data["fca_status"]==1) {
        $newsta = 2;
      } else {
        $newsta = 1;
      }

      $sqlup = "update tt_fair_content_article set fca_status = ? where fca_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('ii',$newsta,$id);
        $stmtup->execute();
      }

      if($newsta==1) {
        ?>
        <img id="s_<?=$data["fca_id"]?>" src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["fca_id"]?>');" >
        <?
      } else {
        ?>
        <img id="s_<?=$data["fca_id"]?>" src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["fca_id"]?>');" >
        <?
      }
      exit();

    }
  }

  exit();
}

$menu_head = $datac["fcat_name"];
$save_link = "admin_fair_content_aricle";
?>

<div class="row wrapper page-heading">
     <div class="col-xs-12">
       <br>
       <ol class="breadcrumb">
           <li>
               <h3><span class="backnav-size-txt" style="padding-left:0;"><?=$menu_head?></span></h3>
           </li>
            <div class="div-right text-right">
              <? if($datac["fct_cms_type"]==2) { ?>
                <div class="radio radio-info radio-inline">
                    <input type="radio" id="inlineRadio1" value="1" name="fct_cms_type" <? if($datac["fct_cms_type"]==1) { echo "checked"; } ?> onclick="changeTypeCMS('1',' One Page');" >
                    <label for="inlineRadio1"> One Page</label>
                </div>
                <div class="radio radio-info radio-inline">
                    <input type="radio" id="inlineRadio2" value="2" name="fct_cms_type" <? if($datac["fct_cms_type"]==2) { echo "checked"; } ?>  >
                    <label for="inlineRadio2"> List</label>
                </div>
                <div class="radio radio-info radio-inline">
                    <input type="radio" id="inlineRadio3" value="3" name="fct_cms_type" <? if($datac["fct_cms_type"]==3) { echo "checked"; } ?>  onclick="changeTypeCMS('3','Thumbnail');" >
                    <label for="inlineRadio3"> Thumbnail</label>
                </div>
              <? } ?>

              <? if($datac["fct_cms_type"]==3) { ?>
                <div class="radio radio-info radio-inline">
                    <input type="radio" id="inlineRadio1" value="1" name="fct_cms_type" <? if($datac["fct_cms_type"]==1) { echo "checked"; } ?> onclick="changeTypeCMS('1',' One Page');"  >
                    <label for="inlineRadio1"> One Page</label>
                </div>
                <div class="radio radio-info radio-inline">
                    <input type="radio" id="inlineRadio2" value="2" name="fct_cms_type" <? if($datac["fct_cms_type"]==2) { echo "checked"; } ?> onclick="changeTypeCMS('2','List');" >
                    <label for="inlineRadio2"> List</label>
                </div>
                <div class="radio radio-info radio-inline">
                    <input type="radio" id="inlineRadio3" value="3" name="fct_cms_type" <? if($datac["fct_cms_type"]==3) { echo "checked"; } ?> >
                    <label for="inlineRadio3"> Thumbnail</label>
                </div>
              <? } ?>

              &nbsp;&nbsp;&nbsp;
              <a href="home.php?show=admin_fair_content_aricle_add&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>" class="btn btn-success">+ Add <?=$menu_head?></a>

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
                    <th class="text-center tr-head">Image</th>
                    <th class="text-center tr-head">News Headline</th>
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
                    $sql = "select * from  tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id where a.fct_id = ? order by a.fca_id DESC ";
                    $stmt = $mysqli->prepare($sql);
                    if($stmt) {
                      $stmt->bind_param('i',$fct_id);
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
                              <? if($data["fca_banner_path"]=="") { ?>
                                <img src="../backoffice/img/noimage.jpg" width="150">
                              <? } else { ?>
                                <img src="<?=ROOTPATHDOMAIN?><?=$data["fca_banner_path"]?>" width="150">
                              <? } ?>

                            </td>
                            <td class="text-center vmiddle"><?=$data["fca_title_en"]?></td>
                            <td class="text-center vmiddle"><?=$data["fair_name"]?></td>
                            <td class="text-center vmiddle"><?=getTagFairShow($data["fca_tag_fair_id"])?></td>
                            <td class="text-center vmiddle"><?=getTagMaster($data["fca_tag_master_id"])?></td>
                            <td class="text-center vmiddle"><?=$data["fca_create_date"]?></td>
                            <td class="text-center vmiddle s_<?=$data["fca_id"]?>">
                              <? if($data["fca_status"]==2) { ?>
                                <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$data["fca_id"]?>');" >
                              <? } else { ?>
                                <img src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$data["fca_id"]?>');" >
                              <? } ?>
                            </td>
                            <td class="text-center vmiddle">

                              <span><img id="e_<?=$data["fca_id"]?>" onmouseover="actionOver('e_<?=$data["fca_id"]?>','1');" onmouseout="actionOut('e_<?=$data["fca_id"]?>','1')" src="../backoffice/asset/icon_edit_null.png" height="35" onclick="window.location='home.php?show=admin_fair_content_aricle_edit&id=<?=$data["fca_id"]?>&fair_id=<?=$data["fair_id"]?>&fct_id=<?=$data["fct_id"]?>';" ></span>
                              &nbsp;
                              <span><img id="d_<?=$data["fca_id"]?>" onmouseover="actionOver('d_<?=$data["fca_id"]?>','3');" onmouseout="actionOut('d_<?=$data["fca_id"]?>','3')" src="../backoffice/asset/icon_del_null.png" height="35" onclick="confirmDelete('<?=$data["fca_id"]?>');"></span>
                              &nbsp;
                            </td>

                            <td class="text-center vmiddle">
                              <? if($data["fca_pubish"]==1) { ?>
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
      url: "php/admin_fair_content_aricle.php?method=chgstatus&id="+id+"&status="+type,
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
      url: "php/admin_fair_content_aricle.php?method=delete&id="+id+'&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>',
      dataType: "text",
      success : function(data) {
        alertpopup('1','ลบรายการเรียบร้อยแล้ว');
        setTimeout(function () {top.window.location="home.php?show=admin_fair_content_portal&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>";},100);
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
      url: "php/admin_fair_content_aricle.php?method=chnagetpye&type="+id+'&fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>',
      dataType: "text",
      success : function(data) {
        setTimeout(function () {top.window.location="home.php?show=admin_fair_content_portal&fct_id=<?=$fct_id?>";},100);
      }
  });
}

</script>
