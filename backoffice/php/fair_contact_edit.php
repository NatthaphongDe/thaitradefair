<?php
$menu_name = "Message";
$save_link = "fair_contact_edit";
$back_link = "fair_contact_list";
$reply_link = "fair_reply_link";
$con_id = $_GET['cont_id'];
$num_v = "1";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $cont_id = $_POST["cont_id"];
  $num = "99";

  $sql_del = "update tt_contact_list set cont_status = ? where cont_id = ? "; //Update เป็นลบ หรือ Mark Del
  $stmt = $mysqli->prepare($sql_del);
  $stmt->bind_param('ii',$num,$cont_id);
  $stmt->execute();
  if($stmt) {
  ?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.alertpopup("1","บันทึกรายการเรียบร้อย");
    setTimeout(function () {top.window.location="../home.php?show=<?=$back_link?>";},500);
  </script>
  <?php }
  exit();

}

$sql = "select cont_status from  tt_contact_list a left join tt_fair_list b on a.fair_id=b.fair_id where a.cont_id = ? and cont_status != 2 and a.fair_id > 0 and a.cont_status != 99  ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('i',$con_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {

$sql_view = "update tt_contact_list set cont_status = ? where cont_id = ? "; //Update เป็นสถาน่ะว่าเปิดจดหมายแล้ว
$stmt_view = $mysqli->prepare($sql_view);
$stmt_view->bind_param('ii',$num_v,$con_id);
$stmt_view->execute();

  } }

$cont_id = $_GET["cont_id"];
$sql = "select * from  tt_contact_list a left join tt_fair_list b on a.fair_id=b.fair_id where a.cont_id = ? and a.fair_id > 0 and a.cont_status != 99  ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('i',$cont_id);
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

$sql_check = "select * FROM tt_contact_list_reply as a LEFT JOIN tt_contact_list AS b ON a.cont_id = b.cont_id WHERE a.cont_id = ?";
$stmt_check = $mysqli->prepare($sql_check);
  $stmt_check->bind_param('i',$con_id);
  $stmt_check->execute();
  $result_check = $stmt_check->get_result();
  $numrow_check = $result_check->num_rows;
  $data_check = $result_check->fetch_assoc();
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
            <form class="form-horizontal" id="saveformform" method="post" action="php/<?=$save_link?>.php?method=add" name="form_Suppliers_add" target="com_m">

            <div class="row">
              <div class="col-xs-12">
                <h3><?=$menu_name?></h3>
              </div>
            </div>

            <div class="ibox-content">

              <!--  -->
              <div class="container">
                <div class="row">
                  <div class="col-sm-12">
                    <h3 style="color:#1C5FA1;"><?=$data["cont_name"]?></h3>
                        <div class="row">
                            <div class="col-sm-4"><font color="#1C5FA1"><?=$data["cont_email"]?> | <?=$data["cont_tel"]?></font></div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4" style="text-align:right"><font color="#1C5FA1"><?=$data["cont_create_date"]?></font></div>
                        </div>
                        <hr>
                  </div>
                  <!--  -->

                  <!--  -->
                  <div class="col-sm-12">
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4"> <h3><?=$data["cont_subject"]?></h3></div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4" style="text-align:right">
                            <?php  if($numrow_check == 0) { ?>
                              <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span> Delete</button> 
                            <?php } else { ?>
                              <button class="btn btn-default" disabled><span class="glyphicon glyphicon-trash"></span> Delete</button> 
                            <?php } ?>

                            <?php  if($numrow_check == 0) { ?>
                            <a href="home.php?show=fair_reply_link&cont_id=<?=$data["cont_id"]?>" class="btn btn-default"><span class="glyphicon glyphicon-share-alt"></span> Reply</a></div>
                            <?php } else { ?>
                              <a class="btn btn-default" disabled><span class="glyphicon glyphicon-share-alt"></span> Reply</a></div>
                            <?php } ?>
                            </div>

                       
                        <?=$data["cont_message"]?><br><br>
                        <span>Best Regards,</span><br>
                        <?=$data["cont_name"]?>
                    </li>
                  </div>
                  <!--  -->
                </div>
              </div><br>

              <!-- การตอบกลับ -->
              <?php if($data_check["reply_name"] != "") { ?>
              <div class="container">
                <div class="row">
                  <div class="col-sm-12">
                    <h3 style="color:#1C5FA1;"><?=$data_check["reply_name"]?></h3>
                        <div class="row">
                            <div class="col-sm-4"><font color="#1C5FA1"><?=$data_check["reply_email"]?> | <?=$data_check["reply_tel"]?></font></div>
                            <div class="col-sm-4"></div>
                            <div class="col-sm-4" style="text-align:right"><font color="#1C5FA1"><?=$data_check["reply_date"]?></font></div>
                        </div>
                        <hr>
                  </div>
                  <!--  -->

                  <!--  -->
                  <div class="col-sm-12">
                    <div class="list-group-item">
                    <h3><?=$data_check["reply_subject"]?></h3>
                    <?php 
                      // $a = $data_check["reply_mesage"];
                      // $b = explode('<img src="',$a);
                      // $c = $b[1];
                      // $d = explode('/"',$);
                      // print_r($b[1]);
                    ?>
                    <?php $a = str_replace("<img","<img height='400px'",$data_check["reply_mesage"]); ?>
                    <?=$a?>
                    <br><br>
                    <span>Best Regards,</span><br>
                    <?=$data_check["reply_name"]?>
                    </li>
                  </div>
                  <!--  -->
                </div>
              </div>
            </div>
            <?php } ?>
            </div>

            
          </div>



            <input type="hidden" name="cont_id" value="<?=$data["cont_id"]?>">

            </form>
          </div>
        </div>
	</div>
</div>


<script type="text/javascript">
   document.querySelector('#saveformform').addEventListener('submit', function(e) {
        var form = this;
        e.preventDefault(); // <--- prevent form from submitting

        swal({
            title: "",
            text: "ต้องการลบ Mail นี้หรือไม่ ?",
            type: "info",
            confirmButtonColor: "#5cb85c",
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            closeOnConfirm: true,
            showCancelButton: true,
          }, function (isConfirm) {
            if (isConfirm) {
               form.submit();
              //  pc_overlay(1);
            }
          });
      });
  </script>
