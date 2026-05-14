<?php
$menu_name = "Edit Users";
$save_link = "admin_edit";
$back_link = "admin_list";


if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $iddata = $_POST["iddata"];
  $adminid = $_SESSION["id"];
  $username = $_POST["username"];
  $password = $_POST["password"];
  $fullname = $_POST["fullname"];
  $org = $_POST["org"];
  $phone = $_POST["phone"];
  $email = $_POST["email"];
  $user_type = $_POST["user_type"];

  $sql = "select * from tt_admin where username = ? and id != ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('si',$username,$iddata);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if($numrow>0) {
      ?>
      <script type="text/javascript">
        top.pc_overlay(2);
        top.alertpopup("2","มี Username นี้ในระบบแล้วค่ะ");
      </script>
      <?php
      exit();
    }
  }

  $sql = "update tt_admin set
          username = ?,
          password = ?,
          fullname = ?,
          email = ?,
          org = ?,
          phone = ?,
          user_type = ?,
          update_date = now(),
          update_by = ?
          where id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('ssssssiii',$username,$password,$fullname,$email,$org,$phone,$user_type,$adminid,$iddata);
    $stmt->execute();

    ?>
    <script type="text/javascript">
      top.pc_overlay(2);
      top.alertpopup("1","บันทึกรายการเรียบร้อย");
      setTimeout(function () {top.window.location="../home.php?show=<?=$back_link?>";},500);
    </script>
    <?php
    exit();

  } else {
    ?>
    <script type="text/javascript">
      top.pc_overlay(2);
      top.alertpopup("2","มี Username นี้ในระบบแล้วค่ะ");
    </script>
    <?php
    exit();
  }

  exit();
}
?>

<?
$id = $_GET["id"];
$sql = "select * from tt_admin where id = ? ";
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
            <form class="form-horizontal" method="post" name="form_Suppliers_add" enctype="multipart/form-data" id="form_Suppliers_add" action="php/<?=$save_link?>.php?method=add" target="com_m" onSubmit="pc_overlay(1);">

            <h3><?=$menu_name?></h3>
            <div class="ibox-content">


                <div class="row">
                  <div class="col-xs-6 col-lg-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Role*</label>
                      <div class="col-lg-12">
                        <select class="form-control" name="user_type" required>
                          <option value="">- Select Role -</option>
                          <?
                          $sqlf = "select * from tt_admin_role where role_status != '9' order by role_name ASC ";
                          $stmtf = $mysqli->prepare($sqlf);
                          if($stmtf) {
                            $stmtf->execute();
                            $resultf = $stmtf->get_result();
                            $numrowf = $resultf->num_rows;
                            if($numrowf>0) {
                              while($dataf = $resultf->fetch_assoc()) {
                                ?>
                                <option value="<?=$dataf["role_id"]?>" <? if($data["user_type"]==$dataf["role_id"]) { echo "selected"; } ?> ><?=$dataf["role_name"]?></option>
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

                <div class="row">
                  <div class="col-xs-6 col-lg-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Username*</label>
                      <div class="col-lg-12">
                        <input type="text" class="form-control" name="username" required autocomplete="off" value="<?=$data["username"]?>" />
                      </div>
                    </div>
                  </div>
                </div>



                <div class="row">
                  <div class="col-xs-6 col-lg-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Password* </label>
                      <div class="col-lg-12">
                        <input type="password" class="form-control" name="password" required id="password" autocomplete="off" value="<?=$data["password"]?>">
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-6 col-lg-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Re-enter Password* </label>
                      <div class="col-lg-12">
                        <input type="password" class="form-control" name="passwordx" required id="passwordx" oninput="check(this);" autocomplete="off" value="<?=$data["password"]?>">
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row">
                  <div class="col-xs-6 col-lg-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Responsibility Name* </label>
                      <div class="col-lg-12">
                        <input type="text" class="form-control" name="fullname" required value="<?=$data["fullname"]?>">
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-6 col-lg-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Organization </label>
                      <div class="col-lg-12">
                        <input type="text" class="form-control" name="org" value="<?=$data["org"]?>">
                      </div>
                    </div>
                  </div>

                </div>


                <div class="row">
                  <div class="col-xs-6 col-lg-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Email </label>
                      <div class="col-lg-12">
                        <input type="email" class="form-control" name="email" required value="<?=$data["email"]?>">
                      </div>
                    </div>
                  </div>

                  <div class="col-xs-6 col-lg-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">Phone </label>
                      <div class="col-lg-12">
                        <input type="text" class="form-control" name="phone" value="<?=$data["phone"]?>">
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


            <input type="hidden" name="iddata"  value="<?=$data["id"]?>">
            </form>
          </div>
        </div>
	</div>
</div>

<script language='javascript' type='text/javascript'>
function check(input) {
    if (input.value != document.getElementById('password').value) {
        input.setCustomValidity('Password Must be Matching.');
    } else {
        input.setCustomValidity('');
    }
}
</script>
