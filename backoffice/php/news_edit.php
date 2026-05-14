<?php
$menu_name = "Edit News";
$save_link = "news_edit";
$back_link = "news_list";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];

  $fca_id = $_POST["fca_id"];
  $fair_id = $_POST["fair_id"];
  $fcat_id = 26;
  $fca_date = $_POST["fca_date"];
  $fca_title_th = $_POST["fca_title_th"];
  $fca_title_en = $_POST["fca_title_en"];
  $fca_detail_th = $_POST["fca_detail_th"];
  $fca_detail_en = $_POST["fca_detail_en"];

  $fca_status = $_POST["fca_status"];
  $fca_pubish = $_POST["fca_pubish"];

  $fca_tag_fair_id = $_POST["tag_fair_id"];
  $fca_tag_master_id = $_POST["tag_master_id"];

  $fca_map_status = $_POST["fca_map_status"];
  $fca_map_title = $_POST["maptitle"];
  $fca_lat = $_POST["maplat"];
  $fca_lng = $_POST["maplng"];
  $fca_address = $_POST["mapdesc"];

  $fc_urls = $_POST["fc_url"];
  $fc_url = "";
  foreach($fc_urls as $fc_urldata){
    if(trim($fc_urldata)!="") {
      $fc_url = $fc_url."|".$fc_urldata;
    }
  }

  $fc_youtubes = $_POST["fc_youtube"];
  $fc_youtube = "";
  foreach($fc_youtubes as $fc_youtubedata){
    if(trim($fc_youtubedata)!="") {
      $fc_youtube = $fc_youtube."|".$fc_youtubedata;
    }
  }

  $fct_id = 0;
  $sqlf = "select * from tt_fair_list_cat where fair_id = ? and fcat_id = ? limit 1 ";
  $stmtf = $mysqli->prepare($sqlf);
  if($stmtf) {
    $stmtf->bind_param('ii',$fair_id,$fcat_id);
    $stmtf->execute();
    $resultf = $stmtf->get_result();
    $numrowf = $resultf->num_rows;
    if($numrowf>0) {
      $dataf = $resultf->fetch_assoc();
      $fct_id = $dataf["fct_id"];
    }
  }


  $sql = " update tt_fair_content_article set
           fct_id = ?,
           fair_id = ?,
           fca_date = ?,
           fca_status = ?,
           fca_update_date = now(),
           fca_update_date = ?,
           fca_title_th = ?,
           fca_title_en = ?,
           fca_detail_th = ?,
           fca_detail_en = ?,
           fca_url = ?,
           fca_youtube = ?,
           fca_pubish = ?,
           fca_tag_fair_id = ?,
           fca_tag_master_id = ?,
           fca_map_status = ?,
           fca_map_title = ?,
           fca_lat = ?,
           fca_lng = ?,
           fca_address = ?
           where fca_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('iisiissssssiisissssi',$fct_id,$fair_id,$fca_date,$fca_status,$adminid,$fca_title_th,$fca_title_en,$fca_detail_th,$fca_detail_en,$fc_url,$fc_youtube,$fca_pubish,$fca_tag_fair_id,$fca_tag_master_id,$fca_map_status,$fca_map_title,$fca_lat,$fca_lng,$fca_address,$fca_id);
    $stmt->execute();

    saveLogActivity(5,$_SESSION["id"],$fca_id,"Edit");

    if($_FILES['filebanner']['name']!="") {

      $filedata = file_get_contents($_FILES["filebanner"]["tmp_name"]);

      if($fca_id>0) {
        $rmdirs =ROOTPATH."/data/fairarticle/".$fca_id."/banner";
        deleteDirectory($rmdirs);
      }

      $path_parts = pathinfo($_FILES['filebanner']['name']);
      $extension = $path_parts['extension'];
      $path_namepic = alphanumeric_random_wms(10);
      $path_mini = ROOTPATH."/data/fairarticle/$fca_id/banner/$path_namepic.$extension";
      $pathdb = "/data/fairarticle/$fca_id/banner/$path_namepic.$extension";
      if (!is_dir(ROOTPATH."/data")){
        @mkdir(ROOTPATH."/data");
      }
      if (!is_dir(ROOTPATH."/data/fairarticle")){
        @mkdir(ROOTPATH."/data/fairarticle");
      }
      if (!is_dir(ROOTPATH."/data/fairarticle/".$fca_id)){
        @mkdir(ROOTPATH."/data/fairarticle/".$fca_id);
      }
      if (!is_dir(ROOTPATH."/data/fairarticle/".$fca_id."/banner")){
        @mkdir(ROOTPATH."/data/fairarticle/".$fca_id."/banner");
      }
      if (!is_dir(ROOTPATH."/data/fairarticle/".$fca_id."/banner/resize")){
        @mkdir(ROOTPATH."/data/fairarticle/".$fca_id."/banner/resize");
      }
      if (!is_dir(ROOTPATH."/data/fairarticle/".$fca_id."/banner/crop")){
        @mkdir(ROOTPATH."/data/fairarticle/".$fca_id."/banner/crop");
      }
      if(move_uploaded_file($_FILES["filebanner"]["tmp_name"], $path_mini)) {
        $sqlup = "update tt_fair_content_article set fca_banner_path = ? where fca_id = ? ";
        $stmtup = $mysqli->prepare($sqlup);
        if($stmtup) {
          $stmtup->bind_param('si',$pathdb,$fca_id);
          $stmtup->execute();
        }

        $path_resize = ROOTPATH."/data/fairarticle/$fca_id/banner/resize/$path_namepic.$extension";
        $path_crop = ROOTPATH."/data/fairarticle/$fca_id/banner/crop/$path_namepic.$extension";
        $wid = 1600;
        imageresize($filedata,$path_resize,$wid,0);

        $wid = 1200;
        imageresize($filedata,$path_crop,$wid,380,true,true);
      }
    }

    $countfile = count(array_filter($_FILES['filedoc']['name']));
    if($countfile>0) {
      for($i=0;$i<$countfile;$i++ ) {

        $sqlgl = "insert into tt_fair_content_file (file_type,content_id,file_update_date,file_update_by) values ('2',?,now(),?) ";
        $stmtgl = $mysqli->prepare($sqlgl);
        if($stmtgl) {
          $stmtgl->bind_param('ii',$fca_id,$adminid);
          $stmtgl->execute();
          $file_id = $stmtgl->insert_id;

          $path_parts = pathinfo($_FILES['filedoc']['name'][$i]);
          $extension = $path_parts['extension'];
          $filenamedata = pathinfo($_FILES['filedoc']['name'][$i], PATHINFO_FILENAME);
          $fillfullname = $filenamedata.".".$extension;
          $path_namepic = alphanumeric_random_wms(10);
          $path_mini = ROOTPATH."/data/fairattachfile/$file_id/$path_namepic.$extension";
          $pathdb = "/data/fairattachfile/$file_id/$path_namepic.$extension";
          if (!is_dir(ROOTPATH."/data")){
            @mkdir(ROOTPATH."/data");
          }
          if (!is_dir(ROOTPATH."/data/fairattachfile")){
            @mkdir(ROOTPATH."/data/fairattachfile");
          }
          if (!is_dir(ROOTPATH."/data/fairattachfile/".$file_id)){
            @mkdir(ROOTPATH."/data/fairattachfile/".$file_id);
          }
          if(move_uploaded_file($_FILES["filedoc"]["tmp_name"][$i], $path_mini)) {
            $sqlup = "update tt_fair_content_file set file_name = ?, file_path = ? where file_id = ? ";
            $stmtup = $mysqli->prepare($sqlup);
            if($stmtup) {
              $stmtup->bind_param('ssi',$fillfullname,$pathdb,$file_id);
              $stmtup->execute();
            }
          }

        }

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

if($_REQUEST["method"]=="chgpublish") {
  include_once("../connect.php");

  $status = $_GET["status"];
  if($status==1) {
    $newsta = 2;
  } else {
    $newsta = 1;
  }

  if($newsta==1) {
    ?>
    <button type="button" onclick="changePublish();" class="btn btn-publish">Approved</a>
    <?
  } else {
    ?>
    <button type="button" onclick="changePublish();" class="btn btn-unpublish">Pending</a>
    <?
  }
  exit();
}

if($_REQUEST["method"]=="changefair") {
  include_once("../connect.php");

  $gid = $_GET["gid"];
  ?>
  <select class="form-control" name="fair_id" required>
    <option value="">- Select Fair List -</option>
    <?
    $sqlf = "select * from tt_fair_list where fair_status != '9' and fair_id in (select fair_id from tt_fair_group_list where fair_group_id = ? and fair_flag = 1) order by fair_name ASC ";
    $stmtf = $mysqli->prepare($sqlf);
    if($stmtf) {
      $stmtf->bind_param('i',$gid);
      $stmtf->execute();
      $resultf = $stmtf->get_result();
      $numrowf = $resultf->num_rows;
      if($numrowf>0) {
        while($dataf = $resultf->fetch_assoc()) {
          ?>
          <option value="<?=$dataf["fair_id"]?>"><?=$dataf["fair_name"]?></option>
          <?
        }
      }
    }
    ?>
  </select>
  <?
  exit();
}

$gid =  0;
$id = $_GET["id"];
$sql = "select * from  tt_fair_content_article a left join tt_fair_list b on a.fair_id=b.fair_id where a.fca_id = ?  ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('i',$id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();


    if($data["pid"]=="") {
      $pid = uniqid();
      $sqlup = "update tt_fair_content_article set pid = ? where fca_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      $stmtup->bind_param('si',$pid,$data["fca_id"]);
      $stmtup->execute();
    } else {
      $pid = $data["pid"];
    }

    $sqlg = "select * from  tt_fair_group_list where fair_id = ? and fair_flag = 1  ";
    $stmtg = $mysqli->prepare($sqlg);
    $stmtg->bind_param('i',$data["fair_id"]);
    $stmtg->execute();
    $resultg = $stmtg->get_result();
    $numrowg = $resultg->num_rows;
    if($numrowg>0) {
      $datag = $resultg->fetch_assoc();
      $gid = $datag["fair_group_id"];
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

$pulishdata = 0;
if($data["fca_pubish"]==1) {
  $pulishdata = 1;
} else {
  $pulishdata = 2;
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

            <div class="row">
              <div class="col-xs-6">
                <h3><?=$menu_name?></h3>
              </div>
              <div class="col-xs-6 text-right">
                <span class="st-grey-color">Status&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;</span>
                <span class="_pp">
                  <? if($pulishdata==1) { ?>
                    <button type="button" onclick="changePublish();" class="btn btn-publish">Approved</a>
                  <? } else { ?>
                    <button type="button" onclick="changePublish();" class="btn btn-unpublish">Pending</a>
                  <? } ?>
                </span>
              </div>
            </div>

            <div class="ibox-content">

                <div class="row">

                  <div class="col-xs-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Fair Group</label>
                      <div class="col-lg-12">
                        <select class="form-control" name="fair_group_id" required onchange="changeTagFair(this.value);changeSeleteFair(this.value);">
                          <option value="">- Select Fair Group -</option>
                          <?
                          $sqlf = "select * from tt_fair_group where fair_group_status != '9' order by fair_group_name_th ASC ";
                          $stmtf = $mysqli->prepare($sqlf);
                          if($stmtf) {
                            $stmtf->execute();
                            $resultf = $stmtf->get_result();
                            $numrowf = $resultf->num_rows;
                            if($numrowf>0) {
                              while($dataf = $resultf->fetch_assoc()) {
                                ?>
                                <option value="<?=$dataf["fair_group_id"]?>" <? if($gid==$dataf["fair_group_id"]) { echo "selected"; } ?> ><?=$dataf["fair_group_name_th"]?></option>
                                <?
                              }
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Fair List</label>
                      <div class="col-lg-12 _fairdiv">
                        <select class="form-control" name="fair_id" required>
                          <option value="">- Select Fair List -</option>
                            <?
                            $sqlf = "select * from tt_fair_list where fair_status != '9' and fair_id in (select fair_id from tt_fair_group_list where fair_group_id = ? and fair_flag = 1) order by fair_name ASC ";
                            $stmtf = $mysqli->prepare($sqlf);
                            if($stmtf) {
                              $stmtf->bind_param('i',$gid);
                              $stmtf->execute();
                              $resultf = $stmtf->get_result();
                              $numrowf = $resultf->num_rows;
                              if($numrowf>0) {
                                while($dataf = $resultf->fetch_assoc()) {
                                  ?>
                                  <option value="<?=$dataf["fair_id"]?>" <? if($data["fair_id"]==$dataf["fair_id"]) { echo "selected";  } ?> ><?=$dataf["fair_name"]?></option>
                                  <?
                                }
                              }
                            }
                            ?>
                          </select>
                      </div>
                    </div>
                  </div>
                </div>

                <hr>


                    <div class="row">
                      <div class="col-xs-6 col-lg-6">

                        <div class="row">
                          <div class="col-xs-12">
                            <div class="form-group">
                              <label class="col-lg-8 control-label">Create Date</label>
                              <div class="col-lg-4 text-right">
                                <label class="col-lg-6 control-label">
                                  <a onclick="showMap();"><img src="../backoffice/asset/icon_mapgoogle.png" ></a>
                                </label>
                                <div class="sm_" style="padding-top:35px;">
                                  <? if($data["fca_map_status"]==1) { ?>
                                    <img src="../backoffice/asset/icon_active.png" onclick="changeStatusMap();" >
                                  <? } else { ?>
                                    <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatusMap();" >
                                  <? } ?>
                                </div>
                              </div>
                              <div class="col-lg-12">
                                <div class='input-group date datepicker_box'>
                                    <input type="text" class="form-control" name="fca_date" required value="<?=$data["fca_date"]?>" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-12">
                            <div class="form-group">
                              <label class="col-lg-12 control-label">Title [EN]*</label>
                              <div class="col-lg-12">
                                <input type="text" class="form-control" name="fca_title_en" required value="<?=$data["fca_title_en"]?>" />
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-12">
                            <div class="form-group">
                              <label class="col-lg-12 control-label">Title [TH]</label>
                              <div class="col-lg-12">
                                <input type="text" class="form-control" name="fca_title_th" value="<?=$data["fca_title_th"]?>" />
                              </div>
                            </div>
                          </div>
                        </div>


                        <div class="row">
                          <div class="col-xs-12">
                            <div class="form-group">
                              <label class="col-lg-12 control-label">Add URL</label>
                              <div class="_addurl">

                                <?
                                $urlall = array();
                                if($data["fca_url"]!="") {
                                  $urlall = explode("|",$data["fca_url"]);
                                }
                                if(count($urlall)<=0) { ?>
                                  <div>
                                    <div class="col-lg-9">
                                      <input type="text" class="form-control" name="fc_url[]" value="">
                                    </div>
                                    <div class="col-lg-3">
                                      <button type="button" onclick="addmoreurl();" class="btn btn-addurl">+ Add</button>
                                    </div>
                                  </div>
                                <? } else { ?>
                                  <?
                                  $urun = 0;
                                  for($u=0;$u<count($urlall);$u++) { ?>
                                    <? if($urlall[$u]!="") { ?>
                                      <? if($urun==0) { ?>
                                        <div>
                                          <div class="col-lg-9">
                                            <input type="text" class="form-control" name="fc_url[]" value="<?=$urlall[$u]?>">
                                          </div>
                                          <div class="col-lg-3">
                                            <button type="button" onclick="addmoreurl();" class="btn btn-addurl">+ Add</button>
                                          </div>
                                        </div>
                                      <? } else { ?>
                                        <div >
                                          <div class="col-lg-9">
                                            <input type="text" class="form-control" style="margin-bottom:5px;" name="fc_url[]" value="<?=$urlall[$u]?>">
                                          </div>
                                        </div>
                                      <? } ?>
                                    <? $urun++; } ?>
                                  <? } ?>
                                <? } ?>

                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-12">
                            <div class="form-group">
                              <label class="col-lg-12 control-label">Add Youtube VDO URL</label>
                              <div class="_addyt">

                                <?
                                $ytall = array();
                                if($data["fca_youtube"]!="") {
                                  $ytall = explode("|",$data["fca_youtube"]);
                                }
                                if(count($ytall)<=0) { ?>
                                  <div>
                                    <div class="col-lg-9">
                                      <input type="text" class="form-control" name="fc_youtube[]" value="">
                                    </div>
                                    <div class="col-lg-3">
                                      <button type="button" onclick="addmoreyoutube();" class="btn btn-addurl">+ Add</button>
                                    </div>
                                  </div>
                                <? } else { ?>
                                  <?
                                  $urun = 0;
                                  for($u=0;$u<count($ytall);$u++) { ?>
                                    <? if($ytall[$u]!="") { ?>
                                      <? if($urun==0) { ?>
                                        <div>
                                          <div class="col-lg-9">
                                            <input type="text" class="form-control" name="fc_youtube[]" value="<?=$ytall[$u]?>">
                                          </div>
                                          <div class="col-lg-3">
                                            <button type="button" onclick="addmoreyoutube();" class="btn btn-addurl">+ Add</button>
                                          </div>
                                        </div>
                                      <? } else { ?>
                                        <div >
                                          <div class="col-lg-9">
                                            <input type="text" class="form-control" style="margin-bottom:5px;" name="fc_youtube[]" value="<?=$ytall[$u]?>">
                                          </div>
                                        </div>
                                      <? } ?>
                                    <? $urun++; } ?>
                                  <? } ?>
                                <? } ?>

                              </div>
                            </div>
                          </div>
                        </div>


                        <div class="row">
                          <div class="col-xs-12">
                            <div class="form-group">
                              <label class="col-lg-12 control-label">Add Attachment File</label>

                              <?
                              $sqlfile = " select * from tt_fair_content_file where file_type = '2' and content_id = ? order by file_id DESC  ";
                              $stmtfile = $mysqli->prepare($sqlfile);
                              $stmtfile->bind_param('i',$data["fca_id"]);
                              $stmtfile->execute();
                              $resultfile = $stmtfile->get_result();
                              $numrowfile = $resultfile->num_rows;
                              if($numrowfile>0) {
                                while($datafile = $resultfile->fetch_assoc()) {
                              ?>
                              <div class="_f_<?=$datafile["file_id"]?>">
                                <div class="col-xs-12">
                                  <div class="form-group">
                                    <div class="col-lg-9">
                                      <a href="<?=ROOTPATHDOMAIN?><?=$datafile["file_path"]?>" target="_blank"><?=$datafile["file_name"]?></a>
                                    </div>
                                    <div class="col-lg-3">
                                      <a onclick="removeFileAtt('<?=$datafile["file_id"]?>');"><i class="fa fa-trash text-danger" style="font-size:20px;" aria-hidden="true"></i></a>
                                    </div>
                                  </div>
                                </div>

                              </div>
                              <? } } ?>

                              <div class="col-lg-12">
                                <input type="file" name="filedoc[]" multiple class="form-control" accept=".xlsx,.xls,image/*,.doc, .docx,.ppt,.pptx,.txt,.pdf,.zip,.rar" >
                              </div>
                            </div>
                          </div>
                        </div>


                        </div>

                        <div class="col-xs-6 col-lg-6">

                            <div class="row">
                              <div class="col-xs-12">
                                <div class="form-group">
                                  <label class="col-lg-6 control-label">Title Image</label>
                                  <div class="col-lg-6 text-right ">
                                    <label class="col-lg-6 control-label">การแสดงผล</label>
                                    <div class="s_">
                                      <? if($data["fca_status"]==1) { ?>
                                        <img src="../backoffice/asset/icon_active.png" onclick="changeStatus();" >
                                      <? } else { ?>
                                        <img src="../backoffice/asset/icon_inactive.png" onclick="changeStatus();" >
                                      <? } ?>
                                    </div>
                                  </div>
                                  <? if($data["fca_banner_path"]!="") { ?>
                                    <div class="col-lg-12">
                                      <img src="<?=ROOTPATHDOMAIN?><?=$data["fca_banner_path"]?>" width="100%">
                                    </div>
                                  <? } ?>
                                  <div class="col-lg-12">
                                    <? if($data["fca_banner_path"]!="") { ?>
                                    <br>
                                    <? } ?>
                                    <input type="file" name="filebanner" class="form-control"  >
                                    <span class="text-danger " style="font-size:90%;">
                                      *กรุณาเลือกไฟล์ JPG,JPEG,PNG ที่มีขนาดมากกว่า 1600*540 px ขึ้นไปเพื่อความสวยงามของการแสดงผล
                                    </span>
                                  </div>
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
                            <textarea name="fca_detail_en" class="tinyclass"><?=$data["fca_detail_en"]?></textarea>
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
                            <textarea name="fca_detail_th" class="tinyclass"><?=$data["fca_detail_th"]?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-12">
                        <div class="form-group">
                          <label class="col-lg-12 control-label">Tag Fair</label>
                          <div class="col-lg-12">
                            <?
                            $sqlf = "select * from tt_fair_group where fair_group_status != '9' order by fair_group_abb ASC ";
                            $stmtf = $mysqli->prepare($sqlf);
                            if($stmtf) {
                              $stmtf->execute();
                              $resultf = $stmtf->get_result();
                              $numrowf = $resultf->num_rows;
                              if($numrowf>0) {
                                while($dataf = $resultf->fetch_assoc()) {
                                  ?>
                                  <span>
                                    <button onclick="changeTagFair('<?=$dataf["fair_group_id"]?>');" id="tagf_<?=$dataf["fair_group_id"]?>" type="button" class="tagf btn btn-tag <? if($data["fca_tag_fair_id"]==$dataf["fair_group_id"]) { ?>btn-tag-active<? } ?>"><?=$dataf["fair_group_abb"]?></button>
                                  </span>
                                  <?
                                }
                              }
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-12">
                        <div class="form-group">
                          <label class="col-lg-12 control-label">Tags Master</label>
                          <div class="col-lg-12 _showtagmaster">
                            <?
                            $datatag = $data["fca_tag_master_id"];
                            $tagitem = explode('|',$datatag);
                            $sqlf = "select * from tt_tag_master where tag_status != '9' order by tag_name ASC ";
                            $stmtf = $mysqli->prepare($sqlf);
                            if($stmtf) {
                              $stmtf->execute();
                              $resultf = $stmtf->get_result();
                              $numrowf = $resultf->num_rows;
                              if($numrowf>0) {
                                while($dataf = $resultf->fetch_assoc()) {
                                  $haveteg = 0;
                                  for($tt=0;$tt<count($tagitem);$tt++) {
                                    if($dataf["tag_id"]==$tagitem[$tt]) {
                                      $haveteg = 1;
                                      break;
                                    }
                                  }
                                  ?>
                                  <span>
                                    <button data-val="<?=$haveteg?>" data-id="<?=$dataf["tag_id"]?>" onclick="changeTagMaster('<?=$dataf["tag_id"]?>');" id="tagm_<?=$dataf["tag_id"]?>" type="button" class="tagm_ btn btn-tag <? if($haveteg==1) { ?>btn-tag-active<? } ?>"><?=$dataf["tag_name"]?></button>
                                  </span>
                                  <?
                                }
                              }
                            }

                            ?>
                            <span>
                              <button onclick="addTag();" type="button" class="btn btn-addtag">+ Add Tags</button>
                            </span>
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

            <input type="hidden" name="fca_id" value="<?=$data["fca_id"]?>">
            <input type="hidden" name="fca_status" id="fca_status" value="<?=$data["fca_status"]?>">
            <input type="hidden" name="fca_pubish" id="fca_pubish" value="<?=$pulishdata?>">

            <input type="hidden" name="tag_master_id" id="tag_master_id" value="<?=$datatag?>">
            <input type="hidden" name="tag_fair_id" id="tag_fair_id" value="<?=$data["fca_tag_fair_id"]?>">

            <input type="hidden" id="maptitle" name="maptitle" value="<?=$data["fca_map_title"]?>">
            <input type="hidden" id="maplat" name="maplat" value="<?=$data["fca_lat"]?>">
            <input type="hidden" id="maplng" name="maplng" value="<?=$data["fca_lng"]?>">
            <input type="hidden" id="mapdesc" name="mapdesc" value="<?=$data["fca_address"]?>">
            <input type="hidden" name="fca_map_status" id="fca_map_status" value="<?=$data["fca_map_status"]?>">
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
  var st = $('#fca_status').val();
  $.ajax({
      type: "GET",
      url: "php/news_edit.php?method=chgstatus&status="+st,
      dataType: "text",
      success : function(data) {
        $('.s_').empty();
        $('.s_').html(data);
        if(st==1) {
          $('#fca_status').val(2);
        } else {
          $('#fca_status').val(1);
        }
      }
  });
}

function changePublish() {
  var st = $('#fca_pubish').val();
  $.ajax({
      type: "GET",
      url: "php/news_edit.php?method=chgpublish&status="+st,
      dataType: "text",
      success : function(data) {
        $('._pp').empty();
        $('._pp').html(data);
        if(st==1) {
          $('#fca_pubish').val(2);
        } else {
          $('#fca_pubish').val(1);
        }
      }
  });
}

function changeStatusMap() {
  var st = $('#fca_map_status').val();
  $.ajax({
      type: "GET",
      url: "php/news_edit.php?method=chgstatusmap&status="+st,
      dataType: "text",
      success : function(data) {
        $('.sm_').empty();
        $('.sm_').html(data);
        if(st==1) {
          $('#fca_map_status').val(2);
        } else {
          $('#fca_map_status').val(1);
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

function changeSeleteFair(id) {
  $('._fairdiv').empty();
  $.ajax({
      type: "GET",
      url: "php/news_edit.php?method=changefair&gid="+id,
      dataType: "text",
      success : function(data) {
        $('._fairdiv').html(data);
      }
  });
}

</script>
