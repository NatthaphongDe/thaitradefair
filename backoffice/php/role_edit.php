<?php
$menu_name = "Edit Role";
$save_link = "role_edit";
$back_link = "role_list";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $role_id = $_POST["role_id"];
  $role_name = $_POST["role_name"];
  $role_status = $_POST["role_status"];
  $role_lv = $_POST["role_lv"];

  $ids = $_POST["page_id"];
  $statuss = $_POST["page_status"];
  $actions = $_POST["page_permission"];

  $sql = "update tt_admin_role set
          role_lv = ?,
          role_name = ?,
          role_status = ?,
          role_update_date = now(),
          role_update_by = ?
          where role_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('isiii',$role_lv,$role_name,$role_status,$adminid,$role_id);
    $stmt->execute();

    $sqlup = " delete from tt_admin_role_page_item where role_id = ? ";
    $stmtup = $mysqli->prepare($sqlup);
    $stmtup->bind_param('i',$role_id);
    $stmtup->execute();


    if($role_lv!=3) {
      for($i=0;$i<count($ids);$i++) {
        $id = (int)$ids[$i];
        $status = (int)$statuss[$i];
        $action = (int)$actions[$i];

        $sqlup = " insert into tt_admin_role_page_item (role_id,page_id,page_item_status,page_item_action) values (?,?,?,?) ";
        $stmtup = $mysqli->prepare($sqlup);
        $stmtup->bind_param('iiii',$role_id,$id,$status,$action);
        $stmtup->execute();

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
$id = $_GET["id"];
$sql = "select * from tt_admin_role where role_id = ? ";
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
            <form class="form-horizontal" method="post" name="form_Suppliers_add" enctype="multipart/form-data" id="form_Suppliers_add" action="php/<?=$save_link?>.php?method=add" target="com_m" onSubmit="pc_overlay(1);" >

            <h3><?=$menu_name?></h3>
            <div class="ibox-content">

              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Role Names *</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="role_name" required autocomplete="off" value="<?=$data["role_name"]?>" />
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Status *</label>
                    <div class="col-lg-9">
                      <div class="radio radio-info radio-inline">
                          <input type="radio" id="inlineRadio1" value="1" name="role_status" required <? if($data["role_status"]==1) { echo "checked"; } ?>  >
                          <label for="inlineRadio1"> Active</label>
                      </div>
                      <div class="radio radio-info radio-inline">
                          <input type="radio" id="inlineRadio2" value="2" name="role_status" required <? if($data["role_status"]==2) { echo "checked"; } ?>>
                          <label for="inlineRadio2"> Inactive</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Role Level  *</label>
                    <div class="col-lg-9">
                      <div class="radio radio-info radio-inline">
                          <input type="radio" id="inlineRadio1x" value="1" name="role_lv" required <? if($data["role_lv"]==1) { echo "checked"; } ?> onclick="changeRole(1);">
                          <label for="inlineRadio1x"> Super Admin</label>
                      </div>
                      <div class="radio radio-info radio-inline">
                          <input type="radio" id="inlineRadio2x" value="2" name="role_lv" required <? if($data["role_lv"]==2) { echo "checked"; } ?> onclick="changeRole(2);">
                          <label for="inlineRadio2x"> Admin</label>
                      </div>
                      <div class="radio radio-info radio-inline">
                          <input type="radio" id="inlineRadio3x" value="3" name="role_lv" required <? if($data["role_lv"]==3) { echo "checked"; } ?> onclick="changeRole(3);">
                          <label for="inlineRadio3x"> Fair Admin</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="table-responsive _roleblog">
                 <table class="table table-stripped table-hover table-bordered" id="deferRenderTable">
                    <thead>
                    <tr>
                      <th class="text-left tr-head">Menu Name</th>
                      <th class="text-center tr-head">Inactive / Active</th>
                      <th class="text-center tr-head">Permission</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?
                      $array_child = array();
                      $runno = 0;
                      $sqlc = "select * from tt_admin_role_page where page_master = '0' order by page_pos ASC ";
                      $stmtc = $mysqli->prepare($sqlc);
                      if($stmtc) {
                        $stmtc->execute();
                        $resultc = $stmtc->get_result();
                        $numrowc = $resultc->num_rows;
                        if($numrowc>0) {
                          while($datac = $resultc->fetch_assoc()) {
                            $itemdata = getRolePageStatus($data["role_id"],$datac["page_id"]);
                            ?>
                            <tr style="background:#F7F7F8 !important;">
                              <td class="text-left vmiddle" style="color:#1C5FA1;">
                                <?=$datac["page_name"]?>
                                <input type="hidden" name="page_id[<?=$runno?>]" value="<?=$datac["page_id"]?>">
                                <input type="hidden" id="page_id_<?=$datac["page_id"]?>" name="page_status[<?=$runno?>]" value="<?=$itemdata["page_item_status"]?>">
                              </td>
                              <td class="text-center vmiddle">
                                <? if($itemdata["page_item_status"]==1) { ?>
                                  <img class="img_<?=$datac["page_id"]?>" src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$datac["page_id"]?>');" >
                                <? } else { ?>
                                  <img class="img_<?=$datac["page_id"]?>" src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$datac["page_id"]?>');" >
                                <? } ?>

                              </td>
                              <td class="text-center vmiddle">
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" class="_req" id="inlineRadio1x_<?=$datac["page_id"]?>" value="1" name="page_permission[<?=$runno?>]" required <? if($itemdata["page_item_action"]==1) { echo "checked"; } ?> >
                                    <label for="inlineRadio1x_<?=$datac["page_id"]?>"> Read Only</label>
                                </div>
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" class="_req" id="inlineRadio2x_<?=$datac["page_id"]?>" value="2" name="page_permission[<?=$runno?>]" required <? if($itemdata["page_item_action"]==2) { echo "checked"; } ?>>
                                    <label for="inlineRadio2x_<?=$datac["page_id"]?>"> Read & Write</label>
                                </div>
                              </td>
                            </tr>

                            <?
                            $runno++;

                            $sqlc2 = "select * from tt_admin_role_page where page_master = ? order by page_pos ASC ";
                            $stmtc2 = $mysqli->prepare($sqlc2);
                            if($stmtc2) {
                              $stmtc2->bind_param('i',$datac["page_id"]);
                              $stmtc2->execute();
                              $resultc2 = $stmtc2->get_result();
                              $numrowc2 = $resultc2->num_rows;
                              if($numrowc2>0) {
                                $array_child[$datac["page_id"]] = array();
                                while($datac2 = $resultc2->fetch_assoc()) {
                                  $itemdata2 = getRolePageStatus($data["role_id"],$datac2["page_id"]);
                            ?>
                            <tr>
                              <td class="text-left vmiddle" style="padding-left:20px;">
                                <?=$datac2["page_name"]?>
                                <input type="hidden" name="page_id[<?=$runno?>]" value="<?=$datac2["page_id"]?>">
                                <input type="hidden" id="page_id_<?=$datac2["page_id"]?>" name="page_status[<?=$runno?>]" value="<?=$itemdata2["page_item_status"]?>">
                              </td>
                              <td class="text-center vmiddle">
                                <? if($itemdata2["page_item_status"]==1) { ?>
                                  <img class="img_s_<?=$datac["page_id"]?> img_c_<?=$datac2["page_id"]?>" src="../backoffice/asset/icon_active.png" onclick="changeStatusChild('<?=$datac["page_id"]?>','<?=$datac2["page_id"]?>');" >
                                <? } else { ?>
                                  <img class="img_s_<?=$datac["page_id"]?> img_c_<?=$datac2["page_id"]?>" src="../backoffice/asset/icon_inactive.png" onclick="changeStatusChild('<?=$datac["page_id"]?>','<?=$datac2["page_id"]?>');" >
                                <? } ?>

                              </td>
                              <td class="text-center vmiddle">
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" class="inlineRadioM1_<?=$datac["page_id"]?> _req" id="inlineRadio1x_<?=$datac2["page_id"]?>" value="1" name="page_permission[<?=$runno?>]"  required <? if($itemdata2["page_item_action"]==1) { echo "checked"; } ?>>
                                    <label for="inlineRadio1x_<?=$datac2["page_id"]?>"> Read Only</label>
                                </div>
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" class="inlineRadioM2_<?=$datac["page_id"]?> _req" id="inlineRadio2x_<?=$datac2["page_id"]?>" value="2" name="page_permission[<?=$runno?>]"  required <? if($itemdata2["page_item_action"]==2) { echo "checked"; } ?>>
                                    <label for="inlineRadio2x_<?=$datac2["page_id"]?>"> Read & Write</label>
                                </div>
                              </td>
                            </tr>
                            <? $runno++;
                                array_push($array_child[$datac["page_id"]],$datac2["page_id"]);

                          } }  } ?>
                            <?
                          }
                        }
                      }
                      ?>
                    </tbody>
                </table>
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

            <input type="hidden" name="role_id" value="<?=$data["role_id"]?>">
            </form>
          </div>
        </div>
	</div>
</div>

<script type="text/javascript">
function changeStatus(id) {

  <?
  $js_array = json_encode($array_child);
  echo "var javascript_array = ". $js_array . ";\n";
  ?>
  var stc = $('#page_id_'+id).val();
  if(stc==1) {
    $(".img_"+id).attr("src","../backoffice/asset/icon_inactive.png");
    $('#page_id_'+id).val(2);
    for(i=0;i<javascript_array[id].length;i++) {
      cid = javascript_array[id][i];
      $(".img_c_"+cid).attr("src","../backoffice/asset/icon_inactive.png");
      $('#page_id_'+cid).val(2);
    }
  } else {
    $(".img_"+id).attr("src","../backoffice/asset/icon_active.png");
    $('#page_id_'+id).val(1);

    for(i=0;i<javascript_array[id].length;i++) {
      cid = javascript_array[id][i];
      $(".img_c_"+cid).attr("src","../backoffice/asset/icon_active.png");
      $('#page_id_'+cid).val(1);
    }
  }
}

function changeStatusChild(id,cid) {
  var stc = $('#page_id_'+cid).val();
  if(stc==1) {
    $(".img_c_"+cid).attr("src","../backoffice/asset/icon_inactive.png");
    $('#page_id_'+cid).val(2);
  } else {
    $(".img_c_"+cid).attr("src","../backoffice/asset/icon_active.png");
    $('#page_id_'+cid).val(1);

    $(".img_"+id).attr("src","../backoffice/asset/icon_active.png");
    $('#page_id_'+id).val(1);
  }
}

function changeRole(type) {
  if(type==1 || type==2) {
    $('._roleblog').show();
    $('._req').prop('required',true);
  } else {
    $('._roleblog').hide();
    $('._req').prop('required',false);
  }
}

$(document).ready(function() {
  changeRole('<?=$data["role_lv"]?>');

});
</script>
