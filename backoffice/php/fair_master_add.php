<?php
$menu_name = "Add Fair Group";
$save_link = "fair_master_add";
$back_link = "fair_master_list";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $fair_group_name_th = $_POST["fair_group_name_th"];
  $fair_group_abb = $_POST["fair_group_abb"];
  $fair_group_link = $_POST["fair_group_link"];
  $fair_group_social = $_POST["fair_group_social"];
  $fair_item = $_POST["act_data_select"];
  $fair_item = explode(',',$fair_item);

  $sql = "select * from tt_fair_group where fair_group_abb = ? and fair_group_status != '9' ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('s',$fair_group_abb);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if($numrow>0) {
      ?>
      <script type="text/javascript">
        top.pc_overlay(2);
        top.alertpopup("2","มี ABB นี้ในระบบแล้วค่ะ");
      </script>
      <?php
      exit();
    }
  }


  $sql = "insert into tt_fair_group (fair_group_name_th,fair_group_abb,fair_group_status,fair_group_link,fair_group_create_date,fair_group_create_by,fair_group_update_date,fair_group_update_by,fair_group_social) values (?,?,'2',?,now(),?,now(),?,?) ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('sssiis',$fair_group_name_th,$fair_group_abb,$fair_group_link,$adminid,$adminid,$fair_group_social);
    $stmt->execute();
    $fair_group_id = $stmt->insert_id;

    saveLogActivity(2,$_SESSION["id"],$fair_group_id,"Create");

    $fairlist = getFairList();

    for($i=0;$i<count($fair_item);$i++) {
      $idact = $fair_item[$i];
      if($idact>0) {

        $sqlchk = "select * from tt_fair_list where fair_ditp_id = ? and fair_status != '9' limit 1 ";
        $stmtchk = $mysqli->prepare($sqlchk);
        $stmtchk->bind_param('i',$idact);
        $stmtchk->execute();
        $resultchk = $stmtchk->get_result();
        $numrowchk = $resultchk->num_rows;
        if($numrowchk>0) {
          $datachk = $resultchk->fetch_assoc();

          $sql2 = "insert into tt_fair_group_list (fair_group_id,fair_id,fair_flag) values (?,?,'1') ";
          $stmt2 = $mysqli->prepare($sql2);
          $stmt2->bind_param('ii',$fair_group_id,$datachk["fair_id"]);
          $stmt2->execute();
        } else {
          $fair_name = "";
          $fair_year = "";
          $fair_url = "";
          $fair_trade_start = "";
          $fair_trade_end = "";
          $fair_public_start = "";
          $fair_public_end = "";
          $fair_event_start = "";
          $fair_event_end = "";
          $fair_vanue = "";
          for($j=0;$j<count($fairlist["Acitvities"]);$j++) {
            if($fairlist["Acitvities"][$j]["Activity_Detail"]["ID"]==$idact) {
              $fair_name = $fairlist["Acitvities"][$j]["Activity_Detail"]["Activity_Topic_TH"];
              $fair_year = $fairlist["Acitvities"][$j]["Activity_Detail"]["Activity_In_Year"];
              $fair_year = (($fair_year*1)-543);
              $fair_url = $fairlist["Acitvities"][$j]["Activity_Detail"]["Register_Url"];
              $fair_trade_start = $fairlist["Acitvities"][$j]["Activity_Date"]["Activity_Trade_Start"];
              $fair_trade_start = date("Y-m-d",strtotime($fair_trade_start));
              $fair_trade_end = $fairlist["Acitvities"][$j]["Activity_Date"]["Activity_Trade_End"];
              $fair_trade_end = date("Y-m-d",strtotime($fair_trade_end));
              $fair_public_start = $fairlist["Acitvities"][$j]["Activity_Date"]["Activity_Public_Start"];
              $fair_public_start = date("Y-m-d",strtotime($fair_public_start));
              $fair_public_end = $fairlist["Acitvities"][$j]["Activity_Date"]["Activity_Public_End"];
              $fair_public_end = date("Y-m-d",strtotime($fair_public_end));
              $fair_event_start = $fairlist["Acitvities"][$j]["Activity_Date"]["Activity_Start_Date"];
              $fair_event_start = date("Y-m-d",strtotime($fair_event_start));
              $fair_event_end = $fairlist["Acitvities"][$j]["Activity_Date"]["Activity_End_Date"];
              $fair_event_end = date("Y-m-d",strtotime($fair_event_end));
              $fair_vanue = $fairlist["Acitvities"][$j]["Activity_Location"][0]["Activity_Location_EN"];
              break;
            }
          }

          $sql2 = "insert into tt_fair_list (fair_ditp_id,fair_create_date,fair_create_by,fair_update_date,fair_update_by,fair_name,fair_year,fair_trade_start,fair_trade_end,fair_public_start,fair_public_end,fair_event_start,fair_event_end,fair_vanue,fair_url) values (?,now(),?,now(),?,?,?,?,?,?,?,?,?,?,?) ";
          $stmt2 = $mysqli->prepare($sql2);
          $stmt2->bind_param('iiisissssssss',$idact,$adminid,$adminid,$fair_name,$fair_year,$fair_trade_start,$fair_trade_end,$fair_public_start,$fair_public_end,$fair_event_start,$fair_event_end,$fair_vanue,$fair_url);
          $stmt2->execute();
          $fair_id = $stmt2->insert_id;

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

        }


      }
    }


    $filedata = file_get_contents($_FILES["fair_group_image_path"]["tmp_name"]);
    $path_parts = pathinfo($_FILES['fair_group_image_path']['name']);
    $extension = $path_parts['extension'];
    $path_namepic = alphanumeric_random_wms(10);
    $path_mini = ROOTPATH."/data/fairgroup/$fair_group_id/main/$path_namepic.$extension";
    $pathdb = "/data/fairgroup/$fair_group_id/main/$path_namepic.$extension";
    if (!is_dir(ROOTPATH."/data")){
      @mkdir(ROOTPATH."/data");
    }
    if (!is_dir(ROOTPATH."/data/fairgroup")){
      @mkdir(ROOTPATH."/data/fairgroup");
    }
    if (!is_dir(ROOTPATH."/data/fairgroup/".$fair_group_id)){
      @mkdir(ROOTPATH."/data/fairgroup/".$fair_group_id);
    }
    if (!is_dir(ROOTPATH."/data/fairgroup/".$fair_group_id."/main")){
      @mkdir(ROOTPATH."/data/fairgroup/".$fair_group_id."/main");
    }
    if (!is_dir(ROOTPATH."/data/fairgroup/".$fair_group_id."/resize")){
      @mkdir(ROOTPATH."/data/fairgroup/".$fair_group_id."/resize");
    }

    if (!is_dir(ROOTPATH."/data/fairgroup/".$fair_group_id."/crop")){
      @mkdir(ROOTPATH."/data/fairgroup/".$fair_group_id."/crop");
    }

    if(move_uploaded_file($_FILES["fair_group_image_path"]["tmp_name"], $path_mini)) {
      $sqlup = "update tt_fair_group set fair_group_image_path = ? where fair_group_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('si',$pathdb,$fair_group_id);
        $stmtup->execute();
      }

      $path_resize = ROOTPATH."/data/fairgroup/$fair_group_id/resize/$path_namepic.$extension";
      $path_crop = ROOTPATH."/data/fairgroup/$fair_group_id/crop/$path_namepic.$extension";
      $wid = 1600;
      imageresize($filedata,$path_resize,$wid,0);

      $wid = 900;
      imageresize($filedata,$path_crop,$wid,450,true,true);

    }


    $filedata = file_get_contents($_FILES["fair_group_logo_path"]["tmp_name"]);
    $path_parts = pathinfo($_FILES['fair_group_logo_path']['name']);
    $extension = $path_parts['extension'];
    $path_namepic = alphanumeric_random_wms(10);
    $path_mini = ROOTPATH."/data/fairgroup/$fair_group_id/logo/$path_namepic.$extension";
    $pathdb = "/data/fairgroup/$fair_group_id/logo/$path_namepic.$extension";
    if (!is_dir(ROOTPATH."/data")){
      @mkdir(ROOTPATH."/data");
    }
    if (!is_dir(ROOTPATH."/data/fairgroup")){
      @mkdir(ROOTPATH."/data/fairgroup");
    }
    if (!is_dir(ROOTPATH."/data/fairgroup/".$fair_group_id)){
      @mkdir(ROOTPATH."/data/fairgroup/".$fair_group_id);
    }
    if (!is_dir(ROOTPATH."/data/fairgroup/".$fair_group_id."/logo")){
      @mkdir(ROOTPATH."/data/fairgroup/".$fair_group_id."/logo");
    }
    if (!is_dir(ROOTPATH."/data/fairgroup/".$fair_group_id."/logo/resize")){
      @mkdir(ROOTPATH."/data/fairgroup/".$fair_group_id."/logo/resize");
    }
    if (!is_dir(ROOTPATH."/data/fairgroup/".$fair_group_id."/logo/crop")){
      @mkdir(ROOTPATH."/data/fairgroup/".$fair_group_id."/logo/crop");
    }
    if(move_uploaded_file($_FILES["fair_group_logo_path"]["tmp_name"], $path_mini)) {
      $sqlup = "update tt_fair_group set fair_group_logo_path = ? where fair_group_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      if($stmtup) {
        $stmtup->bind_param('si',$pathdb,$fair_group_id);
        $stmtup->execute();
      }

      $path_resize = ROOTPATH."/data/fairgroup/$fair_group_id/logo/resize/$path_namepic.$extension";
      $path_crop = ROOTPATH."/data/fairgroup/$fair_group_id/logo/crop/$path_namepic.$extension";
      $wid = 600;
      imageresize($filedata,$path_resize,$wid,600);

      $wid = 300;
      imageresize($filedata,$path_crop,$wid,300,true,true);
    }



  }

  ?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.alertpopup("1","บันทึกรายการเรียบร้อย");
    setTimeout(function () {top.window.location="../home.php?show=fair_master_list";},500);
  </script>
  <?php
  exit();

  exit();
}
?>


<?
if($_REQUEST["method"]=="getdata") {
  include_once("../connect.php");
  $fairlist = getFairList();
  $itemdata = $_GET["item"];
  $itemdata = explode(',',$itemdata);
  ?>
  <? for($i=0;$i<count($fairlist["Acitvities"]);$i++) { ?>
  <? if(in_array($fairlist["Acitvities"][$i]["Activity_Detail"]["ID"],$itemdata)) { ?>
  <div class="boxitem-item _item_select">
    <div class="boxitem-item-chk">
      <input type="checkbox" class="act_id_ss_s" id="actss_id_<?=$fairlist["Acitvities"][$i]["Activity_Detail"]["ID"]?>" value="<?=$fairlist["Acitvities"][$i]["Activity_Detail"]["ID"]?>" >
    </div>
    <div class="boxitem-item-name">
      <label for="actss_id_<?=$fairlist["Acitvities"][$i]["Activity_Detail"]["ID"]?>"><?=$fairlist["Acitvities"][$i]["Activity_Detail"]["Activity_Topic_TH"]?></label>
    </div>
  </div>
<? } } ?>
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

            <h3>Add Fair Group</h3>
            <div class="ibox-content">


                    <div class="row">
                      <div class="col-xs-6 col-lg-6">
                            <div class="form-group">
                              <label class="col-lg-12 control-label">Fair Group Name *</label>
                              <div class="col-lg-12">
                                <input type="text" name="fair_group_name_th"  class="form-control" required  />
                              </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-lg-6">
                              <div class="form-group">
                                <label class="col-lg-12 control-label">ABB *</label>
                                <div class="col-lg-12">
                                  <input type="text" name="fair_group_abb"  class="form-control" required  />
                                </div>
                              </div>
                          </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-6 col-lg-6">
                            <div class="form-group">
                              <label class="col-lg-12 control-label">Fair Group Main Image * </label>
                              <div class="col-lg-12">

                                <input type="file" name="fair_group_image_path" accept="image/*"  >
                                <span class="text-danger " style="font-size:90%;">
                                  *กรุณาเลือกไฟล์ JPG,JPEG,PNG ที่มีขนาดมากกว่า 1600*800 px ขึ้นไปเพื่อความสวยงามของการแสดงผล
                                </span>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-6 col-lg-6">
                            <div class="form-group">
                              <label class="col-lg-12 control-label">Fair Group Logo *</label>
                              <div class="col-lg-12">
                                <input type="file" name="fair_group_logo_path" required  >
                                <span class="text-danger " style="font-size:90%;">
                                  *กรุณาเลือกไฟล์ JPG,JPEG,PNG ที่มีขนาดมากกว่า 600*600 px ขึ้นไปเพื่อความสวยงามของการแสดงผล
                                </span>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-6 col-lg-6">
                            <div class="form-group">
                              <label class="col-lg-12 control-label"><span><img src="../backoffice/asset/icon_link.png" height="20"></span> Official Fair Link URL</label>
                              <div class="col-lg-12">
                                <input type="text" name="fair_group_link"  class="form-control"  />
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-6 col-lg-6">
                            <div class="form-group">
                              <label class="col-lg-12 control-label"><span><img src="../backoffice/asset/icon_link.png" height="20"></span> Facebook Facepage Link URL</label>
                              <div class="col-lg-12">
                                <input type="text" name="fair_group_social"  class="form-control"  />
                              </div>
                            </div>
                        </div>
                    </div>

            </div>

            <br>
            <h3>Edit Fair Group</h3>
            <div class="ibox-content">

              <?
              $fairlist = getFairList();
              ?>
                    <div class="row">
                      <div class="col-xs-5 col-lg-5">
                            <div class="form-group">
                              <label class="col-lg-12 control-label">Fair Name</label>
                              <div class="col-lg-12">
                                <div class="boxitem">
                                  <div class="boxitem-head">Fair Name</div>
                                  <div class="boxitem-inner-search">
                                    <input type="text" class="form-control" name="search_start" id="search_start" placeholder="search" onkeyup="searchNone(this.value);">
                                  </div>
                                  <div class="boxitem-inner _fairnoneselecd">
                                    <? for($i=0;$i<count($fairlist["Acitvities"]);$i++) { ?>
                                    <div class="boxitem-item _item_none">
                                      <div class="boxitem-item-chk">
                                        <input type="checkbox" class="act_id_ss" id="act_id_<?=$fairlist["Acitvities"][$i]["Activity_Detail"]["ID"]?>" value="<?=$fairlist["Acitvities"][$i]["Activity_Detail"]["ID"]?>">
                                      </div>
                                      <div class="boxitem-item-name">
                                        <label for="act_id_<?=$fairlist["Acitvities"][$i]["Activity_Detail"]["ID"]?>"><?=$fairlist["Acitvities"][$i]["Activity_Detail"]["Activity_Topic_TH"]?></label>
                                      </div>
                                    </div>
                                    <? } ?>
                                  </div>
                                  <div class="boxitem-inner-search">
                                    <hr>
                                    <div class="boxitem-item">
                                      <div class="boxitem-item-chk">
                                        <input type="checkbox" name="act_id_chk" id="act_id_chk"  onchange="getCheckItem('act_id_ss','act_id_chk');">
                                      </div>
                                      <div class="boxitem-item-name">
                                        <label for="act_id_chk">All Item</label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="col-xs-2 col-lg-2 ">
                              <div class="select-move-box text-center">
                                <div class="row">
                                  <div class="col-xs-12">
                                    <button type="button" onclick="addItem();" class="btn btn-default" style="width:150px;"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                  <div class="col-xs-12">
                                    <button type="button" onclick="removeItem();" class="btn btn-default" style="width:150px;"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                                  </div>
                                </div>
                              </div>
                        </div>

                        <div class="col-xs-5 col-lg-5">
                              <div class="form-group">
                                <label class="col-lg-12 control-label">Selected</label>
                                <div class="col-lg-12">
                                  <div class="boxitem">
                                    <div class="boxitem-head">All</div>
                                    <div class="boxitem-inner-search">
                                      <input type="text" class="form-control" name="search_stop" id="search_stop" placeholder="search" onkeyup="searchSelect(this.value);">
                                    </div>

                                    <div class="boxitem-inner _fairselecd">

                                    </div>

                                    <div class="boxitem-inner-search">
                                      <hr>
                                      <div class="boxitem-item">
                                        <div class="boxitem-item-chk">
                                          <input type="checkbox" name="act_id_chk_s" id="act_id_chk_s"  onchange="getCheckItem('act_id_ss_s','act_id_chk_s');">
                                        </div>
                                        <div class="boxitem-item-name">
                                          <label for="act_id_chk_s">All Item</label>
                                        </div>
                                      </div>
                                    </div>

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

            <input type="hidden" name="act_data" id="act_data" value="">
            <input type="hidden" name="act_data_select" id="act_data_select" value="">
            </form>
          </div>
        </div>
	</div>
</div>

<script type="text/javascript">
function getCheckItem(div,id) {
  if($('#' + id).is(":checked")) {
    $("."+div).prop("checked", true);
  } else {
    $("."+div).prop("checked", false);
  }
}

function searchNone(val) {
  $('._item_none').show();
  $('._item_none:not(:contains('+val+'))').hide();
}

function searchSelect(val) {
  $('._item_select').show();
  $('._item_select:not(:contains('+val+'))').hide();
}

function addItem() {
  var allitem = "";
  const ckb = document.querySelectorAll("._fairnoneselecd input[type=checkbox]");
  [...ckb].forEach( el => {

    if( el.checked ) {
      allitem = allitem+','+el.value;
    }
  });

  $('#act_data').val(allitem);
  $('#act_data_select').val(allitem);

  loadSelect();
}

function removeItem() {
  var allitem = "";
  const ckb = document.querySelectorAll("._fairselecd input[type=checkbox]");
  [...ckb].forEach( el => {

    if( el.checked ) {

    } else {
      allitem = allitem+','+el.value;
    }
  });

  $('#act_data_select').val(allitem);

  loadSelect2();
}

function loadSelect() {
  pc_overlay(1);
  var dataitem = $('#act_data').val();
  $.ajax({
      type: "GET",
      url: "php/fair_master_add.php?method=getdata&item="+dataitem,
      dataType: "text",
      success : function(data) {
        $('._fairselecd').empty();
        $("._fairselecd").html(data);
        pc_overlay(2);
      }
  });
}

function loadSelect2() {
  pc_overlay(1);
  var dataitem = $('#act_data_select').val();
  $.ajax({
      type: "GET",
      url: "php/fair_master_add.php?method=getdata&item="+dataitem,
      dataType: "text",
      success : function(data) {
        $('._fairselecd').empty();
        $("._fairselecd").html(data);
        pc_overlay(2);
      }
  });
}
</script>
