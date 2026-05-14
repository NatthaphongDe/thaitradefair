<?php
$menu_name = "Menu Setting";
$save_link = "adfair_menu_list";
$back_link = "adfair_list";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $fair_id = $_POST["fair_id"];
  $ids = $_POST["fct_id"];
  $statuss = $_POST["fct_status"];
  $flags = $_POST["fct_flag"];



  for($i=0;$i<count($ids);$i++) {
    $id = (int)$ids[$i];
    $status = (int)$statuss[$i];
    $flag = (int)$flags[$i];
    if($flag==0) {
      $flag = 1;
    }

    $sqlup = " update tt_fair_list_cat set fct_status = ?, fct_flag = ? where fct_id = ? ";
    $stmtup = $mysqli->prepare($sqlup);
    $stmtup->bind_param('iii',$status,$flag,$id);
    $stmtup->execute();
  }

  ?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.alertpopup("1","บันทึกรายการเรียบร้อย");
    setTimeout(function () {top.window.location="../home.php?show=<?=$save_link?>&id=<?=$fair_id?>";},500);
  </script>
  <?php
  exit();

  exit();
}
?>


<?
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

              <div class="table-responsive">
                 <table class="table table-stripped table-hover table-bordered" id="deferRenderTable">
                    <thead>
                    <tr>
                      <th class="text-center tr-head">&nbsp;</th>
                      <th class="text-left tr-head">Menu Name</th>
                      <th class="text-center tr-head">Inactive / Active</th>
                      <th class="text-center tr-head">Action</th>
                      <th class="text-center tr-head">Approved</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?
                      $array_child = array();
                      $runno = 0;
                      $sqlc = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_type = '1' order by a.fct_pos ASC ";
                      $stmtc = $mysqli->prepare($sqlc);
                      if($stmtc) {
                        $stmtc->bind_param('i',$data["fair_id"]);
                        $stmtc->execute();
                        $resultc = $stmtc->get_result();
                        $numrowc = $resultc->num_rows;
                        if($numrowc>0) {
                          while($datac = $resultc->fetch_assoc()) {
                            ?>
                            <tr style="background:#F7F7F8 !important;">
                              <td class="text-center vmiddle">
                                <i class="fa fa-bars" aria-hidden="true"></i>
                                <input type="hidden" name="fct_id[<?=$runno?>]" value="<?=$datac["fct_id"]?>">
                                <input type="hidden" id="fct_id_<?=$datac["fct_id"]?>" name="fct_status[<?=$runno?>]" value="<?=$datac["fct_status"]?>">
                              </td>
                              <td class="text-left vmiddle" style="color:#1C5FA1;"><?=$datac["fcat_name"]?></td>
                              <td class="text-center vmiddle">
                                <? if($datac["fct_status"]==2) { ?>
                                  <img class="img_<?=$datac["fct_id"]?>" src="../backoffice/asset/icon_inactive.png" onclick="changeStatus('<?=$datac["fct_id"]?>');" >
                                <? } else { ?>
                                  <img class="img_<?=$datac["fct_id"]?>" src="../backoffice/asset/icon_active.png" onclick="changeStatus('<?=$datac["fct_id"]?>');" >
                                <? } ?>
                              </td>
                              <td class="text-center vmiddle">
                                <?
                                $actionlink = "";
                                if($datac["fct_cms_type"]==1 or $datac["fct_cms_type"]==2 or $datac["fct_cms_type"]==3 or $datac["fct_cms_type"]==4) {
                                  $actionlink = "fair_content_portal";
                                }
                                ?>
                                <? if($datac["fct_cms_type"]==1 or $datac["fct_cms_type"]==2 or $datac["fct_cms_type"]==3 or $datac["fct_cms_type"]==4) { ?>
                                  <span><img id="e_<?=$datac["fct_id"]?>" onmouseover="actionOver('e_<?=$datac["fct_id"]?>','1');" onmouseout="actionOut('e_<?=$datac["fct_id"]?>','1')" src="../backoffice/asset/icon_edit_null.png" height="35"
                                    <? if($actionlink!="") { ?>onclick="window.open('home.php?show=<?=$actionlink?>&fair_id=<?=$data["fair_id"]?>&fct_id=<?=$datac["fct_id"]?>');"
                                  <? } ?> ></span>
                                <? } ?>
                                &nbsp;
                              </td>
                              <td class="text-center vmiddle">
                                <? if($datac["fct_cms_type"]>0) { ?>
                                  <div class="radio radio-info radio-inline">
                                      <input type="radio" id="inlineRadio1xx_<?=$datac["fct_id"]?>" value="1" name="fct_flag[<?=$runno?>]" <? if($datac["fct_flag"]==1) { echo "checked"; } ?>  >
                                      <label for="inlineRadio1xx_<?=$datac["fct_id"]?>"> Auto Publish</label>
                                  </div>
                                  <div class="radio radio-info radio-inline">
                                      <input type="radio" id="inlineRadio2xx_<?=$datac["fct_id"]?>" value="2" name="fct_flag[<?=$runno?>]" <? if($datac["fct_flag"]==2) { echo "checked"; } ?> >
                                      <label for="inlineRadio2xx_<?=$datac["fct_id"]?>"> Require Approve</label>
                                  </div>
                                <? } ?>
                                &nbsp;
                              </td>
                            </tr>

                            <?
                            $runno++;

                            $sqlc2 = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_master = ? and b.fcat_type = '2' order by a.fct_pos ASC ";
                            $stmtc2 = $mysqli->prepare($sqlc2);
                            if($stmtc2) {
                              $stmtc2->bind_param('ii',$data["fair_id"],$datac["fcat_id"]);
                              $stmtc2->execute();
                              $resultc2 = $stmtc2->get_result();
                              $numrowc2 = $resultc2->num_rows;
                              if($numrowc2>0) {
                                $array_child[$datac["fct_id"]] = array();
                                while($datac2 = $resultc2->fetch_assoc()) {
                            ?>
                            <tr>
                              <td class="text-center vmiddle">
                                <i class="fa fa-bars" aria-hidden="true"></i>
                                <input type="hidden" name="fct_id[<?=$runno?>]" value="<?=$datac2["fct_id"]?>">
                                <input type="hidden" id="fct_id_<?=$datac2["fct_id"]?>" name="fct_status[<?=$runno?>]" value="<?=$datac2["fct_status"]?>">
                              </td>
                              <td class="text-left vmiddle" style="padding-left:20px;"><?=$datac2["fcat_name"]?></td>
                              <td class="text-center vmiddle">
                                <? if($datac2["fct_status"]==2) { ?>
                                  <img class="img_s_<?=$datac["fct_id"]?> img_c_<?=$datac2["fct_id"]?>" src="../backoffice/asset/icon_inactive.png" onclick="changeStatusChild('<?=$datac["fct_id"]?>','<?=$datac2["fct_id"]?>');" >
                                <? } else { ?>
                                  <img class="img_s_<?=$datac["fct_id"]?> img_c_<?=$datac2["fct_id"]?>" src="../backoffice/asset/icon_active.png" onclick="changeStatusChild('<?=$datac["fct_id"]?>','<?=$datac2["fct_id"]?>');" >
                                <? } ?>
                              </td>
                              <td class="text-center vmiddle">
                                <?
                                $actionlink = "";
                                if($datac2["fct_cms_type"]==1 or $datac2["fct_cms_type"]==2 or $datac2["fct_cms_type"]==3 or $datac2["fct_cms_type"]==4) {
                                  $actionlink = "fair_content_portal";
                                }
                                ?>
                                <? if($datac2["fct_cms_type"]==1 or $datac2["fct_cms_type"]==2 or $datac2["fct_cms_type"]==3 or $datac2["fct_cms_type"]==4) { ?>
                                  <span><img id="e_<?=$datac2["fct_id"]?>" onmouseover="actionOver('e_<?=$datac2["fct_id"]?>','1');" onmouseout="actionOut('e_<?=$datac2["fct_id"]?>','1')" src="../backoffice/asset/icon_edit_null.png" height="35"
                                    <? if($actionlink!="") { ?>onclick="window.open('home.php?show=<?=$actionlink?>&fair_id=<?=$data["fair_id"]?>&fct_id=<?=$datac2["fct_id"]?>');"
                                    <? } ?>
                                    ></span>
                                <? } ?>
                                &nbsp;
                              </td>
                              <td class="text-center vmiddle">
                                <? if($datac2["fct_cms_type"]>0) { ?>
                                  <div class="radio radio-info radio-inline">
                                      <input type="radio" id="inlineRadio1xx_<?=$datac2["fct_id"]?>" value="1" name="fct_flag[<?=$runno?>]" <? if($datac2["fct_flag"]==1) { echo "checked"; } ?>  >
                                      <label for="inlineRadio1xx_<?=$datac2["fct_id"]?>"> Auto Publish</label>
                                  </div>
                                  <div class="radio radio-info radio-inline">
                                      <input type="radio" id="inlineRadio2xx_<?=$datac2["fct_id"]?>" value="2" name="fct_flag[<?=$runno?>]" <? if($datac2["fct_flag"]==2) { echo "checked"; } ?> >
                                      <label for="inlineRadio2xx_<?=$datac2["fct_id"]?>"> Require Approve</label>
                                  </div>
                                <? } ?>
                                &nbsp;
                              </td>
                            </tr>
                            <? $runno++;
                                array_push($array_child[$datac["fct_id"]],$datac2["fct_id"]);

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

            <input type="hidden" name="fair_id" value="<?=$data["fair_id"]?>">
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
  var stc = $('#fct_id_'+id).val();
  if(stc==1) {
    $(".img_"+id).attr("src","../backoffice/asset/icon_inactive.png");
    $('#fct_id_'+id).val(2);
    for(i=0;i<javascript_array[id].length;i++) {
      cid = javascript_array[id][i];
      $(".img_c_"+cid).attr("src","../backoffice/asset/icon_inactive.png");
      $('#fct_id_'+cid).val(2);
    }
  } else {
    $(".img_"+id).attr("src","../backoffice/asset/icon_active.png");
    $('#fct_id_'+id).val(1);

    for(i=0;i<javascript_array[id].length;i++) {
      cid = javascript_array[id][i];
      $(".img_c_"+cid).attr("src","../backoffice/asset/icon_active.png");
      $('#fct_id_'+cid).val(1);
    }
  }
}

function changeStatusChild(id,cid) {
  var stc = $('#fct_id_'+cid).val();
  if(stc==1) {
    $(".img_c_"+cid).attr("src","../backoffice/asset/icon_inactive.png");
    $('#fct_id_'+cid).val(2);
  } else {
    $(".img_c_"+cid).attr("src","../backoffice/asset/icon_active.png");
    $('#fct_id_'+cid).val(1);

    $(".img_"+id).attr("src","../backoffice/asset/icon_active.png");
    $('#fct_id_'+id).val(1);
  }
}

function actionOver(id,type) {
  if(type==1) {
    $('#'+id).attr('src','../backoffice/asset/icon_edit_act.png');
  }
}

function actionOut(id,type) {
  if(type==1) {
    $('#'+id).attr('src','../backoffice/asset/icon_edit_null.png');
  }
}
</script>
